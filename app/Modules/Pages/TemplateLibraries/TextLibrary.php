<?php

namespace Modules\Pages\TemplateLibraries;

use Modules\Pages\Models\InfoBlock;
use Xcart\App\Template\Renderer;
use Xcart\App\Template\TemplateLibrary;

class TextLibrary extends TemplateLibrary
{
    use Renderer;

    /**
     * @name fetch_info_block
     * @kind accessorFunction
     * @return string
     */
    public static function fetchInfoBlock($tag = null, $id = null, $data = []):string
    {
        $filter = [];

        if ($id) {
            $filter['id'] = $id;
        }

        if ($tag) {
            if ($data['sfcode']){
                $data['sfcode'] = strtolower($data['sfcode']);
                $tag .= ":store.{$data['sfcode']}";
            }
            $filter['tag'] = $tag;
        }
        if ($text = InfoBlock::objects()->filter($filter)->get()->text){
            return $text;
        }

        return '';
    }
}