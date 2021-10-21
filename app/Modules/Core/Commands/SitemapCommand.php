<?php

namespace Modules\Core\Commands;

use DateTime;
use Icamys\SitemapGenerator\SitemapGenerator;
use Modules\Brand\Models\BrandModel;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Commands\Command;
use Xcart\App\Helpers\Paths;
use Xcart\App\QueryBuilder\Q\QOr;

class SitemapCommand extends Command
{
    private const SITE_MAP_PRODUCT = 'P';
    private const SITE_MAP_CATEGORY = 'C';
    private const SITE_MAP_BRAND = 'B';
    private const SITE_MAP_PAGES = 'S';

    private const SITE_MAP_FREQ_MONTHLY = 'monthly';
    private const SITE_MAP_FREQ_DAILY = 'daily';

    private static array $sitemap_items = [
        [
            'type' => self::SITE_MAP_CATEGORY,
            'freq' => self::SITE_MAP_FREQ_MONTHLY,
            'priority' => 0.1,
        ],
        [
            'type' => self::SITE_MAP_PRODUCT,
            'freq' => self::SITE_MAP_FREQ_DAILY,
            'priority' => 0.9,
        ],
        [
            'type' => self::SITE_MAP_BRAND,
            'freq' => self::SITE_MAP_FREQ_MONTHLY,
            'priority' => 0.1,
        ],
        [
            'type' => self::SITE_MAP_PAGES,
            'freq' => self::SITE_MAP_FREQ_MONTHLY,
            'priority' => 0.1,
        ],
    ];

    public function handle($arguments = [])
    {
        foreach (SiteModel::getAllEnabled() as $site) {
            print("$site->domain: XML generation.\n");

            $outputDir = Paths::get('www');

            $generator = new SitemapGenerator($site->getAbsoluteUrl(), $outputDir);

            $generator->enableCompression();

            $generator->setMaxUrlsPerSitemap(50000);

            $generator->setSitemapFileName("$site->domain-sitemap.xml");

            $generator->setSitemapIndexFileName("$site->domain-sitemap-index.xml");

            foreach (self::$sitemap_items as $item) {
                switch ($item['type']) {
                    case self::SITE_MAP_PRODUCT:
                        $qs = ProductModel::forsale()
                            ->filter([
                                'sites__storefrontid' => $site->pk,
                                new QOr(['is_group_root' => true, 'group_root__isnull' => true]),
                            ])
                            ->exclude([
                                'prevent_search_indexing_this_product_page__isnt' => 'Y',
                                'categories__prevent_index_products' => 'Y'
                            ]);

                        /** @var ProductModel $product */
                        foreach ($qs as $product) {
                            $date = $product->mod_date ? (new DateTime())->setTimestamp($product->mod_date) : new DateTime();
                            $generator->addURL($product->getAbsoluteUrl(), $date, $item['freq'], $item['priority'], []);
                        }

                        break;
                    case self::SITE_MAP_CATEGORY:
                        $qs = CategoryModel::objects()
                            ->filter([
                                'avail' => 'Y',
                                'site__storefrontid' => $site->pk
                            ])
                            ->exclude([
                                'prevent_index_category_page' => 'Y'
                            ]);

                        foreach ($qs as $category) {
                            $generator->addURL($category->getAbsoluteUrl(), new DateTime(), $item['freq'], $item['priority'], []);
                        }
                        break;
                    case self::SITE_MAP_BRAND:
                        $qs = BrandModel::objects()
                            ->filter([
                               'avail' => 'Y',
                               'brand_storefront__sfid' => $site->pk
                            ])
                            ->exclude([
                                'prevent_search_indexing_brand_page' => 'Y'
                            ]);
                        foreach ($qs as $brand) {
                            $generator->addURL($brand->getAbsoluteUrl(), new DateTime(), $item['freq'], $item['priority'], []);
                        }
                        break;
                }
            }

            $generator->flush();

            $generator->finalize();

            break;
        }
    }
}