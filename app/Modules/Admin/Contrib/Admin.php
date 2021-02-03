<?php

namespace Modules\Admin\Contrib;


use Mindy\QueryBuilder\Aggregation\Count;
use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Admin\Models\AdminConfig;
use Xcart\App\Exceptions\HttpException;
use Xcart\App\Form\Form;
use Xcart\App\Form\ModelForm;
use Xcart\App\Helpers\ClassNames;
use Xcart\App\Helpers\SmartProperties;
use Xcart\App\Helpers\Text;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Fields\TreeForeignField;
use Xcart\App\Orm\Manager;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\QuerySet;
use Xcart\App\Orm\TreeManager;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;
use Xcart\App\Template\Renderer;
use Xcart\App\Traits\SmartyRenderTrait;

abstract class Admin
{
    use SmartProperties, ClassNames, Renderer, SmartyRenderTrait;

    protected $parent_pk;

    private $admin_config;

    public $section;

    public static $public = true;

    public $allTemplate = 'admin/all.tpl';
    public string $allList = 'admin/list/_list.tpl';
    public $listItemActionsTemplate = 'admin/list/_item_actions.tpl';
    public $listPaginationTemplate = 'admin/list/_pagination.tpl';
    public $listRowTemplate = 'admin/list/_tr.tpl';

    public $infoTemplate = 'admin/info.tpl';
    public $createTemplate = 'admin/create.tpl';
    public $updateTemplate = 'admin/update.tpl';
    public $filterTemplate = 'admin/filter.tpl';
    public $formTemplate = 'admin/form/_form.tpl';
    public $columnDefaultTemplate = 'admin/list/columns/default.tpl';

    public $pageSize = 20;
    public $pageSizes = [20, 50, 100];

    /**
     * Sorting column
     *
     * @var null|string
     */
    public ?string $sort = null;

    public bool $innerRender = false;

    public bool $autoFixSort = true;

    /** @var Model */
    public $model;

    /**
     * @return mixed
     */
    public function getAvailableListColumns()
    {
        return [];
    }

    public function getListColumns()
    {
        return [];
    }

    public function getExcludedColumns()
    {
        return [];
    }

    /**
     * Available string options: "update", "view", "remove", "info"
     * @return array
     */
    public function getListItemActions()
    {
        return [
            'update',
            'remove'
        ];
    }

    /**
     * @return array
     *
     * Example:
     *
     * [
     *  'remove',
     *  'activate' => 'Activate items',
     *  'process' => [
     *      'title' => 'Process',
     *      'callback' => function ($qs, $ids) {
     *          $qs->filter(['status' => 1])->delete();
     *          return true;
     *      }
     *  ],
     * 'example' => [
     *      'title' => 'Example return',
     *      'callback' => function ($qs, $ids) {
     *          $qs->filter(['status' => 3])->delete();
     *          return [true, "Objects successfully removed"];
     *      }
     *  ],
     *  'do' => [
     *      'title' => 'Do some action',
     *      'callback' => [$this, 'do']
     *  ]
     * ]
     */
    public function getListGroupActions()
    {
        return [
            'add',
        ];
    }

    public function getListGroupActionsConfig()
    {
        $actions = $this->getListGroupActions();
        $result = [];
        foreach ($actions as $key => $item) {
            $title = null;
            $callback = null;

            if (is_numeric($key) && is_string($item)) {
                $id = $item;
            } elseif (is_string($key) && $item) {
                $id = $key;
                if (is_array($item)) {
                    $title = isset($item['title']) ? $item['title'] : [];
                    $callback = isset($item['callback']) ? $item['callback'] : [];
                } elseif (is_string($item)) {
                    $title = $item;
                }
            } else {
                continue;
            }
            if (!$title) {
                $title = Text::ucfirst($id);
            }
            if (!$callback) {
                $callback = [$this, 'group' . Text::ucfirst($id)];
            }
            $result[$id] = [
                'title' => $title,
                'callback' => $callback
            ];
        }
        return $result;
    }

