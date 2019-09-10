<?php

namespace App\Controller;

use App\Entity\SourceOfReservation;
use App\Entity\ReservationGroup;

use App\Repository\SourceOfReservationRepository;
use App\Repository\ReservationGroupRepository;

use App\Form\SourceOfReservationType;
use App\Form\ReservationGroupType;

use Doctrine\Common\Persistence\ObjectManager;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Twig_Compiler;

/**
 * Controller used to manage reservations in WebAppSoft.
 *
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class ReservationController extends AbstractController
{
    /**
     * Lists all Source Of Reservation.
     *
     *
     * @Route("/source-of-reservation", methods={"GET"}, name="reservation_source-of-reservation")
     * 
     */
    public function showSourceOfReservation(SourceOfReservationRepository $sourceOfReservations): Response
    {
        $userSourceOfReservations = $sourceOfReservations->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('reservation/show_source-of-reservation.html.twig', ['sourceOfReservations' => $userSourceOfReservations]);
    }

    /**
     * Creates a new Source Of Reservation entity.
     *
     * @Route("/source-of-reservation/new", methods={"GET", "POST"}, name="reservation_source-of-reservation_new")
     *
     */
    public function newSourceOfReservation(Request $request, SourceOfReservation $sourceOfReservation = null, ObjectManager $em): Response
    {
        if(!$sourceOfReservation){$sourceOfReservation = new SourceOfReservation();}

        // On Instancie le formulaire 
        $form = $this->createForm(SourceOfReservationType::class, $sourceOfReservation);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See https://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister 
            $sourceOfReservation->setCreatedAt(new\DateTime());
            $sourceOfReservation->setUser($this->getUser());
            $sourceOfReservation->setIsEnabled(true);

            $em->persist($sourceOfReservation);
            $em->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'sourceofreservation.created_successfully');

            return $this->redirectToRoute('reservation_source-of-reservation');
        }

        return $this->render('reservation/edit_source-of-reservation.html.twig', [
            'sourceOfReservation' => $sourceOfReservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Source Of Reservation entity.
     *
     * @Route("/source-of-reservation/{id<\d+>}/edit",methods={"GET", "POST"}, name="reservation_source-of-reservation_edit")
     * 
     */
    public function editSourceOfReservation(Request $request, SourceOfReservation $sourceOfReservation, ObjectManager $em): Response
    {
        $form = $this->createForm(SourceOfReservationType::class, $sourceOfReservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'sourceofreservation.updated_successfully');

            return $this->redirectToRoute('reservation_source-of-reservation');
        }

        return $this->render('reservation/edit_source-of-reservation.html.twig', [
            'sourceOfReservation' => $sourceOfReservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Source Of Reservation entity.
     *
     * @Route("/source-of-reservation/{id}/delete", methods={"GET", "POST"}, name="reservation_source-of-reservation_delete")
     * 
     */
    public function deleteSourceOfReservation(Request $request, SourceOfReservation $sourceOfReservation, ObjectManager $em): Response
    {
        /*if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('reservation_sourceOfReservation');
        }*/

        // Delete the tags associated with this blog post. This is done automatically
        // by Doctrine, except for SQLite (the database used in this application)
        // because foreign key support is not enabled by default in SQLite
        // $post->getTags()->clear();

        $em->remove($sourceOfReservation);
        $em->flush();

        $this->addFlash('success', 'sourceofreservation.deleted_successfully');

        return $this->redirectToRoute('reservation_source-of-reservation');
    }

    /**
     * Lists all Group Of Reservation.
     *
     *
     * @Route("/reservation-group", methods={"GET"}, name="reservation_reservation-group")
     * 
     */
    public function showReservationGroup(ReservationGroupRepository $reservationGroups): Response
    {
        $userReservationGroups = $reservationGroups->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('reservation/show_reservation-group.html.twig', ['reservationGroups' => $userReservationGroups]);
    }

    /**
     * Creates a new Group Of Reservation entity.
     *
     * @Route("/reservation-group/new", methods={"GET", "POST"}, name="reservation_reservation-group_new")
     *
     */
    public function newReservationGroup(Request $request, ReservationGroup $reservationGroup = null, ObjectManager $em): Response
    {
        if(!$reservationGroup){$reservationGroup = new ReservationGroup();}

        // On Instancie le formulaire 
        $form = $this->createForm(ReservationGroupType::class, $reservationGroup);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See https://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister 
            $reservationGroup->setCreatedAt(new\DateTime());
            $reservationGroup->setUser($this->getUser());
            $reservationGroup->setIsEnabled(true);

            $em->persist($reservationGroup);
            $em->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'reservationgroup.created_successfully');

            return $this->redirectToRoute('reservation_reservation-group');
        }

        return $this->render('reservation/edit_reservation-group.html.twig', [
            'reservationGroup' => $reservationGroup,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Group Of Reservation entity.
     *
     * @Route("/reservation-group/{id<\d+>}/edit",methods={"GET", "POST"}, name="reservation_reservation-group_edit")
     * 
     */
    public function editReservationGroup(Request $request, ReservationGroup $reservationGroup, ObjectManager $em): Response
    {
        $form = $this->createForm(ReservationGroupType::class, $reservationGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'reservationgroup.updated_successfully');

            return $this->redirectToRoute('reservation_reservation-group');
        }

        return $this->render('reservation/edit_reservation-group.html.twig', [
            'reservationGroup' => $reservationGroup,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Group Of Reservation entity.
     *
     * @Route("/reservation-group/{id}/delete", methods={"GET", "POST"}, name="reservation_reservation-group_delete")
     * 
     */
    public function deleteReservationGroup(Request $request, ReservationGroup $reservationGroup, ObjectManager $em): Response
    {
        /*if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('reservation_reservationGroup');
        }*/

        // Delete the tags associated with this blog post. This is done automatically
        // by Doctrine, except for SQLite (the database used in this application)
        // because foreign key support is not enabled by default in SQLite
        // $post->getTags()->clear();

        $em->remove($reservationGroup);
        $em->flush();

        $this->addFlash('success', 'reservationgroup.deleted_successfully');

        return $this->redirectToRoute('reservation_reservation-group');
    }
}
