<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class StatsService {

    private $manager; 

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    
    /**
     * getUsersCount -> retourne le nombre d'utilisateurs dans le db
     *
     * @return number
     */
    public function getUsersCount()
    {
       return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }
    
    /**
     * getAdsCount -> retourne le nombre d'annonces dans le db
     *
     * @return number
     */
    public function getAdsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Ad a')->getSingleScalarResult();
    }
    
    /**
     * getCommentsCount -> retourne le nombre de commentaires dans le db
     *
     * @return number
     */
    public function getCommentsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();
    }
    
    /**
     * getBookingsCount -> retourne le nombre de réservations dans le db
     *
     * @return number
     */
    public function getBookingsCount()
    {
        return $this->manager->createQuery('SELECT count(b) FROM App\Entity\Booking b')->getSingleScalarResult();
    }
    
    
    /**
     * getAdsStats -> retourne cinq annonces
     *
     * @return array
     */
    public function getAdsStats($direction) 
    {
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstname, u.lastname, u.picture
            FROM App\Entity\Comment c 
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a
            ORDER BY note ' . $direction
        )
        ->setMaxResults(5)
        ->getResult();
    }
    
    /**
     * getStats
     *
     * @return array
     */
    public function getStats() 
    {
        $users = $this->getUsersCount();
        $ads = $this->getAdsCount();
        $bookings = $this->getBookingsCount();
        $comments = $this->getCommentsCount();

        return compact('users', 'ads', 'bookings', 'comments');
    }
    
}