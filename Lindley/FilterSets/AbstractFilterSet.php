<?php
namespace Lindley\FilterSets;

use Lindley\AbstractSingleton;

abstract class AbstractFilterSet extends AbstractSingleton
{
    /**
     * UWAGA: przetestować przed rozszerzeniem o nowe frazy
     * pamiętać, że "without" zawiera "with",
     * stąd taka kolejność
     * 
     * @var array
     */
    protected $oppositionPairs = [
        'without' => 'with',
        'isNot' => 'is',
        'areNot' => 'are'
    ];

    protected function __construct()
    {
        $this->oppositionPairs = array_merge(
            $this->oppositionPairs,
            array_flip( $this->oppositionPairs )
        );
    }

    /**
     * Szuka czy istnieje podany filtr
     * i jeśli tak zwraca, jak go użyć
     * 
     * @param  string $filterName
     * @return object filterManual
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
            'filterName' => $filterName,
            'howToUse' => $howToUse,
        ];
    }

    public function filter( $node, string $filterName, array $filterArgs )
    {
        $manual = $this->findFilterManual( $filterName );

        if ( ! $manual ) {
            return null;
        }

        //$result = $this->{$manual->filterName}( $node );
        $result = call_user_func_array(
            [$this, $manual->filterName],
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
