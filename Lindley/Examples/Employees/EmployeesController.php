<?php
namespace Lindley\Examples\Employees;

use Lindley\Examples\AbstractExampleController;

class EmployeesController extends AbstractExampleController
{
    protected $exampleTitle = 'Chicago Employees';

    public function getExamples() : array
    {
        return [
            [
                'description' => 'Earns at least 40$/hour, DESC',
                'filters' => [
                    'isHourlyWorker',
                    ['earnsPerHourAtLeast', 40]
                ],
                'order' => [
                    [
                        function( $profile ) { return $profile['hourly-rate']; },
                        'DESC'
                    ]
                ] 
            ],
            [
                'description' => 'Works in health department',
                'filters' => [
                    ['worksInDepartment', 'health']
                ]
            ],
            [
                'description' => 'Has surname with polish pattern',
                'filters' => [
                    'hasPolishSurname'
                ]
            ],
            [
                'description' => 'Has polish surname and works in police',
                'filters' => [
                    'hasPolishSurname',
                    ['worksInDepartment', 'police']
                ]
            ]
        ];
    }

    public function getData() : array
    {
        $employeesCSV = file( __DIR__ . '/chicago-employees.csv' );
        $employees = [];

        array_shift( $employeesCSV ); // header

        foreach ( $employeesCSV as $row ) {
            $arr = str_getcsv( $row );

            $employee = [
                'name' => $arr[0], // AARON,  JEFFERY M
                'job-titles' => $arr[1], // 'SERGEANT'
                'department' => $arr[2], // 'POLICE'
                'full-or-part-time' => $arr[3], // 'F'|'P'
                'salary-or-hourly' => $arr[4], // 'Salary'|'Hourly'
                'typical-hours' => $arr[5] ? (int) $arr[5] : null, // '20'|''
                'annual-salary' => $arr[6] ? (float) $arr[6] : null, // '101442.00'|''
                'hourly-rate' =>  $arr[7] ? (float) $arr[7] : null, // '19.86'|''
            ];

            $employees[] = $employee;
        }

        return $employees;
    }

    public function __construct( array $defaultFilterSets )
    {
        parent::__construct( $defaultFilterSets );

        $this->afterIndexHTML = file_get_contents(
            __DIR__ . '/views/disclaimer.php'
        );
    }
}
