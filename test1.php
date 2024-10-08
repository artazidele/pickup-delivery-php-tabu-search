<?php

include_once 'Instances.php';
include_once 'TabuSearchAlgorithm.php';

/*
Testpiemēra fails, kurā ir doti ievaddati 3 klientiem.
Ar N tiek apzīmēta noliktava. Ar 0, 1, 2 - klienti.
*/

/*
Matrica NxN, kur abās asīs norādīti attālumi no 0, 1, 2 vai N-tā 
klienta (vai noliktavas) līdz 0, 1, 2 vai N-tajam klientam (vai noliktavai)
*/
$distanceArray = array(
    array(0,6,9,8),
    array(5,0,10,3),
    array(4,2,0,13),
    array(5,1,8,0)
);

// Matrica ar x, y, z izmēriem katra klienta lietai
$itemSizeArray = array(
    array(3,3,3),
    array(3,3,3),
    array(3,3,3)
);

/*
Virkne ar piegādes veidiem:
    0 - ja lieta jāpiegādā klientam
    1 - ja lieta jāpaņem no klienta
*/  
$deliveryType = array(1,1,0);

// Mašīnas izmēri
$carSizeX = 3;
$carSizeY = 3;
$carSizeZ = 5;

// Izveido objektu, kas sastāda visas iespējamās instances
$instances = new Instances(
    $distanceArray, 
    $itemSizeArray, 
    $deliveryType, 
    $carSizeX,
    $carSizeY,
    $carSizeZ
);

// $instances->printValidRoads();

// Izveido Tabu Search objektu
$tabuSearchAlgorithm = new TabuSearchAlgorithm(
    $instances->allFilteredInstances,
    $instances->distanceMatrix,
    $instances->n
);

$tabuSearchAlgorithm->tabuSearch();



?>