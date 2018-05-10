<?php
namespace Modules\Meta\Components;


use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;

trait MetaTrait
{
    /**
     * @var bool
     */
    public $titleSortAsc = true;
    /**
     * @var string
     */
    protected $canonical;
    /**
     * @var string
     */
    protected $keywords;
    /**
     * @var string
     */
    protected $description;

    /**
     * @var string[]
     */
    protected $title = [];

    /**
     * @var string|null
     */
    protected $metaTemplate = 'default';
    protected $metaBase;

    /**
     * @var array
     */
    protected $metaTemplateParams = [];

    /**
     * @param $canonical string
     */
    public function setCanonical($canonical): void
    {
        if($canonical instanceof Model) {
            $canonical = $canonical->getAbsoluteUrl();
        }
        $this->canonical = Xcart::app()->request->getHostInfo() . '/' . ltrim($canonical, '/');
    }

    /**
     * @return string
     */
    public function getCanonical(): string
    {
        return $this->canonical;
    }

    /**
     * @param $keywords string
     */
    public function setKeywords($keywords): void
    {
        $this->keywords = $keywords;
    }

    /**
     * @return string
     */
    public function getKeywords():? string
    {
        return $this->keywords;
    }

    /**
     * @param $description string
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription():? string
    {
        return $this->description;
    }

    /**
     * @param $values array|string
     * @return $this
     */
    public function setBreadcrumbs($values): self
    {
        if (!\is_array($values)) {
            $values = [$values];
        }

        foreach ($values as $value => $url) {
            $this->addBreadcrumb($value, $url);
        }

        return $this;
    }

    /**
     * @param $name
     * @param $url
     * @return $this
     */
    public function addBreadcrumb($name, $url = null, array $items = []): self
    {
        $ba = Xcart::app()->breadcrumbs->getActive();
        Xcart::app()->breadcrumbs->setActive('metaTrait');
        Xcart::app()->breadcrumbs->add($name, $url);
        Xcart::app()->breadcrumbs->setActive($ba);

        return $this;
    }

    /**
     * @return array
     */
    public function getBreadcrumbs(): array
    {
        return Xcart::app()->breadcrumbs->get('metaTrait');
    }

    /**
     * @param $value
     * @return $this
     */
    public function addTitle($value): self
    {
        $this->title[] = (string) $value;
        return $this;
    }

    /**
     * @param $value array|string
     * @return $this
     */
    public function setTitle($value): self
    {
        if (!\is_array($value)) {
            $value = [$value];
        }

        $this->title = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getTitle(): array
    {
        $title = $this->title;
        if ($this->titleSortAsc) {
            krsort($title);
        }
        return $title;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setPageTitle($value): self
    {
        return $this->setTitle($value);
    }

    /**
     * @return array
     */
    public function getPageTitle(): array
    {
        return $this->getTitle();
    }

    /**
     * @param $template string
     * @param array $params
     */
    public function setMetaTemplate($template, array $params = []): void
    {
        $this->metaTemplate = $template;
        $this->metaTemplateParams = $params;
    }

    public function setMetaBase($type, array $params = []): void
    {
        $this->metaBase = $type;
        $this->metaTemplateParams = $params;
    }

    public function addMetaTemplateParam($param, $data): void
    {
        $this->metaTemplateParams[$param] = $data;
    }

    /**
     * @return mixed
     */
    public function getMetaTemplate():? string
    {
        return $this->metaTemplate;
    }

    public function getMetaBase():? int
    {
        return $this->metaBase;
    }

    /**
     * @return mixed
     */
    public function getMetaTemplateParams():? array
    {
        return $this->metaTemplateParams;
    }
}
