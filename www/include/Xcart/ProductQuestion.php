<?php

namespace Xcart;


class ProductQuestion extends Data
{
    const PRODUCT_QUESTION_MODIFY_LINK = "product_question.php?id=%d";

    private $oProduct = null;

    public function __construct($iId = null)
    {
        $this->sPrimaryTable = 'product_question';
        $this->aPrimaryKeys = ['id'];

        parent::__construct($iId);
    }

    public function getProductQuestionId()
    {
        return $this->getField('id');
    }

    public function getProductId()
    {
        return $this->getField('productid');
    }

    /**
     * @return Product
     */
    public function getProductEntity()
    {
        if (is_null($this->oProduct)) {
            $this->oProduct = Product::model(['productid' => $this->getProductId()]);
        }
        return $this->oProduct;
    }

    public function getQuestionDate()
    {
        return (new \DateTime())->setTimestamp($this->getField('date'));
    }

    public function getProductQuestionModifyURL()
    {
        return sprintf(self::PRODUCT_QUESTION_MODIFY_LINK, $this->getProductQuestionId());
    }
}