<?php
namespace Modules\Meta\Helpers;


use Mindy\QueryBuilder\Q\QAnd;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Brand\Models\BrandModel;
use Modules\Meta\Models\Meta;
use Modules\Meta\Models\MetaTemplate;
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
    /** @var string */
    private $base_code;

    private $_params = [];
    private $_composed = [];
    private $_advanced = [];

    public function setParams(iterable $params): void
    {
        $this->_params = $params;
    }

    public function addParam(string $property, $val): void
    {
        $this->_params[$property] = $val;
    }

    public function getParams(): iterable
    {
        return $this->_params;
    }

    public function addAdvanced($tag, $type, $content): void
    {
        $this->_advanced[$tag][$type] = $content;
    }

    public function setTemplateCode(string $code): void
    {
        $this->template_code = $code;
    }

    public function setBaseCode(string $code): void
    {
        $this->base_code = $code;
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


    public function compose(): bool
    {
        $url = Xcart::app()->request->getUrl();
        $path = Xcart::app()->request->getPath();

        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();

        /** @var Meta $meta */
        $meta = Meta::objects()->filter([
            new QOr([
                new QAnd(['url' => $url, 'site' => $site->pk]),
                new QAnd(['url' => $path, 'site' => $site->pk]),
                new QAnd(['url' => $path, 'site__isnull' => true]),
            ])
        ])
        ->order(['-site_id', 'url'])
        ->limit(1)
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
        elseif ($this->template_code && $template = MetaTemplate::objects()->get(['code' => $this->template_code])) {
            /** @var MetaTemplate $template */
            $template->params = $this->_params;

            $this->_composed = [
                'title' => $template->renderTitle(),
                'description' => $template->renderDescription(),
                'advanced' => $this->render(),
            ];

            return true;
        }
        elseif ($this->base_code && !empty($this->_params['model']) && is_subclass_of($this->_params['model'], Model::class)) {
            $model = $this->_params['model'];

            switch ($this->base_code) {
                case 'brand': {
                    /** @var BrandModel $model */
                    $code =

                    break;
                }
            }
        }

        d($url, $path, $site);

        return false;
    }

    public function render(): ?string
    {
        if ($this->_composed) {
            return $this->renderTemplate('meta/meta_helper.tpl', $this->_composed);
        }

        return null;
    }

}