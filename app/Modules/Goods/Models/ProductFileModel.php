<?php

namespace Modules\Goods\Models;

use Xcart\App\Helpers\Paths;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Model;

class ProductFileModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_product_files';
    }

    public static function getFields()
    {
        return [
            'fileid' => [
                'class' => AutoField::className()
            ]
        ];
    }

    public function getAbsoluteUrl() :string
    {
        return "/product_files/{$this->productid}/{$this->filename}";
    }

    public function getFileFormat() :string
    {
        $regexp = '/\.([a-zA-Z\d]{3,4})$/m';
        preg_match($regexp, $this->filename, $matches);

        return strtolower($matches[1]);
    }

    public function getFileSizeMB() :string
    {
        $size = round($this->filesize / 1024 / 1024, 2, PHP_ROUND_HALF_UP);

        return "{$size}MB";
    }

    public function getGoodFileName() :string
    {
        return $this->description . "." . $this->getFileFormat();

    }

    public function getFormatIconUrl() :string
    {
        $file_path = Paths::get('www') . "/static/frontend/dist/images/icons/file_format/{$this->getFileFormat()}.svg";
        if (file_exists($file_path)){
            return "/static/frontend/dist/images/icons/file_format/{$this->getFileFormat()}.svg";
        }
        else {
            return '';
        }
        //   "/static/frontend/dist/images/icons/file_fromat/{$this->>getFileFormat()}.svg";
    }

    public function isNeedBottomAlign() :bool
    {
        if (strlen($this->description) > 20){
            return true;
        }
        else {
            return false;
        }
    }
}