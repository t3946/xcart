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

        $content = substr($content, 0, $s-1);

        $names = str_replace("{{break}}\r\n", '', $names);

        $names = explode("\r\n", $names);

        $members = [];

        $regexp = '/.*?\((.*?)\)/s';
        foreach ($names as $name){
            preg_match_all($regexp, $name, $matches);
        }

        return ['content' => $content, 'members' => $members];
    }

}