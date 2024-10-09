<?php

include_once './Instances.php';
include_once './TabuSearchAlgorithm.php';


$distanceArray = array(
    array(0,1,1,1,1),
    array(2,0,2,2,2),
    array(3,3,0,3,3),
    array(4,4,4,0,4),
    array(5,5,5,5,0)
    // array(0,1,1,1,1,1,1,2),
    // array(2,0,2,2,2,2,3,2),
    // array(3,3,0,3,3,4,3,3),
    // array(4,4,4,0,5,4,4,4),
    // array(5,5,5,6,0,5,5,5),
    // array(6,6,7,6,6,0,6,6),
    // array(7,8,7,7,7,7,0,7),
    // array(1,8,8,8,8,8,8,0)
);

$itemSizeArray = array(
    // array(1,1,1),
    // array(1,1,1),
    // array(1,1,1),
    array(1,1,1),
    array(1,1,1),
    array(1,1,1),
    array(1,1,1)
);

$deliveryType = array(0,0,0,0);

$carSizeX = 10;
$carSizeY = 10;
$carSizeZ = 10;

$instances = new Instances(
    $distanceArray, 
    $itemSizeArray, 
    $deliveryType, 
    $carSizeX,
    $carSizeY,
    $carSizeZ
);

$instances->printValidRoads();

$tabuSearchAlgorithm = new TabuSearchAlgorithm(
    $instances->allFilteredInstances,
    $instances->distanceMatrix,
    $instances->n
);

$tabuSearchAlgorithm->tabuSearch();



?>