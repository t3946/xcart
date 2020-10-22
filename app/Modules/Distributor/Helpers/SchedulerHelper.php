<?php


namespace Modules\Distributor\Helpers;


class SchedulerHelper
{
    // найти подходящий интевал, с минимальным уровнем загруженности
    // если подходящих интервалов нет - вернуть -1
    private static function getMinInterval( $intervals, $time ): int
    {
        foreach ( $intervals as $i => $interval ) {
            if ( $interval[ 'end' ] - $interval[ 'begin' ] <= $time ) {
                return $i;
            }
        }

        return -1;
    }

    private static function Interval( int $i_tree_level ): array
    {
        return [];
    }

    // insert sub-intervals in intervals with replace by index
    private static function insertReplaceIntervals( $intervals, $sub_intervals, $index ): array
    {
        // merge sub-intervals
        $head = array_slice( $intervals, 0, $index );
        $tail = array_slice( $intervals, $index + 1 );
        $intervals = array_merge( $head, $tail, $sub_intervals );
        $count = count( $intervals );

        // join intervals with equal complexity
        for ( $i = 0; $i < $count - 1; $i++ ) {
            if ( $intervals[ $i ]->getComplexity() === $intervals[ $i + 1 ]->getComplexity() ) {
                $intervals[ $i + 1 ]->merge( $intervals[ $i ] );
                unset( $intervals[ $i ] );
            }
        }

        return array_values( $intervals );
    }

    public static function algorithm( $end_circle_time, $feeds ): array
    {
        $schedule = [];
        $intervals = [ new IntervalHelper( 0, $end_circle_time, 0 ) ];

        foreach ( $feeds as $time ) {
            if ($time > $end_circle_time) {
                $schedule[] = 0;
                $intervals[0]->incComplexity();
                continue;
            }
            // search the best interval for current feed
            $best_interval = null;
            $i_interval = -1;
            foreach ( $intervals as $i => $interval ) {
                if ( !$interval->isSuitable( $time ) ) {
                    continue;
                }

                if (
                    $best_interval === null ||
                    $best_interval->getComplexity() > $interval->getComplexity()
                ) {
                    $i_interval = $i;
                    $best_interval = $interval;
                }
            }

            // save feed runtime
            $schedule[] = $best_interval->getBegin();

            // update intervals
            $sub_intervals = $best_interval->split( $time );
            $intervals = self::insertReplaceIntervals( $intervals, $sub_intervals, $i_interval );
        }

        return $schedule;
    }
}