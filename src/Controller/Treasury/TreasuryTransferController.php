<?php

namespace App\Controller\Treasury;

use App\Entity\InterCashTransfer;
use App\Entity\Ecritcpta;

use App\Form\BankToBankType;
use App\Form\BankToCashType;
use App\Form\CashToBankType;
use App\Form\CashToCashType;

use App\Repository\InterCashTransferRepository;
use App\Repository\UserRepository;
use App\Repository\CashDeskRepository;
use App\Repository\BankRepository;
use App\Repository\YearRepository;
use App\Repository\CurrencyRepository;

use Doctrine\Common\Persistence\ObjectManager;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Stopwatch\StopwatchEvent;
use Twig\Node\Expression\Binary\ConcatBinary;
use Twig\Node\Expression\Binary\LessBinary;
use Twig\Node\Expression\MethodCallExpression;
use Twig\Node\Expression\NullCoalesceExpression;

/**
 * Controller used to manage treasury transfer.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class TreasuryTransferController extends AbstractController
{
    /**
     * Creates a new Cash to Cash entity.
     *
     * @Route("{_locale}/cash-to-cash", methods={"GET", "POST"}, name="cash_to_cash")
     *
     */
    public function newCashToCash(Request $request, CashDeskRepository $cashDesks, UserRepository $user, InterCashTransferRepository $cashtocashs, ObjectManager $em): Response
    {
        $cashToCash = new InterCashTransfer();

        // On Instancie le formulaire
        $form = $this->createForm(CashToCashType::class, $cashToCash);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // --> Check if source equal to destination 
            $cashSrc = $form->get('cashdeskSrc')->getData();
            $cashdeskSrc = $cashDesks->findOneBy(['id' => $cashSrc]);

            $cashDes = $form->get('cashdeskDes')->getData();
            $cashdeskDes = $cashDesks->findOneBy(['id' => $cashDes]);

            if ($cashdeskSrc == $cashdeskDes) {
                $this->addFlash('warning', 'Cashiers can not be the same choice!');
                return $this->redirectToRoute('cash_to_cash');
            }

            // SUM of potentiel transfer payment 
            $sumAwaitingTransfer = $cashtocashs->totalAmountForAwaitingTransferOfCashdesk($cashdeskSrc);

            // --> Check available balance of cashier source
            $cashdeskSrcAvailableBalance = $cashdeskSrc->getBalance() - $sumAwaitingTransfer;

            $amount = $form->get('amount')->getData();

            if ($cashdeskSrcAvailableBalance < $amount) {
                $this->addFlash('warning', 'The available balance ' . $cashdeskSrcAvailableBalance . ' is less than ' . $amount . ' needed!');
                return $this->redirectToRoute('cash_to_cash');
            }

            //var_dump($cashdeskSrcAvailableBalance);
            //exit();

            // Get Branch 
            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            // On prépare l'objet à persister
            $cashToCash->setCreatedAt(new \DateTime());
            $cashToCash->setUser($this->getUser());
            $cashToCash->setBranch($branch);
            $cashToCash->setType('cashTOcash');
            $cashToCash->setIsValid(false);

            $em->persist($cashToCash);
            $em->flush();

            $this->addFlash('success', 'Success!');

            return $this->redirectToRoute('cash_to_cash');
        }
        $userCashToCashs = $cashtocashs->findBy(['type' => 'cashTOcash'], ['id' => 'DESC']);

        return $this->render('treasury_transfer/edit_cash_to_cash.html.twig', [
            'form' => $form->createView(),
            'cashToCashs' => $userCashToCashs,
        ]);
    }

    /**
     * Displays a form to edit an existing Cash To Cash entity.
     *
     * @Route("{_locale}/cash-to-cash/{id<\d+>}/edit", methods={"GET", "POST"}, name="cash_to_cash_edit")
     *
     */
    public function editCashToCash(Request $request, CashDeskRepository $cashDesks, InterCashTransferRepository $cashToCashs, InterCashTransfer $cashToCash, ObjectManager $em): Response
    {
        $form = $this->createForm(CashToCashType::class, $cashToCash);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // --> Check if source equal to destination
            $cashSrc = $form->get('cashdeskSrc')->getData();
            $cashdeskSrc = $cashDesks->findOneBy(['id' => $cashSrc]);

            $cashDes = $form->get('cashdeskDes')->getData();
            $cashdeskDes = $cashDesks->findOneBy(['id' => $cashDes]);

            if ($cashdeskSrc == $cashdeskDes) {
                $this->addFlash('warning', 'Cashiers can not be the same choice!');
                return $this->redirectToRoute('cash_to_cash_edit', array('id' => $cashToCash->getId()));
            }

            // SUM of potentiel transfer payment 
            $sumAwaitingTransfer = $cashToCashs->totalAmountForAwaitingTransferOfCashdesk($cashdeskSrc);

            $amount = $form->get('amount')->getData();

            $sumAwaitingTransfer = $sumAwaitingTransfer - $amount;

            // --> Check available balance of cashier source
            $cashdeskSrcAvailableBalance = $cashdeskSrc->getBalance() - $sumAwaitingTransfer;

            /*var_dump($sumAwaitingTransfer);
            exit();*/

            if ($cashdeskSrcAvailableBalance < $amount) {
                $this->addFlash('warning', 'The available balance ' . $cashdeskSrcAvailableBalance . ' is less than ' . $amount . ' needed!');
                return $this->redirectToRoute('cash_to_cash_edit', array('id' => $cashToCash->getId()));
            }

            $em->flush();

            $this->addFlash('success', 'Success!');
            return $this->redirectToRoute('cash_to_cash');
        }
        $userCashToCashs = $cashToCashs->findBy(['type' => 'cashTOcash'], ['id' => 'DESC']);

        return $this->render('treasury_transfer/edit_cash_to_cash.html.twig', [
            'cashToCash' => $cashToCash,
            'form' => $form->createView(),
            'cashToCashs' => $userCashToCashs,
        ]);
    }

    /**
     * Deletes a Cash To Cash entity.
     *
     * @Route("/cash-to-cash/{id}/delete", methods={"GET", "POST"}, name="cash_to_cash_delete")
     *
     */
    public function deleteCashToCash(InterCashTransfer $cashToCash, ObjectManager $em): Response
    {
        $em->remove($cashToCash);

        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('cash_to_cash');
    }

    /**
     * Validate an Cash To Cash entity.
     *
     * @Route("/cash-to-cash/{id}/validate", methods={"GET", "POST"}, name="cash_to_cash_validate")
     *
     */
    public function validateCashToCash(InterCashTransfer $cashToCash, ObjectManager $em, InterCashTransferRepository $cashToCashs, YearRepository $years, CurrencyRepository $currencies): Response
    {
        // Check if the current user is the operator of source cashier ?
        if ($cashToCash->getCashdeskSrc()->getOperator() != $this->getUser()) {
            $this->addFlash('warning', 'Only the operator of source cashier can validate this transfer!');
            return $this->redirectToRoute('cash_to_cash');
        }

        // Check if his cashier is open 
        if ($cashToCash->getCashdeskSrc()->getIsOpen() == 0) {
            $this->addFlash('warning', 'Your cashier must be open before validation!');
            return $this->redirectToRoute('cash_to_cash');
        }

        /**
         * Check cashdesk source balance again
         */

        // SUM of potentiel transfer payment 
        $sumAwaitingTransfer = $cashToCashs->totalAmountForAwaitingTransferOfCashdesk($cashToCash->getCashdeskSrc());

        $amount = $cashToCash->getAmount();

        $sumAwaitingTransfer = $sumAwaitingTransfer - $amount;

        // --> Check available balance of cashier source
        $cashdeskSrcAvailableBalance = $cashToCash->getCashdeskSrc()->getBalance() - $sumAwaitingTransfer;

        /*var_dump($cashdeskSrcAvailableBalance);
        exit();*/

        if ($cashdeskSrcAvailableBalance < $amount) {
            $this->addFlash('warning', 'The available balance ' . $cashdeskSrcAvailableBalance . ' is less than ' . $amount . ' needed!');
            return $this->redirectToRoute('cash_to_cash');
        }

        /**
         * Accounting operations
         * 
         * debit of cashdesk source account 
         * credit of cashdesk destination account 
         */

        // Get the current open year
        $year = $years->findOneBy(['is_open' => true, 'is_current' => true]);
        $currency = $currencies->findOneBy(['is_current' => true]);

        /**
         * Check if cashier have their accounts configure
         */
        if (!$cashToCash->getCashdeskSrc()->getAccount()) {
            $this->addFlash('warning', 'Check account of source cashier!');
            return $this->redirectToRoute('cash_to_cash');
        }
        if (!$cashToCash->getCashdeskDes()->getAccount()) {
            $this->addFlash('warning', 'Check account of destination cashier!');
            return $this->redirectToRoute('cash_to_cash');
        }

        /**
         * Check if cashier have their journals configure
         */
        if (!$cashToCash->getCashdeskSrc()->getJournal()) {
            $this->addFlash('warning', 'Check the source cashier journal!');
            return $this->redirectToRoute('cash_to_cash');
        }
        if (!$cashToCash->getCashdeskDes()->getJournal()) {
            $this->addFlash('warning', 'Check the destination cashier journal!');
            return $this->redirectToRoute('cash_to_cash');
        }

        /**
         * Generate : 
         * Transaction number
         */
        if ($cashToCash->getReference() == "") {
            $tr_random = $this->randomUnique(8);
            $tr_number = "TrCTC-" . $tr_random;
        } else {
            $tr_number = $cashToCash->getReference();
        }

        /**
         * Save cashdesk source : debit
         */
        $ecritCpta = new Ecritcpta();

        $refs_random = $this->randomUnique(7);
        $refs_number = "CashSr-" . $refs_random;

        $ecritCpta->setAccount($cashToCash->getCashdeskSrc()->getAccount());
        $ecritCpta->setJournal($cashToCash->getCashdeskSrc()->getJournal());
        $ecritCpta->setCurrency($currency);
        $ecritCpta->setYear($year);
        $ecritCpta->setUser($this->getUser());
        // piece 
        $ecritCpta->setReference($refs_number);
        $ecritCpta->setTransactionNumber($tr_number);
        $ecritCpta->setDescription($cashToCash->getDescription());
        $ecritCpta->setDebit($cashToCash->getAmount());
        $ecritCpta->setCredit(0);
        $ecritCpta->setDoingAt(new \DateTime());
        $ecritCpta->setIsSent(false);
        $ecritCpta->setCreatedAt(new \DateTime());
        $ecritCpta->setSourceId($cashToCash->getId());
        $ecritCpta->setBranch($cashToCash->getBranch());
        $em->persist($ecritCpta);

        /**
         * Save cashdesk destination : credit
         *
         */
        $ecritCpta = new Ecritcpta();

        $refd_random = $this->randomUnique(6);
        $refd_number = "CashDes-" . $refd_random;

        $ecritCpta->setAccount($cashToCash->getCashdeskDes()->getAccount());
        $ecritCpta->setJournal($cashToCash->getCashdeskDes()->getJournal());
        $ecritCpta->setCurrency($currency);
        $ecritCpta->setYear($year);
        $ecritCpta->setUser($this->getUser());
        // piece 
        $ecritCpta->setReference($refd_number);
        $ecritCpta->setTransactionNumber($tr_number);
        $ecritCpta->setDescription($cashToCash->getDescription());
        $ecritCpta->setDebit(0);
        $ecritCpta->setCredit($cashToCash->getAmount());
        $ecritCpta->setDoingAt(new \DateTime());
        $ecritCpta->setIsSent(false);
        $ecritCpta->setCreatedAt(new \DateTime());
        $ecritCpta->setSourceId($cashToCash->getId());
        $ecritCpta->setBranch($cashToCash->getBranch());
        $em->persist($ecritCpta);
        /*                                 End save accounting operations                                       */

        /**
         * Update cashdesk balance, debit and credit 
         */

        // Update cashdesk source balance and debit 
        $cashToCash->getCashdeskSrc()->setBalance($cashToCash->getCashdeskSrc()->getBalance() + $cashToCash->getAmount());
        $cashToCash->getCashdeskSrc()->setCredit($cashToCash->getCashdeskSrc()->getCredit() + $cashToCash->getAmount());

        // Update cashdesk destination balance and credit 
        $cashToCash->getCashdeskDes()->setBalance($cashToCash->getCashdeskDes()->getBalance() - $cashToCash->getAmount());
        $cashToCash->getCashdeskDes()->setDebit($cashToCash->getCashdeskDes()->getDebit() + $cashToCash->getAmount());


        $cashToCash->setIsValid(true);

        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('cash_to_cash');
    }

    /**
     * Cancel validation of an Income entity.
     *
     * @Route("/cash-to-cash/{id}/cancel", methods={"GET", "POST"}, name="cash_to_cash_cancel")
     *
     */
    public function cancelCashToCash(InterCashTransfer $cashToCash, ObjectManager $em, YearRepository $years, CurrencyRepository $currencies): Response
    {
        /**
         * Reverse accounting operations
         * 
         * debit of cashdesk source account --> credit 
         * credit of cashdesk destination account -- > debit  
         */
        // Get the current open year
        $year = $years->findOneBy(['is_open' => true, 'is_current' => true]);
        $currency = $currencies->findOneBy(['is_current' => true]);

        /**
         * Generate : 
         * Transaction number
         */
        if ($cashToCash->getReference() == "") {
            $tr_random = $this->randomUnique(8);
            $tr_number = "TraCTC-" . $tr_random;
        } else {
            $tr_number = $cashToCash->getReference();
        }

        /**
         * Save cashdesk source : debit --> credit
         */
        $ecritCpta = new Ecritcpta();

        $refs_random = $this->randomUnique(7);
        $refs_number = "CashSr-" . $refs_random;

        $ecritCpta->setAccount($cashToCash->getCashdeskSrc()->getAccount());
        $ecritCpta->setJournal($cashToCash->getCashdeskSrc()->getJournal());
        $ecritCpta->setCurrency($currency);
        $ecritCpta->setYear($year);
        $ecritCpta->setUser($this->getUser());
        // piece 
        $ecritCpta->setReference($refs_number);
        $ecritCpta->setTransactionNumber($tr_number);
        $ecritCpta->setDescription($cashToCash->getDescription());
        $ecritCpta->setDebit(0);
        $ecritCpta->setCredit($cashToCash->getAmount());
        $ecritCpta->setDoingAt(new \DateTime());
        $ecritCpta->setIsSent(false);
        $ecritCpta->setCreatedAt(new \DateTime());
        $ecritCpta->setSourceId($cashToCash->getId());
        $ecritCpta->setBranch($cashToCash->getBranch());
        $em->persist($ecritCpta);

        /**
         * Save cashdesk destination : credit --> debit
         *
         */
        $ecritCpta = new Ecritcpta();

        $refd_random = $this->randomUnique(6);
        $refd_number = "CashDes-" . $refd_random;

        $ecritCpta->setAccount($cashToCash->getCashdeskDes()->getAccount());
        $ecritCpta->setJournal($cashToCash->getCashdeskDes()->getJournal());
        $ecritCpta->setCurrency($currency);
        $ecritCpta->setYear($year);
        $ecritCpta->setUser($this->getUser());
        // piece 
        $ecritCpta->setReference($refd_number);
        $ecritCpta->setTransactionNumber($tr_number);
        $ecritCpta->setDescription($cashToCash->getDescription());
        $ecritCpta->setDebit($cashToCash->getAmount());
        $ecritCpta->setCredit(0);
        $ecritCpta->setDoingAt(new \DateTime());
        $ecritCpta->setIsSent(false);
        $ecritCpta->setCreatedAt(new \DateTime());
        $ecritCpta->setSourceId($cashToCash->getId());
        $ecritCpta->setBranch($cashToCash->getBranch());
        $em->persist($ecritCpta);
        /*                                 End save accounting operations                                       */

        /**
         * Reverse cashdesk balance, debit and credit 
         */

        // Reverse cashdesk source balance and debit == credit
        $cashToCash->getCashdeskSrc()->setBalance($cashToCash->getCashdeskSrc()->getBalance() - $cashToCash->getAmount());
        $cashToCash->getCashdeskSrc()->setDebit($cashToCash->getCashdeskSrc()->getDebit() + $cashToCash->getAmount());

        // Reverse cashdesk destination balance and credit == debit 
        $cashToCash->getCashdeskDes()->setBalance($cashToCash->getCashdeskDes()->getBalance() + $cashToCash->getAmount());
        $cashToCash->getCashdeskDes()->setCredit($cashToCash->getCashdeskDes()->getCredit() + $cashToCash->getAmount());


        $cashToCash->setIsValid(false);

        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('cash_to_cash');
    }

    /**
     * Creates a new Bank to Bank entity.
     *
     * @Route("{_locale}/bank-to-bank", methods={"GET", "POST"}, name="bank_to_bank")
     *
     */
    public function newBankToBank(Request $request, BankRepository $banks, UserRepository $user, InterCashTransferRepository $bankToBanks, ObjectManager $em): Response
    {
        $bankToBank = new InterCashTransfer();

        // On Instancie le formulaire
        $form = $this->createForm(BankToBankType::class, $bankToBank);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if source equal to destination 
            $bSrc = $form->get('bankSrc')->getData();
            $bankSrc = $banks->findOneBy(['id' => $bSrc]);

            $bDes = $form->get('bankDes')->getData();
            $bankDes = $banks->findOneBy(['id' => $bDes]);

            if ($bankSrc == $bankDes) {
                $this->addFlash('warning', 'Banks can not be the same choice!');
                return $this->redirectToRoute('bank_to_bank');
            }

            // SUM of potentiel transfer payment 
            $sumAwaitingTransfer = $bankToBanks->totalAmountForAwaitingTransferOfBank($bankSrc);

            // --> Check available balance of cashier source
            $bankSrcAvailableBalance = $bankSrc->getBalance() - $sumAwaitingTransfer;

            $amount = $form->get('amount')->getData();

            if ($bankSrcAvailableBalance < $amount) {
                $this->addFlash('warning', 'The available balance ' . $bankSrcAvailableBalance . ' is less than ' . $amount . ' needed!');
                return $this->redirectToRoute('bank_to_bank');
            }

            /*var_dump($bankSrcAvailableBalance);
            exit();*/

            // Get Branch 
            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            // On prépare l'objet à persister
            $bankToBank->setCreatedAt(new \DateTime());
            $bankToBank->setUser($this->getUser());
            $bankToBank->setBranch($branch);
            $bankToBank->setType('bankTObank');
            $bankToBank->setIsValid(false);

            $em->persist($bankToBank);
            $em->flush();

            $this->addFlash('success', 'Success!');
            return $this->redirectToRoute('bank_to_bank');
        }
        $userBankToBanks = $bankToBanks->findBy(['type' => 'bankTObank'], ['id' => 'DESC']);

        return $this->render('treasury_transfer/edit_bank_to_bank.html.twig', [
            'form' => $form->createView(),
            'bankToBanks' => $userBankToBanks,
        ]);
    }

    /**
     * Displays a form to edit an existing Bank To Bank entity.
     *
     * @Route("{_locale}/bank-to-bank/{id<\d+>}/edit", methods={"GET", "POST"}, name="bank_to_bank_edit")
     *
     */
    public function editBankToBank(Request $request, BankRepository $banks, InterCashTransferRepository $bankToBanks, InterCashTransfer $bankToBank, ObjectManager $em): Response
    {
        $form = $this->createForm(BankToBankType::class, $bankToBank);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if source equal to destination 
            $bSrc = $form->get('bankSrc')->getData();
            $bankSrc = $banks->findOneBy(['id' => $bSrc]);

            $bDes = $form->get('bankDes')->getData();
            $bankDes = $banks->findOneBy(['id' => $bDes]);

            if ($bankSrc == $bankDes) {
                $this->addFlash('warning', 'Banks can not be the same choice!');
                return $this->redirectToRoute('bank_to_bank_edit', array('id' => $bankToBank->getId()));
            }

            // SUM of potentiel transfer payment 
            $sumAwaitingTransfer = $bankToBanks->totalAmountForAwaitingTransferOfBank($bankSrc);

            $amount = $form->get('amount')->getData();

            $sumAwaitingTransfer = $sumAwaitingTransfer - $amount;

            // --> Check available balance of cashier source
            $bankSrcAvailableBalance = $bankSrc->getBalance() - $sumAwaitingTransfer;

            /*var_dump($bankSrcAvailableBalance);
            exit();*/

            if ($bankSrcAvailableBalance < $amount) {
                $this->addFlash('warning', 'The available balance ' . $bankSrcAvailableBalance . ' is less than ' . $amount . ' needed!');
                return $this->redirectToRoute('bank_to_bank_edit', array('id' => $bankToBank->getId()));
            }

            $em->flush();

            $this->addFlash('success', 'Success!');
            return $this->redirectToRoute('bank_to_bank');
        }
        $userBankToBanks = $bankToBanks->findBy(['type' => 'bankTObank'], ['id' => 'DESC']);

        return $this->render('treasury_transfer/edit_bank_to_bank.html.twig', [
            'bankToBank' => $bankToBank,
            'form' => $form->createView(),
            'bankToBanks' => $userBankToBanks,
        ]);
    }

    /**
     * Deletes a Bank To Bank entity.
     *
     * @Route("/bank-to-bank/{id}/delete", methods={"GET", "POST"}, name="bank_to_bank_delete")
     *
     */
    public function deleteBankToBank(InterCashTransfer $bankToBank, ObjectManager $em): Response
    {
        $em->remove($bankToBank);

        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('bank_to_bank');
    }

    /**
     * Validate an Bank To Bank entity.
     *
     * @Route("/bank-to-bank/{id}/validate", methods={"GET", "POST"}, name="bank_to_bank_validate")
     *
     */
    public function validateBankToBank(InterCashTransfer $bankToBank, ObjectManager $em, InterCashTransferRepository $bankToBanks, YearRepository $years, CurrencyRepository $currencies): Response
    {
        /**
         * Check bank source balance again
         */

        // SUM of potentiel transfer payment 
        $sumAwaitingTransfer = $bankToBanks->totalAmountForAwaitingTransferOfBank($bankToBank->getBankSrc());

        $amount = $bankToBank->getAmount();

        $sumAwaitingTransfer = $sumAwaitingTransfer - $amount;

        // --> Check available balance of bank source
        $bankSrcAvailableBalance = $bankToBank->getBankSrc()->getBalance() - $sumAwaitingTransfer;

        if ($bankSrcAvailableBalance < $amount) {
            $this->addFlash('warning', 'The available balance ' . $bankSrcAvailableBalance . ' is less than ' . $amount . ' needed!');
            return $this->redirectToRoute('bank_to_bank');
        }

        /*var_dump($bankSrcAvailableBalance);
        exit();*/

        /**
         * Accounting operations
         * 
         * debit of bank source account 
         * credit of bank destination account 
         */

        // Get the current open year
        $year = $years->findOneBy(['is_open' => true, 'is_current' => true]);
        $currency = $currencies->findOneBy(['is_current' => true]);

        /**
         * Check if banks have their accounts configure
         */
        if (!$bankToBank->getBankSrc()->getAccount()) {
            $this->addFlash('warning', 'Check account of source bank!');
            return $this->redirectToRoute('bank_to_bank');
        }
        if (!$bankToBank->getBankDes()->getAccount()) {
            $this->addFlash('warning', 'Check account of destination bank!');
            return $this->redirectToRoute('bank_to_bank');
        }

        /**
         * Check if banks have their journals configure
         */
        if (!$bankToBank->getBankSrc()->getJournal()) {
            $this->addFlash('warning', 'Check the source bank journal!');
            return $this->redirectToRoute('bank_to_bank');
        }
        if (!$bankToBank->getBankDes()->getJournal()) {
            $this->addFlash('warning', 'Check the destination bank journal!');
            return $this->redirectToRoute('bank_to_bank');
        }

        /**
         * Generate : 
         * Transaction number
         */
        if ($bankToBank->getReference() == "") {
            $tr_random = $this->randomUnique(8);
            $tr_number = "TrBTB-" . $tr_random;
        } else {
            $tr_number = $bankToBank->getReference();
        }

        /**
         * Save bank source : debit
         */
        $ecritCpta = new Ecritcpta();

        $refs_random = $this->randomUnique(7);
        $refs_number = "BankSr-" . $refs_random;

        $ecritCpta->setAccount($bankToBank->getBankSrc()->getAccount());
        $ecritCpta->setJournal($bankToBank->getBankSrc()->getJournal());
        $ecritCpta->setCurrency($currency);
        $ecritCpta->setYear($year);
        $ecritCpta->setUser($this->getUser());
        // piece 
        $ecritCpta->setReference($refs_number);
        $ecritCpta->setTransactionNumber($tr_number);
        $ecritCpta->setDescription($bankToBank->getDescription());
        $ecritCpta->setDebit($bankToBank->getAmount());
        $ecritCpta->setCredit(0);
        $ecritCpta->setDoingAt(new \DateTime());
        $ecritCpta->setIsSent(false);
        $ecritCpta->setCreatedAt(new \DateTime());
        $ecritCpta->setSourceId($bankToBank->getId());
        $ecritCpta->setBranch($bankToBank->getBranch());
        $em->persist($ecritCpta);

        /**
         * Save bank destination : credit
         *
         */
        $ecritCpta = new Ecritcpta();

        $refd_random = $this->randomUnique(6);
        $refd_number = "BankDes-" . $refd_random;

        $ecritCpta->setAccount($bankToBank->getBankDes()->getAccount());
        $ecritCpta->setJournal($bankToBank->getBankDes()->getJournal());
        $ecritCpta->setCurrency($currency);
        $ecritCpta->setYear($year);
        $ecritCpta->setUser($this->getUser());
        // piece 
        $ecritCpta->setReference($refd_number);
        $ecritCpta->setTransactionNumber($tr_number);
        $ecritCpta->setDescription($bankToBank->getDescription());
        $ecritCpta->setDebit(0);
        $ecritCpta->setCredit($bankToBank->getAmount());
        $ecritCpta->setDoingAt(new \DateTime());
        $ecritCpta->setIsSent(false);
        $ecritCpta->setCreatedAt(new \DateTime());
        $ecritCpta->setSourceId($bankToBank->getId());
        $ecritCpta->setBranch($bankToBank->getBranch());
        $em->persist($ecritCpta);
        /*                                 End save accounting operations                                       */

        /**
         * Update cashdesk balance, debit and credit 
         */

        // Update bank source balance 
        $bankToBank->getBankSrc()->setBalance($bankToBank->getBankSrc()->getBalance() + $bankToBank->getAmount());

        // Update bank destination balance 
        $bankToBank->getBankDes()->setBalance($bankToBank->getBankDes()->getBalance() - $bankToBank->getAmount());

        $bankToBank->setIsValid(true);

        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('bank_to_bank');
    }

    /**
     * Cancel validation of an Bank to bank entity.
     *
     * @Route("/bank-to-bank/{id}/cancel", methods={"GET", "POST"}, name="bank_to_bank_cancel")
     *
     */
    public function cancelBankToBank(InterCashTransfer $bankToBank, ObjectManager $em, YearRepository $years, CurrencyRepository $currencies): Response
    {
        /**
         * Reverse accounting operations
         * 
         * debit of bank source account --> credit 
         * credit of bank destination account -- > debit  
         */
        // Get the current open year
        $year = $years->findOneBy(['is_open' => true, 'is_current' => true]);
        $currency = $currencies->findOneBy(['is_current' => true]);

        /**
         * Generate : 
         * Transaction number
         */
        if ($bankToBank->getReference() == "") {
            $tr_random = $this->randomUnique(8);
            $tr_number = "TrBTB-" . $tr_random;
        } else {
            $tr_number = $bankToBank->getReference();
        }

        /**
         * Save bank source : debit --> credit
         */
        $ecritCpta = new Ecritcpta();

        $refs_random = $this->randomUnique(7);
        $refs_number = "BankSr-" . $refs_random;

        $ecritCpta->setAccount($bankToBank->getBankSrc()->getAccount());
        $ecritCpta->setJournal($bankToBank->getBankSrc()->getJournal());
        $ecritCpta->setCurrency($currency);
        $ecritCpta->setYear($year);
        $ecritCpta->setUser($this->getUser());
        // piece 
        $ecritCpta->setReference($refs_number);
        $ecritCpta->setTransactionNumber($tr_number);
        $ecritCpta->setDescription($bankToBank->getDescription());
        $ecritCpta->setDebit(0);
        $ecritCpta->setCredit($bankToBank->getAmount());
        $ecritCpta->setDoingAt(new \DateTime());
        $ecritCpta->setIsSent(false);
        $ecritCpta->setCreatedAt(new \DateTime());
        $ecritCpta->setSourceId($bankToBank->getId());
        $ecritCpta->setBranch($bankToBank->getBranch());
        $em->persist($ecritCpta);

        /**
         * Save bank destination : credit --> debit
         *
         */
        $ecritCpta = new Ecritcpta();

        $refd_random = $this->randomUnique(6);
        $refd_number = "BankDes-" . $refd_random;

        $ecritCpta->setAccount($bankToBank->getBankDes()->getAccount());
        $ecritCpta->setJournal($bankToBank->getBankDes()->getJournal());
        $ecritCpta->setCurrency($currency);
        $ecritCpta->setYear($year);
        $ecritCpta->setUser($this->getUser());
        // piece 
        $ecritCpta->setReference($refd_number);
        $ecritCpta->setTransactionNumber($tr_number);
        $ecritCpta->setDescription($bankToBank->getDescription());
        $ecritCpta->setDebit($bankToBank->getAmount());
        $ecritCpta->setCredit(0);
        $ecritCpta->setDoingAt(new \DateTime());
        $ecritCpta->setIsSent(false);
        $ecritCpta->setCreatedAt(new \DateTime());
        $ecritCpta->setSourceId($bankToBank->getId());
        $ecritCpta->setBranch($bankToBank->getBranch());
        $em->persist($ecritCpta);
        /*                                 End save accounting operations                                       */

        /**
         * Reverse banks balance
         */

        // Reverse bank source balance
        $bankToBank->getBankSrc()->setBalance($bankToBank->getBankSrc()->getBalance() - $bankToBank->getAmount());

        // Reverse bank destination balance 
        $bankToBank->getBankDes()->setBalance($bankToBank->getBankDes()->getBalance() + $bankToBank->getAmount());


        $bankToBank->setIsValid(false);

        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('bank_to_bank');
    }

    /**
     * Creates a new Bank to Bank entity.
     *
     * @Route("{_locale}/bank-to-cash", methods={"GET", "POST"}, name="bank_to_cash")
     *
     */
    public function newBankToCash(Request $request, UserRepository $user, BankRepository $banks, InterCashTransferRepository $bankToCashs, ObjectManager $em): Response
    {
        $bankToCash = new InterCashTransfer();

        // On Instancie le formulaire
        $form = $this->createForm(BankToCashType::class, $bankToCash);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if source equal to destination 
            $bSrc = $form->get('bankSrc')->getData();
            $bankSrc = $banks->findOneBy(['id' => $bSrc]);

            // SUM of potentiel transfer payment 
            $sumAwaitingTransfer = $bankToCashs->totalAmountForAwaitingTransferOfBankToCash($bankSrc);

            // --> Check available balance of bank source
            $bankSrcAvailableBalance = $bankSrc->getBalance() - $sumAwaitingTransfer;

            $amount = $form->get('amount')->getData();

            if ($bankSrcAvailableBalance < $amount) {
                $this->addFlash('warning', 'The available balance ' . $bankSrcAvailableBalance . ' is less than ' . $amount . ' needed!');
                return $this->redirectToRoute('bank_to_cash');
            }

            //var_dump($cashdeskSrcAvailableBalance);
            //exit();

            // Get Branch 
            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            // On prépare l'objet à persister
            $bankToCash->setCreatedAt(new \DateTime());
            $bankToCash->setUser($this->getUser());
            $bankToCash->setBranch($branch);
            $bankToCash->setType('bankTOcash');
            $bankToCash->setIsValid(false);

            $em->persist($bankToCash);
            $em->flush();

            $this->addFlash('success', 'Success!');
            return $this->redirectToRoute('bank_to_cash');
        }
        $userBankToCashs = $bankToCashs->findBy(['type' => 'bankTOcash'], ['id' => 'DESC']);

        return $this->render('treasury_transfer/edit_bank_to_cash.html.twig', [
            'form' => $form->createView(),
            'bankToCashs' => $userBankToCashs,
        ]);
    }

    /**
     * Displays a form to edit an existing Bank To Cash entity.
     *
     * @Route("{_locale}/bank-to-cash/{id<\d+>}/edit", methods={"GET", "POST"}, name="bank_to_cash_edit")
     *
     */
    public function editBankToCash(Request $request, BankRepository $banks, InterCashTransferRepository $bankToCashs, InterCashTransfer $bankToCash, ObjectManager $em): Response
    {
        $form = $this->createForm(BankToCashType::class, $bankToCash);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bSrc = $form->get('bankSrc')->getData();
            $bankSrc = $banks->findOneBy(['id' => $bSrc]);

            // SUM of potentiel transfer payment 
            $sumAwaitingTransfer = $bankToCashs->totalAmountForAwaitingTransferOfBankToCash($bankSrc);

            $amount = $form->get('amount')->getData();

            $sumAwaitingTransfer = $sumAwaitingTransfer - $amount;

            // --> Check available balance of bank source
            $bankSrcAvailableBalance = $bankSrc->getBalance() - $sumAwaitingTransfer;

            if ($bankSrcAvailableBalance < $amount) {
                $this->addFlash('warning', 'The available balance ' . $bankSrcAvailableBalance . ' is less than ' . $amount . ' needed!');
                return $this->redirectToRoute('bank_to_cash_edit', array('id' => $bankToCash->getId()));
            }

            //var_dump($cashdeskSrcAvailableBalance);
            //exit();

            $em->flush();

            $this->addFlash('success', 'Success!');
            return $this->redirectToRoute('bank_to_cash');
        }
        $userBankToCashs = $bankToCashs->findBy(['type' => 'bankTOcash'], ['id' => 'DESC']);

        return $this->render('treasury_transfer/edit_bank_to_cash.html.twig', [
            'bankToCash' => $bankToCash,
            'form' => $form->createView(),
            'bankToCashs' => $userBankToCashs,
        ]);
    }

    /**
     * Deletes a Bank To Bank entity.
     *
     * @Route("/bank-to-cash/{id}/delete", methods={"GET", "POST"}, name="bank_to_cash_delete")
     *
     */
    public function deleteBankToCash(InterCashTransfer $bankToCash, ObjectManager $em): Response
    {
        $em->remove($bankToCash);

        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('bank_to_cash');
    }

    /**
     * Validate an Bank To Cash entity.
     *
     * @Route("/bank-to-cash/{id}/validate", methods={"GET", "POST"}, name="bank_to_cash_validate")
     *
     */
    public function validateBankToCash(InterCashTransfer $bankToCash, ObjectManager $em, InterCashTransferRepository $bankToCashs, YearRepository $years, CurrencyRepository $currencies): Response
    {
        /**
         * Check bank source balance again
         */

        // SUM of potentiel transfer payment 
        $sumAwaitingTransfer = $bankToCashs->totalAmountForAwaitingTransferOfBankToCash($bankToCash->getBankSrc());

        $amount = $bankToCash->getAmount();

        $sumAwaitingTransfer = $sumAwaitingTransfer - $amount;

        // --> Check available balance of bank source
        $bankSrcAvailableBalance = $bankToCash->getBankSrc()->getBalance() - $sumAwaitingTransfer;

        if ($bankSrcAvailableBalance < $amount) {
            $this->addFlash('warning', 'The available balance ' . $bankSrcAvailableBalance . ' is less than ' . $amount . ' needed!');
            return $this->redirectToRoute('bank_to_cash');
        }

        /*var_dump($cashdeskSrcAvailableBalance);
        exit();*/

        /**
         * Accounting operations
         * 
         * debit of bank source account 
         * credit of cashdesk destination account 
         */

        // Get the current open year
        $year = $years->findOneBy(['is_open' => true, 'is_current' => true]);
        $currency = $currencies->findOneBy(['is_current' => true]);

        /**
         * Check if bank and cashier have their accounts configure
         */
        if (!$bankToCash->getBankSrc()->getAccount()) {
            $this->addFlash('warning', 'Check account of source bank!');
            return $this->redirectToRoute('bank_to_cash');
        }
        if (!$bankToCash->getCashdeskDes()->getAccount()) {
            $this->addFlash('warning', 'Check account of destination cashier!');
            return $this->redirectToRoute('bank_to_cash');
        }

        /**
         * Check if bank and cashier have their journals configure
         */
        if (!$bankToCash->getBankSrc()->getJournal()) {
            $this->addFlash('warning', 'Check the source bank journal!');
            return $this->redirectToRoute('bank_to_cash');
        }
        if (!$bankToCash->getCashdeskDes()->getJournal()) {
            $this->addFlash('warning', 'Check the destination cashier journal!');
            return $this->redirectToRoute('bank_to_cash');
        }

        /**
         * Generate : 
         * Transaction number
         */
        if ($bankToCash->getReference() == "") {
            $tr_random = $this->randomUnique(8);
            $tr_number = "TrBTC-" . $tr_random;
        } else {
            $tr_number = $bankToCash->getReference();
        }


        /**
         * Save bank source : debit
         */
        $ecritCpta = new Ecritcpta();

        $refs_random = $this->randomUnique(7);
        $refs_number = "BankSr-" . $refs_random;

        $ecritCpta->setAccount($bankToCash->getBankSrc()->getAccount());
        $ecritCpta->setJournal($bankToCash->getBankSrc()->getJournal());
        $ecritCpta->setCurrency($currency);
        $ecritCpta->setYear($year);
        $ecritCpta->setUser($this->getUser());
        // piece 
        $ecritCpta->setReference($refs_number);
        $ecritCpta->setTransactionNumber($tr_number);
        $ecritCpta->setDescription($bankToCash->getDescription());
        $ecritCpta->setDebit($bankToCash->getAmount());
        $ecritCpta->setCredit(0);
        $ecritCpta->setDoingAt(new \DateTime());
        $ecritCpta->setIsSent(false);
        $ecritCpta->setCreatedAt(new \DateTime());
        $ecritCpta->setSourceId($bankToCash->getId());
        $ecritCpta->setBranch($bankToCash->getBranch());
        $em->persist($ecritCpta);

        /**
         * Save cashdesk destination : credit
         *
         */
        $ecritCpta = new Ecritcpta();

        $refd_random = $this->randomUnique(6);
        $refd_number = "CashDes-" . $refd_random;

        $ecritCpta->setAccount($bankToCash->getCashdeskDes()->getAccount());
        $ecritCpta->setJournal($bankToCash->getCashdeskDes()->getJournal());
        $ecritCpta->setCurrency($currency);
        $ecritCpta->setYear($year);
        $ecritCpta->setUser($this->getUser());
        // piece 
        $ecritCpta->setReference($refd_number);
        $ecritCpta->setTransactionNumber($tr_number);
        $ecritCpta->setDescription($bankToCash->getDescription());
        $ecritCpta->setDebit(0);
        $ecritCpta->setCredit($bankToCash->getAmount());
        $ecritCpta->setDoingAt(new \DateTime());
        $ecritCpta->setIsSent(false);
        $ecritCpta->setCreatedAt(new \DateTime());
        $ecritCpta->setSourceId($bankToCash->getId());
        $ecritCpta->setBranch($bankToCash->getBranch());
        $em->persist($ecritCpta);
        /*                                 End save accounting operations                                       */

        /**
         * Update bank balance, cashdesk balance and credit 
         */

        // Update bank source balance 
        $bankToCash->getBankSrc()->setBalance($bankToCash->getBankSrc()->getBalance() + $bankToCash->getAmount());

        // Update cashdesk destination balance and credit 
        $bankToCash->getCashdeskDes()->setBalance($bankToCash->getCashdeskDes()->getBalance() - $bankToCash->getAmount());
        $bankToCash->getCashdeskDes()->setDebit($bankToCash->getCashdeskDes()->getDebit() + $bankToCash->getAmount());

        $bankToCash->setIsValid(true);

        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('bank_to_cash');
    }

    /**
     * Cancel validation of an Bank to bank entity.
     *
     * @Route("/bank-to-cash/{id}/cancel", methods={"GET", "POST"}, name="bank_to_cash_cancel")
     *
     */
    public function cancelBankToCash(InterCashTransfer $bankToCash, ObjectManager $em, YearRepository $years, CurrencyRepository $currencies): Response
    {
        /**
         * Reverse accounting operations
         * 
         * debit of bank source account --> credit 
         * credit of cashdesk destination account -- > debit  
         */
        // Get the current open year
        $year = $years->findOneBy(['is_open' => true, 'is_current' => true]);
        $currency = $currencies->findOneBy(['is_current' => true]);

        /**
         * Generate : 
         * Transaction number
         */
        if ($bankToCash->getReference() == "") {
            $tr_random = $this->randomUnique(8);
            $tr_number = "TrBTC-" . $tr_random;
        } else {
            $tr_number = $bankToCash->getReference();
        }

        /**
         * Save bank source : debit --> credit
         */
        $ecritCpta = new Ecritcpta();

        $refs_random = $this->randomUnique(7);
        $refs_number = "BankSr-" . $refs_random;

        $ecritCpta->setAccount($bankToCash->getBankSrc()->getAccount());
        $ecritCpta->setJournal($bankToCash->getBankSrc()->getJournal());
        $ecritCpta->setCurrency($currency);
        $ecritCpta->setYear($year);
        $ecritCpta->setUser($this->getUser());
        // piece 
        $ecritCpta->setReference($refs_number);
        $ecritCpta->setTransactionNumber($tr_number);
        $ecritCpta->setDescription($bankToCash->getDescription());
        $ecritCpta->setDebit(0);
        $ecritCpta->setCredit($bankToCash->getAmount());
        $ecritCpta->setDoingAt(new \DateTime());
        $ecritCpta->setIsSent(false);
        $ecritCpta->setCreatedAt(new \DateTime());
        $ecritCpta->setSourceId($bankToCash->getId());
        $ecritCpta->setBranch($bankToCash->getBranch());
        $em->persist($ecritCpta);

        /**
         * Save cashdesk destination : credit --> debit
         *
         */
        $ecritCpta = new Ecritcpta();

        $refd_random = $this->randomUnique(6);
        $refd_number = "CashDes-" . $refd_random;

        $ecritCpta->setAccount($bankToCash->getCashdeskDes()->getAccount());
        $ecritCpta->setJournal($bankToCash->getCashdeskDes()->getJournal());
        $ecritCpta->setCurrency($currency);
        $ecritCpta->setYear($year);
        $ecritCpta->setUser($this->getUser());
        // piece 
        $ecritCpta->setReference($refd_number);
        $ecritCpta->setTransactionNumber($tr_number);
        $ecritCpta->setDescription($bankToCash->getDescription());
        $ecritCpta->setDebit($bankToCash->getAmount());
        $ecritCpta->setCredit(0);
        $ecritCpta->setDoingAt(new \DateTime());
        $ecritCpta->setIsSent(false);
        $ecritCpta->setCreatedAt(new \DateTime());
        $ecritCpta->setSourceId($bankToCash->getId());
        $ecritCpta->setBranch($bankToCash->getBranch());
        $em->persist($ecritCpta);
        /*                                 End save accounting operations                                       */

        /**
         * Reverse bank balance, cashdesk balance and credit 
         */

        // Reverse bank source balance 
        $bankToCash->getBankSrc()->setBalance($bankToCash->getBankSrc()->getBalance() - $bankToCash->getAmount());

        // Reverse cashdesk destination balance and credit == debit 
        $bankToCash->getCashdeskDes()->setBalance($bankToCash->getCashdeskDes()->getBalance() + $bankToCash->getAmount());
        $bankToCash->getCashdeskDes()->setCredit($bankToCash->getCashdeskDes()->getCredit() + $bankToCash->getAmount());

        $bankToCash->setIsValid(false);

        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('bank_to_cash');
    }

    /**
     * Creates a new Cash to Bank entity.
     *
     * @Route("{_locale}/cash-to-bank", methods={"GET", "POST"}, name="cash_to_bank")
     *
     */
    public function newCashToBank(Request $request, UserRepository $user, CashDeskRepository $cashDesks, InterCashTransferRepository $cashToBanks, ObjectManager $em): Response
    {
        $cashToBank = new InterCashTransfer();

        // On Instancie le formulaire
        $form = $this->createForm(CashToBankType::class, $cashToBank);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cashSrc = $form->get('cashdeskSrc')->getData();
            $cashdeskSrc = $cashDesks->findOneBy(['id' => $cashSrc]);

            // SUM of potentiel transfer payment 
            $sumAwaitingTransfer = $cashToBanks->totalAmountForAwaitingTransferOfCashToBank($cashdeskSrc);

            // --> Check available balance of cashier source
            $cashdeskSrcAvailableBalance = $cashdeskSrc->getBalance() - $sumAwaitingTransfer;

            $amount = $form->get('amount')->getData();

            if ($cashdeskSrcAvailableBalance < $amount) {
                $this->addFlash('warning', 'The available balance ' . $cashdeskSrcAvailableBalance . ' is less than ' . $amount . ' needed!');
                return $this->redirectToRoute('cash_to_bank');
            }

            //var_dump($cashdeskSrcAvailableBalance);
            //exit();

            // Get Branch 
            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            // On prépare l'objet à persister
            $cashToBank->setCreatedAt(new \DateTime());
            $cashToBank->setUser($this->getUser());
            $cashToBank->setBranch($branch);
            $cashToBank->setType('cashTObank');
            $cashToBank->setIsValid(false);

            $em->persist($cashToBank);
            $em->flush();

            $this->addFlash('success', 'Success!');
            return $this->redirectToRoute('cash_to_bank');
        }
        $userCashToBanks = $cashToBanks->findBy(['type' => 'cashTObank'], ['id' => 'DESC']);

        return $this->render('treasury_transfer/edit_cash_to_bank.html.twig', [
            'cashToBank' => $cashToBank,
            'form' => $form->createView(),
            'cashToBanks' => $userCashToBanks,
        ]);
    }

    /**
     * Displays a form to edit an existing Cash To Bank entity.
     *
     * @Route("{_locale}/cash-to-bank/{id<\d+>}/edit", methods={"GET", "POST"}, name="cash_to_bank_edit")
     *
     */
    public function editCashToBank(Request $request, InterCashTransferRepository $cashToBanks, CashDeskRepository $cashDesks, InterCashTransfer $cashToBank, ObjectManager $em): Response
    {
        $form = $this->createForm(CashToBankType::class, $cashToBank);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cashSrc = $form->get('cashdeskSrc')->getData();
            $cashdeskSrc = $cashDesks->findOneBy(['id' => $cashSrc]);

            // SUM of potentiel transfer payment 
            $sumAwaitingTransfer = $cashToBanks->totalAmountForAwaitingTransferOfCashToBank($cashdeskSrc);

            $amount = $form->get('amount')->getData();

            $sumAwaitingTransfer = $sumAwaitingTransfer - $amount;

            // --> Check available balance of cashier source
            $cashdeskSrcAvailableBalance = $cashdeskSrc->getBalance() - $sumAwaitingTransfer;

            if ($cashdeskSrcAvailableBalance < $amount) {
                $this->addFlash('warning', 'The available balance ' . $cashdeskSrcAvailableBalance . ' is less than ' . $amount . ' needed!');
                return $this->redirectToRoute('cash_to_bank_edit', array('id' => $cashToBank->getId()));
            }

            //var_dump($cashdeskSrcAvailableBalance);
            //exit();

            $em->flush();

            $this->addFlash('success', 'Success!');
            return $this->redirectToRoute('cash_to_bank');
        }
        $userCashToBanks = $cashToBanks->findBy(['type' => 'cashTObank'], ['id' => 'DESC']);

        return $this->render('treasury_transfer/edit_cash_to_bank.html.twig', [
            'cashToBank' => $cashToBank,
            'form' => $form->createView(),
            'cashToBanks' => $userCashToBanks,
        ]);
    }

    /**
     * Deletes a Cash To Bank entity.
     *
     * @Route("/cash-to-bank/{id}/delete", methods={"GET", "POST"}, name="cash_to_bank_delete")
     *
     */
    public function deleteCashToBank(InterCashTransfer $cashToBank, ObjectManager $em): Response
    {
        $em->remove($cashToBank);

        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('cash_to_bank');
    }

    /**
     * Validate an Cash To Cash entity.
     *
     * @Route("/cash-to-bank/{id}/validate", methods={"GET", "POST"}, name="cash_to_bank_validate")
     *
     */
    public function validateCashToBank(InterCashTransfer $cashToBank, InterCashTransferRepository $cashToBanks, ObjectManager $em, YearRepository $years, CurrencyRepository $currencies): Response
    {
        // Check if the current user is the operator of source cashier ?
        if ($cashToBank->getCashdeskSrc()->getOperator() != $this->getUser()) {
            $this->addFlash('warning', 'Only the operator of source cashier can validate this transfer!');
            return $this->redirectToRoute('cash_to_bank');
        }

        // Check if his cashier is open 
        if ($cashToBank->getCashdeskSrc()->getIsOpen() == 0) {
            $this->addFlash('warning', 'Your cashier must be open before validation!');
            return $this->redirectToRoute('cash_to_bank');
        }

        /**
         * Check cashdesk source balance again
         */

        // SUM of potentiel transfer payment 
        $sumAwaitingTransfer = $cashToBanks->totalAmountForAwaitingTransferOfCashToBank($cashToBank->getCashdeskSrc());

        $amount = $cashToBank->getAmount();

        $sumAwaitingTransfer = $sumAwaitingTransfer - $amount;

        // --> Check available balance of cashier source
        $cashdeskSrcAvailableBalance = $cashToBank->getCashdeskSrc()->getBalance() - $sumAwaitingTransfer;

        if ($cashdeskSrcAvailableBalance < $amount) {
            $this->addFlash('warning', 'The available balance ' . $cashdeskSrcAvailableBalance . ' is less than ' . $amount . ' needed!');
            return $this->redirectToRoute('cash_to_bank');
        }

        /*var_dump($cashdeskSrcAvailableBalance);
        exit();*/

        /**
         * Accounting operations
         * 
         * debit of cashdesk source account 
         * credit of cashdesk destination account 
         */

        // Get the current open year
        $year = $years->findOneBy(['is_open' => true, 'is_current' => true]);
        $currency = $currencies->findOneBy(['is_current' => true]);

        /**
         * Check if cashier and bank have their accounts configure
         */
        if (!$cashToBank->getCashdeskSrc()->getAccount()) {
            $this->addFlash('warning', 'Check account of source cashier!');
            return $this->redirectToRoute('cash_to_bank');
        }
        if (!$cashToBank->getBankDes()->getAccount()) {
            $this->addFlash('warning', 'Check account of destination bank!');
            return $this->redirectToRoute('cash_to_bank');
        }

        /**
         * Check if cashier and bank have their journals configure
         */
        if (!$cashToBank->getCashdeskSrc()->getJournal()) {
            $this->addFlash('warning', 'Check the source cashier journal!');
            return $this->redirectToRoute('cash_to_bank');
        }
        if (!$cashToBank->getBankDes()->getJournal()) {
            $this->addFlash('warning', 'Check the destination bank journal!');
            return $this->redirectToRoute('cash_to_bank');
        }

        /**
         * Generate : 
         * Transaction number
         */
        if ($cashToBank->getReference() == "") {
            $tr_random = $this->randomUnique(8);
            $tr_number = "TrCTB-" . $tr_random;
        } else {
            $tr_number = $cashToBank->getReference();
        }


        /**
         * Save cashdesk source : debit
         */
        $ecritCpta = new Ecritcpta();

        $refs_random = $this->randomUnique(7);
        $refs_number = "CashSr-" . $refs_random;

        $ecritCpta->setAccount($cashToBank->getCashdeskSrc()->getAccount());
        $ecritCpta->setJournal($cashToBank->getCashdeskSrc()->getJournal());
        $ecritCpta->setCurrency($currency);
        $ecritCpta->setYear($year);
        $ecritCpta->setUser($this->getUser());
        // piece 
        $ecritCpta->setReference($refs_number);
        $ecritCpta->setTransactionNumber($tr_number);
        $ecritCpta->setDescription($cashToBank->getDescription());
        $ecritCpta->setDebit($cashToBank->getAmount());
        $ecritCpta->setCredit(0);
        $ecritCpta->setDoingAt(new \DateTime());
        $ecritCpta->setIsSent(false);
        $ecritCpta->setCreatedAt(new \DateTime());
        $ecritCpta->setSourceId($cashToBank->getId());
        $ecritCpta->setBranch($cashToBank->getBranch());
        $em->persist($ecritCpta);

        /**
         * Save bank destination : credit
         *
         */
        $ecritCpta = new Ecritcpta();

        $refd_random = $this->randomUnique(6);
        $refd_number = "BankDes-" . $refd_random;

        $ecritCpta->setAccount($cashToBank->getBankDes()->getAccount());
        $ecritCpta->setJournal($cashToBank->getBankDes()->getJournal());
        $ecritCpta->setCurrency($currency);
        $ecritCpta->setYear($year);
        $ecritCpta->setUser($this->getUser());
        // piece 
        $ecritCpta->setReference($refd_number);
        $ecritCpta->setTransactionNumber($tr_number);
        $ecritCpta->setDescription($cashToBank->getDescription());
        $ecritCpta->setDebit(0);
        $ecritCpta->setCredit($cashToBank->getAmount());
        $ecritCpta->setDoingAt(new \DateTime());
        $ecritCpta->setIsSent(false);
        $ecritCpta->setCreatedAt(new \DateTime());
        $ecritCpta->setSourceId($cashToBank->getId());
        $ecritCpta->setBranch($cashToBank->getBranch());
        $em->persist($ecritCpta);
        /*                                 End save accounting operations                                       */

        /**
         * Update cashdesk balance and debit, bank balance  
         */

        // Update cashdesk source balance and debit 
        $cashToBank->getCashdeskSrc()->setBalance($cashToBank->getCashdeskSrc()->getBalance() + $cashToBank->getAmount());
        $cashToBank->getCashdeskSrc()->setCredit($cashToBank->getCashdeskSrc()->getCredit() + $cashToBank->getAmount());

        // Update bank destination balance 
        $cashToBank->getBankDes()->setBalance($cashToBank->getBankDes()->getBalance() - $cashToBank->getAmount());


        $cashToBank->setIsValid(true);

        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('cash_to_bank');
    }

    /**
     * Cancel validation of an cash to bank entity.
     *
     * @Route("/cash-to-bank/{id}/cancel", methods={"GET", "POST"}, name="cash_to_bank_cancel")
     *
     */
    public function cancelCashToBank(InterCashTransfer $cashToBank, ObjectManager $em, YearRepository $years, CurrencyRepository $currencies): Response
    {
        /**
         * Reverse accounting operations
         * 
         * debit of cashdesk source account --> credit 
         * credit of bank destination account -- > debit  
         */
        // Get the current open year
        $year = $years->findOneBy(['is_open' => true, 'is_current' => true]);
        $currency = $currencies->findOneBy(['is_current' => true]);

        /**
         * Generate : 
         * Transaction number
         */
        if ($cashToBank->getReference() == "") {
            $tr_random = $this->randomUnique(8);
            $tr_number = "TrCTB-" . $tr_random;
        } else {
            $tr_number = $cashToBank->getReference();
        }

        /**
         * Save cashdesk source : debit --> credit
         */
        $ecritCpta = new Ecritcpta();

        $refs_random = $this->randomUnique(7);
        $refs_number = "CashSr-" . $refs_random;

        $ecritCpta->setAccount($cashToBank->getCashdeskSrc()->getAccount());
        $ecritCpta->setJournal($cashToBank->getCashdeskSrc()->getJournal());
        $ecritCpta->setCurrency($currency);
        $ecritCpta->setYear($year);
        $ecritCpta->setUser($this->getUser());
        // piece 
        $ecritCpta->setReference($refs_number);
        $ecritCpta->setTransactionNumber($tr_number);
        $ecritCpta->setDescription($cashToBank->getDescription());
        $ecritCpta->setDebit(0);
        $ecritCpta->setCredit($cashToBank->getAmount());
        $ecritCpta->setDoingAt(new \DateTime());
        $ecritCpta->setIsSent(false);
        $ecritCpta->setCreatedAt(new \DateTime());
        $ecritCpta->setSourceId($cashToBank->getId());
        $ecritCpta->setBranch($cashToBank->getBranch());
        $em->persist($ecritCpta);

        /**
         * Save cashdesk destination : credit --> debit
         *
         */
        $ecritCpta = new Ecritcpta();

        $refd_random = $this->randomUnique(6);
        $refd_number = "BankDes-" . $refd_random;

        $ecritCpta->setAccount($cashToBank->getBankDes()->getAccount());
        $ecritCpta->setJournal($cashToBank->getBankDes()->getJournal());
        $ecritCpta->setCurrency($currency);
        $ecritCpta->setYear($year);
        $ecritCpta->setUser($this->getUser());
        // piece 
        $ecritCpta->setReference($refd_number);
        $ecritCpta->setTransactionNumber($tr_number);
        $ecritCpta->setDescription($cashToBank->getDescription());
        $ecritCpta->setDebit($cashToBank->getAmount());
        $ecritCpta->setCredit(0);
        $ecritCpta->setDoingAt(new \DateTime());
        $ecritCpta->setIsSent(false);
        $ecritCpta->setCreatedAt(new \DateTime());
        $ecritCpta->setSourceId($cashToBank->getId());
        $ecritCpta->setBranch($cashToBank->getBranch());
        $em->persist($ecritCpta);
        /*                                 End save accounting operations                                       */

        /**
         * Reverse cashdesk balance and credit, bank balance  
         */

        // Reverse cashdesk source balance and debit == credit
        $cashToBank->getCashdeskSrc()->setBalance($cashToBank->getCashdeskSrc()->getBalance() - $cashToBank->getAmount());
        $cashToBank->getCashdeskSrc()->setDebit($cashToBank->getCashdeskSrc()->getDebit() + $cashToBank->getAmount());

        // Reverse bank destination balance 
        $cashToBank->getBankDes()->setBalance($cashToBank->getBankDes()->getBalance() + $cashToBank->getAmount());


        $cashToBank->setIsValid(false);

        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('cash_to_bank');
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
}
