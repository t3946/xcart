<?php
namespace Xcart;

class Logs extends Data
{
    const LOG_TYPE_CLIENT = 'C';
    const LOG_TYPE_XCART = 'X';
    const LOG_TYPE_SYSTEM = 'S';
    const LOG_TYPE_PAYMENT_PROCESSOR = 'PP';

    const LOG_RESOURCE_ORDERS = 'orders';
    const LOG_RESOURCE_PURCHASE_ORDERS = 'purchase_orders';
    const LOG_RESOURCE_AMAZON_PRODUCT_VERIFICATION = 'amazon_product_verification';
    const LOG_RESOURCE_SHIPPING_QUOTES= 'shipping_quotes';

    private static $log_resource_type = null;
    private $oCustomer = null;

    public function __construct($log_resource_type)
    {
        self::init($log_resource_type);
    }

    public static function init($log_resource_type)
    {
        self::$log_resource_type = $log_resource_type;
    }

    public static function _log($sResourceType, $iResourceId, $sLogType, $sLog, $sLogin = null)
    {
        $aParams['resource_type'] = $sResourceType;
        $aParams['resource_id'] = $iResourceId;
        $aParams['type'] = $sLogType;
        if (!isset($sLogin)) {
            global $login;
            $sLogin = $login;
        }
        $aParams['login'] = ($sLogin);
        $aParams['log'] = ($sLog);

        if ($sResourceType == 'orders') {
            unset($aParams['resource_type']);
            $aParams['orderid'] = $iResourceId;
            unset($aParams['resource_id']);
            $aParams['date'] = time();
            func_array2insert('order_logs', $aParams);
        }
        else
            func_array2insert('logs', $aParams);
    }

    public static function _getFoundRows()
    {
        $aResult = func_query_column('SELECT FOUND_ROWS()');
        return reset($aResult);
    }

    public static function _getLogs($page = 1, $per_page = 50, $iResourceId = null, $sLogType = null)
    {
        $aLogs = [];
        $oSQL = new SQLBuilder();
        $oSQL->addSelect('SQL_CALC_FOUND_ROWS *')->addFromTable('logs')->addCondition("resource_type = '" . self::$log_resource_type . "'")->addOrderBy('id DESC');
        if (!empty($iResourceId))
            $oSQL->addCondition("resource_id = $iResourceId");
        if (!empty($sLogType)) {
            $oSQL->addCondition("type='$sLogType'");
        }

        $oSQL->setLimit(($page - 1) * $per_page . "," . $per_page);

        $aResult = $oSQL->Execute()->getQueryResult();
        if (!empty($aResult)) {
            foreach ($aResult as $oResult) {
                $oLogs = new Logs($oResult['resource_type']);
                $oLogs->fill($oResult);
                $aLogs[] = $oLogs;
            }
        }
        return $aLogs;
    }

    public function getLogDate($sDateFormat = 'd-M-Y H:i:s')
    {
        $oDate = new \DateTime();
        $oDate->setTimestamp(strtotime($this->getField('date')));
        return $oDate->format($sDateFormat);
    }

    public function getLogText()
    {
        return $this->getField('log');
    }

    public function getLogin()
    {
        return $this->getField('login');
    }

    public function getCustomerEntity()
    {
        if (empty($this->oCustomer)){
            $this->oCustomer = new Customer(['login'=> $this->getLogin()]);
        }
        return $this->oCustomer;
    }

}