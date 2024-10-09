<?php

/*
Testpiemēra fails, kurā ir doti ievaddati 5 klientiem.
Paredzēts, ka vienlaicīgi var pārvadāt visas paciņas. 
Paredzēts, ka sākumā paciņas saņem no klientiem, bet 
noliktavā paņemtās paciņas piegādā vēlāk.
Programmai jāizdrukā 5423015.
*/

include_once './Instances.php';
include_once './TabuSearchAlgorithm.php';


$distanceArray = array(
    array(0,6,6,6,6,6),
    array(6,0,6,6,6,1),
    array(6,1,0,1,6,5),
    array(1,6,6,0,6,6),
    array(4,6,1,4,0,6),
    array(6,4,3,2,1,0),
);

$itemSizeArray = array(
    array(1,1,1),
    array(1,1,1),
    array(1,1,1),
    array(1,1,1),
    array(1,1,1)
);

$deliveryType = array(0,0,1,1,1);

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