<?php


namespace Modules\Admin\Statistic;


use DateTime;

class OrderStatistic
{
    private const ORDER_STATUS = 'cb_status';

    private array $orders;
    private ?DateTime $min_date;

    public function __construct(array $orders, array $statuses = null)
    {
        $this->orders = empty($statuses)
            ? $orders
            : array_filter($orders, static fn($order) => in_array($order[self::ORDER_STATUS], $statuses, true));
    }

    private function getOrdersByDate(): array
    {
        return $this->min_date === null
            ? $this->orders
            : array_filter($this->orders, fn($order) => $order['date'] >= $this->min_date->getTimestamp());
    }

    private static function ordersTotalSum(array $orders): float
    {
        return (float)array_reduce($orders, static fn($c, $o) => $c + $o['total_gross']);
    }

    public function setPeriod(int $days = null): OrderStatistic
    {
        $this->min_date = $days === null ? $days : new DateTime("-$days days");
        return $this;
    }

    public function getTotal(): float
    {
        return self::ordersTotalSum($this->getOrdersByDate());
    }

    public function getCount(): float
    {
        return count($this->getOrdersByDate());
    }

}