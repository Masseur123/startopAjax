<?php

namespace App\Controller\Treasury;

use App\Entity\CostCenterHist;
use App\Entity\TransitHist;
use App\Entity\Ecritcpta;

use App\Form\CashExpensePaymentType;
use App\Form\BankExpensePaymentType;

use App\Repository\BankRepository;
use App\Repository\CashDeskRepository;
use App\Repository\CostCenterHistRepository;
use App\Repository\CostCenterVariationRepository;
use App\Repository\EcritcptaRepository;
use App\Repository\ParamCostCenterRepository;
use App\Repository\UserRepository;
use App\Repository\TransitHistRepository;

use Doctrine\Common\Persistence\ObjectManager;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Stopwatch\StopwatchEvent;
use Twig\Node\Expression\Binary\LessBinary;
use Twig\Node\Expression\NullCoalesceExpression;
use Twig\Node\IncludeNode;
use Twig\Sandbox\SecurityPolicy;

/**
 * Controller used to manage Treasury Paiments.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class TreasuryPaymentController extends AbstractController
{
    /**
     * List all Cash Expense Validated.
     *
     * @Route("{_locale}/cash-expense-validated", methods={"GET", "POST"}, name="cash_expense_validated")
     *
     */
    public function showCashExpenseValidated(CostCenterHistRepository $costCenterHists): Response
    {
        $userCostCenterHists = $costCenterHists->findBy(['type' => "EXPENSE", 'status' => "VALIDATED", 'cashpay' => true], ['id' => 'DESC']);
        return $this->render('treasury_payment/show_cash_expense_validated.html.twig', ['costCenterHists' => $userCostCenterHists]);
    }

    /**
     * Displays a form to visualize a validated cash Expense entity.
     *
     * @Route("{_locale}/expense/{id<\d+>}/cash", methods={"GET", "POST"}, name="expense_cash")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     *
     */
    public function cashExpenseDetails(Request $request, UserRepository $user, CashDeskRepository $cash, CostCenterHist $costCenterHist, ObjectManager $em, ParamCostCenterRepository $param, CostCenterVariationRepository $variations): Response
    {
        $form = $this->createForm(CashExpensePaymentType::class, $costCenterHist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * Global var needed for insertion 
             */
            // Get the user id
            $userId = $this->getUser();

            // Get the cost center id
            $costCenterId = $costCenterHist->getCostcenter()->getId();

            // Get the current open year
            $currentOpenYear = $costCenterHist->getYear();

            /*                                 Start Verification for accounting operations                          */

            /**
             * Check if the user is authenticated
             * And authorized
             */

            /**
             * Check the opening branch
             */
            $branch = $user->findOneBy(['id' => $userId])->getBranch();
            if (!$branch) {
                // Disable this account

                // Then redirect
                $this->addFlash('danger', 'Missing branch!');
                return $this->redirectToRoute('starTop_logout');
            }

            /**
             * Check if cost center has an account configure
             */
            if (!$costCenterHist->getCostcenter()->getAccount()) {
                $this->addFlash('warning', 'Check the account of this budget!');
                return $this->redirectToRoute('expense_cash', array('id' => $costCenterHist->getId()));
            }

            /**
             * Check about the available amount of that cost center
             * Check if it is possible to make the payment or not
             *
             */
            // Get the corresponding cost center configuration for the current opening year
            $costCenterConfiguration = $param->findOneBy(['costcenter' => $costCenterId, 'year' => $currentOpenYear]);
            $configuredAmount = $costCenterConfiguration->getAmount();
            $realizedAmount = $costCenterConfiguration->getAmountRealized();

            // Get all the corresponding cost center variation for the current opening year
            $costCenterVariations = $variations->findBy(['costcenter' => $costCenterId, 'year' => $currentOpenYear]);

            // Get the balance of all the variations
            $variationAmount = 0;
            foreach ($costCenterVariations as $costCenterVariation) {
                if ($costCenterVariation->getIsIncrease()) {
                    $variationAmount += $costCenterVariation->getAmount();
                } else {
                    $variationAmount -= $costCenterVariation->getAmount();
                }
            }

            $configuredAndVariationAmount = $configuredAmount + $variationAmount;

            // Get the available amount for the cost center payment
            $balanceCostCenter = $configuredAndVariationAmount - $realizedAmount;

            // Get the amount of the payment
            $costCenterAmount = $costCenterHist->getAmount();
            $taxAmount = $costCenterHist->getTaxamount();
            $totalAmount = $costCenterAmount + $taxAmount;

            // Check if the balance can pay or not
            if (($balanceCostCenter < $totalAmount) && ($costCenterHist->getCostcenter()->getHasControl())) {
                $this->addFlash('warning', 'The available balance of this budget is insufficient!');
                return $this->redirectToRoute('expense_cash', array('id' => $costCenterHist->getId()));
            }


            /**
             * Get the cash Desk assign to the current user
             * Check the opening status
             * Check the balance
             * Check the account which is assign
             * Check the journal which is assign
             */
            //$cashDesk = $user->findOneBy(['id' => $userId])->getCashdesk();
            $cashDesk = $cash->findOneBy(['operator' => $userId]);
            if (!$cashDesk) {
                $this->addFlash('warning', 'No cash desk assigned to you!');
                return $this->redirectToRoute('expense_cash', array('id' => $costCenterHist->getId()));
            } elseif (!(($cashDesk->getBranch()) == $branch)) {
                $this->addFlash('warning', 'This cash desk is not configured in your branch!');
                return $this->redirectToRoute('expense_cash', array('id' => $costCenterHist->getId()));
            } elseif (!$cashDesk->getIsOpen()) {
                $this->addFlash('warning', 'Your cash desk is not open!');
                return $this->redirectToRoute('expense_cash', array('id' => $costCenterHist->getId()));
            } elseif ((($cashDesk->getBalance()) < ($costCenterHist->getAmount() + $costCenterHist->getTaxamount()))) {
                $this->addFlash('warning', 'Your cash balance is insufficient!');
                return $this->redirectToRoute('expense_cash', array('id' => $costCenterHist->getId()));
            } elseif (!$cashDesk->getAccount()) {
                $this->addFlash('warning', 'Cash desk account not configured!');
                return $this->redirectToRoute('expense_cash', array('id' => $costCenterHist->getId()));
            } elseif (!$cashDesk->getJournal()) {
                $this->addFlash('warning', 'Cash desk journal not configured!');
                return $this->redirectToRoute('expense_cash', array('id' => $costCenterHist->getId()));
            } else {
                //
            }

            /**
             * Check if there is a T.V.A apply
             * Check the account assign to T.V.A
             */
            if (($costCenterHist->getTaxamount() != 0) && (!$costCenterHist->getTax()->getAccount())) {
                $this->addFlash('success', 'T.V.A account not configured!');
                return $this->redirectToRoute('expense_cash', array('id' => $costCenterHist->getId()));
            }

            /*                                 End Verification for accounting operations                            */

            /*                                 Start save accounting operations                                      */

            /**
             * Generated transaction number and data provide from form 
             *
             */
            $tr_random = $this->randomUnique(8);
            $tr_number = "TrCEX-" . $tr_random;
            $payAt = $form->get('payAt')->getData();

            /**
             * Save Cost Center : debit
             *
             */
            $ecritCpta = new Ecritcpta();

            $ecritCpta->setAccount($costCenterHist->getCostcenter()->getAccount());
            $ecritCpta->setJournal($cashDesk->getJournal());
            $ecritCpta->setCurrency($costCenterHist->getCurrency());
            $ecritCpta->setYear($costCenterHist->getYear());
            $ecritCpta->setUser($this->getUser());
            // piece 
            $ecritCpta->setReference($costCenterHist->getRefNumber());
            $ecritCpta->setTransactionNumber($tr_number);
            $ecritCpta->setDescription($costCenterHist->getDescription());
            $ecritCpta->setDebit($costCenterHist->getAmount());
            $ecritCpta->setCredit(0);
            $ecritCpta->setDoingAt($payAt);
            $ecritCpta->setIsSent(false);
            $ecritCpta->setCreatedAt(new \DateTime());
            $ecritCpta->setSourceId($costCenterHist->getId());
            $ecritCpta->setBranch($branch);
            $em->persist($ecritCpta);

            /**
             * Save T.V.A : debit
             *
             */
            if (($costCenterHist->getTaxamount() != 0) && ($costCenterHist->getTax()->getAccount())) {
                $ecritCpta = new Ecritcpta();
                $tr_random = $this->randomUnique(10);
                $reference = "TVA-" . $tr_random;

                $ecritCpta->setAccount($costCenterHist->getTax()->getAccount());
                $ecritCpta->setJournal($cashDesk->getJournal());
                $ecritCpta->setCurrency($costCenterHist->getCurrency());
                $ecritCpta->setYear($costCenterHist->getYear());
                $ecritCpta->setUser($this->getUser());
                // piece 
                $ecritCpta->setReference($reference);
                $ecritCpta->setTransactionNumber($tr_number);
                // $ecritCpta->setDescription($description);
                $ecritCpta->setDebit($costCenterHist->getTaxamount());
                $ecritCpta->setCredit(0);
                $ecritCpta->setDoingAt($payAt);
                $ecritCpta->setIsSent(false);
                $ecritCpta->setCreatedAt(new \DateTime());
                $ecritCpta->setSourceId($costCenterHist->getId());
                $ecritCpta->setBranch($branch);
                $em->persist($ecritCpta);
            }

            /**
             * Save Cash Desk : credit
             *
             */
            $ecritCpta = new Ecritcpta();
            $ref_random = $this->randomUnique(9);
            $reference = "CASH-" . $ref_random;

            $ecritCpta->setAccount($cashDesk->getAccount());
            $ecritCpta->setJournal($cashDesk->getJournal());
            $ecritCpta->setCurrency($costCenterHist->getCurrency());
            $ecritCpta->setYear($costCenterHist->getYear());
            $ecritCpta->setUser($this->getUser());
            // piece
            $ecritCpta->setReference($reference);
            $ecritCpta->setTransactionNumber($tr_number);
            // $ecritCpta->setDescription($description);
            $ecritCpta->setDebit(0);
            $ecritCpta->setCredit($costCenterHist->getAmount() + $costCenterHist->getTaxamount());
            $ecritCpta->setDoingAt($payAt);
            $ecritCpta->setIsSent(false);
            $ecritCpta->setCreatedAt(new \DateTime());
            $ecritCpta->setSourceId($costCenterHist->getId());
            $ecritCpta->setBranch($branch);
            $em->persist($ecritCpta);

            /*                                 End save accounting operations                                       */

            /*                                 Update cost center history                                           */
            $costCenterHist->setStatus("PAID");
            $costCenterHist->setCashier($cashDesk);
            $costCenterHist->setPayAt($payAt);

            /*                                 Update param cost center : amount realized                           */
            $costCenterConfiguration->setAmountRealized($costCenterConfiguration->getAmountRealized() + ($costCenterHist->getAmount() + $costCenterHist->getTaxamount()));

            /*                                 Update cashier : balance                                             */
            $cashDesk->setBalance($cashDesk->getBalance() - ($costCenterHist->getAmount() + $costCenterHist->getTaxamount()));
            $cashDesk->setDebit($cashDesk->getDebit() + ($costCenterHist->getAmount() + $costCenterHist->getTaxamount()));

            $em->flush();

            $this->addFlash('success', 'Cash Payment Complete!');

            return $this->redirectToRoute('cash_expense_validated');
        }

        return $this->render('treasury_payment/view_cash_expense.html.twig', [
            'costCenterHist' => $costCenterHist,
            'form' => $form->createView(),
        ]);
    }

    /**
     * List all Cash Expense Payment.
     *
     * @Route("{_locale}/cash-expense-payment", methods={"GET", "POST"}, name="cash_expense_payment")
     *
     */
    public function showCashExpensePayment(EcritcptaRepository $ecritCptaCashExpenses): Response
    {
        $userEcritCptaCashExpenses = $ecritCptaCashExpenses->findBy([], ['id' => 'DESC']);
        return $this->render('treasury_payment/show_cash_expense_payment.html.twig', ['ecritCptaCashExpenses' => $userEcritCptaCashExpenses]);
    }


    /* BANK EXPENSE */

    /**
     * List all Others Expenses Validated.
     *
     * @Route("{_locale}/bank-expense-validated", methods={"GET", "POST"}, name="bank_expense_validated")
     *
     */
    public function showBankExpenseValidated(CostCenterHistRepository $costCenterHists): Response
    {
        $userCostCenterHists = $costCenterHists->findBy(['type' => "EXPENSE", 'status' => "VALIDATED", 'cashpay' => false], ['id' => 'DESC']);
        return $this->render('treasury_payment/show_bank_expense_validated.html.twig', ['costCenterHists' => $userCostCenterHists]);
    }

    /**
     * Displays a form to visualize a validated other expense entity.
     *
     * @Route("{_locale}/expense/{id<\d+>}/bank", methods={"GET", "POST"}, name="expense_bank")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     *
     */
    public function bankExpenseDetails(Request $request, BankRepository $banks, UserRepository $user, CostCenterHist $costCenterHist, ObjectManager $em, ParamCostCenterRepository $param, CostCenterVariationRepository $variations): Response
    {
        $form = $this->createForm(BankExpensePaymentType::class, $costCenterHist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * Global var
             */
            // Get the user id
            $userId = $this->getUser();

            // Get the cost center id
            $costCenterId = $costCenterHist->getCostcenter()->getId();

            // Get the current open year
            $currentOpenYear = $costCenterHist->getYear();


            /*                                 Start Verification for accounting operations                          */

            /**
             * Check if the user is authenticated
             * And authorized
             *
             */

            /**
             * Check the opening branch
             */
            $branch = $user->findOneBy(['id' => $userId])->getBranch();
            if (!$branch) {
                // Disable this account

                // Then redirect
                $this->addFlash('danger', 'Missing branch!');
                return $this->redirectToRoute('starTop_login');
            }

            /**
             * Check if cost center has an account configure
             */
            if (!$costCenterHist->getCostcenter()->getAccount()) {
                $this->addFlash('warning', 'Check the account of this budget!');
                return $this->redirectToRoute('expense_bank', array('id' => $costCenterHist->getId()));
            }

            /**
             * Check about the available amount of that cost center
             * Check if it is possible to make the payment or not
             *
             */
            // Get the corresponding cost center configuration for the current opening year
            $costCenterConfiguration = $param->findOneBy(['costcenter' => $costCenterId, 'year' => $currentOpenYear]);
            $configuredAmount = $costCenterConfiguration->getAmount();
            $realizedAmount = $costCenterConfiguration->getAmountRealized();

            // Get all the corresponding cost center variation for the current opening year
            $costCenterVariations = $variations->findBy(['costcenter' => $costCenterId, 'year' => $currentOpenYear]);

            // Get the balance of all the variations
            $variationAmount = 0;
            foreach ($costCenterVariations as $costCenterVariation) {
                if ($costCenterVariation->getIsIncrease()) {
                    $variationAmount += $costCenterVariation->getAmount();
                } else {
                    $variationAmount -= $costCenterVariation->getAmount();
                }
            }

            $configuredAndVariationAmount = $configuredAmount + $variationAmount;

            // Get the available amount for payment
            $balanceCostCenter = $configuredAndVariationAmount - $realizedAmount;

            // Get the amount of the payment
            $costCenterAmount = $costCenterHist->getAmount();
            $taxAmount = $costCenterHist->getTaxamount();
            $totalAmount = $costCenterAmount + $taxAmount;

            // Check if the balance can pay or not
            if (($balanceCostCenter < $totalAmount) && ($costCenterHist->getCostcenter()->getHasControl())) {
                $this->addFlash('warning', 'The available balance of this budget is insufficient!');
                return $this->redirectToRoute('expense_bank', array('id' => $costCenterHist->getId()));
            }

            /**
             * Get the Bank choose for payment by the current user
             * Check the balance 
             * Check the account which is assign 
             * Check the journal which is assign 
             */
            $data = $form->get('bank')->getData();
            $bank = $banks->findOneBy(['id' => $data]);

            if (!$data) {
                $this->addFlash('warning', 'Missing Bank!');
                return $this->redirectToRoute('expense_bank', array('id' => $costCenterHist->getId()));
            } elseif (!(($bank->getBranch()) == $branch)) {
                $this->addFlash('warning', 'This bank is not configured in your branch!');
                return $this->redirectToRoute('expense_bank', array('id' => $costCenterHist->getId()));
            } elseif ((($bank->getBalance()) < ($costCenterHist->getAmount() + $costCenterHist->getTaxamount()))) {
                $this->addFlash('warning', 'Your bank balance is insufficient!');
                return $this->redirectToRoute('expense_bank', array('id' => $costCenterHist->getId()));
            } elseif (!$bank->getAccount()) {
                $this->addFlash('warning', 'Bank account not configured!');
                return $this->redirectToRoute('expense_bank', array('id' => $costCenterHist->getId()));
            } elseif (!$bank->getJournal()) {
                $this->addFlash('warning', 'Cash desk journal not configured!');
                return $this->redirectToRoute('expense_bank', array('id' => $costCenterHist->getId()));
            } else {
                //
            }

            /**
             * Check if there is a T.V.A apply
             * Check the account assign to T.V.A
             */
            if (($costCenterHist->getTaxamount() != 0) && (!$costCenterHist->getTax()->getAccount())) {
                $this->addFlash('success', 'T.V.A account is not configured!');
                return $this->redirectToRoute('expense_bank', array('id' => $costCenterHist->getId()));
            }

            /*                                 End Verification for accounting operations                            */

            /*                                 Start save accounting operations                                      */

            /**
             * Global var
             *
             */
            $tr_random = $this->randomUnique(8);
            $tr_number = "TrBEX-" . $tr_random;
            $payAt = $form->get('payAt')->getData();
            $paymentmethod = $form->get('paymentmethod')->getData();

            /**
             * Save Cost Center : debit
             *
             */
            $ecritCpta = new Ecritcpta();

            $ecritCpta->setAccount($costCenterHist->getCostcenter()->getAccount());
            $ecritCpta->setJournal($bank->getJournal());
            $ecritCpta->setCurrency($costCenterHist->getCurrency());
            $ecritCpta->setYear($costCenterHist->getYear());
            $ecritCpta->setUser($this->getUser());
            // piece
            $ecritCpta->setReference($costCenterHist->getRefNumber());
            $ecritCpta->setTransactionNumber($tr_number);
            $ecritCpta->setDescription($costCenterHist->getDescription());
            $ecritCpta->setDebit($costCenterHist->getAmount());
            $ecritCpta->setCredit(0);
            $ecritCpta->setDoingAt($payAt);
            $ecritCpta->setIsSent(false);
            $ecritCpta->setCreatedAt(new \DateTime());
            $ecritCpta->setSourceId($costCenterHist->getId());
            $ecritCpta->setBranch($branch);
            $em->persist($ecritCpta);

            /**
             * Save T.V.A : debit
             *
             */
            if (($costCenterHist->getTaxamount() != 0) && ($costCenterHist->getTax()->getAccount())) {
                $ecritCpta = new Ecritcpta();
                $tr_random = $this->randomUnique(10);
                $reference = "TVA-" . $tr_random;

                $ecritCpta->setAccount($costCenterHist->getTax()->getAccount());
                $ecritCpta->setJournal($bank->getJournal());
                $ecritCpta->setCurrency($costCenterHist->getCurrency());
                $ecritCpta->setYear($costCenterHist->getYear());
                $ecritCpta->setUser($this->getUser());
                // piece 
                $ecritCpta->setReference($reference);
                $ecritCpta->setTransactionNumber($tr_number);
                // $ecritCpta->setDescription($description);
                $ecritCpta->setDebit($costCenterHist->getTaxamount());
                $ecritCpta->setCredit(0);
                $ecritCpta->setDoingAt($payAt);
                $ecritCpta->setIsSent(false);
                $ecritCpta->setCreatedAt(new \DateTime());
                $ecritCpta->setSourceId($costCenterHist->getId());
                $ecritCpta->setBranch($branch);
                $em->persist($ecritCpta);
            }

            /**
             * Save Bank (account) : credit
             *
             */
            $ecritCpta = new Ecritcpta();
            $ref_random = $this->randomUnique(9);
            $reference = "BANK-" . $ref_random;

            $ecritCpta->setAccount($bank->getAccount());
            $ecritCpta->setJournal($bank->getJournal());
            $ecritCpta->setCurrency($costCenterHist->getCurrency());
            $ecritCpta->setYear($costCenterHist->getYear());
            $ecritCpta->setUser($this->getUser());
            // piece
            $ecritCpta->setReference($reference);
            $ecritCpta->setTransactionNumber($tr_number);
            // $ecritCpta->setDescription($description);
            $ecritCpta->setDebit(0);
            $ecritCpta->setCredit($costCenterHist->getAmount() + $costCenterHist->getTaxamount());
            $ecritCpta->setDoingAt($payAt);
            $ecritCpta->setIsSent(false);
            $ecritCpta->setCreatedAt(new \DateTime());
            $ecritCpta->setSourceId($costCenterHist->getId());
            $ecritCpta->setBranch($branch);
            $em->persist($ecritCpta);

            /*                                 End save accounting operations                                       */

            /*                                 Update cost center history                                           */
            $costCenterHist->setStatus("PAID");
            $costCenterHist->setBank($bank);
            $costCenterHist->setPayAt($payAt);
            $costCenterHist->setPaymentmethod($paymentmethod);

            /*                                 Update param cost center : amount realized                           */
            $costCenterConfiguration->setAmountRealized($costCenterConfiguration->getAmountRealized() + ($costCenterHist->getAmount() + $costCenterHist->getTaxamount()));

            /*                                 Update bank : balance                                             */
            $bank->setBalance($bank->getBalance() - ($costCenterHist->getAmount() + $costCenterHist->getTaxamount()));

            $em->flush();

            $this->addFlash('success', 'Bank Payment Complete!');

            return $this->redirectToRoute('bank_expense_validated');
        }

        return $this->render('treasury_payment/view_bank_expense.html.twig', [
            'costCenterHist' => $costCenterHist,
            'form' => $form->createView(),
        ]);
    }

    /**
     * List all Banks Expenses Payment.
     *
     * @Route("{_locale}/bank-expense-payment", methods={"GET", "POST"}, name="bank_expense_payment")
     *
     */
    public function showBankExpensePayment(EcritcptaRepository $ecritCptaBankExpenses): Response
    {
        $userEcritCptaBankExpenses = $ecritCptaBankExpenses->findBy([], ['id' => 'DESC']);
        return $this->render('treasury_payment/show_bank_expense_payment.html.twig', ['ecritCptaBankExpenses' => $userEcritCptaBankExpenses]);
    }

    public function randomUnique($car)
    {
        $string = "";
        $chaine = "@ABCDEFGHJKMNPRSTUVWXY123456789";
        srand((double)microtime() * 1000000);
        for ($i = 0; $i < $car; $i++) {
            $string .= $chaine[rand() % strlen($chaine)];
        }
        return $string;
    }

    /**
     * List all Transit Cash Expense Validated.
     *
     * @Route("{_locale}/transit-cash-expense", methods={"GET", "POST"}, name="transit_cash_expense")
     *
     */
    public function showTransitCashExpense(TransitHistRepository $transitHists): Response
    {
        $usertransitHists = $transitHists->findBy(['is-valid' => true, 'cashpay' => true], ['id' => 'DESC']);
        return $this->render('treasury_payment/show_transit_cash_expense.html.twig', ['transitHists' => $usertransitHists]);
    }

    /**
     * Displays a form to visualize a transit cash Expense entity.
     *
     * @Route("{_locale}/expense/{id<\d+>}/cash", methods={"GET", "POST"}, name="transit_cash_expense_details")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     *
     */
    public function transitCashExpenseDetails(Request $request, UserRepository $user, CashDeskRepository $cash, TransitHist $transitHist, ObjectManager $em): Response
    {
        $form = $this->createForm(TransitCashExpensePaymentType::class, $transitHist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * Global var needed for insertion 
             */
            // Get the user id
            $userId = $this->getUser();

            /*                                 Start Verification for accounting operations                          */

            /**
             * Check if the user is authenticated
             * And authorized
             */

            /**
             * Check the opening branch
             */
            $branch = $user->findOneBy(['id' => $userId])->getBranch();
            if (!$branch) {
                // Disable this account

                // Then redirect
                $this->addFlash('danger', 'Missing branch!');
                return $this->redirectToRoute('starTop_logout');
            }

            /**
             * Check if document has an account configure
             */
            if (!$transitHist->getDocument()->getAccount()) {
                $this->addFlash('warning', 'Check the account of this Expense Type!');
                return $this->redirectToRoute('transit_cash_expenseo_details', array('id' => $transitHist->getId()));
            }

            /**
             * Get the cash Desk assign to the current user
             * Check the opening status
             * Check the balance
             * Check the account which is assign
             * Check the journal which is assign
             */
            $cashDesk = $cash->findOneBy(['operator' => $userId]);
            if (!$cashDesk) {
                $this->addFlash('warning', 'No cash desk assigned to you!');
                return $this->redirectToRoute('transit_cash_expenseo_details', array('id' => $transitHist->getId()));
            } elseif (!(($cashDesk->getBranch()) == $branch)) {
                $this->addFlash('warning', 'This cash desk is not configured in your branch!');
                return $this->redirectToRoute('transit_cash_expenseo_details', array('id' => $transitHist->getId()));
            } elseif (!$cashDesk->getIsOpen()) {
                $this->addFlash('warning', 'Your cash desk is not open!');
                return $this->redirectToRoute('transit_cash_expenseo_details', array('id' => $transitHist->getId()));
            } elseif ((($cashDesk->getBalance()) < ($transitHist->getAmount() + $transitHist->getTaxamount()))) {
                $this->addFlash('warning', 'Your cash balance is insufficient!');
                return $this->redirectToRoute('transit_cash_expenseo_details', array('id' => $transitHist->getId()));
            } elseif (!$cashDesk->getAccount()) {
                $this->addFlash('warning', 'Cash desk account not configured!');
                return $this->redirectToRoute('transit_cash_expenseo_details', array('id' => $transitHist->getId()));
            } elseif (!$cashDesk->getJournal()) {
                $this->addFlash('warning', 'Cash desk journal not configured!');
                return $this->redirectToRoute('transit_cash_expenseo_details', array('id' => $transitHist->getId()));
            } else {
                //
            }

            /**
             * Check if there is a T.V.A apply
             * Check the account assign to T.V.A
             */
            if (($transitHist->getTaxamount() != 0) && (!$transitHist->getTax()->getAccount())) {
                $this->addFlash('success', 'T.V.A account not configured!');
                return $this->redirectToRoute('transit_cash_expenseo_details', array('id' => $transitHist->getId()));
            }

            /*                                 End Verification for accounting operations                            */

            /*                                 Start save accounting operations                                      */

            /**
             * Generated transaction number and data provide from form 
             *
             */
            $tr_random = $this->randomUnique(8);
            $tr_number = "TRANS/EXP-" . $tr_random;

            $payAt = $form->get('payAt')->getData();
            $reference = $form->get('reference')->getData();

            /**
             * Save Cost Center : debit
             *
             */
            $ecritCpta = new Ecritcpta();

            $ecritCpta->setAccount($transitHist->getDocuement()->getAccount());
            $ecritCpta->setJournal($cashDesk->getJournal());
            $ecritCpta->setCurrency($transitHist->getCurrency());
            $ecritCpta->setYear($transitHist->getYear());
            $ecritCpta->setUser($this->getUser());
            // piece 
            $ecritCpta->setReference($reference);
            $ecritCpta->setTransactionNumber($tr_number);
            $ecritCpta->setDescription($transitHist->getDescription());
            $ecritCpta->setDebit($transitHist->getAmount());
            $ecritCpta->setCredit(0);
            $ecritCpta->setDoingAt($payAt);
            $ecritCpta->setIsSent(false);
            $ecritCpta->setCreatedAt(new \DateTime());
            $ecritCpta->setSourceId($transitHist->getId());
            $ecritCpta->setBranch($branch);
            $em->persist($ecritCpta);

            /**
             * Save T.V.A : debit
             *
             */
            if (($transitHist->getTaxamount() != 0) && ($transitHist->getTax()->getAccount())) {
                $ecritCpta = new Ecritcpta();

                $tr_random = $this->randomUnique(8);

                $reference = "TVA/FROM/TRAN/EXP-" . $tr_random;

                $ecritCpta->setAccount($transitHist->getTax()->getAccount());
                $ecritCpta->setJournal($cashDesk->getJournal());
                $ecritCpta->setCurrency($transitHist->getCurrency());
                $ecritCpta->setYear($transitHist->getYear());
                $ecritCpta->setUser($this->getUser());
                // piece 
                $ecritCpta->setReference($reference);
                $ecritCpta->setTransactionNumber($tr_number);
                // $ecritCpta->setDescription($description);
                $ecritCpta->setDebit($transitHist->getTaxamount());
                $ecritCpta->setCredit(0);
                $ecritCpta->setDoingAt($payAt);
                $ecritCpta->setIsSent(false);
                $ecritCpta->setCreatedAt(new \DateTime());
                $ecritCpta->setSourceId($transitHist->getId());
                $ecritCpta->setBranch($branch);
                $em->persist($ecritCpta);
            }

            /**
             * Save Cash Desk : credit
             *
             */
            $ecritCpta = new Ecritcpta();

            $tr_random = $this->randomUnique(8);

            $reference = "CASH/FOR/TRAN/EXP-" . $tr_random;

            $ecritCpta->setAccount($cashDesk->getAccount());
            $ecritCpta->setJournal($cashDesk->getJournal());
            $ecritCpta->setCurrency($transitHist->getCurrency());
            $ecritCpta->setYear($transitHist->getYear());
            $ecritCpta->setUser($this->getUser());
            // piece
            $ecritCpta->setReference($reference);
            $ecritCpta->setTransactionNumber($tr_number);
            // $ecritCpta->setDescription($description);
            $ecritCpta->setDebit(0);
            $ecritCpta->setCredit($transitHist->getAmount() + $transitHist->getTaxamount());
            $ecritCpta->setDoingAt($payAt);
            $ecritCpta->setIsSent(false);
            $ecritCpta->setCreatedAt(new \DateTime());
            $ecritCpta->setSourceId($transitHist->getId());
            $ecritCpta->setBranch($branch);
            $em->persist($ecritCpta);

            /*                                 End save accounting operations                                       */

            /*                                 Update cashier : balance                                             */
            $cashDesk->setBalance($cashDesk->getBalance() - ($transitHist->getAmount() + $transitHist->getTaxamount()));
            $cashDesk->setDebit($cashDesk->getDebit() + ($transitHist->getAmount() + $transitHist->getTaxamount()));

            $em->flush();

            $this->addFlash('success', 'Cash Payment Complete!');

            return $this->redirectToRoute('transit_cash_expense');
        }

        return $this->render('treasury_payment/view_transit_cash_expense.html.twig', [
            'transitHist' => $transitHist,
            'form' => $form->createView(),
        ]);
    }
}
