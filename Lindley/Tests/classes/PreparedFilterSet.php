<?php

class PreparedFilterSet extends \Lindley\FilterSets\AbstractFilterSet
{
    public function hasMoreThanTwoElements( array $array )
    {
        return count( $array ) > 2;
    }
}
