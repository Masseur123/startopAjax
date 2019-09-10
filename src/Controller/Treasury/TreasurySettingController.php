<?php

namespace App\Controller\Treasury;

use App\Entity\CreditCard;
use App\Entity\Bank;
use App\Entity\CashDesk;

use App\Form\CreditCardType;
use App\Form\BankType;
use App\Form\CashDeskType;

use App\Repository\CreditCardRepository;
use App\Repository\BankRepository;
use App\Repository\CashDeskRepository;
use App\Repository\UserRepository;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Stopwatch\StopwatchEvent;
use Twig\Node\Expression\Binary\LessBinary;
use Twig\Node\Expression\NullCoalesceExpression;
use Twig\Node\ModuleNode;
use Twig_Lexer;
use Twig_Sandbox_SecurityNotAllowedMethodError;
use Twig_Template;

/**
 * Controller used to manage Treasury Settings.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class TreasurySettingController extends AbstractController
{
    /**
     * Lists all Credit card .
     *
     *
     * @Route("{_locale}/credit-card", methods={"GET"}, name="credit_card")
     *
     */
    public function showCreditCard(CreditCardRepository $creditCards): Response
    {
        $userCreditCard = $creditCards->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('treasury_setting/show_creditcard.html.twig', [ 'creditCards' => $userCreditCard]);
    }

    /**
     * Creates a new Credit Card entity.
     *
     * @Route("/credit-card-new", methods={"GET", "POST"}, name="credit_card_new")
     *
     */
    public function newCreditCard(Request $request,  ObjectManager $em): Response
    {
        $creditCard = new CreditCard();

        // On Instancie le formulaire
        $form = $this->createForm(CreditCardType::class, $creditCard)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $creditCard->setCreatedAt(new \DateTime());
            $creditCard->setUser($this->getUser());

            $em->persist($creditCard);
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('credit_card_new');
            }

            return $this->redirectToRoute('credit_card');
        }

        return $this->render('treasury_setting/edit_creditcard.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing stay type entity.
     *
     * @Route("/credit-card/{id<\d+>}/edit",methods={"GET", "POST"}, name="credit_card_edit")
     *
     */
    public function editCreditCard(Request $request, CreditCardRepository $creditCards, CreditCard $creditCard, ObjectManager $em): Response
    {
        $form = $this->createForm(CreditCardType::class, $creditCard)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('credit_card_new');
            }

            return $this->redirectToRoute('credit_card');
        }
        $userCreditCard = $creditCards->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('treasury_setting/edit_creditcard.html.twig', [
            'form' => $form->createView(),
            'creditCards' => $userCreditCard
        ]);
    }

    /**
     * Deletes a Credit Card entity.
     *
     * @Route("/credit-card/{id}/delete", methods={"GET", "POST"}, name="credit_card_delete")
     *
     */
    public function deleteCreditCard(CreditCard $creditCard, ObjectManager $em): Response
    {
        $em->remove($creditCard);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('credit_card');
    }

    /**
     * Lists all Cash Desk .
     *
     *
     * @Route("{_locale}/cash-desk", methods={"GET"}, name="cash_desk")
     *
     */
    public function showPark(CashDeskRepository $CashDesks): Response
    {
        $userCashDesks = $CashDesks->findBy([], ['id' => 'DESC']);

        return $this->render('treasury_setting/show_cash-desk.html.twig', [ 'CashDesks' => $userCashDesks,]);
    }

    /**
     * Creates a new Cash Desk entity.
     *
     * @Route("{_locale}/cash-desk-new", methods={"GET", "POST"}, name="cash_desk_new")
     *
     */
    public function newCashDesk(Request $request, CashDeskRepository $CashDesks, ObjectManager $em, UserRepository $user): Response
    {
        $cashDesk = new CashDesk();

        // On Instancie le formulaire
        $form = $this->createForm(CashDeskType::class, $cashDesk)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get Branch 
            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            $operator = $form->get('operator')->getData();

            // Check if operator is already a cashier
            if ($operator && ($CashDesks->findOneBy(['operator' => $operator->getId()]))) {
                $this->addFlash('warning', $operator->getFullname() . ' is already assign to another cash desk!');
                return $this->redirectToRoute('cash_desk');
            }

            /*var_dump($cashdesk->getCode());
            die;*/

            // On prépare l'objet à persister
            $cashDesk->setIsOpen(false);
            $cashDesk->setCreatedAt(new \DateTime());
            $cashDesk->setUser($this->getUser());
            $cashDesk->setBranch($branch);

            $cashDesk->setBalance(0);

            $cashDesk->setDebit(0);
            $cashDesk->setCredit(0);

            $em->persist($cashDesk);
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('cash_desk_new');
            }
            return $this->redirectToRoute('cash_desk');
        }

        return $this->render('treasury_setting/edit_cash-desk.html.twig', [
            'cashDesk' => $cashDesk,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Cash Desk entity.
     *
     * @Route("{_locale}/cash-desk/{id<\d+>}/edit",methods={"GET", "POST"}, name="cash_desk_edit")
     *
     */
    public function editCashDesk(Request $request, CashDeskRepository $CashDesks, CashDesk $cashDesk, ObjectManager $em): Response
    {
        // Operator save in the cash_desk entity 
        $saveOperator = $cashDesk->getOperator()->getId();

        $form = $this->createForm(CashDeskType::class, $cashDesk)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Operator choose on the form 
            $chooseOperator = $form->get('operator')->getData();

            //var_dump($saveOperator);
            //var_dump($chooseOperator->getId());
            //die;

            if ($saveOperator != $chooseOperator->getId()) {
                // Check if the new operator already have cashdesk assign 
                $chooseOperatorCashDesk = $CashDesks->findOneBy(['operator' => $chooseOperator->getId()]);
                /*var_dump($chooseOperatorCashDesk->getId());
                die;*/
                if ($chooseOperatorCashDesk != null) {
                    $this->addFlash('warning', $chooseOperator->getFullname() . ' is already assign to another cash desk! ');
                    return $this->redirectToRoute('cash_desk', array('id' => $cashDesk->getId()));
                }
            }

            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('cash_desk_new');
            }

            return $this->redirectToRoute('cash_desk');
        }
        $userCashDesks = $CashDesks->findBy([], ['id' => 'DESC']);

        return $this->render('treasury_setting/edit_cash-desk.html.twig', [
            'cashDesk' => $cashDesk,
            'form' => $form->createView(),
            'CashDesks' => $userCashDesks,
        ]);
    }

    /**
     * Deletes a Cash Desk entity.
     *
     * @Route("/cash-desk/{id}/delete", methods={"GET", "POST"}, name="cash_desk_delete")
     *
     */
    public function deleteCashDesk(CashDesk $cashDesk, ObjectManager $em): Response
    {
        $em->remove($cashDesk);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('cash_desk');
    }

    /**
     * Open a Cash Desk entity.
     *
     * @Route("/cash-desk/{id}/open", methods={"GET", "POST"}, name="cash_desk_open")
     *
     */
    public function openCashDesk(CashDesk $cashDesk, ObjectManager $em): Response
    {
        $cashDesk->setIsOpen(true);
        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('cash_desk');
    }

    /**
     * Open a Cash Desk entity.
     *
     * @Route("/cash-desk/{id}/close", methods={"GET", "POST"}, name="cash_desk_close")
     *
     */
    public function closeCashDesk(CashDesk $cashDesk, ObjectManager $em): Response
    {
        // Save cashdesk situation  
        $cashDeskSituation = new CashDeskSituation();

        $cashDeskSituation->setCashDesk($cashDesk);
        $cashDeskSituation->setDebit($cashDesk->getDebit());
        $cashDeskSituation->setCredit($cashDesk->getCredit());
        $cashDeskSituation->setBalance($cashDesk->getBalance());
        $cashDeskSituation->setSaveAt(new \DateTime());
        $cashDeskSituation->setUser($this->getUser());
        $cashDeskSituation->setBranch($cashDesk->getBranch());

        // Reset debit and credit
        $cashDesk->setDebit(0);
        $cashDesk->setCredit(0);

        // Plus tard on va ajouter la clause du billetage  

        // Change cashdesk status 
        $cashDesk->setIsOpen(false);

        $em->persist($cashDeskSituation);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('cash_desk');
    }

    /**
     * Lists all Bank .
     *
     *
     * @Route("{_locale}/bank", methods={"GET"}, name="bank")
     *
     */
    public function showBank(BankRepository $banks): Response
    {
        $userBanks = $banks->findBy([], ['id' => 'DESC']);

        return $this->render('treasury_setting/show_bank.html.twig', [ 'banks' => $userBanks,]);
    }

    /**
     * Creates a new Bank entity.
     *
     * @Route("{_locale}/bank/new", methods={"GET", "POST"}, name="bank_new")
     *
     */
    public function newBank(Request $request, ObjectManager $em, UserRepository $user): Response
    {
        $bank = new Bank();

        // On Instancie le formulaire
        $form = $this->createForm(BankType::class, $bank)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get Branch 
            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            // On prépare l'objet à persister
            $bank->setCreatedAt(new \DateTime());
            $bank->setUser($this->getUser());
            $bank->setBranch($branch);
            $bank->setBalance(0);

            $em->persist($bank);
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('bank_new');
            }
            return $this->redirectToRoute('bank');
        }

        return $this->render('treasury_setting/edit_bank.html.twig', [
            'bank' => $bank,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Carrier entity.
     *
     * @Route("{_locale}/bank/{id<\d+>}/edit", methods={"GET", "POST"}, name="bank_edit")
     *
     */
    public function editBank(Request $request, BankRepository $banks, Bank $bank, ObjectManager $em): Response
    {
        $form = $this->createForm(BankType::class, $bank)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('bank_new');
            }
            return $this->redirectToRoute('bank');
        }
        $userBanks = $banks->findBy([], ['id' => 'DESC']);

        return $this->render('treasury_setting/edit_bank.html.twig', [
            'bank' => $bank,
            'form' => $form->createView(),
            'banks' => $userBanks,
        ]);
    }

    /**
     * Deletes a Carrier entity.
     *
     * @Route("/bank/{id}/delete", methods={"GET", "POST"}, name="bank_delete")
     *
     */
    public function deleteBank(Bank $bank, ObjectManager $em): Response
    {
        $em->remove($bank);

        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('bank');
    }
}
