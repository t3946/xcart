<?php

namespace Modules\Dashboard\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Dashboard\Forms\MailboxForwardingForm;
use Modules\Mail\Models\MailboxForwardingModel;
use Modules\Order\Models\OrderStatusModel;
use Xcart\App\Form\ModelForm;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;

class MailboxForwardingAdmin extends Admin
{
    public function getListColumns(): array
    {
        return [
            'image_path',
            'status',
            'file',
            'type',
            'date',
            'unique_id',
        ];
    }

    public function getForm(): MailboxForwardingForm
    {
        return new MailboxForwardingForm();
    }

    public function getListItemActions(): array
    {
        return [];
    }

    public function getModel(): MailboxForwardingModel
    {
        return new MailboxForwardingModel();
    }

    public static function getName(): string
    {
        return 'Mailbox Forwarding';
    }

    /**
     * @param MailboxForwardingModel $item
     * @param $property
     * @return string
     */
    public function getItemProperty(Model $item, $property): string
    {
        switch ($property) {
            case 'image_path':
                return $item->$property->getValue() ? "<div style='text-align: center'><img src=\"{$item->getImagePath()}\" width='60'/></div>" : '';
            case 'file':
                return $item->$property->getValue() ? "<a target='_blank' href=\"{$item->getFilePath()}\">File</a>" : '';
            case 'unique_id':
            case 'status':
                return $item->$property ?? '';
        }
        return parent::getItemProperty($item, $property);
    }

    public function isAjaxCreate(): bool
    {
        return true;
    }
}