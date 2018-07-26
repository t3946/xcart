<?php

namespace Modules\Pages\Helpers;

use Modules\Main\Models\EmployeesModel;

class PageHelper
{

    static public function getTeamMember($name = ''): object
    {

        return EmployeesModel::objects()->get(['name' => $name]);
    }

    static public function getClearContent($content = ''): array
    {

        $s = strripos($content, "{");

        $names = substr($content, $s-1);

        $names = strip_tags($names);

        $content = substr($content, 0, $s-1);

        $names = str_replace("{{break}}\r\n", '', $names);

        $names = explode("\r\n", $names);

        $members = [];

        $regexp = '/.*?\((.*?)\)/s';

        $regexp_2 = '/(.*?)\(/s';
        foreach ($names as $key => $name){
            preg_match_all($regexp, $name, $matches);

            if (!$matches[1][1]){
                continue;
            }
            /** @var EmployeesModel $model */
            $model = self::getTeamMember($matches[1][1]);

            preg_match($regexp_2, $name, $matches_2);

            $members[$key]['post'] = trim($matches_2[1]);
            $members[$key]['photo'] = $model->getField('photo')->getUrl();
            $members[$key]['name'] = trim($matches[1][0]);
        }

        $members[4]['post'] = "Connoisseur of <br>beauty and aesthetics";

        return ['content' => $content, 'members' => $members];
    }

}