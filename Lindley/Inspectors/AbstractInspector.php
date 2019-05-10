<?php
namespace Lindley\Inspectors;

use Lindley\FilterSets\AbstractFilterSet;

abstract class AbstractInspector
{
    protected $filterSets = [];

    protected $node;

    protected $filters = [];

    protected $results = [];

    protected $errors = [];

    /**
     * @param any    $node
     * @param array  $filters
     * @param array  $filterSets
     */
    public function __construct( $node, array $filters, array $filterSets, bool $breakOnFirstFail = true )
    {
        $this->filters = $filters;
        $this->node = $node;
        $this->filterSets = $filterSets;

        $this->inspect( $breakOnFirstFail );
    }

    /**
     * @return void
     */
    public function inspect( $breakOnFirstFail )
    {
        foreach ( $this->filters as $filterData ) {
            // fetch filter name and filter args
            if ( is_array( $filterData ) ) {
                $filterName = array_shift( $filterData );
                $filterArgs = $filterData; // beware of objects inside array
            } else {
                $filterName = $filterData;
                $filterArgs = [];
            }

            foreach ( $this->filterSets as $filterSet ) {

                $error = null;

                try {
                    $filterResult = $filterSet->filter(
                        $this->node,
                        $filterName,
                        $filterArgs
                    );
                } catch ( Exception $e ) {
                    trigger_error( $e->getMessage(), E_USER_WARNING );
                    $error = $e;
                }

                // if null maybe we'll find filter in another FilterSet
                if ( is_bool( $filterResult ) || ! is_null( $error ) ) {
                    $this->addFilterResult(
                        $filterName,
                        $filterResult,
                        get_class( $filterSet ),
                        $error
                    );

                    break;
                }
            } // /foreach

            // if filter was not found in all FilterSets,
            // finally we need to add null to filterName result
            if ( is_null( $filterResult ) ) {
                $this->addFilterResult(
                    $filterName,
                    $filterResult,
                    get_class( $filterSet ),
                    $error
                );
            }

            if ( $breakOnFirstFail && ( is_null( $filterResult ) || ! $filterResult ) ) {
                break;
            }
        } // /foreach
    }

    protected function addFilterResult( $filterName, $result, string $filterSetName, $error )
    {
        $this->results[ $filterName ] = $result;

        if ( $error ) {
            $this->errors[ $filterName ] = $error;
        }
    }

    public function getResults()
    {
        return $this->results;
    }

    public function areAllFiltersTrue()
    {
        return (bool) array_product(
            array_values( $this->results )
        );
    }
}
