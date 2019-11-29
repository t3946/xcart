<?php


namespace Modules\Goods\Commands;


use GuzzleHttp\Client;
use Modules\Core\Helpers\GuzzleDownloader;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Commands\Command;

class ProductUpcByAsinCommand extends Command
{

    public function handle($arguments = [])
    {

        $downloader = new GuzzleDownloader(['timeout' => 30, 'use_proxy' => true]);
        $i = 0;
        while ($qs = ProductModel::objects()->filter(['manufacturerid' => 605, 'ASIN__isnull' => false, 'upc' => ''])->paginate(++$i, 1000)) {
            /** @var ProductModel $q */
            foreach ($qs as $q) {
                $upc = null;
                echo "Search ASIN: {$q->ASIN}\n";

                if (($crawler = $downloader->get('https://www.synccentric.com/features/upc-asin/', 'POST', [
                    'locale' => 'US',
                    'identifier' => $q->ASIN,
                    'usr_ip' => $downloader->getParams()['use_proxy'] ? $downloader->getProxy() : '8.8.8.8'
                ]))) {
                    if ($crawler->count() && preg_match_all('/(\w+):\s<strong>(.+)<\/strong>/m', $crawler->html(), $matches)) {
                        $properties = array_combine($matches[1], $matches[2]);
                        if (isset($properties['UPC']) && $properties['UPC']) {
                            $upc = $properties['UPC'];
                        }
                        if (isset($properties['EAN']) && $properties['EAN']) {
                            $upc = $properties['EAN'];
                        }
                    }
                }
                if ($upc) {
                    echo "Found UPC: {$upc}\n";
                    $q->upc = $upc;
                    $q->save();
                } else {
                    $downloader = new GuzzleDownloader(['timeout' => 30, 'use_proxy' => true]);
                }
            }
        }
    }
}