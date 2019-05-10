<?php

class PreparedFilterSetTest extends \PHPUnit\Framework\TestCase
{
    protected $filterSet;

    public function setUp()
    {
        $this->filterSet = PreparedFilterSet::getInstance();
    }

    /**
     * @test
     * @dataProvider getDataForFilters
     */
    public function filterFunction(
        $node,
        string $filterName,
        $filterArgs,
        $expectedResult
    ) {
        $this->assertSame(
            $expectedResult,
            $this->filterSet->filter(
                $node,
                $filterName,
                $filterArgs
            )
        );
    }

    public function getDataForFilters()
    {
        return [
            // $node, $filterName, $filterArgs, $expectedResult
            [[1,2,3,4,5], 'hasMoreThanTwoElements', [], true],
            [[1,2], 'hasMoreThanTwoElements', [], false],
            [[1,2], 'hasNotMoreThanTwoElements', [], true],
            ['azaz', 'nonExistingFilter', [], null],
            ['azaz', 'isNotExistingFilter', [], null],
        ];
    }
}
