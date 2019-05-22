<?php
namespace Lindley\Funnels;

use Lindley\Inspectors\InspectorFactory;

/**
 * Filters array of nodes
 */
class SimpleFunnel
{
    protected $inspectorFactory;

    public function __construct( InspectorFactory $inspectorFactory )
    {
        $this->inspectorFactory = $inspectorFactory;
    }

    public function filter( array $nodes, array $filters, array $filterSets ) : array
    {
        return array_values( array_filter(
            $nodes,
            function( $node ) use ( $filters, $filterSets ) {
                return ($this->inspectorFactory->createInspector(
                    [$node, $filters, $filterSets]
                ))->areAllFiltersTrue();
            }
        ));
    }

    /**
     * @param  array  $nodes things to order
     * @param  FunnelOrder[]  $order
     * 
     * @return array
     */
    public function order( array $nodes, array $order )
    {
        foreach ( $order as $fO ) {
            $nodes = $this->performNumericOrder(
                $nodes,
                $fO->orderBy,
                $fO->order
            );
        }

        return $nodes;
    }

    /**
     * Order array of nodes
     * 
     * @param  array   $nodes    Array of things to order
     * @param  \Closure $orderBy Closure to get "order value"
     * @param  string  $order    'ASC' or 'DESC'
     * 
     * @return array
     */
    protected function performNumericOrder( array $nodes, \Closure $orderBy, string $order ) : array
    {
        usort(
            $nodes,
            function( $nodeA, $nodeB ) use ( $orderBy, $order ) {
                $valA = $orderBy( $nodeA );
                $valB = $orderBy( $nodeB );

                if ( $order === 'ASC' ) {
                    return $valA <=> $valB;
                }
                elseif ( $order === 'DESC' ) {
                    return $valB <=> $valA;
                }
                else {
                    throw new \InvalidArgumentException(
                        'Wrong $order parameter. Should have been ASC or DESC.'
                    );
                }
            }
        );

        return $nodes;
    }
}
