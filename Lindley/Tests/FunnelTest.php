<?php

use Lindley\Funnels\SimpleFunnel;
use Lindley\Funnels\FunnelOrder;
use Lindley\Inspectors\InspectorFactory;

class FunnelTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function filterByBrandName()
    {
        $fords = $this->getCars( 'Fords' );
        $nissans = $this->getCars( 'Nissans' );

        $allCars = array_merge( $fords, $nissans );
        shuffle( $allCars );

        $funnel = new SimpleFunnel(
            new InspectorFactory( '\Lindley\Inspectors\SimpleInspector' )
        );

        $filteredFordCars = $funnel->filter(
            $allCars,
            [
                ['isOfBrand', 'Ford']
            ],
            [
                CarsFilterSet::getInstance()
            ]
        );

        $this->assertSame(
            count( $fords ),
            count( $filteredFordCars )
        );
    }

    protected function getCars( string $cars )
    {
        $data = $this->{'get' . ucfirst( $cars )}();

        return array_map(
            function( $arr ) {
                return new Car( ...$arr );
            },
            $data
        );
    }

    public function getFords()
    {
        // $brand, $model, $bodytype, $price, $yearOfProduction

        return [
            ['Ford', 'Fiesta', 'coupe', 10000, 2005],
            ['Ford', 'Focus', 'sedan', 25000, 2010]
        ];
    }

    public function getNissans()
    {
        // $brand, $model, $bodytype, $price, $yearOfProduction

        return [
            ['Nissan', 'Primera', 'sedan', 20000, 2008]
        ];
    }
}
