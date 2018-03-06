<?php

class Pagination {
    
    private $rpp,$rowCount, $page;
    
    function __construct($rowCount, $page = 1, $rpp = 12 ) {
        $this->rowCount = $rowCount;
        $this->page = $page;
        $this->rpp = $rpp;
    }
    
    function getRpp() {
        return $this->rpp;
    }
    
    function getOffset() {
        return $this->rpp * ($this->page - 1);
    }
    
    function getLastPage() {
        return ceil($this->rowCount / $this->rpp);
    }
    
    function getFirstPage() {
        return 1;
    }
    
    function nextPage() {
        return min($this->page + 1 , $this->getLastPage() );
    }
    
    function previousPage() {
        return max($this->page - 1 , $this->getFirstPage() );
    }
    
    function setPage($page) {
        $this->page = $page;
    }
    
    function getRange($range = 3) {
        $numeros = array();
        $lastPage = $this->getLastPage();
        for ($i = $this->page - $range; $i <=  $this->page + $range; $i++) {
            
            if ($i >= 1 && $i <= $lastPage ) {
                $numeros[] = $i;
            }
            
        }
        
        return $numeros;
    }
    
    function getCurrentPage() {
        return $this->page;
    }
}