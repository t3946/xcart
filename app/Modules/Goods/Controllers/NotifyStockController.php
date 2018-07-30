<?php

namespace Modules\Goods\Controllers;

use Modules\Goods\Forms\NotifyStockForm;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Xcart\App\Validation\EmailValidator;

class NotifyStockController extends FrontendController
{
    public function getCustomerClaim()
    {
        $request = $this->getRequest();


        $form = new NotifyStockForm();

        $form->populate($request->post);

        if (!$form->isValid()) {
//            Xcart::app()->flash->error('The data is not valid');
            $this->refresh();
        }
        else {
            $form->save();
dd($form->save());
//            Xcart::app()->flash->error('Still wait for email when product is get stock status');
            $this->refresh();
        }

    }

    public function getTpl()
    {
        $request = $this->getRequest();

        $form = new NotifyStockForm();

        $form->getField('product')->setValue($request->get->get('product_id'));

        $this->display('/product/parts/_notify_stock.tpl', ['form' => $form]);
    }

}