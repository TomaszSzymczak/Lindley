<?php
namespace Lindley\Examples;

$examples = scandir( __DIR__ );
$examples = array_slice( $examples, 2 ); // getting rid of "." and ".."

$examples = array_values( array_filter(
    $examples,
    function( $name ) {
        return is_dir( __DIR__ . '/' . $name );
    }
));

require( __DIR__ . '/index-view.php' );
