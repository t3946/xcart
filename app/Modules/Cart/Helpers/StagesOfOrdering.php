<?php
/**
 * Класс для управления списком этапов оформления заказа
 *
 * @param $active string активный этап оформления заказа
 * @param $stages array возвращает список этапов оформления заказа
 * @param $firstStage bool true - активен первый этап оформления заказа
 */

namespace Modules\Cart\Helpers;


use Modules\Main\MainModule;

class StagesOfOrdering
{

    const FIRST_STAGE = 'shopping_cart';
    /**
     * @var array
     */
    private $_stages;
    /**
     * @var string
     */
    private $_active = self::FIRST_STAGE;
    /**
     * @var bool
     */
    private $_firstStage = true;

    public function __construct()
    {
        // Устанавливает список этапов оформления поумолчанию (с активным этапом поумолчанию)
        $this->_stages = $this->getDefaultStages();
    }

    /**
     * Устанавливает указанному этапу оформления заказа статуст "активен",
     * а всем предыдущим этапам "неактивен"
     * @param $stageKey
     * @return bool
     */
    public function setActive($stageKey)
    {

        if (!isset($this->_stages[$stageKey])) {
            return false;
        }


        $this->clearStageStatus();

        foreach ($this->_stages as $key => $stage) {

            if ($key !== $stageKey) {
                $this->_stages[$key]['status'] = 'inactive';
            } else {
                $this->_stages[$key]['status'] = 'active';
                break;
            }
        }


        $this->_firstStage = ($stageKey === self::FIRST_STAGE) ? true : false;
        $this->_active = $stageKey;

    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->_active;
    }

    /**
     * @return array
     */
    public function getStages()
    {
        return $this->_stages;
    }

    /**
     * @return bool
     */
    public function getFirstStage()
    {
        return $this->_firstStage;
    }

    /**
     * список этапов офопмления заказа поумолчанию
     * @return array
     */
    private function getDefaultStages()
    {
        return [
            'shopping_cart' => [
                'status' => 'active',
                'number' => '1',
                'label' => MainModule::t('Shopping cart'),
            ],
            'shipping_address' => [
                'status' => '',
                'number' => '2',
                'label' => MainModule::t('Shipping Address'),
            ],
            'shipping_payment_options' => [
                'status' => '',
                'number' => '3',
                'label' => MainModule::t('Shipping & payment options'),
            ],
            'order_review' => [
                'status' => '',
                'number' => '4',
                'label' => MainModule::t('order review'),
            ],
            'payment' => [
                'status' => '',
                'number' => '5',
                'label' => MainModule::t('Payment'),
            ],
        ];
    }

    /**
     * сбрасывает статусы всех этапов оформления заказа
     */
    private function clearStageStatus()
    {

        foreach ($this->_stage as $key => $stage) {
            $this->_stage[$key]['status'] = '';
        }

    }

}