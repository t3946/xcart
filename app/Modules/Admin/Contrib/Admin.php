<?php

namespace Modules\Admin\Contrib;


use Mindy\QueryBuilder\Aggregation\Count;
use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Admin\Models\AdminConfig;
use Xcart\App\Exceptions\HttpException;
use Xcart\App\Form\ModelForm;
use Xcart\App\Helpers\ClassNames;
use Xcart\App\Helpers\SmartProperties;
use Xcart\App\Helpers\Text;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\QuerySet;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;
use Xcart\App\Template\Renderer;
use Xcart\App\Traits\SmartyRenderTrait;

abstract class Admin
{
    use SmartProperties, ClassNames, Renderer, SmartyRenderTrait;

    protected $parent_pk = null;

    public static $public = true;

    public $allTemplate = 'admin/all.tpl';
    public $listItemActionsTemplate = 'admin/list/_item_actions.tpl';
    public $listPaginationTemplate = 'admin/list/_pagination.tpl';

    public $infoTemplate = 'admin/info.tpl';
    public $createTemplate = 'admin/create.tpl';
    public $updateTemplate = 'admin/update.tpl';
    public $formTemplate = 'admin/form/_form.tpl';
    public $columnDefaultTemplate = 'admin/list/columns/default.tpl';

    public $pageSize = 20;
    public $pageSizes = [20, 50, 100];

    /**
     * Sorting column
     *
     * @var null|string
     */
    public $sort = null;

    public $innerRender = false;

    public $autoFixSort = true;

    /** @var Model */
    public $model;

    /**
     * @return mixed
     */
    public function getAvailableListColumns()
    {
        return [
            'id' => [
                'title' => 'ID',
                'template' => $this->columnDefaultTemplate,
                'order' => 'id'
            ],
            '(string)' => [
                'title' => $this->getItemName(),
                'template' => $this->columnDefaultTemplate,
                'order' => 'id'
            ],
        ];
    }

    public function getListColumns()
    {
        return ['id', '(string)'];
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
            'view',
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
            'update',
            'remove'
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
        $message = 'Изменения успешно применены';

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
        return $actions;
    }

