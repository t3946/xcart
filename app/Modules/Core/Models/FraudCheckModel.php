<?php
namespace Modules\Core\Models;

use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class FraudCheckModel extends Model
{
	public static function tableName()
	{
		return 'xcart_fraud_check_new';
	}

	public static function getFields()
	{
		return [
			'f_fraud' => [
				'field' => 'f_fraud_id',
				'class' => ForeignField::class,
				'modelClass' => FraudCheckColumnModel::class,
				'link' => ['f_fraud_id' => 'fraud_id'],
				'null' => false,
				'primary' => true,
			],
			't_fraud' => [
				'field' => 't_fraud_id',
				'class' => ForeignField::class,
				'modelClass' => FraudCheckColumnModel::class,
				'link' => ['t_fraud_id' => 'fraud_id'],
				'null' => false,
				'primary' => true,
			],
			'weight' => [
				'class' => IntField::class,
				'default' => '0',
				'null' => false,
			],
			'type' => [
				'class' => CharField::class,
				'null' => false,
			]
		];
	}
}