<?php

namespace App\Controller\Transit;

use App\Entity\DocumentFile;
use App\Entity\Park;
use App\Entity\Containerlength;
use App\Entity\Transit;
use App\Entity\Wood;

use App\Form\DocumentFileType;
use App\Form\ParkType;
use App\Form\ContainerlengthType;
use App\Form\TransitType;
use App\Form\WoodType;

use App\Repository\DocumentFileRepository;
use App\Repository\ParkRepository;
use App\Repository\ContainerlengthRepository;
use App\Repository\TransitRepository;
use App\Repository\WoodRepository;

use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Stopwatch\StopwatchEvent;
use Twig\Node\Expression\AssignNameExpression;
use Twig\Node\Expression\Binary\GreaterBinary;
use Twig\Node\Expression\Binary\NotInBinary;
use Twig\Sandbox\SecurityNotAllowedMethodError;
use Twig_Sandbox_SecurityNotAllowedTagError;
use Twig_TokenParser_Include;


/**
 * Controller used to manage transit setting operation.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class TransitSettingController extends AbstractController
{

     /**
     * Lists all Park .
     *
     * @Route("{_locale}/park", methods={"GET"}, name="park")
     *
     */
    public function showPark(ParkRepository $parks): Response
    {
        // $userParks = $parks->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);
		$userParks = $parks->findBy([], ['createdAt' => 'DESC']);

        return $this->render('transit_setting/show_park.html.twig', ['parks' => $userParks]);
    }

    /**
     * Creates a new Park  entity.
     *
     * @Route("{_locale}/park/new", methods={"GET", "POST"}, name="park_new")
     *
     */
    public function newPark(Request $request, Park $park = null, ObjectManager $em, UserRepository $user): Response
    {
        if(!$park){$park = new Park();}

        // On Instancie le formulaire
        $form = $this->createForm(ParkType::class, $park)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            // On prépare l'objet à persister
            $park->setCreatedAt(new\DateTime());
            $park->setUser($this->getUser());
            $park->setBranch($branch);

            $em->persist($park);
            $em->flush();

            $this->addFlash('success', 'park.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('park_new');
            }
			
            return $this->redirectToRoute('park');
        }

        return $this->render('transit_setting/edit_park.html.twig', [
            'park' => $park,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing meal  entity.
     *
     * @Route("{_locale}/park/{id<\d+>}/edit", methods={"GET", "POST"}, name="park_edit")
     *
     */
    public function editPark(Request $request, Park $park, ObjectManager $em): Response
    {
        $form = $this->createForm(ParkType::class, $park)
                ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'park.updated_successfully');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('park_new');
            }

            return $this->redirectToRoute('park');
        }

        return $this->render('transit_setting/edit_park.html.twig', [
            'park' => $park,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Park entity.
     *
     * @Route("/park/{id}/delete", methods={"GET", "POST"}, name="park_delete")
     *
     */
    public function deletePark(Park $park, ObjectManager $em): Response
    {
        $em->remove($park);
        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('park');
    }

    /**
     * Lists all length .
     *
     *
     * @Route("{_locale}/length", methods={"GET"}, name="length")
     *
     */
    public function showLength(ContainerlengthRepository $lengths): Response
    {
        $userlengths = $lengths->findBy([], ['createdAt' => 'DESC']);

        return $this->render('transit_setting/show_length.html.twig', ['lengths' => $userlengths]);
    }

    /**
     * Creates a new length  entity.
     *
     * @Route("{_locale}/length/new", methods={"GET", "POST"}, name="length_new")
     *
     */
    public function newLength(Request $request, Containerlength $length = null, ObjectManager $em, UserRepository $user): Response
    {
        if(!$length){$length = new Containerlength();}

        // On Instancie le formulaire
        $form = $this->createForm(ContainerlengthType::class, $length)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            // On prépare l'objet à persister
            $length->setCreatedAt(new\DateTime());
            $length->setUser($this->getUser());

            $em->persist($length);
            $em->flush();

            $this->addFlash('success', 'length.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('length_new');
            }

            return $this->redirectToRoute('length');
        }

        return $this->render('transit_setting/edit_length.html.twig', [
            'length' => $length,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing meal  entity.
     *
     * @Route("{_locale}/length/{id<\d+>}/edit",methods={"GET", "POST"}, name="length_edit")
     *
     */
    public function editLength(Request $request, Containerlength $length, ObjectManager $em): Response
    {
        $form = $this->createForm(ContainerlengthType::class, $length)
                     ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'length.updated_successfully');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('length_new');
            }

            return $this->redirectToRoute('length');
        }

        return $this->render('transit_setting/edit_length.html.twig', [
            'length' => $length,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a Length entity.
     *
     * @Route("/length/{id}/delete", methods={"GET", "POST"}, name="length_delete")
     *
     */
    public function deleteLength(Containerlength $length, ObjectManager $em): Response
    {
        $em->remove($length);
        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('length');
    }

     /**
     * Lists all Transit .
     *
     *
     * @Route("{_locale}/transit", methods={"GET"}, name="transit")
     *
     */
    public function showTransit(TransitRepository $transits): Response
    {
        $userTransits = $transits->findBy([], ['createdAt' => 'DESC']);

        return $this->render('transit_setting/show_transit.html.twig', ['transits' => $userTransits]);
    }

    /**
     * Creates a new transit  entity.
     *
     * @Route("{_locale}/transit/new", methods={"GET", "POST"}, name="transit_new")
     *
     */
    public function newTransit(Request $request, Transit $transit = null, ObjectManager $em, UserRepository $user): Response
    {
        if(!$transit){$transit = new Transit();}

        // On Instancie le formulaire
        $form = $this->createForm(TransitType::class, $transit)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $countryFrom = $form->get('countryfrom')->getData();
            $countryTo = $form->get('countryto')->getData();

            if ($countryFrom == $countryTo) {
                $this->addFlash('warning', 'Both country can not be the same choice!');
                return $this->redirectToRoute('transit_new');
            }

            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            // On prépare l'objet à persister
            $transit->setCreatedAt(new\DateTime());
            $transit->setUser($this->getUser());
            $transit->setBranch($branch);

            $em->persist($transit);
            $em->flush();

            $this->addFlash('success', 'transit.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('transit_new');
            }

            return $this->redirectToRoute('transit');
        }

        return $this->render('transit_setting/edit_transit.html.twig', [
            'transit' => $transit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing transit  entity.
     *
     * @Route("{_locale}/transit/{id<\d+>}/edit",methods={"GET", "POST"}, name="transit_edit")
     *
     */
    public function editTransit(Request $request, Transit $transit, ObjectManager $em): Response
    {
        $form = $this->createForm(TransitType::class, $transit)
                ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $countryFrom = $form->get('countryfrom')->getData();
            $countryTo = $form->get('countryto')->getData();

            if ($countryFrom == $countryTo) {
                $this->addFlash('warning', 'Both country can not be the same choice!');
                return $this->redirectToRoute('transit_new', array('id' => $transit->getId()));
            }

            $em->flush();

            $this->addFlash('success', 'transit.updated_successfully');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('transit_new');
            }

            return $this->redirectToRoute('transit');
        }

        return $this->render('transit_setting/edit_transit.html.twig', [
            'transit' => $transit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a Transit entity.
     *
     * @Route("{_locale}/transit/{id}/delete", methods={"GET", "POST"}, name="transit_delete")
     *
     */
    public function deleteTransit(Transit $transit, ObjectManager $em): Response
    {
        $em->remove($transit);
        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('transit');
    }

    /**
     * Lists all Document Files .
     *
     *
     * @Route("{_locale}/document-file", methods={"GET"}, name="document_file")
     *
     */
    public function showDocumentFile(DocumentFileRepository $documentFiles): Response
    {
        $userDocumentFiles = $documentFiles->findBy([], ['createdAt' => 'DESC']);

        return $this->render('transit_setting/show_document-file.html.twig', ['documentFiles' => $userDocumentFiles]);
    }

    /**
     * Creates a new Document File  entity.
     *
     * @Route("{_locale}/document-file/new", methods={"GET", "POST"}, name="document_file_new")
     *
     */
    public function newDocumentFile(Request $request, DocumentFile $documentFile = null, ObjectManager $em, UserRepository $user): Response
    {
        if(!$documentFile){$documentFile = new DocumentFile();}

        // On Instancie le formulaire
        $form = $this->createForm(DocumentFileType::class, $documentFile)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            // On prépare l'objet à persister
            $documentFile->setCreatedAt(new\DateTime());
            $documentFile->setUser($this->getUser());

            $em->persist($documentFile);
            $em->flush();

            $this->addFlash('success', 'document.file.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('document_file_new');
            }

            return $this->redirectToRoute('document_file');
        }

        return $this->render('transit_setting/edit_document-file.html.twig', [
            'documentFile' => $documentFile,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Document File  entity.
     *
     * @Route("{_locale}/document-file/{id<\d+>}/edit",methods={"GET", "POST"}, name="document_file_edit")
     *
     */
    public function editDocumentFile(Request $request, DocumentFile $documentFile, ObjectManager $em): Response
    {
        $form = $this->createForm(DocumentFileType::class, $documentFile)
                              ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'document.File.updated_successfully');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('document_file_new');
            }

            return $this->redirectToRoute('document_file');
        }

        return $this->render('transit_setting/edit_document-file.html.twig', [
            'documentFile' => $documentFile,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes document file entity.
     *
     * @Route("/document-file/{id}/delete", methods={"GET", "POST"}, name="document_file_delete")
     *
     */
    public function deleteDocumentFile(DocumentFile $documentFile, ObjectManager $em): Response
    {
        $em->remove($documentFile);
        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('document_file');
    }

    /**
     * Lists all wood .
     *
     *
     * @Route("{_locale}/wood", methods={"GET"}, name="wood")
     *
     */
    public function showWood(WoodRepository $woods): Response
    {
        $userWoods = $woods->findBy([], ['createdAt' => 'DESC']);

        return $this->render('transit_setting/show_wood.html.twig', ['woods' => $userWoods]);
    }

    /**
     * Creates a new wood  entity.
     *
     * @Route("{_locale}/wood/new", methods={"GET", "POST"}, name="wood_new")
     *
     */
    public function newWood(Request $request, Wood $wood = null, ObjectManager $em, UserRepository $user): Response
    {
        if(!$wood){$wood = new Wood();}

        // On Instancie le formulaire
        $form = $this->createForm(WoodType::class, $wood)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            // On prépare l'objet à persister
            $wood->setCreatedAt(new\DateTime());
            $wood->setUser($this->getUser());

            $em->persist($wood);
            $em->flush();

            $this->addFlash('success', 'wood.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('wood_new');
            }

            return $this->redirectToRoute('wood');
        }

        return $this->render('transit_setting/edit_wood.html.twig', [
            'wood' => $wood,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing wood  entity.
     *
     * @Route("{_locale}/wood/{id<\d+>}/edit",methods={"GET", "POST"}, name="wood_edit")
     *
     */
    public function editWood(Request $request, Wood $wood, ObjectManager $em): Response
    {
        $form = $this->createForm(WoodType::class, $wood)
                ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'wood.updated_successfully');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('wood_new');
            }

            return $this->redirectToRoute('wood');
        }

        return $this->render('transit_setting/edit_wood.html.twig', [
            'wood' => $wood,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Wood entity.
     *
     * @Route("/wood/{id}/delete", methods={"GET", "POST"}, name="wood_delete")
     *
     */
    public function deleteWood(Wood $wood, ObjectManager $em): Response
    {
        $em->remove($wood);
        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('wood');
    }
}
