<?php
namespace Lindley\FilterSets;

class PrimaryTestFilterSet extends AbstractFilterSet
{
    function isIntegerInPrimary( $value )
    {
        return is_integer( $value );
    }
}

