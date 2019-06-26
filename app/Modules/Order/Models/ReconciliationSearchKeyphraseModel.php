<?php


namespace Modules\Order\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Model;

class ReconciliationSearchKeyphraseModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_reconciliation_search_keyphrases';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::className(),
            ],
        ];
    }
}