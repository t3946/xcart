<?php
namespace Modules\Cart\Interfaces;

interface IDiscountRestriction
{
    public function getName();

    public function validate();

    public function getFormClass();

    public function toString();
}