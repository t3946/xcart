<?php
namespace Modules\Goods\Models;

use Doctrine\DBAL\Types\Type;
use Modules\User\Models\UserModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
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
                'class' => AutoField::class,
            ],
            'status' => [
                'class' => CharField::class,
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
            ],
            'user' => [
                'class' => ForeignField::class,
                'field' => 'login',
                'modelClass' => UserModel::class,
                'link' => ['login' => 'login'],
                'sqlType' => Type::STRING,
                'null' => true
            ],
            'product' => [
                'class' => ForeignField::class,
                'field' => 'productid',
                'modelClass' => ProductModel::class,
                'link' => ['productid' => 'productid'],
                'null' => true
            ],
            'date' => [
                'class' => UnixTimestampField::class,
                'autoNowAdd' => true,
            ],

        ];
    }

    /**
     * @return string
     */
    public function createPhone(): string
    {
        $phone = $this->phone;
        if($this->phone_ext) {
            $phone .= ' x ' . $this->phone_ext;
        }
        return $phone;
    }


}