<?php

namespace App\Entity;

class EventSearch{

    private $title;

    private $keyword;

    private $ville;

    public function getTitle(){
        return $this->title;
    }

    public function setTitle($title){
        $this->title = $title;
        return $this;
    }

    public function getKeyword(){
        return $this->keyword;
    }

    public function setKeyword(string $keyword){

        $this->keyword = $keyword;
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
