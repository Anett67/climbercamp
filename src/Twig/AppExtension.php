<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension{

    public function getFunctions()
    {
        return [
            new TwigFunction('interestedPersons', [$this, 'singularPluralPersons']),
            new TwigFunction('pluralize', [$this, 'singularPlural']),
            new TwigFunction('clubUsers', [$this, 'clubUsers'])
        ];
    }

    public function singularPluralPersons(int $length){

        if($length !== 1){
            return $length . ' personnes sont intéressées';
        }else{
            return $length . ' personne est intéressée';
        }
    }

    public function singularPlural(int $length, string $string){

        if($length === 1){
            return $length . ' ' . $string;
        }else{
            return $length . ' ' . $string . 's'; 
        }

    }

    public function clubUsers(int $length){

        if($length === 1){
            return $length . ' personne aime cette salle';
        }else{
            return $length . ' personnes aiment cette salle';
        }
    }

}