<?php
namespace Lindley\Examples\Employees;

use Lindley\FilterSets\AbstractFilterSet;
use Lindley\FilterSets\Helpers\StringsHelper;

class EmployeesFilterSet extends AbstractFilterSet
{
    public function worksInDepartment( $profile, string $department )
    {
        return
            strtolower( $department )
            ===
            strtolower( $profile['department'] )
        ;
    }

    public function isSalaryWorker( $profile )
    {
        return 'Salary' === $profile['salary-or-hourly'];
    }

    public function isFullTimeWorker( $profile )
    {
        return 'F' === $profile['full-or-part-time'];
    }

    /**
     * Let's assume that category "A" workers
     * in company nomenclature are such workers
     * that earns >= 100k
     */
    public function isCategoryAWorker( $profile )
    {
        return
            ! empty( $profile['annual-salary'] )
            &&
            $profile['annual-salary'] >= 100000;
    }

    public function isHourlyWorker( $profile )
    {
        return 'Hourly' === $profile['salary-or-hourly'];
    }

    public function earnsPerHourAtLeast( $profile, $value )
    {
        return $profile['hourly-rate'] >= $value;
    }

    public function earnsPerHourBetween( $profile, $leftValue, $rightValue )
    {
        return
            $profile['hourly-rate'] >= $leftValue
            &&
            $profile['hourly-rate'] <= $rightValue
        ;
    }

    public function hasPolishSurname( $profile )
    {
        return StringsHelper::endsWithArray(
            strtolower( explode( ',', $profile['name'] )[0] ), // not utf8
            ['ski', 'ska', 'cki', 'cka', 'ak', 'icz']
        );
    }
}
