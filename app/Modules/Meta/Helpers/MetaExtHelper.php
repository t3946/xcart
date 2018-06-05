<?php
namespace Modules\Meta\Helpers;


use Mindy\QueryBuilder\Q\QAnd;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Brand\Models\BrandModel;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductModel;
use Modules\Meta\Components\MetaTrait;
use Modules\Meta\Models\Meta;
use Modules\Meta\Models\MetaTemplate;
use Modules\Meta\Types\MetaType;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\RenderTrait;

class MetaExtHelper
{
    use RenderTrait;

    /** @var static */
    private static $_instance;
    /** @var string */
    private $template_code;
    /** @var int */
    private $base_code = MetaType::DEFAULT;
    private $noIndex = false;

    private $_params = [];
    private $_composed = [];
    private $_advanced = [];

    public function getParams(): iterable
    {
        return $this->_params;
    }

    public function setParams(iterable $params): self
    {
        $this->_params = $params;

        return $this;
    }

    public function addParam(string $property, $val): self
    {
        $this->_params[$property] = $val;

        return $this;
    }

    public function addAdvanced($tag, $type, $content): self
    {
        $this->_advanced[$tag][$type] = $content;

        return $this;
    }

    public function setTemplateCode(string $code): self
    {
        $this->template_code = $code;

        return $this;
    }

    public function setBaseCode(string $code): self
    {
        $this->base_code = $code;

        return $this;
    }

    public static function newInstance(): self
    {
        return new static();
    }

    public static function getInstance(): self
    {
        if (empty(static::$_instance)) {
            static::$_instance = static::newInstance();
        }

        return static::$_instance;
    }

    /**
     * @param MetaTrait $controller
     * @param null $canonical
     */
    public static function getMeta($controller, $canonical = null):void
    {
        if ($controller && $base_code = $controller->getMetaBase()) {
            $instance = self::newInstance();
            $instance->setBaseCode($base_code);
            $instance->setParams($controller->getMetaTemplateParams());
            $instance->getNoIndexFlag($controller);

            if ($instance->compose()) {
                echo $instance->render();
                return;
            }
        }

        MetaHelper::getMeta($controller, $canonical);
    }


    public function compose(): bool
    {
        $this->_composed = [];

        $url = Xcart::app()->request->getUrl();
        $path = Xcart::app()->request->getPath();
        $connections = Xcart::app()->db->getConnection();

        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();
        $site_code = strtolower($site->code);

        if ($list_config = $site->list_config) {
            $site_name = $list_config->getName();
        }
        else {
            $site_name = $site->getName();
        }

        $this->addParam('site_name', $site_name);

        $this->_params['site'] = $site;
        $this->_params['siteConfig'] = $site->getConfig();

        /** @var Meta $meta */
        $meta = Meta::objects()->filter([
            new QOr([
                new QAnd(['url' => $connections->quote($url),  'site_id' => $site->pk]),
                new QAnd(['url' => $path, 'site_id' => $site->pk]),
                new QAnd(['url' => $path, 'site_id__isnull' => true]),
            ])
        ])
            ->order(['-site_id', '-url'])
            ->limit(1)
            ->cache(3600)
            ->get();

        if ($meta) {
            $this->_composed = [
                'title' => $meta->title,
                'description' => $meta->description,
                'keywords' => $meta->keywords,
                'advanced' => $this->_advanced,
            ];

            return true;
        }
        elseif ($this->template_code && $template = MetaTemplate::objects()->cache(3600)->get(['code' => $this->template_code])) {
            /** @var MetaTemplate $template */

            return $this->prepare($template);
        }
        elseif ($this->base_code && (
                ($this->base_code == MetaType::DEFAULT)
                || (!empty($this->_params['model']) && is_subclass_of($this->_params['model'], Model::class))
            ))
        {
            $model = $this->_params['model'] ?? null;
            $codes = null;

            switch ($this->base_code)
            {
                case MetaType::BRAND: {
                    /** @var BrandModel $model */

                    $codes = [
                        "brands:store.{$site_code}",
                        'brands:base'
                    ];

                    break;
                }

                case MetaType::CATEGORY: {
                    /** @var CategoryModel $model */
                    $codes = [];

                    $ids = $model->getObjects()->ancestors(true)->valuesList(['categoryid'], true);
                    if ($ids) {
                        foreach ($ids as $id) {
                            $codes[] = 'categories:category.' . $id;
                        }
                    }

                    $codes = array_merge($codes,[
                        "categories:store.{$site_code}",
                        "categories:base"
                    ]);

                    break;
                }

                case MetaType::PRODUCT: {
                    /** @var ProductModel $model */
                    $dx_id = $model->manufacturerid;
                    $brand_id = $model->brandid;

                    if (empty($this->_params['category']) && $category = $model->getMainCategory()) {
                        $this->_params['category'] = $category;
                    }

                    $codes = [
                        "products:dx.{$dx_id}:brand.{$brand_id}:store.{$site_code}",
                        "products:brand.{$brand_id}:store.{$site_code}",
                        "products:dx.{$dx_id}:store.{$site_code}",
                        "products:dx.{$dx_id}:brand.{$brand_id}",
                        "products:brand.{$brand_id}",
                        "products:dx.{$dx_id}",
                        "products:store.{$site_code}",
                        "products:base",
                    ];

                    break;
                }

                case MetaType::SEARCH: {
                    $codes = [
                        "searches:store.{$site_code}",
                        "searches:base"
                    ];

                    break;
                }

                case MetaType::DEFAULT: {
                    $codes = [
                        "default:store.{$site_code}",
                        "default:base",
                    ];

                    break;
                }
            }

            if ($codes) {
                $order_codes = array_map(function($val){ return "'{$val}'"; }, $codes);
                $order = [
                    "FIELD( code, " . implode(',', $order_codes) . ")",
                ];

                /** @var MetaTemplate $template */
                if ($template = MetaTemplate::objects()->filter(['code__in' => $codes])->order($order)->limit(1)->get())
                {
                    return $this->prepare($template);
                }
            }
        }

        return false;
    }

    public function getNoIndexFlag($controller): void
    {
        if ($controller->noIndex){
            $this->noIndex = $controller->noIndex;
        }
    }

    public function render(): ?string
    {
        if ($this->_composed) {
            return $this->renderTemplate('meta/meta_helper.tpl', $this->_composed);
        }

        return null;
    }

    private function cleanup(array $strs)
    {
        foreach ($strs as $k => $str) {
            $strs[$k] = str_replace("\n", '', $str);
        }

        return $strs;
    }

    private function prepare(MetaTemplate $template): bool
    {
        try {
            $template->params = $this->_params;

            ob_start();
            $this->_composed = [
                'title' => $this->cleanup([$template->renderTitle()])[0],
                'description' => $this->cleanup([$template->renderDescription()])[0],
                'noIndex' => $this->noIndex,
                'advanced' => $this->cleanup($template->renderAdvanced()),
            ];
            /*if ($this->getCanonical()) {
                $this->_composed['canonical'] = $this->getCanonical();
            }*/
            ob_end_clean();
        }
        catch (\Throwable $e) {
            Xcart::app()->logger->critical('Error in compose meta tags from template', [
                'template code' => $template->code,
                'message' => $e->getMessage(),
                'type' => get_class($e),
                'errcode' => $e->getCode(),
                'trace' => $e->getTraceAsString()
            ], 'error');

            $this->_composed = [];

            return false;
        }

        return true;
    }

}