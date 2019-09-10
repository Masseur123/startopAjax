<?php

namespace App\Controller\Accounting;

use App\Entity\Piece;
use App\Entity\Ecritcpta;

use App\Form\PieceType;

use App\Repository\PieceRepository;
use App\Repository\CurrencyRepository;
use App\Repository\CashDeskRepository;
use App\Repository\BankRepository;

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
use Twig\Node\Expression\Binary\FloorDivBinary;
use Twig\Node\Expression\Binary\LessBinary;
use Twig\Node\Expression\MethodCallExpression;
use Twig\Node\Expression\NullCoalesceExpression;

/**
 * Controller used to manage Accounting Entries.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class AccountingEntriesController extends AbstractController
{
    /**
     * Lists all Piece .
     *
     * @Route("{_locale}/piece", methods={"GET"}, name="piece")
     *
     */
    public function showPiece(PieceRepository $pieces): Response
    {
        // Get all the pending operations for the current user
        $userPieces = $pieces->findBy(['user' => $this->getUser()], ['id' => 'ASC']);

        // Get the total debit amount
        $totalDebit = $pieces->countTotalDebitUserPiece($this->getUser());

        // Get the total credit amount
        $totalCredit = $pieces->countTotalCreditUserPiece($this->getUser());

        return $this->render('accounting_entries/show_piece.html.twig', [
            'pieces' => $userPieces,
            'debits' => $totalDebit,
            'credits' => $totalCredit,
        ]);
    }

    /**
     * Create a single Piece entity.
     *
     * @Route("{_locale}/piece/new", methods={"GET", "POST"}, name="piece_new")
     *
     */
    public function newPiece(Request $request, PieceRepository $pieces, ObjectManager $em): Response
    {
        $piece = new Piece();

        // On Instancie le formulaire
        $form = $this->createForm(PieceType::class, $piece)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data_accountDb = $form->get('accountDb')->getData();
            $data_accountCr = $form->get('accountCr')->getData();

            if (!$data_accountDb && !$data_accountCr) {
                // Error  
                $this->addFlash('warning', 'Choose at least one debit or credit account!');
                return $this->redirectToRoute('piece');
            }

            // Check for the transaction number : Before get the piece if existing  
            $pieceForTrNumber = $pieces->findOneBy(['user' => $this->getUser()]);

            if ($pieceForTrNumber) {
                // Get the existing transanction number 
                $tr_number = $pieceForTrNumber->getPieceNumber();
            } else {
                $tr_suf = $this->randomUnique(10);
                $tr_number = "ENT-" . $tr_suf;
            }

            // Persist all the data 
            $amount = $form->get('amount')->getData();
            if ($data_accountDb && !$data_accountCr) {
                // Persist debit only  
                $piece->setDebit($amount);
                $piece->setCredit(0);
                $piece->setAccount($data_accountDb);

                $piece->setPieceNumber($tr_number);
                $piece->setCreatedAt(new \DateTime());
                $piece->setUser($this->getUser());

                $em->persist($piece);
            } elseif (!$data_accountDb && $data_accountCr) {
                // Persist credit only  
                $piece->setDebit(0);
                $piece->setCredit($amount);
                $piece->setAccount($data_accountCr);

                $piece->setPieceNumber($tr_number);
                $piece->setCreatedAt(new \DateTime());
                $piece->setUser($this->getUser());

                $em->persist($piece);
            } else {
                // Persist debit and credit   

                // Persist debit
                $pieceDb = new Piece();
                $pieceDb->setReference($piece->getReference());
                $pieceDb->setPieceNumber($tr_number);
                $pieceDb->setDescription($piece->getDescription());
                $pieceDb->setAmount($piece->getAmount());
                $pieceDb->setDoingAt($piece->getDoingAt());
                $pieceDb->setCreatedAt(new \DateTime());
                $pieceDb->setDebit($amount);
                $pieceDb->setCredit(0);
                $pieceDb->setJournal($piece->getJournal());
                $pieceDb->setAccountDb($piece->getAccountDb());
                $pieceDb->setAccountCr($piece->getAccountCr());
                $pieceDb->setYear($piece->getYear());
                $pieceDb->setBranch($piece->getBranch());
                $pieceDb->setUser($this->getUser());
                $pieceDb->setAccount($data_accountDb);

                $em->persist($pieceDb);

                // Persist credit
                $pieceCr = new Piece();
                $pieceCr->setReference($piece->getReference());
                $pieceCr->setPieceNumber($tr_number);
                $pieceCr->setDescription($piece->getDescription());
                $pieceCr->setAmount($piece->getAmount());
                $pieceCr->setDoingAt($piece->getDoingAt());
                $pieceCr->setCreatedAt(new \DateTime());
                $pieceCr->setDebit(0);
                $pieceCr->setCredit($amount);
                $pieceCr->setJournal($piece->getJournal());
                $pieceCr->setAccountDb($piece->getAccountDb());
                $pieceCr->setAccountCr($piece->getAccountCr());
                $pieceCr->setYear($piece->getYear());
                $pieceCr->setBranch($piece->getBranch());
                $pieceCr->setUser($this->getUser());
                $pieceCr->setAccount($data_accountCr);

                $em->persist($pieceCr);
            }
            
            $em->flush();

            $this->addFlash('success', 'Success!');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('piece_new');
            }

            return $this->redirectToRoute('piece');
        }

        return $this->render( 'accounting_entries/edit_piece.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing single Piece entity.
     *
     * @Route("{_locale}/piece/{id<\d+>}/edit", methods={"GET", "POST"}, name="piece_edit")
     *
     */
    public function editPiece(Request $request, PieceRepository $pieces, Piece $piece, ObjectManager $em): Response
    {
        $form = $this->createForm(PieceType::class, $piece)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data_accountDb = $form->get('accountDb')->getData();
            $data_accountCr = $form->get('accountCr')->getData();

            if (!$data_accountDb && !$data_accountCr) {
                // Error  
                $this->addFlash('warning', 'Choose at least one debit or credit account!');
                return $this->redirectToRoute('piece', array('id' => $piece->getId()));
            }

            // Persist all the data 
            $amount = $form->get('amount')->getData();
            if ($data_accountDb && !$data_accountCr) {
                // Persist debit only  
                $piece->setDebit($amount);
                $piece->setCredit(0);
            } elseif (!$data_accountDb && $data_accountCr) {
                // Persist credit only  
                $piece->setDebit(0);
                $piece->setCredit($amount);
            } else {
                // Persist debit and credit   
                $piece->setDebit($amount);
                $piece->setCredit($amount);
            }

            $em->flush();

            $this->addFlash('success', 'Success!');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('piece_new');
            }

            return $this->redirectToRoute('piece');
        }

        return $this->render( 'accounting_entries/edit_piece.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a single Piece entity.
     *
     * @Route("/piece/{id}/delete", methods={"GET", "POST"}, name="piece_delete")
     *
     */
    public function deletePiece(Piece $piece, ObjectManager $em): Response
    {
        $em->remove($piece);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('piece');
    }

    /**
     * Validate a Transaction.
     *
     * @Route("{_locale}/piece/validate", methods={"GET", "POST"}, name="piece_validate")
     *
     */
    public function validateTransaction(ObjectManager $em, PieceRepository $pieces, CurrencyRepository $currency, CashDeskRepository $cashDesks, BankRepository $banks): Response
    {
        // Get the total debit amount 
        $totalDebit = $pieces->countTotalDebitUserPiece($this->getUser());

        // Get the total credit amount 
        $totalCredit = $pieces->countTotalCreditUserPiece($this->getUser());

        // Check if total debit = total credit 
        if ($totalDebit != $totalCredit) {
            $this->addFlash('danger', 'Your transaction is not balanced!');
            return $this->redirectToRoute('piece');
        }

        // Get the current currency 
        $currentCurrency = $currency->findOneBy(['is_current' => true]);

        // Get all the pending entries for the current user 
        $userPieces = $pieces->findBy(['user' => $this->getUser()]);

        /**
         * For each entry
         * 
         * Writte : debit and/or credit ecrit cpta 
         * 
         * Delete entry  
         * 
         * 
         */

        foreach ($userPieces as $userPiece) {

            if ($userPiece->getAccountDb() && ($userPiece->getAccountCr() == null)) {

                // Debit operation only 
                $ecritCpta = new Ecritcpta();

                $ecritCpta->setAccount($userPiece->getAccountDb());
                $ecritCpta->setJournal($userPiece->getJournal());
                $ecritCpta->setCurrency($currentCurrency);
                $ecritCpta->setYear($userPiece->getYear());
                $ecritCpta->setUser($this->getUser());
                //$ecritCpta->setPiece($userPiece);
                $ecritCpta->setReference($userPiece->getReference());
                $ecritCpta->setTransactionNumber($userPiece->getPieceNumber());
                $ecritCpta->setDescription($userPiece->getDescription());
                $ecritCpta->setDebit($userPiece->getDebit());
                $ecritCpta->setCredit(0);
                $ecritCpta->setDoingAt(new \DateTime());
                $ecritCpta->setIsSent(false);
                $ecritCpta->setCreatedAt(new \DateTime());
                $ecritCpta->setSourceId($userPiece->getId());
                $ecritCpta->setBranch($userPiece->getBranch());
                $em->persist($ecritCpta);

                // Get the sub class 
                $subClassDb = substr($userPiece->getAccountDb()->getNumber(), 0, 2);

                if ($subClassDb == "57") {
                    // Check if this account is configured for a cashier
                    $cashDeskDb = $cashDesks->findOneCashDeskByAccount($userPiece->getAccountDb());

                    if ($cashDeskDb) {
                        // Update the balance, credit
                        $cashDeskDb->setBalance($cashDeskDb->getBalance() + $userPiece->getAmount());
                        $cashDeskDb->setCredit($cashDeskDb->getCredit() + $userPiece->getAmount());
                    } else {
                        //
                    }
                } elseif ($subClassDb == "52") {
                    // Check if this account is configured for a bank
                    $bankDb = $banks->findOneBankByAccount($userPiece->getAccountDb());

                    if ($bankDb) {
                        // Update the balance
                        $bankDb->setBalance($bankDb->getBalance() + $userPiece->getAmount());
                    } else {
                        //
                    }
                } else {
                    //
                }

                // Remove the line 
                $em->remove($userPiece);
            } elseif ($userPiece->getAccountCr() && ($userPiece->getAccountDb() == null)) {

                // Credit operation only 
                $ecritCpta = new Ecritcpta();

                $ecritCpta->setAccount($userPiece->getAccountCr());
                $ecritCpta->setJournal($userPiece->getJournal());
                $ecritCpta->setCurrency($currentCurrency);
                $ecritCpta->setYear($userPiece->getYear());
                $ecritCpta->setUser($this->getUser());
                //$ecritCpta->setPiece($userPiece);
                $ecritCpta->setReference($userPiece->getReference());
                $ecritCpta->setTransactionNumber($userPiece->getPieceNumber());
                $ecritCpta->setDescription($userPiece->getDescription());
                $ecritCpta->setDebit(0);
                $ecritCpta->setCredit($userPiece->getCredit());
                $ecritCpta->setDoingAt(new \DateTime());
                $ecritCpta->setIsSent(false);
                $ecritCpta->setCreatedAt(new \DateTime());
                $ecritCpta->setSourceId($userPiece->getId());
                $ecritCpta->setBranch($userPiece->getBranch());
                $em->persist($ecritCpta);

                // Get the sub class 
                $subClassCr = substr($userPiece->getAccountCr()->getNumber(), 0, 2);

                if ($subClassCr == "57") {
                    // Check if this account is configured for a cashier
                    $cashDeskCr = $cashDesks->findOneCashDeskByAccount($userPiece->getAccountCr());

                    if ($cashDeskCr) {
                        // Update the balance, credit
                        $cashDeskCr->setBalance($cashDeskCr->getBalance() - $userPiece->getAmount());
                        $cashDeskCr->setDebit($cashDeskCr->getDebit() + $userPiece->getAmount());
                    } else {
                        //
                    }
                } elseif ($subClassCr == "52") {
                    // Check if this account is configured for a bank
                    $bankCr = $banks->findOneBankByAccount($userPiece->getAccountCr());

                    if ($bankCr) {
                        // Update the balance
                        $bankCr->setBalance($bankCr->getBalance() - $userPiece->getAmount());
                    } else {
                        //
                    }
                } else {
                    //
                }

                // Remove the line 
                $em->remove($userPiece);
            } elseif ($userPiece->getAccountDb() && $userPiece->getAccountCr()) {

                // Debit operation
                $ecritCpta = new Ecritcpta();

                $ecritCpta->setAccount($userPiece->getAccountDb());
                $ecritCpta->setJournal($userPiece->getJournal());
                $ecritCpta->setCurrency($currentCurrency);
                $ecritCpta->setYear($userPiece->getYear());
                $ecritCpta->setUser($this->getUser());
                //$ecritCpta->setPiece($userPiece);
                $ecritCpta->setReference($userPiece->getReference());
                $ecritCpta->setTransactionNumber($userPiece->getPieceNumber());
                $ecritCpta->setDescription($userPiece->getDescription());
                $ecritCpta->setDebit($userPiece->getDebit());
                $ecritCpta->setCredit(0);
                $ecritCpta->setDoingAt(new \DateTime());
                $ecritCpta->setIsSent(false);
                $ecritCpta->setCreatedAt(new \DateTime());
                $ecritCpta->setSourceId($userPiece->getId());
                $ecritCpta->setBranch($userPiece->getBranch());
                $em->persist($ecritCpta);


                // Get the sub class 
                $subClassDb = substr($userPiece->getAccountDb()->getNumber(), 0, 2);

                if ($subClassDb == "57") {
                    // Check if this account is configured for a cashier
                    $cashDeskDb = $cashDesks->findOneCashDeskByAccount($userPiece->getAccountDb());

                    if ($cashDeskDb) {
                        // Update the balance, credit
                        $cashDeskDb->setBalance($cashDeskDb->getBalance() + $userPiece->getAmount());
                        $cashDeskDb->setCredit($cashDeskDb->getCredit() + $userPiece->getAmount());
                    } else {
                        //
                    }
                } elseif ($subClassDb == "52") {
                    // Check if this account is configured for a bank
                    $bankDb = $banks->findOneBankByAccount($userPiece->getAccountDb());

                    if ($bankDb) {
                        // Update the balance
                        $bankDb->setBalance($bankDb->getBalance() + $userPiece->getAmount());
                    } else {
                        //
                    }
                } else {
                    //
                }


                // Credit operation 
                $ecritCpta = new Ecritcpta();

                $ecritCpta->setAccount($userPiece->getAccountCr());
                $ecritCpta->setJournal($userPiece->getJournal());
                $ecritCpta->setCurrency($currentCurrency);
                $ecritCpta->setYear($userPiece->getYear());
                $ecritCpta->setUser($this->getUser());
                //$ecritCpta->setPiece($userPiece);
                $ecritCpta->setReference($userPiece->getReference());
                $ecritCpta->setTransactionNumber($userPiece->getPieceNumber());
                $ecritCpta->setDescription($userPiece->getDescription());
                $ecritCpta->setDebit(0);
                $ecritCpta->setCredit($userPiece->getCredit());
                $ecritCpta->setDoingAt(new \DateTime());
                $ecritCpta->setIsSent(false);
                $ecritCpta->setCreatedAt(new \DateTime());
                $ecritCpta->setSourceId($userPiece->getId());
                $ecritCpta->setBranch($userPiece->getBranch());
                $em->persist($ecritCpta);


                // Get the sub class 
                $subClassCr = substr($userPiece->getAccountCr()->getNumber(), 0, 2);

                if ($subClassCr == "57") {
                    // Check if this account is configured for a cashier
                    $cashDeskCr = $cashDesks->findOneCashDeskByAccount($userPiece->getAccountCr());

                    if ($cashDeskCr) {
                        // Update the balance, credit
                        $cashDeskCr->setBalance($cashDeskCr->getBalance() - $userPiece->getAmount());
                        $cashDeskCr->setDebit($cashDeskCr->getDebit() + $userPiece->getAmount());
                    } else {
                        //
                    }
                } elseif ($subClassCr == "52") {
                    // Check if this account is configured for a bank
                    $bankCr = $banks->findOneBankByAccount($userPiece->getAccountCr());

                    if ($bankCr) {
                        // Update the balance
                        $bankCr->setBalance($bankCr->getBalance() - $userPiece->getAmount());
                    } else {
                        //
                    }
                } else {
                    //
                }

                // Remove the line 
                $em->remove($userPiece);
            } else {
                //Nothing 
            }
        }

        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('piece');
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
