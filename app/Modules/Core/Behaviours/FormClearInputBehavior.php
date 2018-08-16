<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 18.07.2018
 * Time: 16:54
 */

namespace Modules\Core\Behaviours;


class FormClearInputBehavior extends FormJsEventBehavior
{

    /**
     * @var array
     */
    protected $jsEvent = 'form.client.fields.clear';
    protected $jsObjName = 'formClearFields';


    protected function createJsFieldsConditions(&$fields): string
    {
        return '';
    }

}