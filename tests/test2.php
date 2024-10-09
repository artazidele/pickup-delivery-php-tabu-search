<?php

/*
Testpiemēra fails, kurā ir doti ievaddati 6 klientiem.
Paredzēts, ka vienlaicīgi var pārvadāt vienu paciņu. 
3 klientiem paciņa jāaizved, 3 jāsaņem.
Programmai jāizdrukā virkne 6xy6xy6xy6, kur xy ir kāds no pāriem 25, 14 vai 03.
*/

include_once './Instances.php';
include_once './TabuSearchAlgorithm.php';


$distanceArray = array(
    array(0,6,6,1,6,6,6),
    array(6,0,6,6,1,6,6),
    array(6,6,0,6,6,1,6),
    array(6,6,6,0,6,6,3),
    array(6,6,6,6,0,6,2),
    array(6,6,6,6,6,0,1),
    array(6,2,1,6,6,6,0),
);

$itemSizeArray = array(
    array(3,3,2),
    array(3,3,2),
    array(3,3,2),
    array(3,3,2),
    array(3,3,2),
    array(3,3,2)
);

$deliveryType = array(0,0,0,1,1,1);

$carSizeX = 3;
$carSizeY = 3;
$carSizeZ = 3;

$instances = new Instances(
    $distanceArray, 
    $itemSizeArray, 
    $deliveryType, 
    $carSizeX,
    $carSizeY,
    $carSizeZ
);

// $instances->printValidRoads();

$tabuSearchAlgorithm = new TabuSearchAlgorithm(
    $instances->allFilteredInstances,
    $instances->distanceMatrix,
    $instances->n
);

$tabuSearchAlgorithm->tabuSearch();



?>