    public function handleGroupAction($action, $pkList = [])
    {
        /** @var Flash $flash */
        $flash = Xcart::app()->flash;
        $request = Xcart::app()->request;

        $actions = $this->getListGroupActionsConfig();
        if (!isset($actions[$action])) {
            throw new HttpException(404);
        }
        $actionConfig = $actions[$action];
        $callback = $actionConfig['callback'];
        $qs = $this->getQuerySet();
        $qs = $qs->filter(['pk__in' => $pkList]);
        $result = call_user_func($callback, $qs, $pkList);

        $success = true;
        $message = 'Changes have been successfully applied.';

        if (is_array($result) && count($result) == 2 && is_bool($result[0]) && is_string($result[1])) {
            $success = $result[0];
            $message = $result[1];
        } elseif ($result !== true) {
            $success = false;
            if (is_string($result)) {
                $message = $result;
            } else {
                $message = 'При применении изменений произошла ошибка';
            }
        }

        if ($request->getIsAjax()) {
            $this->jsonResponse([
                'success' => $success,
                'message' => $message
            ]);
            Xcart::app()->end();
        } else {
            $flash->add($message, $success ? 'success' : 'error');
            $request->redirect($this->getAllUrl());
        }
    }

    public function getListDropDownGroupActions()
    {
        $actions = $this->getListGroupActionsConfig();
        if (array_key_exists('remove', $actions)) {
            unset($actions['remove']);
        }
        if (array_key_exists('update', $actions)) {
            unset($actions['update']);
        }
        if (array_key_exists('add', $actions)) {
            unset($actions['add']);
        }

        return $actions;
    }

    /**
     * @TODO From cookies/db/etc
     * @return null|string[]
     */
    public function getUserColumns()
    {
        return [];
        /*$config = $this->getConfig();
        return $config->getColumnsList();*/
    }


    public function getConfig(): AdminConfig
    {
        if (!$this->admin_config) {
            $this->admin_config = AdminConfig::fetch(static::getModuleName(), static::classNameShort());
        }

        return $this->admin_config;
    }

    public function buildListColumns()
    {
        $defaultColumns = $this->getListColumns();
        $userColumns = $this->getUserColumns();

        $availableColumns = $this->getAvailableListColumns();
        $fields = $this->getModel()->getFields();
        $excluded = $this->getExcludedColumns();

        $config = [];
        $enabled = [];
        foreach ($defaultColumns as $key => $value) {
            if (is_string($key) && is_array($value)) {
                $enabled[] = $value;
                $config[$key] = $value;
            } elseif (is_string($value)) {
                $config[$value] = [];
                $enabled[] = $value;
            }
        }
        foreach ($availableColumns as $key => $value) {
            if (is_string($key) && is_array($value) && (!array_key_exists($key, $config) || empty($config[$key]))) {
                $config[$key] = $value;
            } elseif (is_string($value) && !array_key_exists($value, $config)) {
                $config[$value] = [];

                if ($field = $this->getModel()->getField($value)) {
                    $config[$value] = [
                        'title' => $field->getVerboseName(),
                        'template' => $this->columnDefaultTemplate,
                    ];
                }
            }
        }
        foreach ($fields as $name => $field) {
            if (in_array($name, $excluded)) {
                continue;
            }

            if (is_array($field)) {
                $columnConfig = $config[$name] ?? [];
                if (!isset($columnConfig['title']) && (isset($field['label']) || isset($field['verboseName']))) {
                    if (!empty($field['label'])) {
                        $columnConfig['title'] = $field['label'];
                    } elseif (!empty($field['verboseName'])) {
                        $columnConfig['title'] = $field['verboseName'];
                    }
                }
                if (!isset($columnConfig['order'])) {
                    /** @var Field $modelField */
                    $modelField = $this->getModel()->getField($name);
                    $attribute = $modelField->getAttributeName();
                    if ($attribute) {
                        $columnConfig['order'] = $attribute;
                    }
                }
                $columnConfig['template'] ??= $this->columnDefaultTemplate;
                $config[$name] = $columnConfig;
            }
        }
        foreach ($config as $key => $item) {
            if (!isset($item['title'])) {
                $config[$key]['title'] = ucfirst($key);
            }
            if (!isset($item['template'])) {
                $config[$key]['template'] = $this->columnDefaultTemplate;
            }
        }
        if ($userColumns) {
            $safeUserColumns = [];
            foreach ($userColumns as $column) {
                if (array_key_exists($column, $config)) {
                    $safeUserColumns[] = $column;
                }
            }
            if ($safeUserColumns) {
                $enabled = $safeUserColumns;
            }
        }

        return [
            'enabled' => $enabled,
            'config' => $config
        ];
    }

