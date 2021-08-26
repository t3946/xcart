<?php

namespace Modules\Core\Commands;

use GuzzleHttp\Client;
use Xcart\App\Commands\Command;

class DomainCommand extends Command
{

    public function handle($arguments = [])
    {
        $letters = range('a', 'z');

        $client = new Client(['verify' => false, 'timeout' => 10]);

        foreach ($letters as $l1) {
            foreach ($letters as $l2) {
                foreach ($letters as $l3) {
                    foreach ($letters as $l4) {
                        foreach ($letters as $l5) {
                            $domain = "{$l1}{$l2}{$l3}{$l4}{$l5}.com";
                            $url = "https://www.domainnamesoup.com/cell3.php?domain={$domain}&pt=84";
                            $res = "{$domain}:";
                            try {
                                if ($response = $client->request('GET', $url)) {
                                    if ($response = $response->getBody()->getContents()) {
                                        if ((int)$response === -1) {
                                            $res .= " avail\n";
                                        } else {
                                            $res .= " busy\n";
                                        }
                                    }
                                }
                            } catch (\Throwable $exception) {
                                $res .= " n/a\n";
                            }
                            echo $res;
                            file_put_contents('domains.txt', file_get_contents('domains.txt') . $res);
                        }
                    }
                }
            }
        }
    }
}