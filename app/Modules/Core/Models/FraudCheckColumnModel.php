<?php


namespace Modules\Core\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

/**
 * Class FraudCheckColumnModel
 * @package Modules\Core\Models
 * @property string type
 * @property string fraud_code
 * @property string fraud_name
 */
class FraudCheckColumnModel extends Model
{
	public static function tableName()
	{
		return 'xcart_fraud_columns';
	}
	public static function getFields()
	{
		return [
			'fraud_id' => [
				'class' => AutoField::class,
				'primary' => true,
				'null' => false,
			],
			'fraud_code' => [
				'class' => CharField::class,
				'default' => null,
				'null' => true,
			],
			'fraud_name' => [
				'class' => CharField::class,
				'default' => null,
				'null' => true,
			]
		];
	}
}