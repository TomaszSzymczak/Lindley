<?php
namespace Lindley;

// może nawet lepiej byłoby zrobić to za pomocą trait'a

abstract class AbstractSingleton
{
    protected static $oInstancesByClass = [];

    final public static function getInstance()
    {
        $className = get_called_class();

        if ( empty( self::$oInstancesByClass[ $className ] ) ) {
            self::$oInstancesByClass[ $className ] = new static;
        }

        return self::$oInstancesByClass[ $className ];
    }

    protected function __construct() {}

    public function reset()
    {
        self::$oInstancesByClass[ $className ] = null;
    }
}
