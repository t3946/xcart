<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classSQLBuilder.php";

class classOrderReports
{
    private $iStartDate;
    private $iEndDate;
    private $sOrderSource;
    private $aStoreForonts;
    private $aManufacturers;
    private $sAccountingMethod;
    private $sProfitMarginRange;
    private $iProfitMarginRangeBegin;
    private $iProfitMarginRangeEnd;
    private $sOrderStatus;
    private $sGraphPeriod;
    private $aGraphData;

    private $oSQL = null;

    public function __construct()
    {
        $this->oSQL = new classSQLBuilder();
    }

    public function setStartDate($iStartDate)
    {
        $this->iStartDate = $iStartDate;
        return $this;
    }

    public function setEndDate($iEndDate)
    {
        $this->iEndDate = $iEndDate;
        return $this;
    }

    public function setOrderSource($sOrderSource)
    {
        $this->sOrderSource = $sOrderSource;
        return $this;
    }

    public function setStoreFronts($aStoreForonts)
    {
        $this->aStoreForonts = $aStoreForonts;
        return $this;
    }

    public function setManufacturers($aManufacturers)
    {
        $this->aManufacturers = $aManufacturers;
        return $this;
    }

    public function setAccountingMethod($sAccountingMethod)
    {
        $this->sAccountingMethod = $sAccountingMethod;
        return $this;
    }

    public function setProfitMarginRange($sProfitMarginRange, $iProfitMarginRangeBegin = null, $iProfitMarginRangeEnd = null)
    {
        $this->sProfitMarginRange = $sProfitMarginRange;
        $this->iProfitMarginRangeBegin = $iProfitMarginRangeBegin;
        $this->iProfitMarginRangeEnd = $iProfitMarginRangeEnd;
        return $this;
    }

    public function setGraphPeriod($sGraphPeriod)
    {
        $this->sGraphPeriod = $sGraphPeriod;
        return $this;
    }

    public function setOrderStatus($sStatus)
    {
        $this->sOrderStatus = $sStatus;
    }

    public function getReportSQL() {
        return $this->oSQL->getQuery();
    }

