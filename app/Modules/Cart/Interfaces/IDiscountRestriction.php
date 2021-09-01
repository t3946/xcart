<?php
namespace Modules\Cart\Interfaces;

interface IDiscountRestriction
{
    const VALIDATION_PRODUCT = 0x01;
    const VALIDATION_CUSTOMER = 0x02;
    const VALIDATION_OTHER = 0x03;

    public function getName();

    /**
     * @param null $object
     *
     * @return boolean
     */
    public function validate($object = null);

    public function getFormClass();

    public function dataToString();
}