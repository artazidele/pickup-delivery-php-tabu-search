<?php
class Instances {

    public $distanceMatrix;
    private $itemSizeMatrix;
    private $deliveryTypeArray;
    private $carX;
    private $carY;
    private $carZ;

    public $n;
    private $allIndexes = [];

    private $allInstances = [];
    private $allInstancesWithOutN = [];
    private $allArraysInSizeOne = [];

    public $allFilteredInstances = [];

    // Klases konstruktors
    public function __construct($dM, $iSM, $dTA, $cX, $cY, $cZ) {
        $this->distanceMatrix = $dM;
        $this->itemSizeMatrix = $iSM;
        $this->deliveryTypeArray = $dTA;
        $this->carX = $cX;
        $this->carY = $cY;
        $this->carZ = $cZ;
        $this->n = sizeof($this->distanceMatrix[0]) - 1;

        for ($i = 0; $i < $this->n; $i++) {
            array_push($this->allIndexes, [$i]);
        }
        
        for ($i = 0; $i < $this->n; $i++) {
            array_push($this->allArraysInSizeOne, [$i]);
        }

        $this->createInstances($this->allArraysInSizeOne);
    }

    // Funkcijas, kas izveido visas iespējamās instances

    // Funkcija, kas izveido visus dažādos masīvus
    // bez noliktavas indeksa un izsauc nākamo funciju
    public function createInstances($allInstancesArray) {
        $newInstanceArray = [];
        foreach ($allInstancesArray as $currentArray) {
            for ($i = 0; $i < sizeof($this->allIndexes); $i++) {
                if (!in_array($this->allIndexes[$i][0], $currentArray)) {
                    $arrayToAdd = $currentArray;
                    array_push($arrayToAdd, $this->allIndexes[$i][0]);
                    array_push($newInstanceArray, $arrayToAdd);
                }
            }
        }
        if (sizeof($newInstanceArray[0]) < sizeof($this->allIndexes)) {
            $this->createInstances($newInstanceArray);
        } else {
            foreach($newInstanceArray as $arr) {
                array_push($this->allInstancesWithOutN, $arr);
            }
            $this->createAllroadsWithN();
        }
    }

    // Funkcija, kas izsauc funkciju, lai izveidotu
    // visus iespējamos ceļus ar noliktavu un filtrēšanas funkciju
    public function createAllroadsWithN() {
        foreach ($this->allInstancesWithOutN as $oneArr) {
            $arrToAdd = [];
            array_push($arrToAdd, $this->n);
            for($i=0; $i<$this->n; $i++) {
                array_push($arrToAdd, $oneArr[$i]);
            }
            array_push($arrToAdd, $this->n);
            array_push($this->allInstances, $arrToAdd);
            $this->returnAll($oneArr);
        }
        $this->filterRoads();
    }

    // Funkcija, kas izsauc rekursīvo funkciju visu dažādo ceļu 
    // izveidei ar noliktavas indeksu
    public function returnAll($arr) {
        $emptyArr = [];
        $n = sizeof($arr);
        $firstArr = [];
        array_push($firstArr, $n);
        array_push($emptyArr, $firstArr);
        $this->recursion($emptyArr, $arr, 0);
    }

    // Rekursīvā funkcija visu dažādo ceļu izveidei
    public function recursion($createdArrs, $givenArr, $givenArrIndex) {
        $newArrs = [];
        foreach($createdArrs as $oneArr) {
            $arrToAdd = [];
            foreach($oneArr as $i) {
                array_push($arrToAdd, $i);
            }
            array_push($arrToAdd, $givenArr[$givenArrIndex]);
            $arrToAddN = $arrToAdd;
            array_push($arrToAddN, $oneArr[0]);
            array_push($newArrs, $arrToAdd);
            array_push($newArrs, $arrToAddN);
        }
        $newGivenArrIndex = $givenArrIndex + 1;
        if ($newGivenArrIndex == sizeof($givenArr)) {
            foreach($newArrs as $arr) {
                if ($arr[sizeof($arr)-1] != $arr[0]) {
                    array_push($arr, $arr[0]);
                }
                if (!in_array($arr, $this->allInstances)) {
                    array_push($this->allInstances, $arr);
                }
            }
        } else {
            $this->recursion($newArrs, $givenArr, $newGivenArrIndex);
        }
    }

    // Ceļu filtrēšanas funkcija, kas sadala maršrutu daļās starp 
    // noliktavas indeksu
    public function filterRoads() {
        foreach ($this->allInstances as $instance) {
            $parts = [];
            $newPart = [];
            array_push($newPart, $instance[0]);
            for ($i=1; $i<sizeof($instance); $i++) {
                array_push($newPart, $instance[$i]);
                if ($instance[$i] == $this->n) {
                    array_push($parts, $newPart);
                    $newPart = [];
                }
            }
            $this->canReceiveAndDeliver($parts);
        }
    }

    // Funkcija, kas pārbauda, vai starp noliktavas apmeklējumu
    // var saņemt un piegādāt visas lietas no klientiem
    public function canReceiveAndDeliver($instancesParts) {
        $maxSize = 0;
        foreach ($instancesParts as $part) {
            // aprēķina sākumā ievietoto paciņu tilpumu
            $size = 0;
            foreach ($part as $index) {
                if($index != $this->n) {
                    if($this->deliveryTypeArray[$index] == 0) {
                        $itemSize = $this->itemSizeMatrix[$index][0]*$this->itemSizeMatrix[$index][1]*$this->itemSizeMatrix[$index][2];
                        $size = $size + $itemSize;
                    } 
                }
            }
            foreach ($part as $index) {
                if($index != $this->n) {
                    // nodod paciņu klientam
                    if($this->deliveryTypeArray[$index] == 0) {
                        $itemSize = $this->itemSizeMatrix[$index][0]*$this->itemSizeMatrix[$index][1]*$this->itemSizeMatrix[$index][2];
                        $size = $size - $itemSize;
                    } else {
                        // paņem paciņu no klienta
                        $itemSize = $this->itemSizeMatrix[$index][0]*$this->itemSizeMatrix[$index][1]*$this->itemSizeMatrix[$index][2];
                        $size = $size + $itemSize;
                    }
                }
                if ($maxSize < $size) {
                    $maxSize = $size;
                }
            }
        }
        $carSize = $this->carX * $this->carY * $this->carZ;
        if ($maxSize <= $carSize) {
            $validInstance = [];
            foreach ($instancesParts as $part) {
                foreach($part as $index) {
                    array_push($validInstance, $index);
                }
            }
            array_push($this->allFilteredInstances, $validInstance);
        }
    }

    // Funkcija, kas izprintē visus derīgos maršrutus
    public function printValidRoads() {
        foreach ($this->allFilteredInstances as $instance) {
            echo " Road: ";
            foreach ($instance as $index) {
                echo $index;
            }
            echo " . ";
        }
    }
}
?>