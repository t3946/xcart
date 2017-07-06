<?php

namespace Modules\Cart\Controllers;

use Modules\Cart\CartModule;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

abstract class BaseCartController extends Controller
{
    /**
     * @var string
     */
    public $listTemplate = 'cart/list.tpl';
    /**
     * @var string
     */
    public $defaultListRoute = 'cart:list';
    /**
     * @var null|string
     */
    public $listRoute = null;

    protected function getListRoute()
    {
        $module = Xcart::app()->getModule('Cart');
        return $module->listRoute ? $module->listRoute : $this->defaultListRoute;
    }

    /**
     * @return \Modules\Cart\Components\Cart
     */
    protected function getCart()
    {
        return Xcart::app()->getModule('Cart')->getComponent('cart');
    }

    public function actionAdd($uniqueId, $quantity = 1, $type = null)
    {
        $isAjax = $this->getRequest()->getIsAjax();
        $cart = $this->getCart();
        if ($this->addInternal($uniqueId, $quantity, $type)) {
            if ($isAjax) {
                $this->jsonResponse([
                    'status' => true,
                    'total' => $cart->getTotal(),
                    'message' => [
                        'title' => CartModule::t('Product added')
                    ]
                ]);
                Xcart::app()->end();
            } else {
                $this->request->flash->success(CartModule::t('Product added'));
                $this->request->redirect($this->getListRoute());
            }
        } else {
            if ($isAjax) {
                echo $this->json([
                    'status' => false,
                    'total' => $cart->getTotal(),
                    'message' => [
                        'title' => CartModule::t('Error has occurred')
                    ]
                ]);
                Xcart::app()->end();
            } else {
                $this->get->flash->success(CartModule::t('Error has occurred'));
                $this->request->redirect($this->getListRoute());
            }
        }
    }

    public function actionList()
    {
        $listRoute = $this->getListRoute();
        $url = Xcart::app()->urlManager->reverse($listRoute);
        if ($listRoute && strpos($this->request->path, $url) === false) {
            $this->request->redirect($listRoute);
        }
        $cart = $this->getCart();
        echo $this->render($this->listTemplate, [
            'items' => $cart->getItems(),
            'total' => $cart->getTotal(),
        ]);
    }

    public function actionQuantity($key, $quantity)
    {
        $isAjax = $this->getRequest()->getIsAjax();
        $cart = $this->getCart();
        if ($cart->updateQuantityByKey($key, $quantity)) {
            if ($isAjax) {
                $this->jsonResponse([
                    'status' => true,
                    'total' => $cart->getTotal(),
                    'message' => [
                        'title' => CartModule::t('Quantity updated')
                    ]
                ]);
                Xcart::app()->end();
            } else {
                $this->request->flash->success(CartModule::t('Quantity updated'));
                $this->request->redirect($this->getListRoute());
            }
        } else {
            if ($isAjax) {
                $this->jsonResponse([
                    'status' => false,
                    'total' => $cart->getTotal(),
                    'message' => [
                        'title' => CartModule::t('Error has occurred')
                    ]
                ]);
                Xcart::app()->end();
            } else {
                $this->request->flash->success(CartModule::t('Error has occurred'));
                $this->request->redirect($this->getListRoute());
            }
        }
    }

    public function actionIncrease($key)
    {
        $isAjax = $this->getRequest()->getIsAjax();
        $cart = $this->getCart();
        if ($cart->increaseQuantityByKey($key)) {
            if ($isAjax) {
                $this->jsonResponse([
                    'status' => true,
                    'total' => $cart->getTotal(),
                    'message' => [
                        'title' => CartModule::t('Quantity updated')
                    ]
                ]);
                Xcart::app()->end();
            } else {
                $this->request->flash->success(CartModule::t('Quantity updated'));
                $this->request->redirect($this->getListRoute());
            }
        } else {
            if ($isAjax) {
                $this->jsonResponse([
                    'status' => false,
                    'total' => $cart->getTotal(),
                    'error' => [
                        'title' => CartModule::t('Error has occurred')
                    ]
                ]);
                Xcart::app()->end();
            } else {
                $this->request->flash->success(CartModule::t('Error has occurred'));
                $this->request->redirect($this->getListRoute());
            }
        }
    }

    public function actionDecrease($key)
    {
        $isAjax = $this->getRequest()->getIsAjax();
        $cart = $this->getCart();
        if ($cart->decreaseQuantityByKey($key)) {
            if ($isAjax) {
                echo $this->json([
                    'status' => true,
                    'total' => $cart->getTotal(),
                    'message' => [
                        'title' => CartModule::t('Quantity updated')
                    ]
                ]);
                Xcart::app()->end();
            } else {
                $this->request->flash->success(CartModule::t('Quantity updated'));
                $this->request->redirect($this->getListRoute());
            }
        } else {
            if ($isAjax) {
                $this->jsonResponse([
                    'status' => false,
                    'total' => $cart->getTotal(),
                    'error' => [
                        'title' => CartModule::t('Error has occurred')
                    ]
                ]);
                Xcart::app()->end();
            } else {
                $this->request->flash->success(CartModule::t('Error has occurred'));
                $this->request->redirect($this->getListRoute());
            }
        }
    }

    public function actionDelete($key)
    {
        $cart = $this->getCart();
        $deleted = $cart->removeByKey($key);
        $isAjax = $this->getRequest()->getIsAjax();
        if ($deleted) {
            if ($isAjax) {
                $this->jsonResponse([
                    'status' => true,
                    'total' => $cart->getTotal(),
                    'message' => [
                        'title' => CartModule::t('Position sucessfully removed'),
                    ]
                ]);
                Xcart::app()->end();
            } else {
                $this->request->flash->success(CartModule::t('Position sucessfully removed'));
                $this->request->redirect($this->getListRoute());
            }
        } else {
            if ($isAjax) {
                $this->jsonResponse([
                    'status' => false,
                    'total' => $cart->getTotal(),
                    'error' => [
                        'title' => CartModule::t('Error has occurred'),
                    ]
                ]);
                Xcart::app()->end();
            } else {
                $this->request->flash->error(CartModule::t('Error has occurred'));
                $this->request->redirect($this->getListRoute());
            }
        }
    }

    abstract protected function addInternal($uniqueId, $quantity, $type);
}
