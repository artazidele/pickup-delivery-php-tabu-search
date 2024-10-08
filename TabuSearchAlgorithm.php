<?php

class TabuSearchAlgorithm {
    private $instances = [];
    private $distanceMatrix = [];
    private $n;

    private $neighborhood = [];
    private $subNeighborhood = [];
    private $memory = [];

    // Klases konstruktors
    public function __construct($i, $d, $n) {
        $this->instances = $i;
        $this->distanceMatrix = $d;
        $this->n = $n;
    }

    // Tabu Search algoritma funkcija
    public function tabuSearch() {
        // iztīra atmiņu
        $this->memory = [];
        // izvēlas sākotnējo instanci
        $i = $this->instances[0];
        $best = $i;
        // atmiņā saglabā sākotnējo intsanci
        array_push($this->memory, $i);
        // izveido izmainīto apkārtni
        $this->subNeighborhoodFunction($i);
        // kamēr apkārtne nav tukša veic algoritmu
        $empty = 0;
        while ($empty == 0) {
            // izveido izmainīto apkārtni
            $this->subNeighborhoodFunction($i);
            // izvēlas labāko no apkārtnes instancēm
            $i = $this->subNeighborhood[0];
            foreach ($this->subNeighborhood as $neighborhood) {
                if($this->costFunction($i) > $this->costFunction($neighborhood)) {
                    $i = $neighborhood;
                }
            }
            // salīdzina ar labāko
            if ($this->costFunction($i) - $this->costFunction($best) < 0) {
                $best = $i;
            }
            // maina atmiņu
            array_push($this->memory, $i);
            // izveido izmainīto apkārtni
            $this->subNeighborhoodFunction($i);
            if ($this->subNeighborhood == []) {
                $empty = 1;
            }
        }
        foreach($best as $b) {
            echo $b;
        }
    }

    // Funkcija, kas izsauc apkārtnes izveides funkciju un 
    // vēlāk izveido izmainīto apkārtni
    public function subNeighborhoodFunction($instances) {
        $this->neighborhoodFunction($instances);
        $this->subNeighborhood = [];
        foreach($this->neighborhood as $instance) {
            if (!in_array($instance, $this->memory)) {
                array_push($this->subNeighborhood, $instance);
            }
        }
    }

    // Funkcija, kas izveido apkārtni, izsaucot pāru maiņas funkciju
    // vai indeksa n noņemšanas vai pielikšanas funkciju
    public function neighborhoodFunction($arr) {
        $this->neighborhood = [];
        for ($i=0; $i<$this->n-1; $i++) {
            $this->changePairs($arr, $i);
        }
        $this->addOrRemoveN($arr);
    }

    // Funkcija, kas, ja iespējams, noņem vai pieliek vienu noliktavas indeksu
    public function addOrRemoveN($arr) {
        $nCount = sizeof($arr)-2-$this->n;
        if($nCount > 0) {
            $nIndexArr = [];
            for($i = 1; $i<sizeof($arr)-1; $i++) {
                if ($arr[$i] == $this->n) {
                    array_push($nIndexArr, $i);
                }
            }
            foreach($nIndexArr as $indexN) {
                $oneNeighbor = [];
                for($i = 0; $i<sizeof($arr); $i++) {
                    if ($i != $indexN) {
                        array_push($oneNeighbor, $arr[$i]);
                    }
                }
                if (in_array($oneNeighbor, $this->instances)){
                    array_push($this->neighborhood, $oneNeighbor); 
                }
            }
            
        }
        if (sizeof($arr)<2*$this->n+1) {
            $nIndexArr = [];
            for($i = 1; $i<sizeof($arr)-1; $i++) {
                if ($arr[$i] != $this->n && $arr[$i+1] != $this->n) {
                    array_push($nIndexArr, $i+1);
                }
            }
            foreach($nIndexArr as $indexN) {
                $oneNeighbor = [];
                for($i = 0; $i<sizeof($arr); $i++) {
                    if ($i == $indexN) {
                        array_push($oneNeighbor, $this->n);
                    }
                    array_push($oneNeighbor, $arr[$i]);
                }
                if (in_array($oneNeighbor, $this->instances)){
                    array_push($this->neighborhood, $oneNeighbor); 
                }
            }
        }
    }

    // Funkcija, kas samaina vietām blakus esošus indeksus, 
    // kas nav noliktavas indeksi
    public function changePairs($arr, $pairIndex) {
        $oneNeighbor = [];
        $indexOne = 0;
        $indexTwo = 0;
        $indexOneP = 0;
        $indexTwoP = 0;
        $valueOne = 0;
        $valueTwo = 0;
        for ($i = 0; $i < sizeof($arr); $i++) {
            if ($arr[$i] != $this->n && $indexOne == $pairIndex) {
                $valueOne = $arr[$i];
                $indexOneP = $i;
            } elseif ($arr[$i] != $this->n && $indexTwo == $pairIndex + 1) {
                $valueTwo = $arr[$i];
                $indexTwoP = $i;
            }
            if ($arr[$i] != $this->n) {
                $indexOne++;
                $indexTwo++;
            }
        }
        for ($i = 0; $i < sizeof($arr); $i++) {
            if ($indexOneP == $i) {
                array_push($oneNeighbor, $valueTwo);
            } elseif ($indexTwoP == $i) {
                array_push($oneNeighbor, $valueOne);
            } else {
                array_push($oneNeighbor, $arr[$i]);
            }
        }
        if (in_array($oneNeighbor, $this->instances)){
            array_push($this->neighborhood, $oneNeighbor); 
        }
    }

    // Funkcija, kas aprēķina ceļa izmaksas
    public function costFunction($arr): Int {
        $arrSize = sizeof($arr);
        $arrSum = 0;
        for ($i = 0; $i < $arrSize-1; $i++) {
            $indexFrom = $arr[$i];
            $indexTo = $arr[$i+1];
            $arrSum += $this->distanceMatrix[$indexFrom][$indexTo];
        }
        return $arrSum;
    }
}
?>