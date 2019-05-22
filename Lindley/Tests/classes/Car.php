<?php
/**
 * Test class
 * Parameters are public to make example simpler.
 */
class Car
{
    /** @var string */
    public $brand;

    /** @var string */
    public $model;

    /** @var string */
    public $bodytype;

    /** @var int - to simplify we dont use float */
    public $price;

    /** @var int */
    public $yearOfProduction;

    /** @var array */
    public $insuranceCompanies;

    public function __construct( $brand, $model, $bodytype, $price, $yearOfProduction )
    {
        $this->brand = $brand;
        $this->model = $model;
        $this->bodytype = $bodytype;
        $this->price = $price;
        $this->yearOfProduction = $yearOfProduction;
    }
}
