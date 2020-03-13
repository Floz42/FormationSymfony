<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
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
        $faker = Factory::create('fr-FR');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstname('Flo')
                  ->setLastname('THIEBAUD')
                  ->setEmail('flo.carreclub@gmail.com')
                  ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                  ->setPicture('https://gravatar.com/avatar/bacac828c87cb60697c13d6bec44f2a5?s=400&d=robohash&r=x')
                  ->setIntroduction($faker->sentence())
                  ->setDescription('<p>' . join('<p></p>', $faker->paragraphs(3)) . '</p>')
                  ->addUserRole($adminRole);
        $manager->persist($adminUser);


        $users = [];
        $genres = ['male', 'female'];

        for ($i=1; $i<=10; $i++) {
            $user = new User();

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';
            $hash = $this->encoder->encodePassword($user, 'password');
            $picture .= ($genre === 'male' ? 'men/' : 'women/') . $pictureId;
    
            $user->setFirstname($faker->firstName($genre))
                 ->setLastname($faker->lastName)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription('<p>' . join('<p></p>', $faker->paragraphs(3)) . '</p>')
                 ->setHash($hash)
                 ->setPicture($picture);
            $manager->persist($user);
            $users[] = $user;
        }

        for ($i = 1; $i <= 30; $i++) {
            $ad = new Ad();
            $title = $faker->sentence();

            $user = $users[mt_rand(0, count($users) -1 )]; 
            $ad->setTitle($title)
                ->setCoverImage($faker->imageUrl(1000, 350))
                ->setIntroduction($faker->paragraph(2))
                ->setContent('<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>')
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1,5))
                ->setAuthor($user);
                for ($j=1; $j <= mt_rand(2,5); $j++) {
                    $image = new Image();
                    $image->setUrl($faker->imageUrl())
                          ->setCaption($faker->sentence())
                          ->setAd($ad);
                    $manager->persist($image);
                }

        for ($j=1; $j <= mt_rand(0,10); $j++) {
            $booking = new Booking();
            $createdAt = $faker->dateTimeBetween('-6 months');
            $startDate = $faker->dateTimeBetween('-3 months');
            $duration = mt_rand(3, 10);
            $endDate = (clone $startDate)->modify("+$duration days");
            $amount = $ad->getPrice() * $duration;
            $booker = $users[mt_rand(0, count($users) -1)];
            $comment = $faker->paragraph();
            $booking->setCreatedAt($createdAt)
                    ->setStartDate($startDate)
                    ->setEndDate($endDate)
                    ->setAmount($amount)
                    ->setBooker($booker)
                    ->setAd($ad)
                    ->setComment($comment);
            $manager->persist($booking);

            if(mt_rand(0,1)) {
                $comment = new Comment();
                $comment->setContent($faker->paragraph())
                        ->setRating(mt_rand(1,5))
                        ->setAuthor($booker)
                        ->setAd($ad);
                $manager->persist($comment);                        
            }
        }
            $manager->persist($ad);
        }

        $manager->flush();
    }
}
