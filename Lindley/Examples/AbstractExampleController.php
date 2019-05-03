<?php
namespace Lindley\Examples;

use Lindley\Inspectors\InspectorFactory;
use Lindley\Funnels\SimpleFunnel;
use Lindley\Funnels\FunnelOrder;

abstract class AbstractExampleController
{
    abstract function getData();

    abstract function getExamples();

    /** @var string */
    protected $exampleTitle;

    protected $afterIndexHTML;

    /** @var string */
    protected $exampleDir;

    /** @var array used when not specified in particular example */
    protected $defaultFilterSets = [];

    public function __construct( array $defaultFilterSets )
    {
        $this->defaultFilterSets = $defaultFilterSets;

        $this->exampleDir = dirname(
            $this->getClassFilePath()
        );
    }

    public function start()
    {
        if ( empty( $_GET['exampleNo'] ) ) {
            return $this->index();
        }

        $exampleIX = (int) $_GET['exampleNo'] - 1;

        $this->example(
            $exampleIX
        );
    }

    /**
     * Prepares data and renders index view.
     * 
     * @return void
     */
    public function index()
    {
        $examples = $this->getExamples();
        $title = $this->exampleTitle . ' - Lindley Examples';
        $header = $this->exampleTitle;
        $afterIndexHTML = $this->afterIndexHTML;

        require(
            __DIR__ . '/example-index-view.php'
        );
    }

    /**
     * Prepares data and renders example view.
     * 
     * @param  int    $exampleIX
     * 
     * @return void
     */
    public function example( int $exampleIX )
    {
        $exampleData = $this->getExamples()[ $exampleIX ];

        $title = $exampleData['description']
            . ' - '
            . $this->exampleTitle
            . ' - Lindley Examples'
        ;

        $funnel = new SimpleFunnel(
            new InspectorFactory(
                'Lindley\Inspectors\SimpleInspector'
            )
        );

        $results = $funnel->filter(
            $this->getData(),
            $exampleData['filters'],
            $exampleData['filterSets'] ?? $this->defaultFilterSets
        );

        if ( ! empty( $exampleData['order'] ) ) {
            $order = array_map(
                function( $orderPart ) {
                    return new FunnelOrder( ...$orderPart );
                },
                $exampleData['order']
            );

            $results = $funnel->order(
                $results,
                $order
            );
        }

        require(
            $this->exampleDir . '/views/example.php'
        );
    }

    /**
     * Return child class file path. 
     * To solve __FILE__ issue in child classes.
     * 
     * @return string
     */
    private function getClassFilePath()
    {
        $c = new \ReflectionClass( $this );
        return $c->getFileName();
    }
}
