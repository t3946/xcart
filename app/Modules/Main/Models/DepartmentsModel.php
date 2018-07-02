<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 02.07.2018
 * Time: 15:55
 */

namespace Modules\Main\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Model;

class DepartmentsModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_departments';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::class,
            ],
        ];
    }

    /**
     * Функция список причин обращения пользователя
     * @return array Массив с причинами обращения
     */
    public function getAllDepartments(): array
    {
        return $this->objects()->all();
    }

    public function getDepartmentByName($name): array
    {
        return $this->objects()->filter(['name' => $name])->limit(1)->get();
    }


}