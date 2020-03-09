<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface as ORMEntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface as ObjectManager;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        $ads = $repo->findAll();
        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
        ]);
    }

    /**
     * @Route("/ads/new", name="ads_create")
     */
    public function create(Request $request, ObjectManager $manager)
    {
        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash('success', 'Félicitations ! Votre annonce a bien été postée');
            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

  /*       $form = $this->createFormBuilder($ad)
                     ->add('title')
                     ->add('introduction')
                     ->add('content')
                     ->add('rooms')
                     ->add('price')
                     ->add('coverImage')
                     ->add('save', SubmitType::class, [
                         'label' => 'Créer une annonce',
                         'attr' => [
                             'class' => 'btn btn-primary'
                         ]
                     ])
                     ->getForm(); */
        return $this->render("ad/new.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/ads/{slug}", name="ads_show")
     * @return Response
     */
    public function show($slug, Ad $ad)
    {
        // $ad = $repo->findOneBySlug($slug);
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }
}
