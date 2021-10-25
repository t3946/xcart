<?php

namespace Modules\Pages\Models;

use Closure;
use Modules\Pages\PagesModule;
use Modules\Sites\Models\SiteModel;
use Modules\Translate\Models\LanguageModel;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Xcart\App\Helpers\Paths;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\ImageField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Fields\TextField;
use Xcart\App\Orm\SlugFields\AutoSlugField;
use Xcart\App\Orm\TreeModel;

/**
 * Class Page
 * @package Modules\Pages
 * @property string view
 * @method static \Modules\Pages\Models\PageManager objects($instance = null)
 */
class Page extends TreeModel
{
    const PAGE = 0;
    const PAGESET = 1;

    public $metaConfig = [
        'title' => 'name',
        'keywords' => 'content',
        'description' => 'content_short'
    ];

    /**
     * Prefix for cache
     */
    const CACHE_PREFIX = 'pages_';

    public static function getFields()
    {
        $sizes = Xcart::app()->getModule('Pages')->sizes;

        return array_merge(parent::getFields(), [
            'id' => [
                'class' => AutoField::class,
            ],
            'name' => [
                'class' => CharField::class,
                'required' => true,
                'verboseName' => 'Name'
            ],
            'url' => [
                'class' => CharField::class,
                'required' => true,
                'verboseName' => 'Url',
            ],
            'content' => [
                'class' => TextField::class,
                'null' => true,
                'verboseName' => 'Content'
            ],
            'content_short' => [
                'class' => TextField::class,
                'null' => true,
                'verboseName' => 'Short content'
            ],
            'file' => [
                'class' => ImageField::class,
                'null' => true,
                'sizes' => $sizes,
                'verboseName' => 'File',
            ],
            'created_at' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
                'verboseName' => 'Created at'
            ],
            'updated_at' => [
                'class' => DateTimeField::class,
                'autoNow' => true,
                'verboseName' => 'Updated at'
            ],
            'published_at' => [
                'class' => DateTimeField::class,
                'null' => true,
                'verboseName' => 'Published at',
            ],
            'view' => [
                'class' => CharField::class,
                'null' => true,
                'verboseName' => 'View'
            ],
            'view_children' => [
                'class' => CharField::class,
                'null' => true,
                'verboseName' => 'View children'
            ],
            'is_index' => [
                'class' => BooleanField::class,
                'verboseName' => 'Is index (main page)'
            ],
            'no_index' => [
                'class' => BooleanField::class,
                'verboseName' => 'No index'
            ],
            'is_published' => [
                'class' => BooleanField::class,
                'verboseName' => 'Is published',
                'default' => true
            ],
            'sorting' => [
                'class' => CharField::class,
                'null' => true,
                'choices' => [
                    'published_at' => 'Published time ASC',
                    '-published_at' => 'Published time DESC',
                    'lft' => 'Position ASC',
                    '-lft' => 'Position DESC',
                ],
                'verboseName' => 'Sorting'
            ],

            'sites' => [
                'class' => ManyToManyField::class,
                'modelClass' => SiteModel::class,
                'through' => PagesStorefrontLink::class,
                'verboseName' => 'Sites',
            ],
            'language' => [
                'class' => ForeignField::class,
                'field' => 'lang_id',
                'modelClass' => LanguageModel::class,
                'link' => ['lang_id' => 'lang_id'],
                'label' => 'Language'
            ],
            'parent' => [
                'field' => 'parent_id',
                'class' => ForeignField::class,
                'modelClass' => __CLASS__,
                'link' => ['parent_id' => 'id'],
                'null' => true,
            ],
        ]);
    }

    public static function objectsManager($instance = null)
    {
        /** @var  TreeModel $instance */
        $className = get_called_class();
        $instance = $instance ? $instance : new $className;
        return new PageManager($instance, $instance->getConnection());
    }

    public function __toString()
    {
        return (string)$this->name;
    }

    /**
     * @return array of page types
     */
    public function getTypes()
    {
        return [
            self::PAGE => 'Page',
            self::PAGESET => 'Set of pages',
        ];
    }

    /**
     * Return view for this model
     * @return string
     */
    public function getView()
    {
        if (empty($this->view)) {
            // Если представления не найдены берем стандартные
            $parent = $this->objects()->ancestors()->filter(['view_children__isnull' => false])->exclude(['view_children' => ''])->limit(1)->get();
            if ($parent) {
                $this->view = $parent->view_children;
            } else {
                $this->view = $this->getIsLeaf() ? 'page.tpl' : 'pageset.tpl';
            }
        }

        return $this->view;
    }

    /**
     * Get available views
     * @return array
     */
    public static function getViews()
    {
        $finder = Xcart::app()->getComponent('finder');
        $theme = $finder->theme;
        if ($theme instanceof Closure) {
            $theme = $theme->__invoke();
        }
        $pathApp = Paths::get($theme ? 'base.themes.' . $theme . '.templates.pages' : 'base.templates.pages');
        $pathModule = Paths::get('base.modules.pages.templates.pages');

        $templates_app = self::getTemplates($pathApp);
        $templates_module = self::getTemplates($pathModule);

        $templates = [null => ''];
        foreach ($templates_app as $template) {
            $templates[$template] = $template;
        }
        foreach ($templates_module as $template) {
            $templates[$template] = $template;
        }

        return $templates;
    }

    /**
     * Get templates
     * @param $dir
     * @return array
     */
    public static function getTemplates($dir)
    {
        if (!is_dir($dir)) {
            return [];
        }

        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        $files = [];
        /** @var RecursiveDirectoryIterator $it */
        while ($it->valid()) {
            if (!$it->isDot() && substr($it->getSubPathName(), 0, 1) !== '_') {
                $files[] = $it->getSubPathName();
            }
            $it->next();
        }
        return $files;
    }

    /**
     * Find parent views if this view is not set
     * @return bool|mixed
     */
    protected function getParentView()
    {
        $model = $this->tree()
            ->filter([
                'lft__lt' => $this->lft,
                'rgt__gt' => $this->rgt,
                'root' => $this->root,
                'view_children__isnull' => false
            ])
            ->order(['-lft'])
            ->get();

        return $model ? $model->view_children : null;
    }

    public function getAbsoluteUrl()
    {
        return Xcart::app()->router->url('page:view', ['url' => $this->url]);
    }

    /**
     * @return \Xcart\App\Orm\QuerySet
     */
    public function getChildrenQuerySet()
    {
        $qs = $this->objects()->published()->children();
        if ($this->sorting) {
            $qs = $qs->order([$this->sorting]);
        }
        return $qs;
    }

    /**
     * @param \Modules\Pages\Models\Page $owner
     * @param bool $isNew
     */
    public function beforeSave($owner, $isNew)
    {
        if ($owner->is_index) {
            $owner->objects()->update(['is_index' => false]);
        }

        if ($this->is_published) {
            if (empty($owner->published_at)) {
                $owner->published_at = new \DateTime();
            }
        }

    }

    /**
     * @param \Modules\Pages\Models\Page $owner
     */
    public function afterSave($owner, $isNew)
    {
//        Xcart::app()->cache->set(self::CACHE_PREFIX . $owner->getAbsoluteUrl(), $owner);
    }
}
