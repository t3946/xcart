<?php


namespace Modules\Order\Helpers;


use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QAnd;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Distributor\Models\DistributorModel;
use Modules\Order\Models\OrderGroupInvoiceModel;
use Modules\Order\Models\OrderGroupMemoModel;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\ReconciliationManufacturerModel;
use Modules\Order\Models\ReconciliationModel;
use Modules\Order\Models\ReconciliationSearchKeyphraseModel;

class OrderReconciliationHelper
{
    public static function getNetFilter($period)
    {
        $having = [];
        foreach ($period as $net) {
            switch ($net) {
                case 0:
                    $having[] = ['net__gte' => 0];
                    break;
                case 30:
                    $having[] = new QAnd(['net__gte' => -30, 'net__lt' => 0]);
                    break;
                case 60:
                    $having[] = new QAnd(['net__gte' => -60, 'net__lt' => -30]);
                    break;
                case 90:
                    $having[] = new QAnd(['net__gte' => -90, 'net__lt' => -60]);
                    break;
                case 91:
                    $having[] = ['net__lt' => -90];
                    break;
            }
        }

        return new QOr($having);
    }

    public static function getPayableManufacturers($aParams)
    {
        if (($period = $aParams['period']) && \is_array($period)) {
            $d = DistributorModel::objects()->filter([
                    'order_groups__order__date__gte' => \DateTime::createFromFormat('Y-m-d', '2018-01-01', new \DateTimeZone('EST'))->getTimestamp(),
                    'order_groups__invoices__status' => 'U',
                    'd_net_payment_terms_in_days__gt' => 0,
                    'order_groups__amz_fullfilment_order_placed' => 'N',
                ]
            )->order('manufacturer');

            $d->select(['*', 'net' => new Expression('DATEDIFF(DATE_ADD(DATE(invoice_date), INTERVAL d_net_payment_terms_in_days-1 DAY), DATE(NOW()))')]);

            $d->having(self::getNetFilter($period));


            $r = $d->all();
            $ar = [];

            if ($r) {
                $r = array_filter($r, function ($d) use (&$ar) {
                    if (!in_array($d->manufacturerid, $ar)) {
                        $ar[] = $d->manufacturerid;
                        return true;
                    }
                    return false;
                });

                foreach ($r as $d) {
                    $res[] = ['manufacturerid' => $d->manufacturerid, 'manufacturer' => $d->manufacturer];
                }
            }
        }

        return $res ?? [];
    }

    public static function getPayableOrders($aParams)
    {
        if ($distributors = $aParams['distributor']) {
            if (($period = $aParams['period']) && \is_array($period)) {
                $t_a = OrderGroupInvoiceModel::objects()->getTableAlias();
                $t_am = OrderGroupMemoModel::objects()->getTableAlias();
                $o = OrderGroupModel::objects()->filter([
                    'order__date__gte' => \DateTime::createFromFormat('Y-m-d', '2018-01-01', new \DateTimeZone('EST'))->getTimestamp(),
                    new QOr(['invoices__status' => 'U', 'memos__status' => 'U']),
                    'manufacturer__d_net_payment_terms_in_days__gt' => 0,
                    'amz_fullfilment_order_placed' => 'N',
                ])->order(["{$t_a}.invoice_date", "{$t_am}.memo_date"]);
                $o->select(['*', 'net' => new Expression('DATEDIFF(DATE_ADD(DATE(COALESCE(invoice_date, memo_date)), INTERVAL d_net_payment_terms_in_days-1 DAY), DATE(NOW()))')]);

                $o->group(['order_group_id']);
                $o->having(self::getNetFilter($period));

                $o->filter(['manufacturerid__in' => $distributors]);

                return $o->all();
            }
        }
        return [];
    }

    public static function checkReconcileRules($_filter = [])
    {
        foreach (ReconciliationSearchKeyphraseModel::objects()->order(['code']) as $key_phrase) {
            $a = [];
            if ($search_keyphrase = trim($key_phrase->search_keyphrase)) {
                $v_arr = explode('<OR>', $search_keyphrase);
                foreach ($v_arr as $k) {
                    if (trim($k)) {
                        $a[] = new QOr(['description_csv__contains' => strtoupper(trim($k))]);
                    }
                }
                foreach (ReconciliationModel::objects()->filter(array_merge($_filter, [
                    'distributors__manufacturerid__isnull' => true,
                    'action' => '',
                    new QOr($a),
                ])) as $all) {
                    $all->action = 'D';
                    $all->save();
                }
            }
        }

        foreach (DistributorModel::objects()->filter(['parent_manufacturer_id' => -1]) as $distributor) {
            $a = [];
            if ($search_keyphrase = trim($distributor->d_search_keyphrase_for_reconciliation)) {
                $v_arr = explode('<OR>', $search_keyphrase);
                foreach ($v_arr as $k) {
                    if (trim($k)) {
                        $a[] = new QOr(['description_csv__contains' => strtoupper(trim($k))]);
                    }
                }
                foreach (ReconciliationModel::objects()->filter(array_merge($_filter, [
                    new QOr($a),
                ])) as $all){
                    [$rdm, $is_new] = ReconciliationManufacturerModel::objects()->getOrCreate([
                        'manufacturer_id' => $distributor->manufacturerid,
                        'reconciliation_id' => $all->id,
                    ]);
                }
            }
        }
    }
}