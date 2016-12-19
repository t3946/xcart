<?php
namespace Xcart;

class Reconciliation extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['id'];
        $this->sPrimaryTable = 'reconciliations';
        parent::__construct($aParams);
    }

    public function getAction()
    {
        return $this->getField('action');
    }

    public function getDescription()
    {
        return $this->getField('description_csv');
    }

    public function findReconciliationOrder()
    {

    }

    public function getPayableTotalReport()
    {
        $sql = <<<SQL
SELECT SUM(COALESCE(oi.invoice_total,0)+COALESCE(om.ref_to_us_total,0)) total,

(SELECT SUM(COALESCE(oi.invoice_total,0)+COALESCE(om.ref_to_us_total,0))
FROM xcart_order_groups og
INNER JOIN xcart_orders o ON o.orderid = og.orderid AND o.date >= unix_timestamp('2016-01-01')
LEFT JOIN xcart_order_group_invoices oi ON oi.orderid = og.orderid AND og.manufacturerid = oi.invoice_number 
LEFT JOIN xcart_order_group_memos om ON og.orderid = om.orderid AND og.manufacturerid = om.manufacturerid
WHERE 
  (oi.orderid IS NOT NULL AND oi.status = 'U' AND date(oi.update_date) < DATE_SUB(NOW() , INTERVAL 1 MONTH)) OR 
  (om.orderid IS NOT NULL AND om.status = 'U' AND date(om.update_date) < DATE_SUB(NOW() , INTERVAL 1 MONTH))) as one_month,
  
(SELECT SUM(COALESCE(oi.invoice_total,0)+COALESCE(om.ref_to_us_total,0))
FROM xcart_order_groups og
INNER JOIN xcart_orders o ON o.orderid = og.orderid AND o.date >= unix_timestamp('2016-01-01')
LEFT JOIN xcart_order_group_invoices oi ON oi.orderid = og.orderid AND og.manufacturerid = oi.invoice_number 
LEFT JOIN xcart_order_group_memos om ON og.orderid = om.orderid AND og.manufacturerid = om.manufacturerid
WHERE 
  (oi.orderid IS NOT NULL AND oi.status = 'U' AND date(oi.update_date) < DATE_SUB(NOW() , INTERVAL 3 MONTH)) OR 
  (om.orderid IS NOT NULL AND om.status = 'U' AND date(om.update_date) < DATE_SUB(NOW() , INTERVAL 3 MONTH))) as three_month,
  
(SELECT SUM(COALESCE(oi.invoice_total,0)+COALESCE(om.ref_to_us_total,0))
FROM xcart_order_groups og
INNER JOIN xcart_orders o ON o.orderid = og.orderid AND o.date >= unix_timestamp('2016-01-01')
LEFT JOIN xcart_order_group_invoices oi ON oi.orderid = og.orderid AND og.manufacturerid = oi.invoice_number 
LEFT JOIN xcart_order_group_memos om ON og.orderid = om.orderid AND og.manufacturerid = om.manufacturerid
WHERE 
  (oi.orderid IS NOT NULL AND oi.status = 'U' AND date(oi.update_date) < DATE_SUB(NOW() , INTERVAL 6 MONTH)) OR 
  (om.orderid IS NOT NULL AND om.status = 'U' AND date(om.update_date) < DATE_SUB(NOW() , INTERVAL 6 MONTH))) as six_month,
  
(SELECT SUM(COALESCE(oi.invoice_total,0)+COALESCE(om.ref_to_us_total,0))
FROM xcart_order_groups og
INNER JOIN xcart_orders o ON o.orderid = og.orderid AND o.date >= unix_timestamp('2016-01-01')
LEFT JOIN xcart_order_group_invoices oi ON oi.orderid = og.orderid AND og.manufacturerid = oi.invoice_number 
LEFT JOIN xcart_order_group_memos om ON og.orderid = om.orderid AND og.manufacturerid = om.manufacturerid
WHERE 
  (oi.orderid IS NOT NULL AND oi.status = 'U' AND date(oi.update_date) < DATE_SUB(NOW() , INTERVAL 1 YEAR)) OR 
  (om.orderid IS NOT NULL AND om.status = 'U' AND date(om.update_date) < DATE_SUB(NOW() , INTERVAL 1 YEAR))) as one_year

FROM xcart_order_groups og
INNER JOIN xcart_orders o ON o.orderid = og.orderid AND o.date >= unix_timestamp('2016-01-01')
LEFT JOIN xcart_order_group_invoices oi ON oi.orderid = og.orderid AND og.manufacturerid = oi.invoice_number 
LEFT JOIN xcart_order_group_memos om ON og.orderid = om.orderid AND og.manufacturerid = om.manufacturerid
WHERE 
(oi.orderid IS NOT NULL AND oi.status = 'U' OR om.orderid IS NOT NULL AND om.status = 'U')
SQL;
        return Connection::getInstance()->query($sql)->fetch();
    }

    public function getReceivablesTotalReport()
    {
        $sql = <<<SQL
SELECT SUM(total_gross) total, 
        COALESCE((SELECT SUM(total_gross)
        FROM xcart_order_groups 
        WHERE cb_status IN ('O') AND dc_status IN ('G', 'S')
        AND  (date(cb_update_datetime) < DATE_SUB(NOW() , INTERVAL 1 MONTH) OR date(dc_update_datetime) < DATE_SUB(NOW() , INTERVAL 1 MONTH))
        ), 0) AS one_month,
        COALESCE((SELECT SUM(total_gross)
        FROM xcart_order_groups
        WHERE cb_status IN ('O') AND dc_status IN ('G', 'S')
        AND (date(cb_update_datetime) < DATE_SUB(NOW() , INTERVAL 3 MONTH) OR date(dc_update_datetime) < DATE_SUB(NOW() , INTERVAL 3 MONTH))
        ),0) AS three_month,
        COALESCE((SELECT SUM(total_gross)
        FROM xcart_order_groups
        WHERE cb_status IN ('O') AND dc_status IN ('G', 'S')
        AND (date(cb_update_datetime) < DATE_SUB(NOW() , INTERVAL 6 MONTH) OR date(dc_update_datetime) < DATE_SUB(NOW() , INTERVAL 6 MONTH))
        ),0) AS six_month,
        COALESCE((SELECT SUM(total_gross)
        FROM xcart_order_groups
        WHERE cb_status IN ('O') AND dc_status IN ('G', 'S')
        AND (date(cb_update_datetime) < DATE_SUB(NOW() , INTERVAL 1 YEAR) OR date(dc_update_datetime) < DATE_SUB(NOW() , INTERVAL 1 YEAR))
        ),0) AS one_year
FROM xcart_order_groups 
WHERE cb_status IN ('O') AND dc_status IN ('G', 'S')
SQL;
        return Connection::getInstance()->query($sql)->fetch();
    }

    /**
     * @param array $aParams
     * @return OrderGroup[]
     */
    public function getReceivablesOrderGroups($aParams)
    {
        $aResult = [];
        $sInterval = $sIntervalQuery = '';
        switch ($aParams) {
            case 'one_month' :
                $sInterval = '1 MONTH';
                break;
            case 'three_month' :
                $sInterval = '3 MONTH';
                break;
            case 'six_month' :
                $sInterval = '6 MONTH';
                break;
            case 'one_year' :
                $sInterval = '1 YEAR';
                break;
        }

        if (!empty($sInterval)) {
            $sIntervalQuery = " AND (date(cb_update_datetime) < DATE_SUB(NOW() , INTERVAL {$sInterval}) OR date(dc_update_datetime) < DATE_SUB(NOW() , INTERVAL {$sInterval}))";
        }
        $sSql = <<<SQL
SELECT og.*        
FROM xcart_order_groups og
WHERE og.cb_status IN ('O') AND og.dc_status IN ('G', 'S')
{$sIntervalQuery}
ORDER BY orderid DESC 
SQL;
        $aOrders = Connection::getInstance()->query($sSql)->fetchAll();
        if (!empty($aOrders)) {
            foreach ($aOrders as $aOrder) {
                $aResult[] = OrderGroup::model()->fill($aOrder);
            }
        }
        return $aResult;
    }

    /**
     * @param array $aParams
     * @return OrderGroup[]
     */
    public function getPayablesOrderGroups($aParams)
    {
        $aResult = [];
        $sInterval = $sIntervalQueryInv = $sIntervalQueryMemo = '';
        switch ($aParams) {
            case 'one_month' :
                $sInterval = '1 MONTH';
                break;
            case 'three_month' :
                $sInterval = '3 MONTH';
                break;
            case 'six_month' :
                $sInterval = '6 MONTH';
                break;
            case 'one_year' :
                $sInterval = '1 YEAR';
                break;
        }

        if (!empty($sInterval)) {
            $sIntervalQueryInv = " AND date(oi.update_date) < DATE_SUB(NOW() , INTERVAL {$sInterval})";
            $sIntervalQueryMemo = " AND date(om.update_date) < DATE_SUB(NOW() , INTERVAL {$sInterval})";
        }
        $sSql = <<<SQL
SELECT og.*        
FROM xcart_order_groups og
INNER JOIN xcart_orders o ON o.orderid = og.orderid AND o.date >= unix_timestamp('2016-01-01')
LEFT JOIN xcart_order_group_invoices oi ON oi.orderid = og.orderid AND og.manufacturerid = oi.invoice_number 
LEFT JOIN xcart_order_group_memos om ON og.orderid = om.orderid AND og.manufacturerid = om.manufacturerid
WHERE 
((oi.orderid IS NOT NULL AND oi.status = 'U' {$sIntervalQueryInv}) OR (om.orderid IS NOT NULL AND om.status = 'U' {$sIntervalQueryMemo} ))

ORDER BY orderid DESC 
SQL;
        $aOrders = Connection::getInstance()->query($sSql)->fetchAll();
        if (!empty($aOrders)) {
            foreach ($aOrders as $aOrder) {
                $aResult[] = OrderGroup::model()->fill($aOrder);
            }
        }
        return $aResult;
    }
}