<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/18/2017
 * Time: 2:24 PM
 */

namespace Modules\PBX\Helpers;

use DateTime;
use Mindy\QueryBuilder\Expression;
use Modules\PBX\Models\PbxAnveoCallModel;
use Modules\User\Models\PbxOptionsModel;
use Modules\User\Models\UserModel;

class PBXHelper
{
    public static function getOperatorsAccount($firstname)
    {
        /** @var UserModel $user_model */
        $user_model = UserModel::objects()->get(['firstname' => $firstname,
                                                 'usertype' => 'A',
                                                 'status' => 'Y',]);
        $pbx_extension = $user_model->pbx_extension;

        /** @var PbxOptionsModel $pbx_option_model */
        $pbx_option_model = PbxOptionsModel::objects()->get(['extension' => $pbx_extension]);
        $anveo_account = $pbx_option_model->anveo_account;

        return $anveo_account;

    }

    public static function getClearDate($date)
    {
        $clear_date = DateTime::createFromFormat("m/d/Y H:i a", $date);

        return $clear_date;

    }

    public static function getListOfNameOperators($for_form = false)
    {
        $op = [];

        if ($for_form){
            $op[] = "Operators";
        }

        $filter = [
            'usertype' => 'A',
            'status' => 'Y',
            //            'login__isnt' => 'sergey2',
            new Expression("trim(pbx_extension) != '' ")
        ];

        $operators = UserModel::objects()
                              ->filter($filter)
                              ->all();

        foreach ($operators as $operator){
            $op[$operator->id] = $operator->firstname;
        }

        return $op;
    }
}