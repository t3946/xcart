<?php

namespace Modules\Mail\Commands;

use Modules\Forms\Helpers\SnippetHelper;
use Modules\Forms\Models\TemplateModel;
use Modules\Order\Models\Decisions\DecisionModel;
use PhpAmqpLib\Message\AMQPMessage;
use Throwable;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;

class MailSenderCommand extends Command
{

    public function handle($arguments = [])
    {
        Xcart::app()->queue->consume('emails', [$this, 'consume']);
    }

    public function consume(AMQPMessage $message): void
    {
        try {
            if ($data = json_decode($message->body, true, 512, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)) {
                switch ($data['action']) {
                    case 'decision':

                        /** @var DecisionModel $decision */
                        if ($decision = DecisionModel::objects()->get(['decision_id' => $data['decision_id']])) {
                            $options = $decision->options;

                            $order = $decision->order;

                            $action = isset($options['action']) ? ['action' => $options['action']] : ['action__isnull' => true];

                            $decision_template = $decision->templates->filter($action)->limit(1)->get();

                            if ($decision_template === null) {
                                break;
                            }

                            /** @var TemplateModel $template */
                            $template = $decision_template->template;

                            if ($template === null) {
                                break;
                            }

                            $data = $decision_template->template_data ?? [];

                            $snippets = $options + $data;

                            $snippet_wrapped = [];

                            foreach ($snippets as $key => $snippet)
                            {
                                $snippet_wrapped["{{" . $key . "}}"] = $snippet;
                            }

                            $subject = SnippetHelper::render($template->subject_line, ['order' => $order]);

                            $subject = SnippetHelper::renderSnippets($subject, $snippet_wrapped);

                            $body = SnippetHelper::render($template->message_body, ['order' => $order]);

                            $body = SnippetHelper::renderSnippets($body, $snippet_wrapped);

                            $from = 'helpdesk@s3stores.com';

                            Xcart::app()->mail->raw($template->send_to_email, $subject, $body, [
                                'from' => $from,
                                'bcc' => ['romann@s3stores.com' => ''],
                                'headers' => ['X-Xcart-Label' => 'order-logs']
                            ]);

                        }
                        break;
                }
            }
        } catch (Throwable $e) {
            echo "Error:{$e->getMessage()}\n";
        } finally {
            $message->ack();

        }
    }
}