<?php


namespace Modules\Amazon\Commands;


use Modules\Amazon\Helpers\AmazonVerificationHelper;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Commands\Command;

class StandToListingCommand extends Command
{

    public function handle($arguments = [])
    {
        $qs = ProductModel::objects()->filter(['manufacturerid' => 605, 'amazon_enabled' => 'N'])->order(['productid'])->limit(100);

        /** @var ProductModel $p */
        foreach ($qs as $p) {
            $products[] = $p;
            $p->amazon_enabled = 'Y';
            $p->save();
        }
        if ($products) {
            if ($FeedSubmissionId = AmazonVerificationHelper::addAmazonListing($products)) {
                echo "Listings has been submited. feed ID {$FeedSubmissionId} \n";
            }
        }
    }
}