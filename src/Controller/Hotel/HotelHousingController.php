<?php

namespace App\Controller\Hotel;

use App\Entity\HousingType;
use App\Entity\Housing;

use App\Repository\HousingTypeRepository;
use App\Repository\HousingRepository;

use App\Repository\UserRepository;


use App\Form\HousingTypeType;
use App\Form\HousingfType;

use Doctrine\Common\Persistence\ObjectManager;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Stopwatch\StopwatchEvent;
use Twig\Node\Expression\ParentExpression;
use Twig\Util\TemplateDirIterator;

/**
 * Controller used to manage Housing in StarTop.
 *
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class HotelHousingController extends AbstractController
{
    /**
     * Lists all Housing Type.
     *
     * @Route("{_locale}/housing-type", methods={"GET"}, name="housing_type")
     *
     */
    public function showHousingType(HousingTypeRepository $housingtypes): Response
    {
        $userHousingTypes = $housingtypes->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('hotel_housing/show_housing_type.html.twig', ['housingtypes' => $userHousingTypes]);
    }

    /*
     * Create a new Housing type entity.
     *
     * @Route("{_locale}/housing-type", methods={"GET", "POST"}, name="housing_type_new")
     *
     */
    public function newHousingType(Request $request, ObjectManager $em, UserRepository $user): Response
    {
        $housingtype= new HousingTYpe();

        // On Instancie le formulaire
        $form = $this->createForm(HousingTypeType::class, $housingtype);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get Branch
            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            // On prépare l'objet à persister
            $housingtype->setCreatedAt(new \DateTime());
            $housingtype->setUser($this->getUser());
            $housingtype->setBranch($branch);
            $housingtype->setIsEnabled(1);

            $em->persist($housingtype);
            $em->flush();

            $this->addFlash('success', 'Success!');

            return $this->redirectToRoute('housing_type');
        }

        return $this->render('housing/edit_housing_type.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Housing Type entity.
     *
     * @Route("/housing-type/{id<\d+>}/edit", methods={"GET", "POST"}, name="housing_type_edit")
     *
     */
    public function editHousingType(Request $request, HousingType $housingtype, ObjectManager $em): Response
    {
        $form = $this->createForm(HousingTypeType::class, $housingtype);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success');

            return $this->redirectToRoute('housing_type');
        }

        return $this->render('hotel_housing/edit_housing_type.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Housing Type entity.
     *
     * @Route("/housing-type/{id}/delete", methods={"GET", "POST"}, name="housing_type_delete")
     *
     */
    public function deleteHousingType(Request $request, HousingType $housingtype, ObjectManager $em): Response
    {
        $em->remove($housingtype);
        $em->flush();

        $this->addFlash('success', 'Success');

        return $this->redirectToRoute('housing_h-type');
    }

    /**
     * Lists all Housing.
     *
     *
     * @Route("/housing", methods={"GET"}, name="housing")
     *
     */
    public function showHousing(HousingRepository $housings): Response
    {
        $userHousings = $housings->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('hotel_housing/show_housing.html.twig', ['housings' => $userHousings]);
    }

    /**
     * Creates a new Housing entity.
     *
     * @Route("/housing/new", methods={"GET", "POST"}, name="housing_new")
     *
     */
    public function newHousing(Request $request, Housing $housing = null, ObjectManager $em): Response
    {
        if(!$housing){$housing = new Housing();}

        // On Instancie le formulaire
        $form = $this->createForm(HousingfType::class, $housing)
        ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See https://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $housing->setCreatedAt(new\DateTime());
            $housing->setUser($this->getUser());
            $housing->setIsEnabled(true);

            $em->persist($housing);
            $em->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'housing.created_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('housing_new');
            }

            return $this->redirectToRoute('housing_housing');
        }

        return $this->render('hotel_housing/edit_housing.html.twig', [
            'housing' => $housing,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Housing entity.
     *
     * @Route("/housing/{id<\d+>}/edit",methods={"GET", "POST"}, name="housing_housing_edit")
     *
     */
    public function editHousing(Request $request, Housing $housing, ObjectManager $em): Response
    {
        $form = $this->createForm(HousingfType::class, $housing)
        ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'housing.updated_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('housing_new');
            }

            return $this->redirectToRoute('housing_housing');
        }

        return $this->render('hotel_housing/edit_housing.html.twig', [
            'housing' => $housing,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Housing entity.
     *
     * @Route("/housing/{id}/delete", methods={"GET", "POST"}, name="housing_housing_delete")
     *
     */
    public function deleteHousing(Request $request, Housing $housing, ObjectManager $em): Response
    {
        /*if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('setting_civility');
        }*/

        // Delete the tags associated with this blog post. This is done automatically
        // by Doctrine, except for SQLite (the database used in this application)
        // because foreign key support is not enabled by default in SQLite
        // $post->getTags()->clear();

        $em->remove($housing);
        $em->flush();

        $this->addFlash('success', 'housing.deleted_successfully');

        return $this->redirectToRoute('housing_housing');
    }
}
