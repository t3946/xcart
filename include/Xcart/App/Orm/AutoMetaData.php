<?php
namespace Xcart\App\Orm;

use Doctrine\DBAL\Schema\Column;
use ReflectionMethod;
use Xcart\App\Orm\Fields\BigIntField;
use Xcart\App\Orm\Fields\BlobField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\DecimalField;
use Xcart\App\Orm\Fields\FloatField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\TextField;
use Xcart\App\Orm\Fields\TimeField;

class AutoMetaData extends MetaData
{

    protected function init($className)
    {
        if ((new ReflectionMethod($className, 'getFields'))->isStatic()
            || (new ReflectionMethod($className, 'getColumns'))->isStatic())
        {
            parent::init($className);
        }

        $primaryFields = [];

        /** @var Data|Model $model */
        $model = new $className;
        $connection = $model->getConnection();
        $sm = $connection->getSchemaManager();

//        func_dump($sm->listTableIndexes($model->getTableName()));
//        func_dump($sm->listTableColumns($model->getTableName()));

        foreach ($sm->listTableColumns($model->getTableName()) as $column)
        {
            $name = $column->getName();

            if (!isset($this->fields[$name]))
            {
                $config = $this->getConfigFromDBAL($column);

                $field = $this->createField($config);
                $field->setName($name);
                $field->setModelClass($className);

                $this->fields[$name] = $field;
                $this->mapping[$field->getAttributeName()] = $name;

                if ($field->primary) {
                    $primaryFields[] = $field->getAttributeName();
                }
            }
        }
        if (empty($primaryFields) && empty($this->primaryKeys)) {
            $this->primaryKeys = call_user_func([$className, 'getPrimaryKeyName']);
        }
        elseif (!empty($primaryFields)) {

            $this->primaryKeys = $primaryFields;
        }
    }

    protected function getConfigFromDBAL(Column $column)
    {
        $config = [
            'null' => !$column->getNotnull(),
            'default' => $column->getDefault()
        ];


        if ($column->getLength()) {
            $config['length'] = $column->getLength();
        }

        switch ($column->getType()->getName())
        {
            case 'smallint' :
            case 'integer' : {
                $config['class'] = IntField::className();
                break;
            }
            case 'bigint' : {
                $config['class'] = BigIntField::className();
                break;
            }
            case 'decimal' : {
                $config['class'] = DecimalField::className();
                $config['precision'] = $column->getPrecision();
                $config['scale'] = $column->getScale();
                break;
            }
            case 'float' : {
                $config['class'] = FloatField::className();
                break;
            }
            case 'longtext' :
            case 'text' : {
                $config['class'] = TextField::className();
                unset($config['length']);
                break;
            }
            case 'string' : {
                $config['class'] = CharField::className();
                break;
            }
            case 'blob' : {
                $config['class'] = BlobField::className();
                unset($config['length']);
                break;
            }
            case 'date' : {
                $config['class'] = DateField::className();
                break;
            }
            case 'datetime' : {
                $config['class'] = DateTimeField::className();
                break;
            }
            case 'time' : {
                $config['class'] = TimeField::className();
                break;
            }
//            case 'timeshtamp' : {
//                $config['class'] = TimestampField::className();
//                break;
//            }
        }


        return $config;
    }
}