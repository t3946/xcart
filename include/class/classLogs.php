<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classSQLBuilder.php";

class classLogs extends classData
{
    const LOG_TYPE_CLIENT = 'C';
    const LOG_TYPE_XCART = 'X';
    const LOG_TYPE_SYSTEM = 'S';
    const LOG_TYPE_PAYMENT_PROCESSOR = 'PP';

    private static $log_resource_type = null;

    public function __construct($log_resource_type)
    {
        self::init($log_resource_type);
    }

    public static function init($log_resource_type)
    {
        self::$log_resource_type = $log_resource_type;
    }

    public static function _log($iResourceId, $sLogType, $sLog, $sLogin = null)
    {
        if (empty(self::$log_resource_type)) throw new Exception ('Log resource type is empty');
        $aParams['resource_type'] = self::$log_resource_type;
        $aParams['resource_id'] = $iResourceId;
        $aParams['type'] = $sLogType;
        if (!isset($sLogin)) {
            global $login;
            $sLogin = $login;
        }
        $aParams['login'] = addslashes($sLogin);
        $aParams['log'] = addslashes($sLog);

        func_array2insert('logs', $aParams);
    }

    public static function _getLogs($iResourceId = null, $sLogType = null)
    {
        $aLogs = [];
        $oSQL = new classSQLBuilder();
        $oSQL->addSelect('*')->addFromTable('logs')->addCondition("resource_type = '" . self::$log_resource_type . "'")->addOrderBy('id DESC');
        if (!empty($iResourceId))
            $oSQL->addCondition("resource_id = $iResourceId");
        if (!empty($sLogType)) {
            $oSQL->addCondition("type='$sLogType'");
        }

        $oSQL->setLimit('50');

        $aResult = $oSQL->Execute()->getQueryResult();
        if (!empty($aResult)) {
            foreach ($aResult as $oResult) {
                $oLogs = new classLogs($oResult['resource_type']);
                $oLogs->fillPrimaryTableValues($oResult);
                $aLogs[] = $oLogs;
            }
        }
        return $aLogs;
    }

    public function getLogDate($sDateFormat='d-M-Y H:i:s')
    {
        $oDate = new DateTime();
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

}