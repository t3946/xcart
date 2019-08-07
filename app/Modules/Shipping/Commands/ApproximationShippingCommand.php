<?php


namespace Modules\Shipping\Commands;


use Mindy\QueryBuilder\Expression;
use Modules\Core\Models\StateModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\Models\ProductModel;
use Modules\Shipping\Helpers\ShippingHelper;
use Modules\Shipping\Models\ApproximationShippingModel;
use Modules\User\Models\UserModel;
use Xcart\App\Commands\Command;

class ApproximationShippingCommand extends Command
{
    public const WEIGHT_POINTS = ['bw_1' => 1, 'bw_75' => 75, 'bw_150' => 150];

    public function handle($arguments = []): void
    {

        $m = DistributorModel::objects()
            ->filter(
                new Expression("DAYOFMONTH(NOW()) = FLOOR(27 * ((manufacturerid - 1) / ((SELECT MAX(manufacturerid) FROM xcart_manufacturers) - 1)) + 1) 
                    OR update_approximation_shipping_rates = 'Y'
                    OR shipping_rates_last_update_date = 0")
            )->filter(['code' => 'AOP']);

        /** @var DistributorModel $distributor */
        foreach ($m as $distributor) {
            foreach(StateModel::objects()->filter(['country_code' => 'US', 'base_state_zipcode__isnt' => '' ]) as $state) {
                foreach(self::WEIGHT_POINTS as $key => $weight) {
                    $product = new ProductModel([
                        'productid' => 0,
                        'cost_to_us' => 0.1,
                        'shipping_dim_x' => 0.1,
                        'shipping_dim_y' => 0.1,
                        'shipping_dim_z' => 0.1,
                        'manufacturerid' => $distributor->manufacturerid,
                        'weight' => $weight
                    ]);
                    $user = new UserModel([
                        's_country' => $state->country_code,
                        's_state' => $state->code,
                        's_zipcode' => $state->base_state_zipcode
                    ]);
                    if ($shipping_rate = ShippingHelper::getShippingRates($user, $distributor, [['model' => $product, 'qty' => 1]], null, false, false, false))
                    {
                        /** @var ApproximationShippingModel $approximation */
                        [$approximation] = ApproximationShippingModel::objects()->getOrNew(['manufacturerid' => $distributor->manufacturerid, 'state' => $state->code]);
                        foreach ($shipping_rate as $rate) {
                            if (in_array((int) $rate->shippingid, [1, 63], true)) {
                                $approximation->$key = $rate->getShippingQuote();
                                echo "{$distributor->code} {$state->code} w:{$weight} r:{$approximation->$key}\n";
                            }
                        }
                        $approximation->save();
                    } else {
                        echo "{$distributor->code} {$state->code} w:{$weight} r:Failed\n";
                    }
                }
            }
            $distributor->setAttributes([
                'update_approximation_shipping_rates' => 'N',
                'shipping_rates_last_update_date' => time(),
            ]);
            $distributor->save();
        }

        echo "Approximation finished\n";
    }
}