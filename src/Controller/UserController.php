<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{slug}", name="user_show")
     */
    public function index(User $user)
    {
        
        return $this->render('user/index.html.twig', [
            'user' => $user
        ]);
    }
}
