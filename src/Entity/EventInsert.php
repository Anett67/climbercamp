<?php

namespace App\Entity;

class EventInsert{

    private $title;

    private $description;

    private $eventDate;

    private $location;

    private $ville;

    public function getTitle(){
        return $this->title;
    }

    public function setTitle($title){
        $this->title = $title;
        return $this;
    }

    public function getDescription(){
        return $this->description;
    }

    public function setDescription($description){
        $this->description = $description;
        return $this;
    }

    public function getEventDate(){
        return $this->eventDate;
    }

    public function setEventDate($eventDate){
        $this->eventDate = $eventDate;
        return $this;
    }

    public function getLocation(){
        return $this->location;
    }

    public function setLocation($location){
        $this->location = $location;
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