    /**
     * @TODO From cookies/db/etc
     * @return null|string[]
     */
    public function getUserColumns()
    {
        $config = AdminConfig::fetch(static::getModuleName(), static::classNameShort());
        return $config->getColumnsList();
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
            }
        }
        foreach ($fields as $name => $field) {
            if (in_array($name, $excluded) || array_key_exists($name, $config)) {
                continue;
            }

            if (is_array($field)) {
                $columnConfig = isset($config[$name]) ? $config[$name] : [];
                if (!isset($columnConfig['title']) && ( isset($field['label']) || isset($field['verboseName']) )) {
                    if (!empty($field['label'])) {
                        $columnConfig['title'] = $field['label'];
                    }
                    elseif (!empty($field['verboseName'])) {
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
                $columnConfig['template'] = $this->columnDefaultTemplate;
                $config[$name] = $columnConfig;
            }
        }
        foreach ($config as $key => $item) {
            if (!isset($item['title'])) {
                $config[$key]['title'] = ucfirst($key);
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
        $order = isset($_GET['order']) ? $_GET['order'] : null;
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
     * @param $qs QuerySet
     * @return QuerySet
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
        }
        else {
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
     * @param $qs QuerySet
     * @return mixed
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
                $pk = $model->getPrimaryKeyName();

                $newQs->order([$this->sort, $pk])->update([
                    $this->sort => new Expression("@position := (@position + 1)")
                ]);
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
            'pk' => $pk ?: $this->getModelPk(),
        ], $query);
    }

    public function getInfoUrl($pk = null)
    {
        return Xcart::app()->router->url('admin:info', [
            'module' => static::getModuleName(),
            'admin' => static::classNameShort(),
            'pk' => $pk ?: $this->getModelPk(),
        ]);
    }

    public function getRemoveUrl($pk = null)
    {
        return Xcart::app()->router->url('admin:remove', [
            'module' => static::getModuleName(),
            'admin' => static::classNameShort(),
            'pk' => $pk ?: $this->getModelPk(),
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
            $value = $value->{$name};
        }
        return $value;
    }

    public function all($pk = null)
    {
        $this->setBreadcrumbs();
        $search = isset($_GET['search']) ? $_GET['search'] : null;

        $qs = $this->getQuerySet();
        $qs = $this->handleSearch($qs, $search);
        $qs = $this->applyOrder($qs);
        $qs = $this->fixSort($qs);

        $pagination = new Pagination($qs, [
            'defaultPageSize' => $this->pageSize,
            'pageSizes' => $this->pageSizes
        ], new QuerySetDataSource());

        $this->renderInternal($this->allTemplate, [
            'objects' => $pagination->paginate(),
            'pagination' => $pagination,
            'order' => $this->getOrder(),
            'search' => $this->getSearchColumns(),
            'columns' => $this->buildListColumns(),
            'canSort' => $this->getCanSort($qs),
        ]);
    }

    public function info($pk)
    {
        $object = $this->getModelOr404($pk);

        $this->setBreadcrumbs('Информация');
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
            foreach ($qs->all() as $v)
            {
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

        $form->setInstance($model);

        $request = Xcart::app()->request;
        if ($request->getIsPost() && $form->populate($_POST, $_FILES)) {
            if ($form->isValid() && $form->save()) {
                if ($request->getIsAjax()) {
                    $this->jsonResponse(['state' => 'success']);
                }
                else {
                    Xcart::app()->flash->success('Изменения сохранены');

                    $next = isset($_POST['save']) ? $_POST['save']: 'save';
                    if ($next == 'save') {
                        $request->redirect(($this->parent_pk) ? $this->getParentAllUrl():$this->getAllUrl());
                    }
                    elseif ($next == 'save-stay') {
                        $request->redirect($this->getUpdateUrl($model->pk));
                    }
                    else {
                        $request->redirect($this->getCreateUrl());
                    }
                }
            } else {
                if (!$request->getIsAjax()) {
                    Xcart::app()->flash->error('Пожалуйста, исправьте ошибки');
                }
            }
        }

        $this->setBreadcrumbs(($pk)? 'Редактировать' : 'Создать');
        $template = $new ? $this->createTemplate : $this->updateTemplate;
        $this->renderInternal($template, [
            'form' => $form,
            'model' => $model,
            'new' => $new
        ]);
    }

    /**
     * @param $qs QuerySet
     * @return bool
     */
    public function getCanSort($qs)
    {
        if ($this->sort) {
            $order = $qs->getOrder();
            return $order == [$this->sort];
        } else {
            return false;
        }
    }

    public function sort($pkList, $to, $prev, $next)
    {
        $qs = $this->getQuerySet();
        $positions = $qs->filter(['pk__in' => $pkList])->valuesList(['position'], true);
        asort($positions);
        $result = array_combine($pkList, $positions);

        $model = $qs->getModel();
        foreach ($result as $pk => $position) {
            $model::objects()->filter(['pk' => $pk])->update(['position' => $position]);
        }
        $this->jsonResponse([
            'success' => true
        ]);
    }

    public function setColumns($columns)
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
        }
        else {
            echo $this->renderSmarty("admin/home.tpl", [
                'single_mode' => true,
                'main'        => 'raw_html',
                'content'     =>  $this->render($view, $params),
            ]);
        }
    }


    public function getBreadcrumbs()
    {
        return [[$this->getName(), $this->getAllUrl()]];
    }

    /**
     * @param $admin Admin
     */
    public function setBreadcrumbs($last = null)
    {
        foreach ($this->getBreadcrumbs() as $bread) {
            list ($name, $url) = $bread;
            Xcart::app()->breadcrumbs->add($name, $url);
        }

        if ($last) {
            Xcart::app()->breadcrumbs->add($last);
        }
    }
}