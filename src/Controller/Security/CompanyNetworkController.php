<?php

namespace App\Controller\Security;

use App\Entity\Institution;
use App\Entity\Branch;

use App\Form\InstitutionType;
use App\Form\BranchType;

use App\Repository\InstitutionRepository;
use App\Repository\BranchRepository;

use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Twig\Node\Expression\Binary\BitwiseAndBinary;
use Twig\Util\DeprecationCollector;


/**
 * Controller used to manage company network in StarTop.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class CompanyNetworkController extends AbstractController
{
	/**
     * Lists all institution .
     *
     * @Route("{_locale}/institution", methods={"GET"}, name="institution")
     *
     */
    public function showInstitution(InstitutionRepository $institutions): Response
    {
		$userInstitutions = $institutions->findBy([], ['id' => 'DESC']);

        return $this->render('company_network/show_institution.html.twig', ['institutions' => $userInstitutions]);
    }
	
    /**
     * Creates a new Institution entity.
     *
     * @Route("{_locale}/institution/new", methods={"GET", "POST"}, name="institution_new")
     *
     */
    public function newInstitution(Request $request, ObjectManager $em): Response
    {
        $institution = new Institution();

        // On Instancie le formulaire
        $form = $this->createForm(InstitutionType::class, $institution)
				->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $institution->setCreatedAt(new \DateTime());
            $institution->setUser($this->getUser());

            $em->persist($institution);
            $em->flush();

            $this->addFlash('success', 'Success!');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('institution_new');
            }

            return $this->redirectToRoute('institution');
        }

        return $this->render('company_network/edit_institution.html.twig', [
            'form' => $form->createView(),
			'institution' => $institution,
        ]);
    }

    /**
     * Displays a form to edit an existing Institution entity.
     *
     * @Route("{_locale}/institution/{id<\d+>}/edit", methods={"GET", "POST"}, name="institution_edit")
     *
     */
    public function editInstitution(Request $request, Institution $institution, ObjectManager $em): Response
    {
        $form = $this->createForm(InstitutionType::class, $institution)
				->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('institution_new');
            }
			
            return $this->redirectToRoute('institution');
        }
		
        return $this->render('company_network/edit_institution.html.twig', [
            'institution' => $institution,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Institution entity.
     *
     * @Route("{_locale}/institution/{id}/delete", methods={"GET", "POST"}, name="institution_delete")
     *
     */
    public function deleteInstitution(Institution $institution, ObjectManager $em): Response
    {
        $em->remove($institution);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('institution');
    }


	/**
     * Lists all branches.
     *
     * @Route("{_locale}/branch", methods={"GET"}, name="branch")
     *
     */
    public function showBranch(BranchRepository $branchs): Response
    {
		$branches = $branchs->findBy([], ['id' => 'DESC']);

        return $this->render('company_network/show_branch.html.twig', ['branches' => $branches]);
    }

    /**
     * Creates a new Branch entity.
     *
     * @Route("{_locale}/branch/new", methods={"GET", "POST"}, name="branch_new")
     *
     */
    public function newBranch(Request $request, ObjectManager $em): Response
    {
        $branch = new Branch();

        // On Instancie le formulaire
        $form = $this->createForm(BranchType::class, $branch)
				->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $branch->setCreatedAt(new \DateTime());
            $branch->setUser($this->getUser());

            $em->persist($branch);
            $em->flush();

            $this->addFlash('success', 'Success!');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('branch_new');
            }
			
            return $this->redirectToRoute('branch');
        }

        return $this->render('company_network/edit_branch.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Branch entity.
     *
     * @Route("{_locale}/branch/{id<\d+>}/edit", methods={"GET", "POST"}, name="branch_edit")
     *
     */
    public function editBranch(Request $request, Branch $branch, ObjectManager $em): Response
    {
        $form = $this->createForm(BranchType::class, $branch)
				->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('branch_new');
            }
			
            return $this->redirectToRoute('branch');
        }
		
        return $this->render('company_network/edit_branch.html.twig', [
            'branch' => $branch,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Branch entity.
     *
     * @Route("{_locale}/branch/{id}/delete", methods={"GET", "POST"}, name="branch_delete")
     *
     */
    public function deleteBranch(Branch $branch, ObjectManager $em): Response
    {
        $em->remove($branch);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('branch');
    }
}
