<?php

class CarsFilterSet extends \Lindley\FilterSets\AbstractFilterSet
{
    public function isOfBrand( $car, $brandName )
    {
        return $car->brand === $brandName;
    }

    public function hasPriceBetween( $car, $min, $max )
    {
        return $car->price >= $min && $car->price <= $max;
    }

    public function hasSpecialDiscount( $car, $sponsorCompany, array $bodyTypes )
    {
        return $this->hasPriceBetween( $car, 10000, 20000 )
            && in_array( $sponsorCompany, $car->insurance_companies )
            && in_array( $car->bodytype, $bodyTypes )
        ;
    }

    public function isThisYearCar( $car )
    {
        return (int) date( 'Y' ) === $car->yearOfProduction;
    }
}
