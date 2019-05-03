<?php
namespace Lindley\FilterSets\Helpers;

class StringsHelper
{
    /**
     * @see  https://stackoverflow.com/a/834355
     * @return boolean
     */
    public static function startsWith( string $haystack, string $needle )
    {
         $length = strlen( $needle );

         return ( substr( $haystack, 0, $length ) === $needle );
    }

    /**
     * @see  https://stackoverflow.com/a/834355
     * @return boolean
     */
    public static function endsWith( string $haystack, string $needle )
    {
        $length = strlen( $needle );

        if ( $length == 0 ) {
            return true;
        }

        return ( substr( $haystack, -$length ) === $needle );
    }

    /**
     * not sure if this is not making more trouble that solving
     */
    public static function __callStatic( $name, $args )
    {
        if ( in_array( $name, ['startsWithArray', 'endsWithArray'] ) ) {
            list( $haystack, $needles ) = $args;
            $func = substr( $name, 0, -5 );

            foreach ( $needles as $needle ) {
                if ( static::{$func}( $haystack, $needle ) ) {
                    return true;
                }
            }

            return false;
        }
    }
}
