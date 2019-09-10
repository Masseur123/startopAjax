<?php

namespace App\Controller\Accounting;

use App\Entity\Account;
use App\Entity\Journal;
use App\Entity\TmpAccount;

use App\Form\TmpAccountEditType;
use App\Form\TmpAccountType;
use App\Form\JournalType;

use App\Repository\AccountRepository;
use App\Repository\JournalRepository;
use App\Repository\ParkRepository;
use App\Repository\TmpAccountRepository;

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
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\SandboxedPrintNode;
use Twig_Parser;

/**
 * Controller used to manage Accounting Settings.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <StarTop>
 */
class AccountingSettingsController extends AbstractController
{

    /**
     * Lists all Journals.
     *
     * @Route("{_locale}/journal", methods={"GET"}, name="journal")
     *
     */
    public function showPark(JournalRepository $journals): Response
    {
        $userJournals = $journals->findBy([], ['createdAt' => 'DESC']);

        return $this->render('accounting_settings/show_journal.html.twig', ['journals' => $userJournals]);
    }

    /**
     * Creates a new Journal entity.
     *
     * @Route("{_locale}/journal/new", methods={"GET", "POST"}, name="journal_new")
     *
     */
    public function newJournal(Request $request, ObjectManager $em): Response
    {
        $journal = new Journal();

        // On Instancie le formulaire
        $form = $this->createForm(JournalType::class, $journal)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $journal->setCreatedAt(new \DateTime());
            $journal->setUser($this->getUser());

            $em->persist($journal);
            $em->flush();

            $this->addFlash('success', 'Success!');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('journal_new');
            }

            return $this->redirectToRoute('journal');
        }

        return $this->render('accounting_settings/edit_journal.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Journal entity.
     *
     * @Route("{_locale}/journal/{id<\d+>}/edit", methods={"GET", "POST"}, name="journal_edit")
     *
     */
    public function editJournal(Request $request, JournalRepository $journals, Journal $journal, ObjectManager $em): Response
    {
        $form = $this->createForm(JournalType::class, $journal)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('journal_new');
            }

            return $this->redirectToRoute('journal');
        }
        $userJournals = $journals->findBy([], ['id' => 'DESC']);

        return $this->render('accounting_settings/edit_journal.html.twig', [
            'form' => $form->createView(),
            'journals' => $userJournals,
        ]);
    }

    /**
     * Deletes a Journal entity.
     *
     * @Route("/journal/{id}/delete", methods={"GET", "POST"}, name="journal_delete")
     *
     */
    public function deleteJournal(Journal $journal, ObjectManager $em): Response
    {
        $em->remove($journal);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('journal');
    }

    /**
     * Creates a new Account entity.
     *
     * @Route("{_locale}/account-generate", methods={"GET", "POST"}, name="account_generate")
     *
     */
    public function generateAccount(Request $request, AccountRepository $Accounts, TmpAccountRepository $tmpAccounts, ObjectManager $em): Response
    {
        $tmpAccount = new TmpAccount();

        // On Instancie le formulaire
        $form = $this->createForm(TmpAccountType::class, $tmpAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On recupère la chaine saisie
            $number = $form->get('number')->getData();

            // On recupère la longueur de la chaine
            $length = strlen($number);

            $count = 0;

            while ($length >= 2) {
                $code = substr($number, 0, $length);

                if ((!$Accounts->findOneBy(['number' => $code], [])) && (!$tmpAccounts->findOneBy(['number' => $code], []))) {
                    // Enregistrement du compte temporaire
                    $tmpAccount = new TmpAccount();
                    $tmpAccount->setNumber($code);
                    $em->persist($tmpAccount);

                    $count++;
                }
                $length--;
            }

            $em->flush();
            if ($count == 0) {
                $this->addFlash('warning', 'No account generated!');
            } else {
                $this->addFlash('success', 'Success!: ' . $count . ' accounts generated');
            }

            return $this->redirectToRoute('account_generate');
        }
        $userTmpAccounts = $tmpAccounts->findBy([], ['id' => 'DESC']);

        return $this->render( 'accounting_settings/generate_account.html.twig', [
            'form' => $form->createView(),
            'tmpAccounts' => $userTmpAccounts,
        ]);
    }

    /**
     * Displays a form to edit an existing Account entity.
     *
     * @Route("{_locale}/account/{id<\d+>}/validate", methods={"GET", "POST"}, name="account_validate")
     *
     */
    public function validateAccount(Request $request, TmpAccount $tmpAccount, ObjectManager $em): Response
    {
        $form = $this->createForm(TmpAccountEditType::class, $tmpAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On initialise le compte
            $account = new Account();

            // On recupere les valeurs du compte temporaire
            $code = $form->get('number')->getData();
            $libele = $form->get('title')->getData();

            //$class = substr($code, 0, 1);
            $class = $code{
                0};

            // On persiste le compte
            $account->setNumber($code);
            $account->setTitle($libele);
            $account->setIsLock(false);
            $account->setClass($class);

            if (strlen($code) == 4) {
                $account->setIsFinal(true);
            } else {
                $account->setIsFinal(false);
            }
            $account->setCreatedAt(new \DateTime());
            $account->setUser($this->getUser());

            $em->persist($account);

            // Suppression du compte temporaire
            $em->remove($tmpAccount);

            $em->flush();

            $this->addFlash('success', 'Success!');

            return $this->redirectToRoute('account_generate');
        }

        return $this->render( 'accounting_settings/edit_account.html.twig', [
            'tmpAccount' => $tmpAccount,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Lists all Account.
     *
     * @Route("{_locale}/account", methods={"GET", "POST"}, name="account")
     *
     */
    public function showAccount(AccountRepository $accounts): Response
    {
        $userAccounts = $accounts->findBy([], ['number' => 'ASC']);

        return $this->render( 'accounting_settings/show_account.html.twig', ['accounts' => $userAccounts]);
    }

    /**
     * Lock Account entity.
     *
     * @Route("/account/{id}/lock", methods={"GET", "POST"}, name="account_lock")
     *
     */
    public function lockAccount(Account $account, ObjectManager $em): Response
    {
        $account->setIsLock(true);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('account');
    }

    /**
     * UnLock Account entity.
     *
     * @Route("/account/{id}/unlock", methods={"GET", "POST"}, name="account_unlock")
     *
     */
    public function unlockAccount(Account $account, ObjectManager $em): Response
    {
        $account->setIsLock(false);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('account');
    }
}