    private function calculateReport()
    {
        $this->oSQL->addSelect('og.manufacturerid')->
        addSelect('m.manufacturer')->
        addSelect('SUM(og.accounting_net_0)', 'accounting_net_0')->
        addSelect('SUM(og.accounting_net_1_cost_to_us)', 'accounting_net_1_cost_to_us')->
        addSelect('SUM(og.accounting_net_2_shipping)', 'accounting_net_2_shipping')->
        addSelect('SUM(og.accounting_net_3_ref_to_cust)', 'accounting_net_3_ref_to_cust')->
        addSelect('SUM(og.accounting_net_4_ref_to_us)', 'accounting_net_4_ref_to_us')->
        addSelect('SUM(og.accounting_net_5_profit)', 'accounting_net_5_profit')->
        addSelect('COUNT(1)', 'order_count');


        $this->oSQL->addFromTable('orders', 'o')->
        addInnerJoin('order_groups', 'og', 'og.orderid = o.orderid')->
        addInnerJoin('manufacturers', 'm', 'og.manufacturerid = m.manufacturerid');

        $this->oSQL->addCondition('o.date>='.$this->iStartDate);
        $this->oSQL->addCondition('o.date<='.$this->iEndDate);

        switch ($this->sOrderSource) {
            case "xcart_orders_only" :
                $this->oSQL->addCondition("o.amazonorderid=''");
                break;
            case "amazon_orders_only" :
                $this->oSQL->addCondition("o.amazonorderid!=''");
                break;
            case "amazon_orders_MFN" :
                $this->oSQL->addCondition("o.amazon_fulfillment_channel='MFN'");
                break;
            case "amazon_orders_FBA" :
                $this->oSQL->addCondition("o.amazon_fulfillment_channel='AFN'");
                break;
        }
        if (!empty($this->aStoreForonts)) {
            $this->oSQL->addCondition("o.storefrontid IN (" . implode(',', $this->aStoreForonts) . ")");
        }

        if (!empty($this->aManufacturers)) {
            $this->oSQL->addCondition("og.manufacturerid IN (" . implode(',', $this->aManufacturers) . ")");
        }

        $sYxis1='Real Net';

        switch ($this->sProfitMarginRange) {
            case "margin_less_100" :
                $this->oSQL->addCondition("og.profit_margin < 100");
                $sYxis1='Real PM';
                break;
            case "margin_less_1" :
                $this->oSQL->addCondition(sprintf("og.profit_margin <= %d", $this->iProfitMarginRangeEnd));
                break;
            case "margin_1_2" :
                $this->oSQL->addCondition(sprintf("og.profit_margin < %d", $this->iProfitMarginRangeEnd));
                $this->oSQL->addCondition(sprintf("og.profit_margin >= %d", $this->iProfitMarginRangeBegin));
                break;

        }

        if (!empty($this->sOrderStatus) && $this->sOrderStatus == 'R') {
            $this->oSQL->addCondition("og.cb_status IN ('AP','P','O','H','A','R')");
        } else
            $this->oSQL->addCondition("og.cb_status IN ('AP','P','O','H','A')");

        $this->oSQL->addCondition('acc_paymentid != 0');

        $sGraphDateFormat = '';

        $this->oSQL->addGroupBy('og.manufacturerid');
        switch ($this->sGraphPeriod) {
            case 'D':
                $this->oSQL->addGroupBy('YEAR(FROM_UNIXTIME(o.date))')->addGroupBy('MONTH(FROM_UNIXTIME(o.date))')->addGroupBy('DAY(FROM_UNIXTIME(o.date))')->
                addSelect("UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(o.date), '%Y-%m-%d 00:00:00'))","report_date");
                $sGraphDateFormat = '%d %b %Y';
                $this->oSQL->addOrderBy("YEAR(FROM_UNIXTIME(o.date)),MONTH(FROM_UNIXTIME(o.date)),DAY(FROM_UNIXTIME(o.date)) ASC");
                break;
            case 'W':
                $this->oSQL->addGroupBy('YEARWEEK(FROM_UNIXTIME(o.date),1)')->
                addSelect("UNIX_TIMESTAMP(STR_TO_DATE(DATE_FORMAT(FROM_UNIXTIME(o.date) - INTERVAL (WEEKDAY(FROM_UNIXTIME(o.date))) DAY,'%d.%m.%Y'), '%d.%m.%Y'))","report_date");
                $sGraphDateFormat = '%b %Y';
                $this->oSQL->addOrderBy('report_date ASC');
                break;
            case 'M':
                $this->oSQL->addGroupBy('YEAR(FROM_UNIXTIME(o.date))')->addGroupBy('MONTH(FROM_UNIXTIME(o.date))')->
                addSelect("UNIX_TIMESTAMP(STR_TO_DATE(DATE_FORMAT(FROM_UNIXTIME(o.date), '01.%m.%Y'), '%d.%m.%Y'))","report_date");
                $sGraphDateFormat = '%b %Y';
                $this->oSQL->addOrderBy('report_date ASC');
                break;
        }

        $this->oSQL->Execute('manufacturerid');

//        echo $this->getReportSQL(); exit;

        $aSqlResult = $this->oSQL->getQueryResult();
//        var_dump($aSqlResult); exit;
        if (!empty($aSqlResult)) {
            $aManArrayReal = [];
            foreach ($aSqlResult as $manufacturerid => $aReport) {
                foreach($aReport as $aReportData) {
                    $realNet = $this->getRealNet($aReportData);
                    $realPM = $this->getRealPM($aReportData, $realNet);

                    switch ($this->sProfitMarginRange) {
                        case "margin_less_100" :
                            $aManArrayReal[$manufacturerid] += $realPM;
                            break;
                        default:
                            $aManArrayReal[$manufacturerid]+=$realNet;
                    }
                }
            }
        }
        arsort($aManArrayReal);

        $aSqlResultOrdered = [];

        foreach (array_keys($aManArrayReal) as $key) {
            $aSqlResultOrdered[$key] = $aSqlResult[$key] ;
        }

        foreach ($aSqlResultOrdered as $iManufacturerId => $aValues) {
            $sReportData = [];
            $sReportData2 = [];
            foreach ($aValues as $iNumber => $aPeriods) {
                $aSqlResultOrdered[$iManufacturerId]['manufacturer'] = $aPeriods['manufacturer'];
                $aSqlResultOrdered[$iManufacturerId][$iNumber]['report_date'] = (int) $aPeriods['report_date'];
                $aSqlResultOrdered[$iManufacturerId][$iNumber]['order_count'] = (int) $aPeriods['order_count'];

                switch ($this->sProfitMarginRange) {
                    case "margin_less_100" :
                        $sReportData[] = [$aSqlResultOrdered[$iManufacturerId][$iNumber]['report_date']*1000,$this->getRealPM($aPeriods, $this->getRealNet($aPeriods))];
                        break;
                    default:
                        $sReportData[] = [$aSqlResultOrdered[$iManufacturerId][$iNumber]['report_date']*1000,$this->getRealNet($aPeriods)];
                }

                $sReportData2[] = [$aSqlResultOrdered[$iManufacturerId][$iNumber]['report_date']*1000,$aSqlResultOrdered[$iManufacturerId][$iNumber]['order_count']];
            }

            $aSqlResultOrdered[$iManufacturerId]['report_string_1'] = json_encode($sReportData);
            $aSqlResultOrdered[$iManufacturerId]['report_string_2'] = json_encode($sReportData2);
            $aSqlResultOrdered[$iManufacturerId]['report_date_format'] = $sGraphDateFormat;
            $aSqlResultOrdered[$iManufacturerId]['yAxis1'] = $sYxis1;
        }

        $this->aGraphData = $aSqlResultOrdered;



        return $this;
    }

    private function getRealNet($aReportData){
        return floatval($aReportData["accounting_net_0"] + $aReportData["accounting_net_4_ref_to_us"] - $aReportData["accounting_net_3_ref_to_cust"]);
    }
    private function getRealPM($aReportData, $realNet){
        $realPm = 0;
        if ($realNet != 0)
            $realPm = floatval(round(($aReportData["accounting_net_5_profit"]/$realNet)*100,2));
        return $realPm;
    }

    public function getReportsData()
    {
        $this->calculateReport();
        return $this->aGraphData;
    }

}