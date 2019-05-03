<?php
namespace Lindley\Inspectors;

class InspectorFactory
{
    protected $inspectorClassName;

    public function __construct( string $inspectorClassName )
    {
        $this->inspectorClassName = $inspectorClassName;
    }

    public function createInspector( array $params ) : AbstractInspector
    {
        return new $this->inspectorClassName( ...$params );
    }
}
