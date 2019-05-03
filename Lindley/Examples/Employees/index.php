<?php
namespace Lindley\Examples\Employees;

// Lindley
require( realpath( __DIR__ . '/../../' ) . '/vendor/autoload.php' );

// example specific
require( __DIR__ . '/EmployeesFilterSet.php' );
require( __DIR__ . '/EmployeesController.php' );

// example start
(new EmployeesController([EmployeesFilterSet::getInstance()]))
    ->start()
;
