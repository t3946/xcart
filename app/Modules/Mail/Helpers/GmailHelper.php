<?php


namespace Modules\Mail\Helpers;


use Google_Client;
use Google_Service_Gmail;
use Google_Service_Gmail_Message;
use Xcart\App\Helpers\Paths;

class GmailHelper
{
    public static function getClient($user): Google_Client
    {
        $g = Paths::get('www') . '/include/system/dx-gmail-access-630d93ce9158.json';
        putenv("GOOGLE_APPLICATION_CREDENTIALS={$g}");
        $client = new Google_Client();
        $client->useApplicationDefaultCredentials();
        $client->setApplicationName('Gmail API PHP');
        $client->setScopes(
            [
                Google_Service_Gmail::GMAIL_READONLY,
                Google_Service_Gmail::GMAIL_MODIFY,
                Google_Service_Gmail::GMAIL_LABELS,
                //Google_Service_Gmail::GMAIL_METADATA,
            ]);
        $client->setAuthConfig(Paths::get('www') . '/include/system/dx-gmail-access-630d93ce9158.json');
        $client->setAccessType("offline");
        $client->setSubject($user);
        return $client;
    }

    /**
     * @param Google_Service_Gmail $service
     * @param $userId
     * @return Google_Service_Gmail_Message[]
     */
    public static function listMessages(Google_Service_Gmail $service, $userId): array
    {
        $pageToken = NULL;
        $messages = [];
        $opt_param = [];
        do {
            try {
                if ($pageToken) {
                    $opt_param = ['pageToken' => $pageToken, 'includeSpamTrash' => true];
                }
                $messagesResponse = $service->users_messages->listUsersMessages($userId, $opt_param);
                if ($messagesResponse->getMessages()) {
                    $messages = array_merge($messages, $messagesResponse->getMessages());
                    $pageToken = $messagesResponse->getNextPageToken();
                }
            } catch (\Exception $e) {
                print 'An error occurred: ' . $e->getMessage();
            }
        } while ($pageToken);

        return $messages;
    }

    public static function getMessage($service, $userId, $messageId)
    {
        try {
            $message = $service->users_messages->get($userId, $messageId);

        } catch (\Exception $e) {
            print 'An error occurred: ' . $e->getMessage();
        }
        return $message ?? null;
    }

    public static function decodeBody($body)
    {
        $rawData = $body;
        $sanitizedData = strtr($rawData, '-_', '+/');
        $decodedMessage = base64_decode($sanitizedData);
        if (!$decodedMessage) {
            $decodedMessage = FALSE;
        }
        return $decodedMessage;
    }

    public static function getBody($message)
    {
        $payload = $message->getPayload();
        $body = $payload->getBody();
        $FOUND_BODY = GmailHelper::decodeBody($body['data']);
        if (!$FOUND_BODY) {
            $parts = $payload->getParts();
            foreach ($parts as $part) {
                if ($part['body']) {
                    $FOUND_BODY = GmailHelper::decodeBody($part['body']->data);
                    break;
                }
                // Last try: if we didn't find the body in the first parts,
                // let loop into the parts of the parts (as @Tholle suggested).
                if ($part['parts'] && !$FOUND_BODY) {
                    foreach ($part['parts'] as $p) {
                        // replace 'text/html' by 'text/plain' if you prefer
                        if ($p['mimeType'] === 'text/html' && $p['body']) {
                            $FOUND_BODY = GmailHelper::decodeBody($p['body']->data);
                            break;
                        }
                    }
                }
                if ($FOUND_BODY) {
                    break;
                }
            }
        }
        return $FOUND_BODY;
    }

    public static function getHeader($headers, $name)
    {
        foreach ($headers as $header) {
            if ($header['name'] === $name) {
                return $header['value'];
            }
        }
        return null;
    }
}