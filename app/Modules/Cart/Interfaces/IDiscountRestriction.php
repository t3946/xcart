<?php
namespace Modules\Cart\Interfaces;

interface IDiscountRestriction
{
    public function getName();

    public function getModel();

    public function getForm();
}