    public function getFilterForm(): ?Form
    {
        return null;
    }

    public function getSearchColumns()
    {
        return [];
    }

    /**
     * @return array
     *  Example: [
     *      'brand' => [
     *          'class' => 'Modules\Brand\Models\BrandModel',
     *          'columns' => [
     *              'name', 'code', 'pk'
     *          ]
     *      ]
     *  ]
     */
    public function getSuggestionColumns()
    {
        return [];
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        if (!$this->model) {
            $this->model = $this->getForm()->getModel();
        }

        return $this->model;
    }

    /**
     * @return Model
     */
    public function newModel()
    {
        $model = $this->getModel();
        return new $model;
    }

    /**
     * @return ModelForm
     */
    abstract public function getForm();

    /**
     * @return ModelForm
     */
    public function getUpdateForm()
    {
        return $this->getForm();
    }

    /**
     * get sql query builder based on current model
     * @return QuerySet
     */
    public function getQuerySet()
    {
        $model = $this->getModel();
        return $model->objects()->getQuerySet();
    }

    /**
     * @return array|null
     */
    public function getOrder()
    {
        $order = $_GET['order'] ?? null;
        if ($order) {
            $clean = $order;
            $asc = true;
            if (Text::startsWith($clean, '-')) {
                $asc = false;
                $clean = mb_substr($clean, 1);
            }
            return [
                'raw' => $order,
                'clean' => $clean,
                'asc' => $asc,
                'desc' => !$asc
            ];
        }
        return null;
    }

    /**
     * add filter conditions to query set
     * @param $qs QuerySet
     * @return QuerySet
     * @throws \Exception
     */
    public function handleSearch($qs, $search)
    {
        $columns = $this->getSearchColumns();
        if ($search && $columns) {
            $orData = [];
            foreach ($columns as $column) {
                $orData[] = [$column . '__contains' => $search];
            }
            $filter = [new QOr($orData)];
            $qs = $qs->filter($filter);
        }
        return $qs;
    }

    public function handleFilter(QuerySet $qs, $form): QuerySet
    {

        foreach ($form->getAttributes() as $key => $value) {
            if ($value && $model_field = $this->getModel()->getFieldsInit()[$key]) {
                if ($model_field instanceof ManyToManyField) {
                    $key = "{$model_field->getName()}__{$model_field->getRelatedModelPk()}";
                    if (is_array($value)) {
                        $qs->filter(["{$key}__in" => $value]);
                    } else {
                        $qs->filter([$key => $value]);
                    }
                }
                elseif ($model_field instanceof TreeForeignField) {
                    [$lft, $rgt, $root] = $model_field->getRelatedModel()::objects()->filter(['pk' => $value])->valuesList(['lft', 'rgt', 'root'], true);
                    $qs->filter([
                        "{$model_field->getName()}__lft__gte" => $lft,
                        "{$model_field->getName()}__rgt__lte" => $rgt,
                        "{$model_field->getName()}__root" => $root,
                    ]);
                }
                elseif ($model_field instanceof ForeignField) {
                    $key = "{$model_field->getName()}__{$model_field->getTo()}";
                    if (is_array($value)) {
                        $qs->filter(["{$key}__in" => $value]);
                    } else {
                        $qs->filter([$key => $value]);
                    }
                }
            }
        }
        return $qs;
    }

    /**
     * @return QuerySet
     */
    public function handleSuggestion($entity, $search)
    {
        $entity = strtolower($entity);
        $entitys = $this->getSuggestionColumns();

        if (array_key_exists($entity, $entitys)) {
            $class = $entitys[$entity]['class'];
            $columns = $entitys[$entity]['columns'];

            /** @var Model $model */
            $model = new $class();
            $qs = $model->objects()->getQuerySet();

            if ($search && $columns) {
                $orData = [];
                foreach ($columns as $column) {
                    $orData[] = [$column . '__contains' => $search];
                }
                $filter = [new QOr($orData)];
                $qs = $qs->filter($filter);
            }
        } else {
            throw new \UnexpectedValueException("Entity: {$entity} not set in suggestion columns");
        }

        return $qs;
    }

