<?php

namespace Xcart\App\Orm\Fields;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Xcart\App\Orm\ModelInterface;

/**
 * Class DateTimeField
 * @package Xcart\App\Orm
 */
class UnixTimestampField extends IntField
{ /**
 * @var bool
 */
    public $autoNowAdd = false;

    /**
     * @var bool
     */
    public $autoNow = false;

    /**
     * {@inheritdoc}
     */
    public function getSqlType()
    {
        return Type::getType(Types::INTEGER);
    }

    /**
     * {@inheritdoc}
     */
    public function getValidationConstraints()
    {
        $constraints = [
//            new Assert\Date()
        ];
        if ($this->isRequired()) {
            $constraints[] = new Assert\NotBlank();
        }

        return $constraints;
    }

    /**
     * {@inheritdoc}
     */
    public function isRequired(): bool
    {
        if ($this->autoNow || $this->autoNowAdd) {
            return false;
        }
        return parent::isRequired();
    }

    /**
     * {@inheritdoc}
     */
    public function beforeInsert(ModelInterface $model, $value)
    {
        if (($this->autoNow || $this->autoNowAdd) && $model->getIsNewRecord()) {
            $model->setAttribute($this->getAttributeName(), time());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function beforeUpdate(ModelInterface $model, $value)
    {
        if ($this->autoNow && $model->getIsNewRecord() === false) {
            $model->setAttribute($this->getAttributeName(), time());
        }
    }

    /**
     * {@inheritdoc}
     */
    /*public function getValue()
    {
        $value = parent::getValue();
        return $value ? (new DateTime)->setTimestamp($value) : $value;
    }*/

}
