<?php


namespace Modules\Distributor\Helpers;


class IntervalHelper
{
    private int $begin;
    private int $end;
    // how many will be run at one time
    private int $complexity;

    public function __construct( $begin, $end, $complexity )
    {
        $this->begin = $begin;
        $this->end = $end;
        $this->complexity = $complexity;
    }

    public function incComplexity(): int
    {
        return ++$this->complexity;
    }

    public function merge( IntervalHelper $interval ): void
    {
        $this->begin = $interval->begin;
    }

    public function getComplexity(): int
    {
        return $this->complexity;
    }

    public function getBegin(): int
    {
        return $this->begin;
    }

    public function isSuitable( $time ): bool
    {
        return $this->end - $this->begin + 1 >= $time;
    }

    // split interval on two parts
    public function split( $time ): array
    {
        if ( $this->begin + $time === $this->end ) {
            return [ new IntervalHelper( $this->begin, $this->end, $this->complexity + 1 ) ];
        }

        return [
            new IntervalHelper( $this->begin, $this->begin + $time, $this->complexity + 1 ),
            new IntervalHelper( $this->begin + $time, $this->end, $this->complexity ),
        ];
    }

}