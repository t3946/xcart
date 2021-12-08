<?php


namespace Modules\Core\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

/**
 * Class FraudCheckColumnModel
 * @package Modules\Core\Models
 * @property string type
 * @property string name
 * @property string code
 * @property string description
 * @property int fraud_id
 * @property string frontend_type
 * @property string frontend_provider
 * @property string source_type
 * @property bool is_melissa_data
 * @property string inferred_from
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
			'code' => [
				'class' => CharField::class,
				'default' => null,
				'null' => true,
			],
			'name' => [
				'class' => CharField::class,
				'default' => null,
				'null' => true,
			],
            'type' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'choices' => [
                    'full_name',
                    'address'
                ]
            ],
            'description' => CharField::class,
            'frontend_type' => CharField::class,
            'frontend_provider' => CharField::class,
            'source_type' => CharField::class,
            'is_melissa_data' => BooleanField::class,
            'inferred_from' => CharField::class
		];
	}
}