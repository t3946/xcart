<?php
namespace Modules\Goods\Admin;

use DateTime;
use Modules\Admin\Contrib\Admin;
use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Goods\Forms\FileProductForm;
use Modules\Goods\Models\ProductFileModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Fields\UnixDateField;
use Xcart\App\Form\ModelForm;
use Xcart\App\Orm\Model;

class FilesProductAdmin extends ListViewAdmin
{
    public $ownerField = 'productid';

    public function getSuggestionColumns()
    {
        return [
            'description' => [
                'class' => CharField::class
            ],
            'filename' => [
                'class' => CharField::class,
            ],
            'filesize' => [
                'class' => CharField::class
            ],
            'date' => [
                'class' => UnixDateField::class
            ],
            'avail' => [
                'class' => Select2Field::class,
            ]
        ];
    }
    public function getListColumns()
    {
        return [
            'description',
            'filename',
            'filesize',
            'date',
            'avail'
        ];
    }

    public function getAvailableListColumns()
    {
        return [
            'avail' => [
                'title' => 'Is active',
            ],
        ];
    }

    public function getModel()
    {
        return new ProductFileModel();
    }
    public function getForm()
    {
        return new FileProductForm();
    }

    /**
     * @param ProductFileModel $item
     * @param $property
     * @return mixed|string
     */
    public function getItemProperty(Model $item, $property)
    {
        switch ($property) {
            case 'date':
                return (new DateTime())->setTimestamp($item->date)->format('d M Y');
            case 'filesize':
                return $item->getFileSizeMB();
            case 'filename':
                return "{$item->$property->getValue()}";
        }
        return parent::getItemProperty($item, $property);
    }
}