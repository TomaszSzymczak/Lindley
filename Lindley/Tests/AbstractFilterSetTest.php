<?php

class AbstractFilterSetTest extends \PHPUnit\Framework\TestCase
{
    use \testingHelpers;

    protected $filters;
    
    protected function setUp()
    {
        $mock = $this->getMockBuilder( \Lindley\FilterSets\AbstractFilterSet::class )
            ->disableOriginalConstructor()
            ->getMockForAbstractClass()
        ;

        $this->filters = $mock::getInstance();
    }

    /**
     * @test
     * @dataProvider getSomeFilterNames
     */
    public function givenFilterNamesFindOpposite( $filterName, $expectedOpposite )
    {
        $this->assertEquals(
            $expectedOpposite,
            $this->invokeNonPublicMethod(
                $this->filters,
                'findOppositeFilterName',
                [$filterName]
            )
        );
    }

    public function getSomeFilterNames()
    {
        return [
            ['withOrange', 'withoutOrange'],
            ['withoutJuice', 'withJuice'],
            ['fakeWord', null],
            ['hasAnything', 'hasNotAnything']
        ];
    }

    /**
     * @test
     * @dataProvider getFilterNamesWithManuals
     */
    public function givenFilterNamesFindFilterManual( $filterName, $expectedManual )
    {
        $this->assertEquals(
            $expectedManual,
            $this->invokeNonPublicMethod(
                $this->filters,
                'findFilterManual',
                [$filterName]
            )
        );
    }

    public function getFilterNamesWithManuals()
    {
        return [
            ['nonExistingFilter', null]
        ];
    }
}