    public function checkSuggestionEntity($entity)
    {
        $entity = strtolower($entity);
        $entitys = $this->getSuggestionColumns();

        return array_key_exists($entity, $entitys);
    }

    /**
     * @param $qs QuerySet
     * @return QuerySet
     */
    public function applyOrder($qs)
    {
        $order = $this->getOrder();

        if ($order && isset($order['raw'])) {
            $qs->order([
                $order['raw']
            ]);
        } else if ($this->sort) {
            $qs->order([
                $this->sort
            ]);
        }
        return $qs;
    }

    /**
     * @param $qs QuerySet|Manager
     * @return QuerySet|Manager
     * @throws \Doctrine\DBAL\DBALException
     */
    public function fixSort($qs)
    {
        if ($this->sort && $this->autoFixSort && $this->getCanSort($qs)) {
            $newQs = clone($qs);
            $raw = $newQs->group([$this->sort])->having(new Expression('c > 1'))->valuesList([$this->sort, 'c' => new Count('*')]);
            if ($raw) {
                $newQs = clone($qs);
                $connection = $newQs->getConnection();
                $connection->query('SET @position = 0;');

                $model = $this->getModel();
                $newQs->order([$this->sort, "-{$model::getPrimaryKeyName()}"]);

                $qb = $newQs->getQueryBuilder();
                $qb->setAlias(null);
                $sql = strtr('{update}{where}{order}', [
                    '{update}' => $qb->getAdapter()->sqlUpdate($model::tableName(), [$this->sort => new Expression("@position := (@position + 10)")]),
                    '{where}' => $qb->buildWhere(),
                    '{order}' => $qb->buildOrder()
                ]);
                $connection->query($sql);
            }
        }
        return $qs;
    }

    public function setModel(Model $model)
    {
        $this->model = $model;
        return $this;
    }

    public function getModelPk()
    {
        if ($this->model) {
            return $this->model->pk;
        }

        return null;
    }

    /**
     * @return array
     */
    public function getCommonData()
    {
        return [
            'admin' => $this,
            'adminClass' => static::classNameShort(),
            'moduleClass' => static::getModuleName()
        ];
    }

    public function getId()
    {
        return implode('-', [static::getModuleName(), static::classNameShort()]);
    }

    public function getAllUrl()
    {
        return Xcart::app()->router->url('admin:list', [
            'module' => static::getModuleName(),
            'admin' => static::classNameShort()
        ]);
    }

    public function getCreateUrl()
    {
        return Xcart::app()->router->url('admin:create', [
            'module' => static::getModuleName(),
            'admin' => static::classNameShort()
        ]);
    }

    public function getUpdateUrl($pk = null)
    {
        $query = [];

        if (Xcart::app()->request->get->has('popup')) {
            $query['popup'] = true;
        }

        return Xcart::app()->router->url('admin:update', [
            'module' => static::getModuleName(),
            'admin' => static::classNameShort(),
            'pk' => $pk ?? $this->getModelPk(),
        ], $query);
    }

    public function isAjaxUpdate(): bool
    {
        return false;
    }

    public function isAjaxCreate(): bool
    {
        return false;
    }

    public function getInfoUrl($pk = null)
    {
        return Xcart::app()->router->url('admin:info', [
            'module' => static::getModuleName(),
            'admin' => static::classNameShort(),
            'pk' => $pk ?? $this->getModelPk(),
        ]);
    }

    public function getRemoveUrl($pk = null)
    {
        return Xcart::app()->router->url('admin:remove', [
            'module' => static::getModuleName(),
            'admin' => static::classNameShort(),
            'pk' => $pk ?? $this->getModelPk(),
        ]);
    }

    public function getGroupActionUrl()
    {
        return Xcart::app()->router->url('admin:group_action', [
            'module' => static::getModuleName(),
            'admin' => static::classNameShort()
        ]);
    }

