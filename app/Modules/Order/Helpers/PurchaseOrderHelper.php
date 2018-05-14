<?php

namespace Modules\Order\Helpers;


use Modules\Order\Models\PurchaseOrderModel;
use Xcart\App\Helpers\Paths;
use Xcart\App\Main\Xcart;
use Xcart\Logs;

class PurchaseOrderHelper
{
    public const PO_FILE_LINK = '/files/purchase_orders/%s';

    public static function uploadPurchaseOrder(PurchaseOrderModel $po_model, $uploaded_file, $extension): bool
    {
        $allow_extensions = ['pdf'];

        $sFileName = $po_model->PO_number . '.' . $extension;
        $sNewFilePath = Paths::get('www') . sprintf(self::PO_FILE_LINK, $sFileName);

        if (\in_array($extension, $allow_extensions, true)) {
            return move_uploaded_file($uploaded_file, $sNewFilePath);
        }
        return false;
    }
}