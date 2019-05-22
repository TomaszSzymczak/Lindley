<?php
namespace Lindley\FilterSets;

use Lindley\AbstractSingleton;

abstract class AbstractFilterSet extends AbstractSingleton
{
    /**
     * Please test before extending by new phrases
     * phrases that contains their opposites should be key.
     * eg. 'without' => 'with'
     * 
     * @var array
     */
    protected $oppositionPairs = [
        'without' => 'with',
        'isNot' => 'is',
        'areNot' => 'are',
        'hasNot' => 'has',
    ];

    protected function __construct()
    {
        $this->oppositionPairs = array_merge(
            $this->oppositionPairs,
            array_flip( $this->oppositionPairs )
        );
    }

    /**
     * @param  string $filterName
     * 
     * @return object|null filterManual
     */
    protected function findFilterManual( $filterName )
    {
        if ( method_exists( $this, $filterName ) ) {
            return $this->createFilterManual( $filterName, 'directly' );
        }

        $oppositeFilterName = $this->findOppositeFilterName( $filterName );

        if ( method_exists( $this, $oppositeFilterName ) ) {
            return $this->createFilterManual( $oppositeFilterName, 'by-negation' );
        }

        return null;
    }

    protected function findOppositeFilterName( $filterName )
    {
        foreach ( $this->oppositionPairs as $a => $b ) {
            if ( 0 !== strpos( $filterName, $a ) ) {
                continue;
            }
            
            return $b . substr( $filterName, strlen( $a ) );
        }
        
        return null;
    }

    protected function createFilterManual( $filterName, $howToUse )
    {
        return (object) [
            'filterToCall' => $filterName,
            'howToUse' => $howToUse,
        ];
    }

    /**
     * Filters
     * 
     * @param  any $node
     * @param  string $filterName
     * @param  array  $filterArgs args which help filtering
     * 
     * @return bool|null null if filter is not found
     */
    public function filter( $node, string $filterName, array $filterArgs = [] )
    {
        $manual = $this->findFilterManual( $filterName );

        if ( ! $manual ) {
            return null;
        }

        /*$result = $this->{$manual->filterToCall}(
            ...array_merge( [$node], $filterArgs )
        );*/ /* TODO: ... vs below performance */

        $result = call_user_func_array(
            [$this, $manual->filterToCall],
            array_merge( [$node], $filterArgs )
        );

        // maybe there will be more options in the future...
        if ( $manual->howToUse === 'directly' ) {
            return $result;
        }

        if ( $manual->howToUse === 'by-negation' ) {
            return ! $result;
        }

        throw new Exception(
            'Filter manual failed.
            Please check @findFilterManual for filter: "' . $filterName . '"'
        );
    }
}
