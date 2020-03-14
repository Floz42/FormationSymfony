<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="admin_comments_index")
     */
    public function show(CommentRepository $repo)
    {
        $comments = $repo->findAll();
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("admin/comment/{id}/edit", name="admin_comment_edit")
     */
    public function edit(Comment $comment, EntityManagerInterface $manager, Request $request)
    {
        $form = $this->createForm(AdminCommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();
            $this->addFlash('success', "Le commentaire a été modifié avec succès");
        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/comment/{id}/delete", name="admin_comment_delete")
     */
    public function delete(Comment $comment, EntityManagerInterface $manager)
    {
        $manager->remove($comment);
        $manager->flush();
        $this->addFlash('success', 'Le commentaire a été supprimé avec succès');

        return $this->redirectToRoute('admin_comments_index');
    }
}
