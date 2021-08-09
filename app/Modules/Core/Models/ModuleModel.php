<?php
namespace Modules\Core\Models;

use Xcart\App\Orm\Model;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;

class ModuleModel extends Model {
	public static function tableName()
	{
		return 'xcart_modules';
	}
	public static function getFields()
	{
		return [
			'moduleid' => [
				'class' => AutoField::class,
				'primary' => true,
				'null' => false,
			],
			'module_name' => [
				'class' => CharField::class,
				'default' => '',
				'null' => false,
			],
			'module_descr' => [
				'class' => CharField::class,
				'default' => '',
				'null' => false,
			],
			'active' => [
				'class' => CharField::class,
				'default' => 'Y',
			]

		];
	}
}