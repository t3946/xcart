<?php
/**
 * Класс для управления списком этапов оформления заказа
 *
 * @param $active string активный этап оформления заказа
 * @param $stages array возвращает список этапов оформления заказа
 */

namespace Modules\Cart\Helpers;


use Modules\Main\MainModule;
use Xcart\App\DataClasses\ArrayClass;
use Xcart\App\Main\Xcart;
use Xcart\App\Traits\Singleton;

class StagesOfOrdering extends ArrayClass
{
    use Singleton;

    const FIRST_STAGE = Stages::SHOPPING_CART;

    /**
     * @var int
     */
    private $_active = self::FIRST_STAGE;

    protected function init(): void
    {
        // Устанавливает список этапов оформления поумолчанию (с активным этапом поумолчанию)
        $this->data = static::getDefaultStages();
    }

    /**
     * @return int
     */
    public function getActive(): int
    {
        return $this->_active;
    }

    /**
     * @param int $n
     * @return bool
     */
    public function hasStage(int $n): bool
    {
        return $this->offsetExists($n);
    }

    /**
     * @return array
     */
    public function getStages():? array
    {
        return $this->data;
    }

    /**
     * @param int $n
     * @return mixed
     */
    public function getStage(int $n)
    {
        if ($this->hasStage($n)) {
            return $this->getData($n);
        }

        return $this->getData(static::FIRST_STAGE);
    }

    /**
     * @return mixed
     */
    public function getPrevStage()
    {
        return $this->_active > 0
            ? $this->getData($this->_active-1)
            : $this->getData(static::FIRST_STAGE);
    }

    /**
     * @return mixed|null
     */
    public function getNextStage()
    {
        return ($this->count() - 1) > $this->_active
            ? $this->getData($this->_active + 1)
            : null;
    }

    /**
     * @param int $n
     * @return bool
     */
    public function setStage(int $n): bool
    {
        if ($this->hasStage($n)) {
            $this->_active = $n;

            return true;
        }

        return false;
    }

    /**
     * @param int $n
     * @return bool
     */
    public function isStagePassed(int $n): bool
    {
        return $n < $this->_active;
    }

    /**
     * @return bool
     */
    public function isFirstStage(): bool
    {
        return $this->_active == static::FIRST_STAGE;
    }

    /**
     * список этапов офопмления заказа поумолчанию
     * @return array
     * @throws \Exception
     */
    private static function getDefaultStages(): array
    {
        $manager = Xcart::app()->router;

        return [
            [
                'status' => 'active',
                'url' => $manager->url('cart:list'),
                'label' => MainModule::t('Shopping cart'),
            ],
            [
                'status' => '',
                'url' => $manager->url('checkout:shipping'),
                'label' => MainModule::t('Shipping Address'),
            ],
             [
                'status' => '',
                 'url' => $manager->url('checkout:options'),
                'label' => MainModule::t('Shipping & payment options'),
            ],
            [
                'status' => '',
                'url' => $manager->url('checkout:review'),
                'label' => MainModule::t('Order review'),
            ],
            [
                'status' => '',
                'label' => MainModule::t('Payment'),
            ],
        ];
    }

}