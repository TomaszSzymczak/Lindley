<?php

use \Lindley\Inspectors\SimpleInspector as Inspector;

class InspectorTest extends \PHPUnit\Framework\TestCase
{
    use \testingHelpers;

    /**
     * @test
     * 
     * @dataProvider getDataForInspectorGivingBoolResults
     * @dataProvider getDataForInspectorGivingNullResults
     */
    public function generalInspectorTests(
        $node,
        array $filters,
        array $filterSets,
        bool $breakOnFirstFail,
        array $expectedResults
    ) {
        $inspector = new Inspector(
            $node,
            $filters,
            $filterSets,
            $breakOnFirstFail
        );

        $this->assertEquals(
            $expectedResults,
            $inspector->getResults()
        );
    }

    /**
     * It's a dataProvider
     */
    public function getDataForInspectorGivingNullResults()
    {
        $filterSets = [PreparedFilterSet::getInstance()];

        return [
            // $node, $filters, $filterSets, $breakOnFirstFail, $results
            [
                [1,2,3],
                ['nonExistingFilter'],
                $filterSets,
                true,
                ['nonExistingFilter' => null]
            ],
            [
                [1,2,3],
                [
                    'nonExistingFilter',
                    'anotherNonExistingFilter'
                ],
                $filterSets,
                false, // $breakOnFirstFail
                [
                    'nonExistingFilter' => null,
                    'anotherNonExistingFilter' => null
                ]
            ]
        ];
    }

    /**
     * It's a dataProvider
     */
    public function getDataForInspectorGivingBoolResults()
    {
        $filterSets = [PreparedFilterSet::getInstance()];

        return [
            // $node, $filters, $filterSets, $breakOnFirstFail, $results
            [
                [1,2,3,4,5],
                ['hasMoreThanTwoElements'],
                $filterSets,
                true,
                ['hasMoreThanTwoElements' => true]
            ],
            [
                [1,2],
                ['hasMoreThanTwoElements'],
                $filterSets,
                true,
                ['hasMoreThanTwoElements' => false]
            ],
            [
                [1,2],
                ['hasNotMoreThanTwoElements'],
                $filterSets,
                true,
                ['hasNotMoreThanTwoElements' => true]
            ],
            [
                [1,2,3,4,5],
                ['hasNotMoreThanTwoElements'],
                $filterSets,
                true,
                ['hasNotMoreThanTwoElements' => false]
            ],
        ];
    }

    /**
     * It's dataProvider
     * Gives existing, and non existing filters
     */
    public function getMixedData()
    {
        $filterSets = [PreparedFilterSet::getInstance()];

        return [
            // $node, $filters, $filterSets, $breakOnFirstFail, $results
            [
                [1,2,3,4,5],
                [
                    'hasMoreThanTwoElements',
                    'nonExistingFilter',
                    'secondNonExistingFilter'
                ],
                $filterSets,
                true, // $breakOnFirstFail
                [
                    'hasMoreThanTwoElements' => true,
                    'nonExistingFilter' => null
                ]
            ],
            [
                [1,2,3,4,5],
                [
                    'hasMoreThanTwoElements',
                    'nonExistingFilter',
                    'secondNonExistingFilter',
                    'hasNotMoreThanTwoElements'
                ],
                $filterSets,
                false, // $breakOnFirstFail
                [
                    'hasMoreThanTwoElements' => true,
                    'nonExistingFilter' => null,
                    'secondNonExistingFilter' => null,
                    'hasNotMoreThanTwoElements' => false
                ]
            ],
        ];
    }
}