    public function getSortUrl()
    {
        return Xcart::app()->router->url('admin:sort', [
            'module' => static::getModuleName(),
            'admin' => static::classNameShort()
        ]);
    }

    public function getColumnsUrl()
    {
        return Xcart::app()->router->url('admin:columns', [
            'module' => static::getModuleName(),
            'admin' => static::classNameShort()
        ]);
    }

    public function getSuggestionUrl($entity)
    {
        if ($this->checkSuggestionEntity($entity)) {
            return Xcart::app()->router->url('admin:suggestion', [
                'module' => static::getModuleName(),
                'admin' => static::classNameShort(),
                'entity' => $entity,
            ]);
        }

        return null;
    }

    public function getItemProperty(Model $item, $property)
    {
        $value = $item;
        $data = explode('__', $property);
        foreach ($data as $name) {
            $value = ($value->$name instanceof Model) ? (string)$value->$name : $value->$name;
        }
        return $value;
    }

    public function all($pk = null)
    {
        $request = Xcart::app()->request;

        $this->setBreadcrumbs();
        $search = $_GET['search'] ?? null;
        $qs = $this->getQuerySet();

        if ($request->getIsGet() && $filter_form = $this->getFilterForm()) {
            $filter_form->populate($_GET, $_FILES);
            $qs = $this->handleFilter($qs, $filter_form);
        }

        $qs = $this->handleSearch($qs, $search);
        $qs = $this->applyOrder($qs);
        $qs = $this->fixSort($qs);

        $pagination = new Pagination($qs, [
            'pageSize' => $this->getConfig()->page_size ?: $this->pageSize,
            'pageSizes' => $this->pageSizes
        ], new QuerySetDataSource());

        if ($request->get->has($pagination->getPageSizeKey())) {
            $this->getConfig()->page_size = $request->get->get($pagination->getPageSizeKey());
            $this->getConfig()->save();
        }

        $this->renderInternal($this->allTemplate, [
            'objects' => $pagination->paginate(),
            'pagination' => $pagination,
            'order' => $this->getOrder(),
            'search' => $this->getSearchColumns(),
            'columns' => $this->buildListColumns(),
            'canSort' => $this->getCanSort($qs),
            'filter_form' => $filter_form ?? null,
        ]);
    }

    public function info($pk)
    {
        $object = $this->getModelOr404($pk);

        $this->setBreadcrumbs('Information');
        $this->renderInternal($this->infoTemplate, [
            'object' => $object,
            'fields' => $object::getFields(),
        ]);
    }

    public function suggestions($entity)
    {
        $search = isset($_GET['term']) ? $_GET['term'] : null;

        if ($qs = $this->handleSuggestion($entity, $search)) {
            $data = [];
            foreach ($qs->all() as $v) {
                $data[] = ['id' => $v->pk, 'text' => (string)$v];
            }

            $this->jsonResponse(['items' => $data]);
        }
    }

    public function remove($pk)
    {
        $object = $this->getModelOr404($pk);
        $removed = $object->delete();
        if ($removed) {
            $data = ['success' => true];
        } else {
            $data = ['error' => 'При удалении объекта произошла ошибка'];
        }
        $this->jsonResponse($data);
    }

    /**
     * @param $qs QuerySet
     * @param $pkList
     * @return bool
     */
    public function groupRemove($qs, $pkList)
    {
        $qs->delete();
        return [true, "Объекты успешно удалены"];
    }

    public function render($template, $data = [])
    {
        return $this->renderTemplate($template, array_merge($data, $this->getCommonData()));
    }

    public function jsonResponse($data = [])
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * @param $pk
     * @return null|Model
     * @throws HttpException
     */
    public function getModelOr404($pk)
    {
        $object = $this->getModel()->objects()->filter(['pk' => $pk])->limit(1)->get();
        if (!$object) {
            throw new HttpException(404);
        }
        return $object;
    }

    public function getFormFieldsets()
    {
        return $this->getForm()->getFieldsets();
    }

    public function create($pk = null)
    {
        $this->update(null, $pk);
    }

    public function updateall()
    {

    }

