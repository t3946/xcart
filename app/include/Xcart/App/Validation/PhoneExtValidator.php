<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 17.07.2018
 * Time: 15:19
 */

namespace Xcart\App\Validation;


class PhoneExtValidator extends NumberValidator
{
    public $message = "Phone extension must be numeric";
}