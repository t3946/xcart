<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 03.08.2018
 * Time: 11:45
 */

namespace Modules\Core\Behaviours;


class CustomSelectBehavior extends FormJsEventBehavior
{

    /**
     * @var array
     */
    protected $jsEvent = 'form.client.fields.custom_select';
    protected $jsObjName = 'formCustomSelect';


    protected function createJsFieldsConditions(&$fields): string
    {
        return '';
    }

}