<?php


namespace Modules\Order\Controllers\Api;


use Exception;
use GuzzleHttp\Client;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Models\GroundMapModel;
use Xcart\App\Controller\Controller;

class UpsMapController extends Controller
{
    public function index(string $zipcode): void
    {

        if (!($map = GroundMapModel::objects()->get(['zipcode' => $zipcode])) && $mapUrl = OrderHelper::fetchMap($zipcode)) {
            [$map] = GroundMapModel::objects()->updateOrCreate(['zipcode' => $zipcode], ['map_url' => $mapUrl]);
        }

        try {
            $client = new Client(['verify' => false, 'timeout' => 5]);
            $res = $client->get($map->map_url, ['http_errors' => false,]);
            if (!($res->getStatusCode() === 200 && $res->getHeader('Content-Length')[0] > 0)) {
                $mapUrl = OrderHelper::fetchMap($zipcode);
                [$map] = GroundMapModel::objects()->updateOrCreate(['zipcode' => $zipcode], ['map_url' => $mapUrl]);
                $res = $client->get($map->map_url, ['http_errors' => false,]);
                if (!($res->getStatusCode() === 200 && $res->getHeader('Content-Length')[0] > 0)) {
                    return;
                }
            }

            foreach ($res->getHeaders() as $name => $header) {
                foreach ($header as $h) {
                    header("{$name}: $h");
                }
            }
            echo $res->getBody()->getContents();

        } catch (Exception $e) {

        }
    }
}