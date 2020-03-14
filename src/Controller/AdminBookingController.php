<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings", name="admin_bookings_index")
     */
    public function index(BookingRepository $repo)
    {
        $bookings = $repo->findAll();
        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $bookings
        ]);
    }

    /**
     * @Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminBookingType::class, $booking, [
            'validation_groups' => ["Default"]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $booking->setAmount(0);
            $manager->persist($booking);
            $manager->flush();
            $this->addFlash('success', "La réservation a bien été modififée.");
            return $this->redirectToRoute('admin_bookings_index');
        }

        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }


    /**
     * @Route("/admin/bookig/{id}/delete", name="admin_booking_delete")
     */
    public function delete(Booking $booking, EntityManagerInterface $manager)
    {
        $manager->remove($booking); 
        $manager->flush();
        $this->addFlash('success', 'La réservation a bien été supprimée.');
        return $this->redirectToRoute('admin_bookings_index');

    }
}
