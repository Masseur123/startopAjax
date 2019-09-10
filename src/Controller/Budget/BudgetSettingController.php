<?php

namespace App\Controller\Budget;

use App\Entity\CostCenter;
use App\Entity\ParamCostCenter;
use App\Entity\CostCenterVariation;

use App\Form\CostCenterExpenseType;
use App\Form\CostCenterIncomeType;
use App\Form\ParamCostCenterType;
use App\Form\CostCenterVariationType;

use App\Repository\CostCenterRepository;
use App\Repository\ParamCostCenterRepository;
use App\Repository\CostCenterVariationRepository;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Stopwatch\StopwatchEvent;
use Twig\Node\AutoEscapeNode;
use Twig_Extension_InitRuntimeInterface;

/**
 * Controller used to manage Budget Settings.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class BudgetSettingController extends AbstractController
{
    /**
     * Lists all Cost center expense .
     *
     *
     * @Route("{_locale}/cost-center-expense", methods={"GET"}, name="cost_center_expense")
     *
     */
    public function showCostCenterExpense(CostCenterRepository $costCenters): Response
    {
        $userCostCenters = $costCenters->findBy(['type' => 'EXPENSE'], ['id' => 'DESC']);

        return $this->render('budget_setting/show_cost_center_expense.html.twig', ['costCenters' => $userCostCenters]);
    }

    /**
     * Create a new Cost Center entity.
     *
     * @Route("{_locale}/cost-center-expense/new", methods={"GET", "POST"}, name="cost_center_expense_new")
     *
     */
    public function newCostCenterExpense(Request $request, ObjectManager $em): Response
    {
        $costCenter = new CostCenter();

        // On Instancie le formulaire
        $form = $this->createForm(CostCenterExpenseType::class, $costCenter)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $costCenter->setType('EXPENSE');
            $costCenter->setCreatedAt(new \DateTime());
            $costCenter->setUser($this->getUser());

            $em->persist($costCenter);
            $em->flush();

            $this->addFlash('success', 'Success!');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('cost_center_expense_new');
            }
            return $this->redirectToRoute('cost_center_expense');
        }

        return $this->render('budget_setting/edit_cost_center_expense.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Cost Center entity.
     *
     * @Route("{_locale}/cost-center-expense/{id<\d+>}/edit",methods={"GET", "POST"}, name="cost_center_expense_edit")
     *
     */
    public function editCostCenterExpense(Request $request, CostCenterRepository $costCenters, CostCenter $costCenter, ObjectManager $em): Response
    {
        $form = $this->createForm(CostCenterExpenseType::class, $costCenter)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('cost_center_expense_new');
            }
            return $this->redirectToRoute('cost_center_expense');
        }
        $userCostCenters = $costCenters->findBy(['type' => 'EXPENSE'], ['id' => 'DESC']);

        return $this->render('budget_setting/edit_cost_center_expense.html.twig', [
            'costCenter' => $costCenter,
            'form' => $form->createView(),
            'costCenters' => $userCostCenters,
        ]);
    }

    /**
     * Deletes a Cost Center entity.
     *
     * @Route("/cost-center-expense/{id}/delete", methods={"GET", "POST"}, name="cost_center_expense_delete")
     *
     */
    public function deleteCostCenterExpense(CostCenter $costCenter, ObjectManager $em): Response
    {
        $em->remove($costCenter);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('cost_center_expense');
    }

    /**
     * Lists all Park .
     *
     *
     * @Route("{_locale}/cost-center-income", methods={"GET"}, name="cost_center_income")
     *
     */
    public function showCostCenterIncome(CostCenterRepository $costCenters): Response
    {
        $userCostCenters = $costCenters->findBy(['type' => 'INCOME'], ['id' => 'DESC']);

        return $this->render('budget_setting/show_cost_center_income.html.twig', ['costCenters' => $userCostCenters]);
    }

    /**
     * Create a new Cost Center entity.
     *
     * @Route("{_locale}/cost-center-income/new", methods={"GET", "POST"}, name="cost_center_income_new")
     *
     */
    public function newCostCenterIncome(Request $request, ObjectManager $em): Response
    {
        $costCenter = new CostCenter();

        // On Instancie le formulaire
        $form = $this->createForm(CostCenterExpenseType::class, $costCenter)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $costCenter->setType('INCOME');
            $costCenter->setCreatedAt(new \DateTime());
            $costCenter->setUser($this->getUser());

            $em->persist($costCenter);
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('cost_center_income_new');
            }

            return $this->redirectToRoute('cost_center_income');
        }


        return $this->render('budget_setting/edit_cost_center_income.html.twig', [
            'costCenter' => $costCenter,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Cost Center entity.
     *
     * @Route("{_locale}/cost-center-income/{id<\d+>}/edit",methods={"GET", "POST"}, name="cost_center_income_edit")
     *
     */
    public function editCostCenterIncome(Request $request, CostCenterRepository $costCenters, CostCenter $costCenter, ObjectManager $em): Response
    {
        $form = $this->createForm(CostCenterExpenseType::class, $costCenter)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('cost_center_income_new');
            }
            return $this->redirectToRoute('cost_center_income');
        }
        $userCostCenters = $costCenters->findBy(['type' => 'INCOME'], ['id' => 'DESC']);

        return $this->render('budget_setting/edit_cost_center_income.html.twig', [
            'costCenter' => $costCenter,
            'form' => $form->createView(),
            'costCenters' => $userCostCenters,
        ]);
    }

    /**
     * Deletes a Cost Center entity.
     *
     * @Route("/cost-center-income/{id}/delete", methods={"GET", "POST"}, name="cost_center_income_delete")
     *
     */
    public function deleteCostCenterIncome(CostCenter $costCenter, ObjectManager $em): Response
    {
        $em->remove($costCenter);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('cost_center_income');
    }

    /**
     * Lists all Param Cost Center .
     *
     *
     * @Route("{_locale}/param-cost-center", methods={"GET"}, name="param_cost_center")
     *
     */
    public function showParamCostCenter(ParamCostCenterRepository $paramCostCenters): Response
    {
        $userParamCostCenters = $paramCostCenters->findBy([], ['id' => 'DESC']);

        return $this->render('budget_setting/show_param_cost_center.html.twig', ['paramCostCenters' => $userParamCostCenters,]);
    }

    /**
     * Create a new param Cost Center entity.
     *
     * @Route("{_locale}/param-cost-center-new", methods={"GET", "POST"}, name="param_cost_center_new")
     *
     */
    public function newParamCostCenter(Request $request, ObjectManager $em, UserRepository $user): Response
    {
        $paramCostCenter = new ParamCostCenter();

        // On Instancie le formulaire
        $form = $this->createForm(ParamCostCenterType::class, $paramCostCenter)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get Branch 
            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            $paramCostCenter->setCreatedAt(new \DateTime());
            $paramCostCenter->setUser($this->getUser());
            $paramCostCenter->setBranch($branch);

            $em->persist($paramCostCenter);
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('param_cost_center_new');
            }
            return $this->redirectToRoute('param_cost_center');
        }

        return $this->render('budget_setting/edit_param_cost_center.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing param Cost Center entity.
     *
     * @Route("{_locale}/param-cost-center/{id<\d+>}/edit",methods={"GET", "POST"}, name="param_cost_center_edit")
     *
     */
    public function editParamCostCenter(Request $request, ParamCostCenterRepository $paramCostCenters, ParamCostCenter $paramCostCenter, ObjectManager $em): Response
    {
        $form = $this->createForm(ParamCostCenterType::class, $paramCostCenter)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('param_cost_center_new');
            }
            return $this->redirectToRoute('param_cost_center');
        }
        $userParamCostCenters = $paramCostCenters->findBy([], ['id' => 'DESC']);

        return $this->render('budget_setting/edit_param_cost_center.html.twig', [
            'paramCostCenter' => $paramCostCenter,
            'form' => $form->createView(),
            'paramCostCenters' => $userParamCostCenters,
        ]);
    }

    /**
     * Deletes a param Cost Center entity.
     *
     * @Route("/param-cost-center/{id}/delete", methods={"GET", "POST"}, name="param_cost_center_delete")
     *
     */
    public function deleteParamCostCenter(ParamCostCenter $paramCostCenter, ObjectManager $em): Response
    {
        $em->remove($paramCostCenter);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('param_cost_center');
    }

    /**
     * Lists all Cost Center Variation .
     *
     *
     * @Route("{_locale}/cost-center-variation", methods={"GET"}, name="cost_center_variation")
     *
     */
    public function showCostCenterVariation(CostCenterVariationRepository $costCenterVariations): Response
    {
        $userCostCenterVariations = $costCenterVariations->findBy([], ['id' => 'DESC']);

        return $this->render('budget_setting/show_cost_center_variation.html.twig', ['costCenterVariations' => $userCostCenterVariations,]);
    }

    /**
     * Creates a new budget_setting cost center variation entity.
     *
     * @Route("{_locale}/cost-center-variation-new", methods={"GET", "POST"}, name="cost_center_variation_new")
     *
     */
    public function newCostCenterVariation(Request $request, ObjectManager $em, UserRepository $user): Response
    {
        $costCenterVariation = new CostCenterVariation();

        // On Instancie le formulaire
        $form = $this->createForm(CostCenterVariationType::class, $costCenterVariation)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get Branch 
            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            $costCenterVariation->setUser($this->getUser());
            $costCenterVariation->setBranch($branch);

            $em->persist($costCenterVariation);
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('cost_center_variation_new');
            }

            return $this->redirectToRoute('cost_center_variation');
        }

        return $this->render('budget_setting/edit_cost_center_variation.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing budget_setting log routing entity.
     *
     * @Route("{_locale}/cost-center-variation/{id<\d+>}/edit",methods={"GET", "POST"}, name="cost_center_variation_edit")
     *
     */
    public function editCostCenterVariation(Request $request, CostCenterVariationRepository $costCenterVariations, CostCenterVariation $costCenterVariation, ObjectManager $em): Response
    {
        $form = $this->createForm(CostCenterVariationType::class, $costCenterVariation)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('cost_center_variation_new');
            }
            return $this->redirectToRoute('cost_center_variation');
        }
        $userCostCenterVariations = $costCenterVariations->findBy([], ['id' => 'DESC']);

        return $this->render('budget_setting/edit_cost_center_variation.html.twig', [
            'costCenterVariation' => $costCenterVariation,
            'form' => $form->createView(),
            'costCenterVariations' => $userCostCenterVariations,
        ]);
    }

    /**
     * Deletes a budget_setting cost center variation entity.
     *
     * @Route("/cost-center-variation/{id}/delete", methods={"GET", "POST"}, name="cost_center_variation_delete")
     *
     */
    public function deleteCostCenterVariation(CostCenterVariation $costCenterVariation, ObjectManager $em): Response
    {
        $em->remove($costCenterVariation);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('cost_center_variation');
    }
}
