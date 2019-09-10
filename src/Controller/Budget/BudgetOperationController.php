<?php

namespace App\Controller\Budget;

use App\Entity\CostCenterHist;

use App\Form\CostCenterHistType;
use App\Form\CostCenterIncomeHistType;

use App\Repository\CostCenterHistRepository;
use App\Repository\YearRepository;
use App\Repository\CurrencyRepository;

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
use Twig\Node\Expression\Binary\ConcatBinary;
use Twig\Node\Expression\MethodCallExpression;

/**
 * Controller used to manage Budget Operations.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class BudgetOperationController extends AbstractController
{
    /**
     * Lists all Park .
     *
     *
     * @Route("{_locale}/expense", methods={"GET"}, name="expense")
     *
     */
    public function showPark(CostCenterHistRepository $costCenterHists): Response
    {
        $userCostCenterHists = $costCenterHists->findBy(['type' => "EXPENSE"], ['id' => 'DESC']);

        return $this->render('budget_operation/show_expense.html.twig', ['costCenterHists' => $userCostCenterHists]);
    }

    /**
     * Create a new Expense entity.
     *
     * @Route("{_locale}/expense/new", methods={"GET", "POST"}, name="expense_new")
     *
     */
    public function newExpense(Request $request, ObjectManager $em, YearRepository $year, CurrencyRepository $currency, UserRepository $user): Response
    {
        $costCenterHist = new CostCenterHist();

        // On Instancie le formulaire
        $form = $this->createForm(CostCenterHistType::class, $costCenterHist)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Current values : year and currency 
            $currentYear = $year->findOneBy(['is_current' => true]);
            $currentCurrency = $currency->findOneBy(['is_current' => true]);

            // Get Branch 
            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            $costCenterHist->setCurrency($currentCurrency);
            $costCenterHist->setYear($currentYear);
            $costCenterHist->setUser($this->getUser());

            $costCenterHist->setIsValid(false);
            $costCenterHist->setCreatedAt(new \DateTime());
            $costCenterHist->setType("EXPENSE");
            $costCenterHist->setStatus("BUDGETED");

            $costCenterHist->setBranch($branch);

            $em->persist($costCenterHist);
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('expense_new');
            }
            return $this->redirectToRoute('expense');
        }

        return $this->render('budget_operation/edit_expense.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Expense entity.
     *
     * @Route("{_locale}/expense/{id<\d+>}/edit", methods={"GET", "POST"}, name="expense_edit")
     *
     */
    public function editExpense(Request $request, CostCenterHistRepository $costCenterHists, CostCenterHist $costCenterHist, ObjectManager $em): Response
    {
        $form = $this->createForm(CostCenterHistType::class, $costCenterHist)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('expense_new');
            }
            return $this->redirectToRoute('expense');
        }
        $userCostCenterHists = $costCenterHists->findBy(['type' => "EXPENSE"], ['id' => 'DESC']);

        return $this->render('budget_operation/edit_expense.html.twig', [
            'costCenterHist' => $costCenterHist,
            'form' => $form->createView(),
            'costCenterHists' => $userCostCenterHists,
        ]);
    }

    /**
     * Validate an Expense entity.
     *
     * @Route("/expense/{id}/delete", methods={"GET", "POST"}, name="expense_delete")
     *
     */
    public function deleteExpense(CostCenterHist $costCenterHist, ObjectManager $em): Response
    {
        $em->remove($costCenterHist);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('expense');
    }

    /**
     * Validate an Expense entity.
     *
     * @Route("/expense/{id}/validate", methods={"GET", "POST"}, name="expense_validate")
     *
     */
    public function validateExpense(CostCenterHist $costCenterHist, ObjectManager $em): Response
    {
        $costCenterHist->setIsValid(true);
        $costCenterHist->setStatus("VALIDATED");

        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('expense');
    }

    /**
     * Un Validate an Expense entity.
     *
     * @Route("/expense/{id}/invalidate", methods={"GET", "POST"}, name="expense_invalidate")
     *
     */
    public function invalidateExpense(CostCenterHist $costCenterHist, ObjectManager $em): Response
    {
        $costCenterHist->setIsValid(false);
        $costCenterHist->setStatus("NOT VALIDATED");

        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('expense');
    }

    /**
     * Cancel validation of an Expense entity.
     *
     * @Route("/expense/{id}/cancel", methods={"GET", "POST"}, name="expense_cancel")
     *
     */
    public function cancelExpense(CostCenterHist $costCenterHist, ObjectManager $em): Response
    {
        $costCenterHist->setIsValid(false);
        $costCenterHist->setStatus("BUDGETED");

        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('expense');
    }


    /**
     * Lists all Income .
     *
     *
     * @Route("{_locale}/income", methods={"GET"}, name="income")
     *
     */
    public function showIncome(CostCenterHistRepository $costCenterHists): Response
    {
        $userCostCenterHists = $costCenterHists->findBy(['type' => "INCOME"], ['id' => 'DESC']);

        return $this->render('budget_operation/show_income.html.twig', ['costCenterHists' => $userCostCenterHists]);
    }

    /**
     * Create a new Income entity.
     *
     * @Route("{_locale}/income/new", methods={"GET", "POST"}, name="income_new")
     *
     */
    public function newIncome(Request $request, ObjectManager $em, YearRepository $year, CurrencyRepository $currency, UserRepository $user): Response
    {
        $costCenterHist = new CostCenterHist();

        // On Instancie le formulaire
        $form = $this->createForm(CostCenterIncomeHistType::class, $costCenterHist)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Current values : year and currency 
            $currentYear = $year->findOneBy(['is_current' => true]);
            $currentCurrency = $currency->findOneBy(['is_current' => true]);

            // Get Branch 
            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            $costCenterHist->setCurrency($currentCurrency);
            $costCenterHist->setYear($currentYear);
            $costCenterHist->setUser($this->getUser());

            $costCenterHist->setIsValid(false);
            $costCenterHist->setCreatedAt(new \DateTime());
            $costCenterHist->setType("INCOME");
            $costCenterHist->setStatus("BUDGETED");

            $costCenterHist->setBranch($branch);

            $em->persist($costCenterHist);
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('income_new');
            }
            return $this->redirectToRoute('income');
        }

        return $this->render('budget_operation/edit_income.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Income entity.
     *
     * @Route("{_locale}/income/{id<\d+>}/edit", methods={"GET", "POST"}, name="income_edit")
     *
     */
    public function editIncome(Request $request, CostCenterHistRepository $costCenterHists, CostCenterHist $costCenterHist, ObjectManager $em): Response
    {
        $form = $this->createForm(CostCenterIncomeHistType::class, $costCenterHist)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('income_new');
            }
            return $this->redirectToRoute('income');
        }
        $userCostCenterHists = $costCenterHists->findBy(['type' => "INCOME"], ['id' => 'DESC']);

        return $this->render('budget_operation/edit_income.html.twig', [
            'costCenterHist' => $costCenterHist,
            'form' => $form->createView(),
            'costCenterHists' => $userCostCenterHists,
        ]);
    }

    /**
     * Validate an Income entity.
     *
     * @Route("/income/{id}/delete", methods={"GET", "POST"}, name="income_delete")
     *
     */
    public function deleteIncome(CostCenterHist $costCenterHist, ObjectManager $em): Response
    {
        $em->remove($costCenterHist);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('income');
    }

    /**
     * Validate an Income entity.
     *
     * @Route("/income/{id}/validate", methods={"GET", "POST"}, name="income_validate")
     *
     */
    public function validateIncome(CostCenterHist $costCenterHist, ObjectManager $em): Response
    {
        $costCenterHist->setIsValid(true);
        $costCenterHist->setStatus("VALIDATED");

        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('income');
    }

    /**
     * Un Validate an Income entity.
     *
     * @Route("/income/{id}/invalidate", methods={"GET", "POST"}, name="income_invalidate")
     *
     */
    public function invalidateIncome(CostCenterHist $costCenterHist, ObjectManager $em): Response
    {
        $costCenterHist->setIsValid(false);
        $costCenterHist->setStatus("NOT VALIDATED");

        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('income');
    }

    /**
     * Cancel validation of an Income entity.
     *
     * @Route("/income/{id}/cancel", methods={"GET", "POST"}, name="income_cancel")
     *
     */
    public function cancelIncome(CostCenterHist $costCenterHist, ObjectManager $em): Response
    {
        $costCenterHist->setIsValid(false);
        $costCenterHist->setStatus("BUDGETED");

        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('income');
    }
}
