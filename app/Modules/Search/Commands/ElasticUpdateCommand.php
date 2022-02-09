<?php

namespace Modules\Search\Commands;

use Dariuszp\CliProgressBar;
use Modules\Goods\Models\UpdatedProductModel;
use Modules\Search\Helpers\Creators\CategoryDocumentCreator;
use Modules\Search\Helpers\Creators\DocumentCreatorInterface;
use Modules\Search\Helpers\Creators\ProductDocumentCreator;
use Modules\Search\SearchModule;
use Modules\Sites\Models\SiteModel;
use Throwable;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\QuerySetInterface;

class ElasticUpdateCommand extends Command
{

    public function handle($arguments = [])
    {

        foreach (SiteModel::getAllEnabled() as $site) {
            echo "update $site->code products\n";

            Xcart::app()->getModule('Sites')->setSite($site);

            $this->updateProductResources();

            $this->updateCategoryResources();
        }
    }

    private function updateProductResources(): void
    {
        $site = Xcart::app()->getModule('Sites')->getSite();

        $engine_name = SearchModule::getEngine($site->code);

        $manager = UpdatedProductModel::objects()->filter([
            'type__in' => [6, 61],
            'product__sites__storefrontid' => $site->pk
        ])->order(['time_stamp']);

        $this->updateResources($engine_name, new ProductDocumentCreator(), $manager);

    }

    private function updateCategoryResources(): void
    {
        $site = Xcart::app()->getModule('Sites')->getSite();

        $engine_name = SearchModule::getEngine($site->code, SearchModule::CATEGORIES_ENGINE);

        $manager = UpdatedProductModel::objects()->filter([
            'type__in' => [8],
            'category__storefrontid' => Xcart::app()->getModule('Sites')->getSite()->pk
        ]);

        $this->updateResources($engine_name, new CategoryDocumentCreator(), $manager);
    }

    private function updateResources($engine_name, DocumentCreatorInterface $creator, QuerySetInterface $manager): void
    {
        $site = Xcart::app()->getModule('Sites')->getSite();

        Xcart::app()->elastic->checkEngine(
            $engine_name,
            strtolower($site->lang->lang_code ?? 'en')
        );

        $bar = new CliProgressBar($manager->count());

        while ($models = $manager->paginate(1, 100)->all()) {

            $queue = $creator->createDocuments($models);

            foreach ($models as $update_model) {
                $update_model->delete();
            }

            try {

                foreach ($queue as $queue_item) {
                    $queue_item->process($engine_name);
                }

            } catch(Throwable $exception){
                echo $exception->getMessage()."\n";
            }

            $bar->progress(100);
        }

        $bar->end();
    }
}