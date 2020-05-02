<?php

namespace App\Entity;

class ClubSearch{

    private $name;

    private $ville;

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
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