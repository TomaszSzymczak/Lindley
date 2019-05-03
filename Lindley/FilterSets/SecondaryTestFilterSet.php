<?php
namespace Lindley\FilterSets;

class SecondaryTestFilterSet extends AbstractFilterSet
{
    function isStringInSecondary( $value )
    {
        return is_string( $value );
    }
}
