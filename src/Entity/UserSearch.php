<?php

namespace App\Entity;

class UserSearch{

    private $firstName;

    private $lastName;

    private $ville;


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

    public function getVille(){
        return $this->ville;
    }

    public function setVille($ville){
        $this->ville = $ville;
        return $this;
    }

}