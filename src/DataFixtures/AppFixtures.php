<?php

namespace App\DataFixtures;


use App\Entity\Post;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\Level;
use App\Entity\Ville;
use App\Entity\Country;
use App\Entity\PostLike;
use App\Entity\EventLike;
use App\Entity\PostComment;
use App\Entity\ClimbingClub;
use App\Entity\EventComment;
use App\Entity\PostCommentLike;
use App\Entity\EventCommentLike;
use App\Entity\PostCommentReply;
use App\Entity\ClimbingCategorie;
use App\Entity\EventCommentReply;
use App\Entity\Message;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {   

        $faker = \Faker\Factory::create('fr_FR');

        $f1 = new Country();
        $f1->setNom('France');
        $manager->persist($f1);        

        // -------------------------------------- LEVEL TABLE-------------------------------------------

        $levels = [];

        $l1 = new Level();
        $l1->setLabel('5a');
        $manager->persist($l1);
        $levels[] = $l1;

        $l2 = new Level();
        $l2->setLabel('5b');
        $manager->persist($l2);
        $levels[] = $l2;
        
        $l3 = new Level();
        $l3->setLabel('5c');
        $manager->persist($l3);
        $levels[] = $l3;

        $l4 = new Level();
        $l4->setLabel('6a');
        $manager->persist($l4);
        $levels[] = $l4;

        $l5 = new Level();
        $l5->setLabel('6b');
        $manager->persist($l5);
        $levels[] = $l5;

        $l6 = new Level();
        $l6->setLabel('6c');
        $manager->persist($l6);
        $levels[] = $l6;

        $l7 = new Level();
        $l7->setLabel('7a');
        $manager->persist($l7);
        $levels[] = $l7;

        $l8 = new Level();
        $l8->setLabel('7b');
        $manager->persist($l8);
        $levels[] = $l8;

        $l9 = new Level();
        $l9->setLabel('7c');
        $manager->persist($l9);
        $levels[] = $l9;

        $l10 = new Level();
        $l10->setLabel('8a');
        $manager->persist($l10);
        $levels[] = $l10;

        $l11 = new Level();
        $l11->setLabel('8b');
        $manager->persist($l11);
        $levels[] = $l11;

        $l12 = new Level();
        $l12->setLabel('8c');
        $manager->persist($l2);
        $levels[] = $l12;

        // ----------------------------------------------------------CLIMBING CATEGORY TABLE----------------------------------------------------------

        $climbingCategories = [];

        $c1 = new ClimbingCategorie();
        $c1->setLabel('Bloc');
        $manager->persist($c1);
        $climbingCategories[] = $c1;

        $c2 = new ClimbingCategorie();
        $c2->setLabel('Voie');
        $manager->persist($c2);
        $climbingCategories[] = $c2;

        $c3 = new ClimbingCategorie();
        $c3->setLabel('Vitesse');
        $manager->persist($c3);
        $climbingCategories[] = $c3;

        //------------------------------VILLE TABLE---------------------------------------------------------

        $villes = [];

        $v1 = new Ville();
        $v1->setCountry($f1)
            ->setNom('Bordeaux');
        $manager->persist($v1);
        $villes[] = $v1;

        $v2 = new Ville();
        $v2->setCountry($f1)
            ->setNom('Toulouse');
        $manager->persist($v2);
        $villes[] = $v2;

        $v3 = new Ville();
        $v3->setCountry($f1)
            ->setNom('Nice');
        $manager->persist($v3);
        $villes[] = $v3;

        $v4 = new Ville();
        $v4->setCountry($f1)
            ->setNom('Paris');
        $manager->persist($v4);
        $villes[] = $v4;

        //------------------------------CLIMBING CLUB TABLE-------------------------------------------------------

        $clubs = [];

        $cc1 = new ClimbingClub();
        $cc1->setVille($v1)
            ->setNom('Arkose')
            ->setEmail($faker->email)
            ->setTelephone($faker->phoneNumber)
            ->setAddresse($faker->streetAddress)
            ->addClimbingCategory($c1);
        $manager->persist($cc1);
        $clubs[]=$cc1;

        $cc2 = new ClimbingClub();
        $cc2->setVille($v1)
            ->setNom('Salle de Ginko')
            ->setEmail($faker->email)
            ->setTelephone($faker->phoneNumber)
            ->setAddresse($faker->streetAddress)
            ->addClimbingCategory($c2)
            ->addClimbingCategory($c3);
        $manager->persist($cc2);
        $clubs[]=$cc2;

        $cc3 = new ClimbingClub();
        $cc3->setVille($v1)
            ->setNom('BlocOut')
            ->setEmail($faker->email)
            ->setTelephone($faker->phoneNumber)
            ->setAddresse($faker->streetAddress)
            ->addClimbingCategory($c1);
        $manager->persist($cc3);
        $clubs[]=$cc3;

        $cc4 = new ClimbingClub();
        $cc4->setVille($v1)
            ->setNom('ClimbUp')
            ->setEmail($faker->email)
            ->setTelephone($faker->phoneNumber)
            ->setAddresse($faker->streetAddress)
            ->addClimbingCategory($c2)
            ->addClimbingCategory($c1);
        $manager->persist($cc4);
        $clubs[]=$cc4;
      
        // //------------------------------USER TABLE----------------------------------------------------------
        
        $users = [];

        $usersBordeaux = [];

        for($i=1; $i<20; $i++){
            $user = new User();
            $password = $this->encoder->encodePassword($user, 'password');
            $user->setEmail($faker->email)
                ->setPassword($password)
                ->setVille($v1)
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setRoles("ROLE_USER")
                ->setLevel($faker->randomElement($levels))
                ->addClimbingCategorie($faker->randomElement($climbingCategories))
                ->addClimbingClub($faker->randomElement($clubs))
                ->setImage("default.png");
            $manager->persist($user);
            $users[] = $user;
            $usersBordeaux[] = $user;
        }

        for($i=1; $i<10; $i++){
            $user = new User();
            $password = $this->encoder->encodePassword($user, 'password');
            $user->setEmail($faker->email)
                ->setPassword($password)
                ->setVille($v2)
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setRoles("ROLE_USER")
                ->setLevel($faker->randomElement($levels))
                ->addClimbingCategorie($faker->randomElement($climbingCategories))
                ->setImage("default.png");
            $manager->persist($user);
            $users[] = $user;
        }

        for($i=1; $i<10; $i++){
            $user = new User();
            $password = $this->encoder->encodePassword($user, 'password');
            $user->setEmail($faker->email)
                ->setPassword($password)
                ->setVille($v3)
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setRoles("ROLE_USER")
                ->setLevel($faker->randomElement($levels))
                ->addClimbingCategorie($faker->randomElement($climbingCategories))
                ->setImage("default.png");
            $manager->persist($user);
            $users[] = $user;
        }

        for($i=1; $i<10; $i++){
            $user = new User();
            $password = $this->encoder->encodePassword($user, 'password');
            $user->setEmail($faker->email)
                ->setPassword($password)
                ->setVille($v4)
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setRoles("ROLE_USER")
                ->setLevel($faker->randomElement($levels))
                ->addClimbingCategorie($faker->randomElement($climbingCategories))
                ->setImage("default.png");
            $manager->persist($user);
            $users[] = $user;
        }

        //------------------------------POST TABLE----------------------------------------------------------
            
        $posts = [];

            for($i = 1; $i <=30; $i++ ){
                $post = new Post();
                $post->setImage($faker->imageUrl())
                    ->setBody($faker->text)
                    ->setPostedBy($faker->randomElement($users))
                    ->setCreatedAt(new DateTime('now'));
                $manager->persist($post);
                $posts[] = $post;
            }

        // //------------------------------POSTLIKE TABLE------------------------------------------------------
            
            for($i=1; $i <= 50; $i++){
                $postLike = new PostLike();
                $postLike->setPost($faker->randomElement($posts))
                        ->setPostedBy($faker->randomElement($users));
                $manager->persist($postLike);
            }

        // //------------------------------POSTCOMMENT TABLE---------------------------------------------------
            
            $postComments = [];

            for($i=1; $i <= 30; $i++){
                $postComment = new PostComment();
                $postComment->setPostedBy($faker->randomElement($users))
                            ->setPost($faker->randomElement($posts))
                            ->setPostedAt(new DateTime('now'))
                            ->setBody($faker->text);
                $manager->persist($postComment);
                $postComments[] = $postComment;
            }

        // //------------------------------POSTCOMMENTLIKE TABLE-----------------------------------------------
            
            for($i=1; $i <= 50; $i++){
                $postCommentLike = new PostCommentLike();
                $postCommentLike->setPostComment($faker->randomElement($postComments))
                        ->setPostedBy($faker->randomElement($users));
                $manager->persist($postCommentLike);
            }

        // //------------------------------POSTCOMMENTREPLY TABLE----------------------------------------------

            for($i=1; $i <= 30; $i++){
                $postCommentReply = new PostCommentReply();
                $postCommentReply->setPostedBy($faker->randomElement($users))
                            ->setPostComment($faker->randomElement($postComments))
                            ->setPostedAt(new DateTime('now'))
                            ->setBody($faker->text);
                $manager->persist($postCommentReply);
            }

        // //------------------------------EVENT TABLE---------------------------------------------------------
            
            $events = [];

            for($i = 1; $i <=30; $i++ ){
                $event = new Event();
                $event->setImage($faker->imageUrl())
                    ->setVille($faker->randomElement($villes))
                    ->setTitle($faker->sentence)
                    ->setDescription($faker->text)
                    ->setPostedBy($faker->randomElement($users))
                    ->setEventDate(new DateTime('now'))
                    ->setLocation($faker->streetAddress);
                $manager->persist($post);
                $events[] = $event;
            }

        //------------------------------EVENTLIKE TABLE-----------------------------------------------------
            
            for($i=1; $i <= 50; $i++){
                $eventLike = new EventLike();
                $eventLike->setEvent($faker->randomElement($events))
                        ->setPostedBy($faker->randomElement($users));
                $manager->persist($eventLike);
            }


        // //------------------------------EVENTCOMMENT TABLE--------------------------------------------------
            
            $eventComments = [];

            for($i=1; $i <= 30; $i++){
                $eventComment = new EventComment();
                $eventComment->setPostedBy($faker->randomElement($users))
                            ->setEvent($faker->randomElement($events))
                            ->setPostedAt(new DateTime('now'))
                            ->setBody($faker->text);
                $manager->persist($eventComment);
                $eventComments[] = $eventComment;
            }

            
        // //------------------------------EVENTCOMMENTLIKE TABLE----------------------------------------------
            
            for($i=1; $i <= 50; $i++){
                $eventCommentLike = new EventCommentLike();
                $eventCommentLike->setEventComment($faker->randomElement($eventComments))
                        ->setPostedBy($faker->randomElement($users));
                $manager->persist($eventCommentLike);
            }     

        // //------------------------------EVENTCOMMENTREPLY TABLE---------------------------------------------

            for($i=1; $i <= 30; $i++){
                $eventCommentReply = new EventCommentReply();
                $eventCommentReply->setPostedBy($faker->randomElement($users))
                            ->setEventComment($faker->randomElement($eventComments))
                            ->setPostedAt(new DateTime('now'))
                            ->setBody($faker->text);
                $manager->persist($eventCommentReply);
            }


        // //------------------------------MESSAGES TABLE-------------------------------------------------------
    
            for($i=1; $i <=50; $i++){
                $message = new Message();
                $message->setFromUser($faker->randomElement($users))
                        ->setToUser($faker->randomElement($users))
                        ->setBody($faker->text)
                        ->setSendDate(new DateTime('now'))
                        ->setSeen(true);
                $manager->persist($message);
            }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
