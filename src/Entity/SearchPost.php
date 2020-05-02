<?php

namespace App\Entity;

class SearchPost{

    private $firstName;

    private $lastName;

    private $keyword;

    public function getFirstName(){
        return $this->firstName;
    }

    public function setFirstName(string $name){

        $this->firstName = $name;
        return $this;

    }

    public function getLastName(){
        return $this->lastName;
    }

    public function setLastName(string $name){

        $this->lastName = $name;
        return $this;

    }

    public function getKeyword(){
        return $this->keyword;
    }

    public function setKeyword(string $keyword){

        $this->keyword = $keyword;
        return $this;

    }

}