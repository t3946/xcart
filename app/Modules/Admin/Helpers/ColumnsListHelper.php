<?php
/**
 * Created by PhpStorm.
 * User: maksim
 * Date: 31.10.17
 * Time: 15:44
 */

class ColumnsListHelper
{

    public $columnDefaultTemplate = 'admin/list/columns/default.tpl';

    public function buildListColumns($model, $excluded = [], $defautlColumns = [], $userColumn = [])
    {
        $defaultColumns = $defautlColumns;
        $userColumns = $userColumn;

        $availableColumns = $this->getAvailableListColumns();
        $fields = $model->getFields();

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
            if (in_array($name, $excluded)) {
                continue;
            }

            if (is_array($field)) {
                $columnConfig = isset($config[$name]) ? $config[$name] : [];
                if (!isset($columnConfig['title']) && isset($field['label'])) {
                    $columnConfig['title'] = $field['label'];
                }
                if (!isset($columnConfig['order'])) {
                    /** @var Field $modelField */
                    $modelField = $model->getField($name);
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
}