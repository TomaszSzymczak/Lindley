# Lindley

## What is it?
It is a tool to filter, validate and order data.

## Why? Explainded by example

Lets assume that we have an array of car objects ($cars)  
and that car object is defined as follows:

```
object(stdClass)
  public 'brand' => string
  public 'model' => string
  public 'bodytype' => string
  public 'price' => int
  public 'yearOfProduction' => int
```

The easiest way of finding i.e. all "Ford" cars would be:

```
$fordCars = array_filter(
    $cars,
    function( $car ) {
        return $car->brand === 'Ford';
    }
);
```

However you may want to implement some sort of filtering in your app and pass brand name as a variable:

```
$brandCars = array_filter(
    $cars,
    function ( $car ) use ( $brand ) {
        return $car->brand === $brand;
    }
);
```

You may also want to get only Fords, which are of "coupe" bodytype and cost not more than 20000 units or some other variations.

```
$filteredCars = array_filter(
    $cars,
    function( $car ) use ( $brand, $bodytype, $maxPrice ) {
        return $car->brand === $brand
            && $car->bodytype === $bodytype
            && $car->price <= $maxPrice;
    }
);
```

Sometimes you will want to set maximum price, sometimes minimum and sometimes don't bother about price. To allow your
callback function to do all that, you will need to add some conditional logic:

```
function( $car ) use ( $brand, $bodytype, $maxPrice, $minPrice ) {
    $status = 1;

    if ( ! is_null( $brand ) ) { $status = $status && $car->brand = $brand; }
    if ( ! is_null( $bodytype ) ) { $status = $status && $car->bodytype = $bodytype; }
    if ( ! is_null( $maxPrice ) ) { $status = $status && $car->price <= $maxPrice; }
    if ( ! is_null( $minPrice ) ) { $status = $status && $car->price >= $minPrice; }

    return $status;
}
```

These logic is as far not so complex, but if you want to check more and more params or some assessments should be
little more sophisticated like this one:

```
if ( ! is_null( $hasSpecialDiscount ) ) {
    $status = $status && $car->price >= 10000
        && $car->price <= 20000
        && in_array( $discountSponsorCompany, $car->insurance_companies )
        && in_array( $car->bodytype, ['van', 'suv'] )
    ;
}
```

it will make your filter function more and more complex which will eventually become tedious and incomprehensible.

## Why?

In order to allow complicated filtering by non-complicated code I've created Lindley. (applause)

It's founded on three types of objects: **FilterSets**, **Inspectors** and **Funnels**.
Each of them has built-in logic which, I hope, will help you achieving your goals.
Eg. functions to check if word starts or ends with some phrase given as string or string[],
another which checks if all filters match or only some of them, which you can also use
to validate user input and so on.

It will be described thoroughly later.

## FilterSets

It's a place to put your filters in.

To utilize it:
```
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

$carsFilterSet = CarsFilterSet::getInstance();

// filter func. gives bool value or null if no such a filter is found
$carsFilterSet->filter( $car, 'isOfBrand', ['Ford'] );
$carsFilterSet->filter( $car, 'hasPriceBetween', [5000, 20000] );
```
As far is nothing special but the "magic" starts when you start using also other elements: Inspectors and Funnels.
Anyway, let me present one feature: you can use negation of filters out of the box:

```
$carsFilterSet->filter( $car, 'isNotOfBrand', ['Ford'] ); // will give true if brand is not "Ford"
```
To use negation you don't need to add another filter to FilterSet - AbstractFilterSet's logic makes it for you.

## Inspectors

You use it to inspect nodes – objects, arrays, scalars etc.
You can use prepared Lindley\Inspectors\SimpleInspector or extend Lindley\Inspectors\AbstractInspector to implement new functionality.

```
AbstractInspector __construct( $node, array $filters, array $filterSets, bool $breakOnFirstFail = true )
```

```
$inspector = new \Lindley\Inspectors\SimpleInspector;

$inspector->inspect(
    $carNode,
    [
        ['isOfBrand', 'Ford'],
        ['hasPriceBetween', 10000, 20000],
        'isNotThisYearCar'
    ],
    [
        CarsFilterSet::getInstance()
    ]
);
```

As first parameter you put $node, which you want to inspect.  
As second parameter array of filters.  

There are two ways of listing filters:  
  * give just filter name – if you don't want to give filter function any extra arguments (basically $node will be given)  
  * give array of [0 => filterName, 1,2,3 => filterParams]

As third parameter you give array of FilterSets.

Inspector will try to look for filter or its negation in first element
in the array, if it doesn't find it, it will look into second element and so on.

You can optionally use fourth argument $breakOnFirstFail, which is set as true by default. True value means that if
inspector won't find filter or filter returns false, inspector won't check next filters. It's useful when you want to filter data.
However, if you want to validate data, you can set $breakOnFirstFail to false, so inspector checks every filter
you've specified as second param.

To check the results you can invoke function:
```
$inspector->getResults();
```
which will give you results as array where key
is a filter name and value is a filter result. If no filter was found, value will be set as null.

In many cases you will want to use
```
$inspector->areAllFiltersTrue();
```
which will give you bool value as a result.

## Funnels

You use it to filter array of nodes and optionally put filtered nodes in some order.

```
SimpleFunnel __construct( \Lindley\Inspectors\InspectorFactory $inspectorFactory )
```

```
$funnel = new \Lindley\Funnels\SimpleFunnel(
    new \Lindley\Inspectors\InspectorFactory( 'SimpleInspector' )
);

$results = $funnel->filter(
    $cars,
    [
        ['isOfBrand', 'Ford'],
        ['hasPriceBetween', 10000, 20000],
        'isNotThisYearCar'
    ],
    [
        CarsFilterSet::getInstance()
    ]
);
```

$results will be array of cars which meets requirements given as second argument,
checked by filter sets given as third parameter.

## FunnelOrder

You can create it, to order nodes which come out from the Funnel.
```
FunnelOrder __construct( \Closure $orderBy, string $order )
```
$order can take two values: ASC or DESC.
$orderBy takes an anonymous function which takes $node and returns "param" by which you want to order. Eg.:
function( $car ) { return $car->price; }

It's possible to order firstly by one parameter and secondly by another.

You use order function of Funnel in following manner:
```
use Lindley\Funnels\FunnelOrder;

$orderedCars = $funnel->order(
    $cars,
    [
        new FunnelOrder( function( $car ) { return $car->price; }, 'ASC' ),
        new FunnelOrder( function( $car ) { return $car->yearOfProduction; }, 'DESC' ),
    ]
);
```
If param returned by Closure is numeric, sort also will be numeric, if string, you get alphabetical order.

## Patron

[Read about Lindley's Warsaw Water Filters](https://en.wikipedia.org/wiki/Warsaw_Water_Filters)
