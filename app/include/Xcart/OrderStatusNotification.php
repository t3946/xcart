<?php
namespace Xcart;

use Modules\Core\Models\GlobalConfigModel;
use Modules\Core\Models\StateModel;
use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Sites\Models\SiteConfigModel;

/**
 * @deprecated deprecated class
 */
class OrderStatusNotification extends Mail
{
    /**
     * @var Order
     */
    protected $oOrder = null;

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['code'];
        $this->sPrimaryTable = 'order_status_notifications';
        parent::__construct($aParams);

    }

    public static function getOrderStatusNotificationsByCode($sStatus)
    {
        return self::model()->findAll(SQLBuilder::getInstance()->addCondition("code='$sStatus'"));
    }

    public function isEnabled()
    {
        return ($this->getField('enabled')=='Y');
    }

    public function getBody()
    {
        return $this->getField('email_body');
    }

    public function getSubject()
    {
        return $this->getField('customer_subject');
    }

    public function getSubjectCopy()
    {
        return $this->getField('copy_subject');
    }

    public function replaceSubject()
    {
        parent::replaceSubject();
        if (!empty($this->oOrder)) {
            $this->setField('customer_subject', str_replace("{{orderid}}", $this->oOrder->getDisplayOrderNumber(), $this->getSubject()));
        }
    }

    public function replaceBody()
    {
        if (!empty($this->oOrder)) {

            $params = [
                'state' => $this->oOrder->s_state,
                'country' => $this->oOrder->s_country,
                'phone' => $this->oOrder->phone,
                'storefrontid' => $this->oOrder->storefrontid,
            ];

            $phones = GeoipHelper::getPhones($params);

            $this->aReplaceRules = array_merge(
                $this->aReplaceRules,
                [
                    '{{c-fullname}}' => $this->oOrder->getFirstName(),
                    '{{orderid}}' => $this->oOrder->getDisplayOrderNumber(),
                    '{{site_url}}' => $this->oOrder->getOrderStoreFront()->getStoreFrontURL(),
                    '{{customer_service_local_phone_number}}' => $phones
                ]);
        }

        parent::replaceBody();
    }

    public function setOrder($oOrder)
    {
        $this->oOrder = $oOrder;
        return $this;
    }

}