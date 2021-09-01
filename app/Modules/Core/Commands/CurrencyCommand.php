<?php


namespace Modules\Core\Commands;


use Aveiv\OpenExchangeRatesApi\Client;
use Modules\Sites\Models\CurrencyModel;
use Xcart\App\Commands\Command;

class CurrencyCommand extends Command
{

    public function handle($arguments = [])
    {
        $client = new Client('adcc3c074263497bb5f0d23bded66db8');
        if ($currencies = $client->getLatest()) {
            /** @var CurrencyModel $c */
            foreach (CurrencyModel::objects()->filter(['is_primary' => 'N']) as $c) {
                if (isset($currencies['rates'][$c->currency_code])) {
                    $c->coefficient = $currencies['rates'][$c->currency_code];
                    $c->save();
                    echo "{$c->currency_code} : $c->coefficient\n";
                }
            }
            echo "Done\n";
        }
    }
}