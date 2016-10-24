<?php

class classLocks extends classData
{
    private $aLockMessage = ['orders' => 'This order is locked by %s (%s) until %s. If you need to make urgent changes to the order, ask %s to unlock it.',
        'purchase_order' => 'PO page is locked by %s (%s) until %s. If you need to make urgent changes, ask %s to unlock it.'];
    private $selfLockMessage = ['orders' => 'You locked this order. Nobody can make any changes to it. The order will be unlocked at %s. You can also',
        'purchase_order' => 'You locked this PO page. Nobody can make any changes to it. The page will be unlocked at %s. You can also'];

    /**
     * @var classCustomer
     */
    private $oCustomer = null;

    public function __construct($aParams = null)
    {
        $this->sPrimaryTable = 'locks';
        $this->aPrimaryKeys = ['lock_type', 'entity_id'];

        parent::__construct($aParams);
    }

    public function getLockType()
    {
        return $this->getField('lock_type');
    }

    public function getLastTimeVisited()
    {
        $time = $this->getField('last_time_visited');
        if (is_null($time)) return false;
        return strtotime($time);
    }

    public function setLastTimeVisited($time)
    {
        global $login;
        $this->setField('last_time_visited', date('Y-m-d H:i:s', $time));
        $this->setField('login', $login);
        $this->_insert(true);
    }

    public function lockEntity()
    {
        $this->setLastTimeVisited(time());
    }

    public function getCustomer()
    {
        if (is_null($this->oCustomer))
            $this->oCustomer = classCustomer::model(['login' => $this->getLogin()]);
        return $this->oCustomer;
    }

    public function getLogin()
    {
        return $this->getField('login');
    }

    public function getTimeUnlock()
    {
        return $this->getLockTime() + $this->getLastTimeVisited();
    }

    public function getLockTime()
    {
        global $config;
        return $config["General"]["order_lock_time_in_seconds"];
    }

    public function isSelfLocking()
    {
        global $login;
        return $login == $this->getCustomer()->getCustomerLogin();
    }

    public function unlockEntity()
    {
        if (!is_null($this->getLogin()))
            $this->updateField('last_time_visited', null);
    }

    public function checkLock($captureLock = true)
    {
        $bResult = false;
        $time_for_order_in_mins = $this->getLockTime() / 60;
        $current_time = time();
        $diff_time_in_mins = ($current_time - $this->getLastTimeVisited()) / 60;
        if ($diff_time_in_mins > $time_for_order_in_mins) {
            if ($this->getLastTimeVisited() === false && (is_null($this->getLogin()) || !$this->isSelfLocking()) ||
                $this->getLastTimeVisited() !== false && !is_null($this->getLogin())) {
                if ($captureLock) $this->lockEntity();
                $bResult = true;
            }
        } else
            $bResult = true;
        return $bResult;
    }

    public function isLocked()
    {
        return $this->checkLock(false);
    }

    public function getWarningMessage()
    {
        if ($this->isSelfLocking()) {
            $sResult = sprintf($this->selfLockMessage[$this->getLockType()], date("G:i", $this->getTimeUnlock()));
        } else {
            $sResult = sprintf($this->aLockMessage[$this->getLockType()], $this->getCustomer()->getCustomerFullName(),
                $this->getCustomer()->getCustomerLogin(), date("G:i", $this->getTimeUnlock()), $this->getCustomer()->getCustomerFullName());
        }
        return $sResult;
    }
}