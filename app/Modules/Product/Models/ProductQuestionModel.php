<?php
namespace Modules\Product\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class ProductQuestionModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_product_question';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::className(),
            ],
            'status' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => 'question_received_from_cust',
                'choices' => [
                    "question_received_from_cust"  => "Question received from customer",
                    "question_sent_to_distr_brand" => "Question sent to distributor/brand",
                    "call_distributor_brand"       => "Call distributor/brand",
                    "answer_sent_to_cust"          => "Answer sent to customer",
                    "order_pending"                => "Order pending",
                    "closed"                       => "Closed",
                ]
            ]
        ];
    }
}