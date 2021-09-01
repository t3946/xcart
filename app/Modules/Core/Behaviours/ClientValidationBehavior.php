<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 13.07.2018
 * Time: 11:08
 */

namespace Modules\Core\Behaviours;


class ClientValidationBehavior extends FormJsEventBehavior
{

    /**
     * @var array
     */
    protected $jsEvent = 'form.client.validation';
    protected $jsObjName = 'formConstraints';

    /**
     * Client validation info
     * @var array
     */
    protected $clientValidation = [];


    /**
     * Execute after form field is created
     * @param $field Field
     */
    public function onAfterCreateField(&$field): void
    {
        $params = $field->createClientValidationConfig();

        if (!empty($params)) {
            $this->clientValidation[$field->name] = [
                'name' => $field->getHtmlName(),
                'json' => $params,
            ];
        }
    }

    protected function createJsFieldsConditions(&$fields): string
    {
        $js = '';
        foreach ($fields as $fieldName) {
            $info = $this->clientValidation[$fieldName];

            if (empty($info)) {
                continue;
            }
            $js .= $this->_createClientValidationField($info);
        }
        return $js;
    }

    /**
     *
     * @param $info
     * @return string
     */
    private function _createClientValidationField($info): string
    {
        return '"' . $info['name'] . '":' . $info['json'] . ',';
    }

}