<?php

namespace Modules\Goods\Controllers;

use Modules\Goods\Forms\NotifyStockForm;
use Modules\Goods\Models\NotifyStockModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class NotifyStockController extends FrontendController
{
    public function getCustomerClaim()
    {
        $request = $this->getRequest();

        $flash_data = [];

        $form = new NotifyStockForm();

        $form->populate($request->post);

        if (!$form->isValid()) {
            $flash_data['error'] = ['The data is not correct'];
        }
        else {

            if ($model = NotifyStockModel::objects()->get([
                                                            'email' => $request->post->get('NotifyStockForm')['email'],
                                                            'productid' => $request->post->get('NotifyStockForm')['product'],
                                                            'first_name' => $request->post->get('NotifyStockForm')['first_name'],
                                                            'storefrontid' => Xcart::app()->getModule('Sites')->getSite()->storefrontid,
                                                            'sent' => false,
                                                          ])){
                $flash_data['success'] = ['You already signed up for this notification'];
            } else {
                $form->save();
                $flash_data['success'] = ['Thank you! You will be notified when the product is in stock.'];
            }
        }

        $this->jsonResponse($flash_data);
    }

    public function getTpl()
    {
        $request = $this->getRequest();

        $form = new NotifyStockForm();

        $form->getField('product')->setValue($request->get->get('product_id'));

        $this->display('/product/parts/_notify_stock.tpl', ['form' => $form, 'productid' => $request->get->get('product_id')]);
    }

}