    public function update($pk = null, $parent_id = null)
    {
        /** @var \Xcart\App\Orm\TreeModel $model */
        $new = false;
        if (is_null($pk)) {
            $new = true;
            $model = $this->newModel();
            $form = $this->getForm();

            if ($parent_id) {
                $model->parent_id = $parent_id;
            }
        } else {
            $model = $this->getModelOr404($pk);
            $form = $this->getUpdateForm();
        }

        if (isset($model->parent_id)) {
            $this->parent_pk = $model->parent_id;
        }

        $this->model = $model;
        $form->setInstance($model);

        if ((string)$model) {
            $bread = $new ? sprintf("Adding a new %s", strtolower($model)) : (string)$model;
            $this->setBreadcrumbs($bread);
        }

        $request = Xcart::app()->request;
        if ($request->getIsGet()) {
            $form->populate($_GET, $_FILES);
        }

        if ($request->getIsPost() && $form->populate($_POST, $_FILES)) {
            if ($form->isValid() && $form->save()) {
                if ($request->getIsAjax()) {
                    $this->jsonResponse(['status' => 'success', 'close' => true]);
                    return;
                }
                Xcart::app()->flash->success('Changes have been successfully applied.');

                $this->redirectAfterSave($model, $_POST['save'] ?? 'save');

            } elseif (!$request->getIsAjax()) {
                Xcart::app()->flash->error('Please, fix errors');
            }
        }

        $template = $new ? $this->createTemplate : $this->updateTemplate;
        $this->renderInternal($template, [
            'form' => $form,
            'model' => $model,
            'new' => $new
        ]);
    }

    public function redirectAfterSave(Model $model, $next): void
    {
        $request = Xcart::app()->request;
        if ($next === 'save') {
            $request->redirect(($this->parent_pk) ? $this->getParentAllUrl() : $this->getAllUrl());
        } elseif ($next === 'save-stay') {
            $request->redirect($this->getUpdateUrl($model->pk));
        } else {
            $request->redirect($this->getCreateUrl());
        }
    }

    /**
     * @param $qs QuerySet|Manager
     * @return bool
     */
    public function getCanSort($qs): bool
    {
        if ($this->sort) {
            $order = $qs->getOrder();
            return $order === [$this->sort];
        }

        return false;
    }

    public function sort($pkList, $to, $prev, $next, $id = null): void
    {
        if ($id) {
            $this->parent_pk = $id;
        }

        $sort = $this->sort ?? 'position';
        $qs = $this->getQuerySet();
        $positions = $qs->filter(['pk__in' => $pkList])->valuesList([$sort], true);
        asort($positions);
        $result = array_combine($pkList, $positions);

        $model = $qs->getModel();
        foreach ($result as $pk => $position) {
            $model::objects()->filter(['pk' => $pk])->update([$sort => $position]);
        }
        $this->jsonResponse([
            'success' => true
        ]);
    }

    public function setColumns($columns): void
    {
        $config = AdminConfig::fetch(static::getModuleName(), static::classNameShort());
        $config->setColumnsList($columns);
        $this->jsonResponse([
            'success' => true
        ]);
    }

    /**
     * @return string
     */
    public static function getName()
    {
        return static::classNameShort();
    }

    /**
     * @return string
     */
    public static function getItemName()
    {
        return static::classNameShort();
    }

    //@TODO: Remove after delete smarty
    public function renderInternal($view, $params)
    {
        $params = array_replace($this->getCommonData(), $params);

        if (
            Xcart::app()->request->getIsAjax()
            || Xcart::app()->request->get->has('popup')
            || $this->innerRender
        ) {
            echo $this->render($view, $params);
        } else {
            echo $this->renderSmarty("admin/home.tpl", [
                'single_mode' => true,
                'main' => 'raw_html',
                'content' => $this->render($view, $params),
            ]);
        }
    }


    public function getBreadcrumbs(): array
    {
        return [[static::getName(), $this->getAllUrl()]];
    }

    /**
     * @param $admin Admin
     */
    public function setBreadcrumbs($last = null): void
    {
        foreach ($this->getBreadcrumbs() as $bread) {
            [$name, $url] = $bread;
            Xcart::app()->breadcrumbs->add($name, $url);
        }

        if ($last) {
            Xcart::app()->breadcrumbs->add($last);
        }
    }
}