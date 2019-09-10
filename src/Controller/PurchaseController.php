<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\SupplyRequest;
use App\Entity\SupplyRequestArticle;
use App\Form\SupplyRequestType;
use App\Form\SupplyRequestArticleType;
use App\Repository\SupplyRequestRepository;
use App\Repository\SupplyRequestArticleRepository;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Monolog\Handler\GelfMockMessagePublisher;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Psr\Container\ContainerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Tests\Extension\Core\EventListener\MergeCollectionListenerArrayObjectTest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


use App\Entity\Delevery;
use App\Entity\DeleveryArticle;
use App\Entity\Invoice;
use App\Entity\Order;
use App\Entity\OrderArticle;
use App\Entity\Quotation;
use App\Entity\QuotationArticle;
use App\Entity\Settlement;
use App\Entity\Stock;

use App\Form\DeleveryArticleType;
use App\Form\DeleveryType;
use App\Form\InvoiceType;
use App\Form\OrderArticleType;
use App\Form\OrderType;
use App\Form\QuotationArticleType;
use App\Form\QuotationType;
use App\Form\SettlementType;
use App\Repository\BankRepository;
use App\Repository\CashDeskRepository;
use App\Repository\DeleveryArticleRepository;
use App\Repository\DeleveryRepository;
use App\Repository\InvoiceRepository;
use App\Repository\OrderArticleRepository;
use App\Repository\OrderRepository;
use App\Repository\QuotationArticleRepository;
use App\Repository\QuotationRepository;
use App\Repository\SettlementRepository;

use App\Repository\UserRepository;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Twig\Extension\EscaperExtension;
use Twig\Node\Expression\Binary\EndsWithBinary;
use Twig\Node\Expression\BlockReferenceExpression;
use Twig\Node\Expression\GetAttrExpression;
use Twig\Node\Expression\NullCoalesceExpression;
use Twig\Node\PrintNode;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Test\NodeTestCase;

/**
 * Controller used to manage Purchase.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class PurchaseController extends AbstractController
{
    /**
     * @Route("{_locale}/supply-request", methods={"GET", "POST"}, name="supply_request")
     */
    public function showSupplyRequest(SupplyRequestRepository $suppliesRequest):Response
    {
        $userSuppliesRequest = $suppliesRequest->findBy([], ['id' => 'DESC']);
        return $this->render('purchase/show_supply_request.html.twig',
        [
            'suppliesRequest' => $userSuppliesRequest
        ]);
    }

    /**
     * Creates a new Supply request entity.
     *
     * @Route("{_locale}/supply-request-new", methods={"GET", "POST"}, name="supply_request_new")
     *
     */
    public function newSupplyRequest(Request $request, ObjectManager $em): Response
    {
        $supplyRequest = new SupplyRequest();

        // On Instancie le formulaire
        $form = $this->createForm(SupplyRequestType::class, $supplyRequest)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $supplyRequest->setCreatedAt(new\DateTime());
            $supplyRequest->setUser($this->getUser());

            $em->persist($supplyRequest);
            $em->flush();

            $this->addFlash('success', 'created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('supply_request_new');
            }

            return $this->redirectToRoute('supply_request');
        }

        return $this->render('purchase/edit_supply_request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing request entity.
     *
     * @Route("/supply-request/{id<\d+>}/edit", methods={"GET", "POST"}, name="supply_request_edit")
     *
     */
    public function editSupplyRequest(Request $request, SupplyRequestRepository $suppliesRequest, SupplyRequest $supplyRequest, ObjectManager $em): Response
    {
        $form = $this->createForm(SupplyRequestType::class, $supplyRequest)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('supply_request_new');
            }

            return $this->redirectToRoute('supply_request');
        }
        $userSuppliesRequest = $suppliesRequest->findBy([], ['id' => 'DESC']);

        return $this->render('purchase/edit_supply_request.html.twig', [
            'supplyrequest' => $supplyRequest,
            'form' => $form->createView(),
            'suppliesRequest' => $userSuppliesRequest,
        ]);
    }

    /**
     * Deletes a supply request entity.
     *
     * @Route("/supply-request/{id}/delete", methods={"GET", "POST"}, name="supply_request_delete")
     *
     */
    public function deleteSupplyRequest(SupplyRequest $supplyRequest, ObjectManager $em): Response
    {
        $em->remove($supplyRequest);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');
        return $this->redirectToRoute('supply_request');
    }


    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("/supply-request/{id<\d+>}/valid",methods={"GET", "POST"}, name="supply_request_valid")
     *
     */
    public function validSupplyRequest(SupplyRequest $supplyRequest, ObjectManager $em): Response
    {
        $supplyRequest->setIsValid(true);
        $em->flush();

        $this->addFlash('success', 'valid_successfully');
        return $this->redirectToRoute('supply_request');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("/supply-request/{id<\d+>}/unvalid", methods={"GET", "POST"}, name="supply_request_reject")
     *
     */
    public function rejectSupplyRequest(SupplyRequest $supplyRequest, ObjectManager $em): Response
    {
        $supplyRequest->setIsValid(false);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('supply_request');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("/supply-request/{id<\d+>}/reverse", methods={"GET", "POST"}, name="supply_request_reverse")
     *
     */
    public function reverseSupplyRequest(SupplyRequest $supplyRequest, ObjectManager $em): Response
    {
        $supplyRequest->setIsValid(null);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('supply_request');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("/supply-request/{id<\d+>}/cancel", methods={"GET", "POST"}, name="supply_request_cancel")
     *
     */
    /*public function cancelSupplyRequest(Request $request, SupplyRequest $supplyRequest, ObjectManager $em): Response
    {
        $supplyRequest->setIsValid(NULL);
        $em->flush();

        $this->addFlash('success', 'successfully');

        return $this->redirectToRoute('supply_request');
    }*/

    /**
     * Lists all Supply request Articles.
     *
     * @Route("/supply-request-article", methods={"GET"}, name="supply_request_article")
     *
     */
    /*public function showSupplyRequestArticle(SupplyRequestArticleRepository $supplyRequestArticles): Response
    {
        $userSupplyRequestArticles = $supplyRequestArticles->findBy([], ['id' => 'DESC']);

        return $this->render('purchase/show_supply_request_article.html.twig', ['supplyRequestArticles' => $userSupplyRequestArticles]);
    }*/

    /**
     * Displays a form to edit an existing Supply request entity.
     *
     * @Route("{_locale}/supply-request/{id<\d+>}/cart", methods={"GET", "POST"}, name="supply_request_cart")
     *
     */
    public function showCartSupplyRequest(SupplyRequestArticleRepository $supplyRequestArticles, SupplyRequest $supplyRequest)
    {
        $userSupplyRequestArticles = $supplyRequestArticles->findBy(['supply' => $supplyRequest], ['id' => 'DESC']);
        return $this->render('purchase/show_cart_supply_request.html.twig',
            [
                'supplyRequestArticles' => $userSupplyRequestArticles,
                'supply'=>$supplyRequest,
            ]);
    }

    /**
     * Displays a form to edit an existing Supply request entity.
     *
     * @Route("{_locale}/supply-request/{id<\d+>}/cart-new", methods={"GET", "POST"}, name="supply_request_cart_new")
     *
     */
    public function cartSupplyRequest(Request $request, SupplyRequest $supplyRequest, ObjectManager $em): Response
    {
        $supplyRequestArticle = new SupplyRequestArticle();

        $form = $this->createFormBuilder($supplyRequestArticle)
            ->add('quantity', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'quantity',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('article', EntityType::class, array(
                'class' => Article::class,
                'expanded' => false,
                'choice_label' => 'reference',
                'multiple' => false,
                'placeholder' => 'Choose an article',
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->add('pu',IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'pu',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->add('pt',IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'pt',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->getForm()
        ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $supplyRequestArticle->setCreatedAt(new\DateTime());
            $supplyRequestArticle->setSupply($supplyRequest);
            $supplyRequestArticle->setIsValid(true);


            if($supplyRequestArticle->getPt())
            {
                $supplyRequestArticle->setPu($supplyRequestArticle->getPt() / $supplyRequestArticle->getQuantity());
            }

            elseif ($supplyRequestArticle->getPu())
            {
                $supplyRequestArticle->setPt($supplyRequestArticle->getQuantity() * $supplyRequestArticle->getPu());
            }
            else {

            }

            $em->persist($supplyRequestArticle);
            $em->flush();


            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('supply_request_cart');
            }

            return $this->redirectToRoute('supply_request_cart', array('id' => $supplyRequest->getId()));
        }

        // retour du formulaire et du tableau
        return $this->render('purchase/cart_supply_request.html.twig', [
            'form' => $form->createView(),
            'supply'=>$supplyRequest
        ]);
    }

    /**
     * Displays a form to edit an existing Supply request article entity.
     *
     * @Route("{_locale}/supply-request-cart/{id<\d+>}/edit", methods={"GET", "POST"}, name="supply_request_cart_edit")
     *
     */
    public function editArticleInSupplyRequest(Request $request, SupplyRequestArticleRepository $supplyRequestArticles, SupplyRequestArticle $supplyRequestArticle, ObjectManager $em): Response
    {
        //dump($supplyRequestArticle->getSupply());exit();
        $form = $this->createForm(SupplyRequestArticleType::class, $supplyRequestArticle)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('supply_request_cart');
            }

            return $this->redirectToRoute('supply_request_cart', array('id' => $supplyRequestArticle->getSupply()->getId()));
        }


        /*$form = $this->createFormBuilder($supplyRequestArticle)
            ->add('quantity', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'quantity',
                ),
                'trim' => true,
                'required' => true,
            ))
            ->add('article', EntityType::class, array(
                'class' => Article::class,
                'expanded' => false,
                'choice_label' => 'reference',
                'multiple' => false,
                'placeholder' => 'Choose an article',
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
            ))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $supplyRequestArticle->setSupply($supplyRequest);
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            return $this->redirectToRoute('supply_request_cart', array('id' => $supplyRequestArticle->getId()));
        }*/

        $userSupplyRequestArticles = $supplyRequestArticles->findBy(['supply' => $supplyRequestArticle->getSupply()], ['id' => 'DESC']);

        return $this->render('purchase/cart_supply_request.html.twig', [
            'supplyrequestarticle' => $supplyRequestArticle,
            'form' => $form->createView(),
            'supplyRequestArticles' => $userSupplyRequestArticles,
        ]);
    }

    /**
     * Deletes a supply request article entity.
     *
     * @Route("{_locale}/supply-request-cart/{id<\d+>}/delete", methods={"GET", "POST"}, name="supply_request_cart_delete")
     *
     */
    public function deleteArticleInSupplyRequest(SupplyRequestArticle $supplyRequestArticle, ObjectManager $em): Response
    {
        $em->remove($supplyRequestArticle);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');
        return $this->redirectToRoute('supply_request_cart', array('id' => $supplyRequestArticle->getSupply()->getId()));
    }

    /**
     * Displays a form to edit an existing Cart supply request entity.
     *
     * @Route("{_locale}/supply-request-cart/{id<\d+>}/valid",methods={"GET", "POST"}, name="supply_request_cart_valid")
     *
     */
    public function validSupplyRequestArticle(SupplyRequestArticle $supplyRequestArticle, ObjectManager $em): Response
    {
        $supplyRequestArticle->setIsValid(true);
        $em->flush();

        $this->addFlash('success', 'valid_successfully');
        return $this->redirectToRoute('supply_request_cart', array('id' => $supplyRequestArticle->getSupply()->getId()));
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("{_locale}/supply-request-cart/{id<\d+>}/unvalid", methods={"GET", "POST"}, name="supply_request_cart_reject")
     *
     */
    public function rejectSupplyRequestArticle(SupplyRequestArticle $supplyRequestArticle, ObjectManager $em): Response
    {
        $supplyRequestArticle->setIsValid(false);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('supply_request_cart', array('id' => $supplyRequestArticle->getSupply()->getId()));
    }

    /**
     * Displays a form to reverse an existing Supply request article entity.
     *
     * @Route("/supply-request-cart/{id<\d+>}/reverse", methods={"GET", "POST"}, name="supply_request_cart_reverse")
     *
     */
    public function reverseSupplyRequestArticle(SupplyRequestArticle $supplyRequestArticle, ObjectManager $em): Response
    {
        $supplyRequestArticle->setIsValid(null);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('supply_request_cart', array('id' => $supplyRequestArticle->getSupply()->getId()));
    }


    /**
     * @Route("{_locale}/quotation", methods={"GET", "POST"}, name="quotation")
     * @return Response
     */
    public function showQuotation(QuotationRepository $quotations)
    {
        $userQuotations = $quotations->findBy([], ['id' => 'DESC']);

        return $this->render('purchase/show_quotation.html.twig',
            [
                'quotations' => $userQuotations,
            ]);
    }

    /**
     * Creates a new Quotation entity.
     *
     * @Route("{_locale}/quotation-new", methods={"GET", "POST"}, name="quotation_new")
     *
     */
    public function newQuotation(Request $request, SupplyRequestArticleRepository $supplyArticles, ObjectManager $em): Response
    {
        $quotation = new Quotation();

        /**
         * If there is a supply request link on this quotation
         * Take all supply request articles and assign to quotation articles
         * Else
         * ...
         * EndIf
         */

        // On Instancie le formulaire
        $form = $this->createForm(QuotationType::class, $quotation)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quotation->setCreatedAt(new\DateTime());
            $quotation->setUser($this->getUser());

            $em->persist($quotation);

            // Get supply request id then object
            $supplyRequest = $form->get('supplyRequest')->getData();
            if ($supplyRequest){
                $supplyRequestArticles = $supplyArticles->findBy(['supply'=> $supplyRequest, 'isValid' => '1']);
                //dump($supplyRequestArticles); exit();

                foreach($supplyRequestArticles as $supplyRequestArticle){
                    $quotationArticle = new QuotationArticle();

                    // Prepare object for persistence
                    $quotationArticle->setQuantity($supplyRequestArticle->getQuantity());
                    $quotationArticle->setArticle($supplyRequestArticle->getArticle());
                    $quotationArticle->setQuotation($quotation);
                    $quotationArticle->setCreatedAt(new\DateTime());
                    $quotationArticle->setIsValid(true);
                    //dump($quotationArticle); exit();

                    $em->persist($quotationArticle);
                }
            }

            $em->flush();

            $this->addFlash('success', 'created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('quotation_new');
            }

            return $this->redirectToRoute('quotation');
        }

        return $this->render('purchase/edit_quotation.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Quotation entity.
     *
     * @Route("{_locale}/quotation/{id<\d+>}/edit",methods={"GET", "POST"}, name="quotation_edit")
     *
     */
    public function editQuotation(Request $request, QuotationRepository $quotations, Quotation $quotation, ObjectManager $em): Response
    {
        $form = $this->createForm(QuotationType::class, $quotation)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('quotation_new');
            }

            return $this->redirectToRoute('quotation');
        }
        $userQuotations = $quotations->findBy(['user' => $this->getUser()]);

        return $this->render('purchase/edit_quotation.html.twig', [
            'quotation' => $quotation,
            'form' => $form->createView(),
            'quotations' => $userQuotations,
        ]);
    }

    /**
     * Deletes a Quotation entity.
     *
     * @Route("{_locale}/quotation/{id}/delete", methods={"GET", "POST"}, name="quotation_delete")
     *
     */
    public function deleteQuotation(Quotation $quotation, ObjectManager $em): Response
    {
        $em->remove($quotation);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('quotation');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("/quotation/{id<\d+>}/valid",methods={"GET", "POST"}, name="quotation_valid")
     *
     */
    public function validQuotation(Quotation $quotation, ObjectManager $em): Response
    {
        $quotation->setIsValid(true);
        $em->flush();

        $this->addFlash('success', 'valid_successfully');

        return $this->redirectToRoute('quotation');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("/quotation/{id<\d+>}/unvalid", methods={"GET", "POST"}, name="quotation_reject")
     *
     */
    public function rejectQuotation(Quotation $quotation, ObjectManager $em): Response
    {
        $quotation->setIsValid(false);
        $em->flush();

        $this->addFlash('success', 'successfully');

        return $this->redirectToRoute('quotation');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("/quotation/{id<\d+>}/reverse", methods={"GET", "POST"}, name="quotation_reverse")
     *
     */
    public function reverseQuotation(Quotation $quotation, ObjectManager $em): Response
    {
        $quotation->setIsValid(null);
        $em->flush();

        $this->addFlash('success', 'successfully');

        return $this->redirectToRoute('quotation');
    }

    /**
     * Displays a form to edit an existing Supply request entity.
     *
     * @Route("{_locale}/quotation/{id<\d+>}/cart", methods={"GET", "POST"}, name="quotation_cart")
     *
     */
    public function showCartQuotation(QuotationArticleRepository $quotationArticles, Quotation $quotation)
    {
        $userQuotationArticles = $quotationArticles->findBy(['quotation' => $quotation], ['id' => 'DESC']);
        return $this->render('purchase/show_cart_quotation.html.twig',
            [
                'quotationArticles' => $userQuotationArticles,
                'quotation'=>$quotation
            ]);
    }

    /**
     * Displays a form to edit an existing Quotation Article (the cart of quotation) entity.
     *
     * @Route("{_locale}/quotation/{id<\d+>}/cart-new", methods={"GET", "POST"}, name="quotation_cart_new")
     *
     */
    public function quotationCart(Request $request, Quotation $quotation, QuotationArticleRepository $quotationArticles, ObjectManager $em): Response
    {

        $quotationArticle = new QuotationArticle();

        $form = $this->createForm(QuotationArticleType::class, $quotationArticle)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $quotationArticle->setCreatedAt(new\DateTime());
            $quotationArticle->setQuotation($quotation);
            $quotationArticle->setIsValid(true);
            //voici ou on gere l'ajout de quantite(sans faire de doubons sur le meme article) dans la quotation Article

            $art = $quotationArticles->findBy(['quantity'=> $quotationArticle->getQuantity()]);
            //$article = $form->get('quantity')->getData();
            foreach ($art as $ar){
                if ($quotationArticle->getArticle()->getName())
                {
                    $quotationArticle->setQuantity($quotationArticle->getQuantity() + $ar->getQuantity());
                }
            }



            if($quotationArticle->getPt())
            {
                $quotationArticle->setPu($quotationArticle->getPt() / $quotationArticle->getQuantity());
            }

            elseif ($quotationArticle->getPu())
            {
                $quotationArticle->setPt($quotationArticle->getQuantity() * $quotationArticle->getPu());
            }
            else
            {

            }


            $em->persist($quotationArticle);
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('quotation_cart');
            }

            return $this->redirectToRoute('quotation_cart', array('id' => $quotation->getId()));
        }

        // retour du formulaire et du tableau
        return $this->render('purchase/cart_quotation.html.twig', [
            'form' => $form->createView(),
            'quotation'=>$quotation,
        ]);
    }

    /**
     * Displays a form to Supply request article edit an existing Family entity.
     *
     * @Route("{_locale}/quotation-cart/{id<\d+>}/edit",methods={"GET", "POST"}, name="quotation_cart_edit")
     *
     */
    public function editQuotationCart(Request $request, QuotationArticleRepository $quotationArticles, QuotationArticle $quotationArticle, ObjectManager $em): Response
    {
        $form = $this->createForm(QuotationArticleType::class, $quotationArticle)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('quotation_cart');
            }

            return $this->redirectToRoute('quotation_cart', array('id' => $quotationArticle->getQuotation()->getId()));
        }
        $userQuotationArticles = $quotationArticles->findBy(['quotation' => $quotationArticle->getQuotation()], ['id' => 'DESC']);

        return $this->render('purchase/cart_quotation.html.twig', [
            'quotationArticle' => $quotationArticle,
            'form' => $form->createView(),
            'quotationArticles' => $userQuotationArticles,
        ]);
    }

    /**
     * Deletes a quotation cart entity.
     *
     * @Route("{_locale}/quotation-cart/{id<\d+>}/delete", methods={"GET", "POST"}, name="quotation_cart_delete")
     *
     */
    public function deleteQuotationCart(QuotationArticle $quotationArticle, ObjectManager $em): Response
    {
        $em->remove($quotationArticle);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('quotation_cart', array('id' => $quotationArticle->getQuotation()->getId()));
    }

    /**
     * Displays a form to invalid an existing quotation article entity.
     *
     * @Route("{_locale}/quotation-cart/{id<\d+>}/valid", methods={"GET", "POST"}, name="quotation_cart_valid")
     *
     */
    public function validQuotationArticle(QuotationArticle $quotationArticle, ObjectManager $em): Response
    {
        $quotationArticle->setIsValid(true);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('quotation_cart', array('id' => $quotationArticle->getQuotation()->getId()));
    }

    /**
     * Displays a form to invalid an existing quotation article entity.
     *
     * @Route("{_locale}/quotation-cart/{id<\d+>}/unvalid", methods={"GET", "POST"}, name="quotation_cart_reject")
     *
     */
    public function rejectQuotationArticle(QuotationArticle $quotationArticle, ObjectManager $em): Response
    {
        $quotationArticle->setIsValid(false);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('quotation_cart', array('id' => $quotationArticle->getQuotation()->getId()));
    }

    /**
     * Displays a form to reverse an existing quotation article entity.
     *
     * @Route("{_locale}/quotation-cart/{id<\d+>}/reverse", methods={"GET", "POST"}, name="quotation_cart_reverse")
     *
     */
    public function reverseQuotationArticle(QuotationArticle $quotationArticle, ObjectManager $em): Response
    {
        $quotationArticle->setIsValid(null);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('quotation_cart', array('id' => $quotationArticle->getQuotation()->getId()));
    }

    /**
     * Lists all Quotations Articles.
     *
     * @Route("{_locale}/quotation-article", methods={"GET"}, name="quotation_article")
     *
     */
    public function newAndAllQuotationArticle(Request $request, QuotationArticle $quotationArticle = null, QuotationArticleRepository $quotationArticles,ObjectManager $em):Response
    {
        if(!$quotationArticle){$quotationArticle = new QuotationArticle();}

        // On Instancie le formulaire
        $form = $this->createForm(QuotationArticleType::class, $quotationArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $quotationArticle->setCreatedAt(new\DateTime());
            $quotationArticle->setUser($this->getUser());

            $em->persist($quotationArticle);
            $em->flush();

            $this->addFlash('success', 'created_successfully');

            return $this->redirectToRoute('quotation_article');
        }
        $userQuotationArticles = $quotationArticles->findBy([], ['id' => 'DESC']);

        return $this->render('purchase/show_quotation_article.html.twig', [
            'quotationArticle' => $quotationArticle,
            'form' => $form->createView(),
            'quotationArticles' => $userQuotationArticles,
        ]);
    }

    /**
     * Displays a form to Quotation article edit an existing Family entity.
     *
     * @Route("{_locale}/quotation-article/{id<\d+>}/edit",methods={"GET", "POST"}, name="quotation_article_edit")
     *
     */
    public function editInAllQuotationCart(Request $request, QuotationArticleRepository $quotationArticles, QuotationArticle $quotationArticle, ObjectManager $em): Response
    {
        $form = $this->createForm(QuotationArticleType::class, $quotationArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            return $this->redirectToRoute('quotation_article');
        }
        $userQuotationArticles = $quotationArticles->findBy([], ['id' => 'DESC']);
        //$userSuppliesRequestArticles = $supplyRequestArticle->findBy(['user' => $this->getUser()]);

        return $this->render('purchase/show_quotation_article.html.twig', [
            'quotationArticle' => $quotationArticle,
            'form' => $form->createView(),
            'quotationArticles' => $userQuotationArticles,
        ]);
    }

    /**
     * Deletes a Quotation article entity.
     *
     * @Route("{_locale}/quotation-article/{id}/delete", methods={"GET", "POST"}, name="quotation_article_delete")
     *
     */
    public function deleteInAllQuotationCart(Request $request, QuotationArticle $quotationArticle, ObjectManager $em): Response
    {
        $em->remove($quotationArticle);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('quotation_article');
    }

    /**
     * Displays a form to valid an existing Quotation entity.
     *
     * @Route("{_locale}/quotation-article/{id<\d+>}/valid",methods={"GET", "POST"}, name="quotation_article_valid")
     *
     */
    /*public function validQuotationArticleCart(Request $request, QuotationArticle $quotationArticle, ObjectManager $em): Response
    {
        $quotationArticle->setIsValid(true);
        $em->flush();

        $this->addFlash('success', 'valid_successfully');

        return $this->redirectToRoute('quotation_article');
    }*/

    /**
     * Displays a form to reject an existing Quotation entity.
     *
     * @Route("{_locale}/quotation-article/{id<\d+>}/unvalid", methods={"GET", "POST"}, name="quotation_article_reject")
     *
     */
    /*public function rejectQuotationArticleCart(Request $request, QuotationArticle $quotationArticle, ObjectManager $em): Response
    {
        $quotationArticle->setIsValid(false);
        $em->flush();

        $this->addFlash('success', 'successfully');

        return $this->redirectToRoute('quotation_article');
    }*/





    /**
     * Lists all Supply request Articles.
     *
     * @Route("{_locale}/supply-request-article", methods={"GET"}, name="supply_request_article")
     *
     */
    public function newAndAllArticleCart(Request $request, SupplyRequestArticle $supplyRequestArticle = null, SupplyRequestArticleRepository $suppliesRequestArticles,ObjectManager $em):Response
    {
        if(!$supplyRequestArticle){$supplyRequestArticle = new SupplyRequestArticle();}

        // On Instancie le formulaire
        $form = $this->createForm(SupplyRequestArticleType::class, $supplyRequestArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $supplyRequestArticle->setCreatedAt(new\DateTime());
            $supplyRequestArticle->setUser($this->getUser());

            $em->persist($supplyRequestArticle);
            $em->flush();

            $this->addFlash('success', 'created_successfully');

            return $this->redirectToRoute('supply_request_article');
        }
        $userSuppliesRequestArticles = $suppliesRequestArticles->findBy([], ['id' => 'DESC']);

        return $this->render('purchase/show_supply_request_article.html.twig', [
            'supplyRequestArticle' => $supplyRequestArticle,
            'form' => $form->createView(),
            'suppliesRequestArticles' => $userSuppliesRequestArticles,
        ]);
    }

    /**
     * Displays a form to Supply request article edit an existing Family entity.
     *
     * @Route("{_locale}/supply-request-article/{id<\d+>}/edit",methods={"GET", "POST"}, name="supply_request_article_edit")
     *
     */
    public function editInAllArticleCart(Request $request, SupplyRequestArticleRepository $suppliesRequestArticles, SupplyRequestArticle $supplyRequestArticle, ObjectManager $em): Response
    {
        $form = $this->createForm(SupplyRequestArticleType::class, $supplyRequestArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            return $this->redirectToRoute('supply_request_article');
        }
        $userSuppliesRequestArticles = $suppliesRequestArticles->findBy([], ['id' => 'DESC']);
        //$userSuppliesRequestArticles = $supplyRequestArticle->findBy(['user' => $this->getUser()]);

        return $this->render('purchase/show_supply_request_article.html.twig', [
            'supplyRequestArticle' => $supplyRequestArticle,
            'form' => $form->createView(),
            'suppliesRequestArticles' => $userSuppliesRequestArticles,
        ]);
    }


    /**
     * Deletes a Supply request article entity.
     *
     * @Route("{_locale}/supply-request-article/{id}/delete", methods={"GET", "POST"}, name="supply_request_article_delete")
     *
     */
    public function deleteInAllArticleCart(SupplyRequestArticle $supplyRequestArticle, ObjectManager $em): Response
    {
        $em->remove($supplyRequestArticle);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('supply_request_article');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("{_locale}/supply-request-article/{id<\d+>}/valid",methods={"GET", "POST"}, name="supply_request_article_valid")
     *
     */
    public function validSupplyRequestArticleCart(SupplyRequestArticle $supplyRequestArticle, ObjectManager $em): Response
    {
        $supplyRequestArticle->setIsValid(true);
        $em->flush();

        $this->addFlash('success', 'valid_successfully');

        return $this->redirectToRoute('supply_request_article');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("{_locale}/supply-request-article/{id<\d+>}/unvalid", methods={"GET", "POST"}, name="supply_request_article_reject")
     *
     */
    public function rejectSupplyRequestArticleCart(Request $request, SupplyRequestArticle $supplyRequestArticle, ObjectManager $em): Response
    {
        $supplyRequestArticle->setIsValid(false);
        $em->flush();

        $this->addFlash('success', 'successfully');

        return $this->redirectToRoute('supply_request_article');
    }


    /**
     * @Route("{_locale}/delevery", methods={"GET", "POST"}, name="delevery")
     */
    public function showDelevery(DeleveryRepository $deleveries)
    {
        $userDeleveries = $deleveries->findBy([], ['id' => 'DESC']);
        return $this->render('purchase/show_delevery.html.twig',
            [
                'deleveries'=>$userDeleveries
            ]);
    }

    /**
     * Creates a new Delevery entity.
     *
     * @Route("{_locale}/delevery-new", methods={"GET", "POST"}, name="delevery_new")
     *
     */
    public function newDelevery(Request $request, OrderArticleRepository $orderArts,ObjectManager $em): Response
    {
        $delevery = new Delevery();

        // On Instancie le formulaire
        $form = $this->createForm(DeleveryType::class, $delevery)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $delevery->setCreatedAt(new\DateTime());
            //$delevery->setUser($this->getUser());

            $em->persist($delevery);

            // Get order id then object
            $order = $form->get('commande')->getData();
            if ($order){
                $delevery->setProvider($delevery->getCommande()->getProvider());

                $orderArticles = $orderArts->findBy(['commande'=> $order, 'isValid' => '1']);
                //dump($supplyRequestArticles); exit();

                foreach($orderArticles as $orderArticle){
                    $deleverArticle = new DeleveryArticle();

                    // Prepare object for persistence
                    $deleverArticle->setQuantity($orderArticle->getQuantity());
                    $deleverArticle->setArticle($orderArticle->getArticle());
                    $deleverArticle->setDelevery($delevery);
                    $deleverArticle->setCreatedAt(new\DateTime());
                    $deleverArticle->setIsValid(true);

                    $em->persist($deleverArticle);
                }
            }

            $em->flush();

            $this->addFlash('success', 'created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('delevery_new');
            }

            return $this->redirectToRoute('delevery');
        }

        return $this->render('purchase/edit_delevery.html.twig', [
            'delevery' => $delevery,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Order entity.
     *
     * @Route("{_locale}/delevery/{id<\d+>}/edit",methods={"GET", "POST"}, name="delevery_edit")
     *
     */
    public function editDelevery(Request $request, DeleveryRepository $deleveries, Delevery $delevery, ObjectManager $em): Response
    {
        $form = $this->createForm(DeleveryType::class, $delevery)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('delevery_new');
            }

            return $this->redirectToRoute('delevery');
        }
        $userDeleveries = $deleveries->findBy([], ['id' => 'DESC']);

        return $this->render('purchase/edit_delevery.html.twig', [
            'delevery' => $delevery,
            'form' => $form->createView(),
            'deleveries' => $userDeleveries,
        ]);
    }

    /**
     * Deletes a Order entity.
     *
     * @Route("{_locale}/delevery/{id}/delete", methods={"GET", "POST"}, name="delevery_delete")
     *
     */
    public function deleteDelevery(Request $request,Delevery $delevery, ObjectManager $em): Response
    {
        $em->remove($delevery);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('delevery');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("/delevery/{id<\d+>}/valid",methods={"GET", "POST"}, name="delevery_valid")
     *
     */
    public function validDelevery(Request $request, Delevery $delevery, ObjectManager $em): Response
    {
        $delevery->setIsvalid(true);
        $em->flush();

        $this->addFlash('success', 'valid_successfully');

        return $this->redirectToRoute('delevery');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("/delevery/{id<\d+>}/unvalid", methods={"GET", "POST"}, name="delevery_reject")
     *
     */
    public function rejectDelevery(Request $request, Delevery $delevery, ObjectManager $em): Response
    {
        $delevery->setIsvalid(false);
        $em->flush();

        $this->addFlash('success', 'successfully');

        return $this->redirectToRoute('delevery');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("/delevery/{id<\d+>}/reverse", methods={"GET", "POST"}, name="delevery_reverse")
     *
     */
    public function reverseDelevery(Delevery $delevery, ObjectManager $em): Response
    {
        $delevery->setIsvalid(null);
        $em->flush();

        $this->addFlash('success', 'successfully');

        return $this->redirectToRoute('delevery');
    }


    /**
     * Displays a form to edit an existing Supply request entity.
     *
     * @Route("{_locale}/delevery/{id<\d+>}/cart", methods={"GET", "POST"}, name="delevery_cart")
     *
     */
    public function showCartDelevery(DeleveryArticleRepository $deleveriesArticles, Delevery $delevery)
    {
        // repository pour l'affichage du tableau
        $userDeleveryArticles = $deleveriesArticles->findBy(['delevery' => $delevery], ['id' => 'DESC']);
        return $this->render('purchase/show_cart_delevery.html.twig',
            [
                'deleveriesArticles' => $userDeleveryArticles,
                'delevery'=>$delevery
            ]);
    }

    /**
     * Displays a form to edit an existing delevery Article (the cart of delevery) entity.
     *
     * @Route("{_locale}/delevery/{id<\d+>}/cart-new", methods={"GET", "POST"}, name="delevery_cart_new")
     *
     */
    public function deleveryCart(Request $request, Delevery $delevery, ObjectManager $em): Response
    {

        $deleveryArticle = new DeleveryArticle();

        $form = $this->createForm(DeleveryArticleType::class, $deleveryArticle)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $deleveryArticle->setCreatedAt(new\DateTime());
            $deleveryArticle->setDelevery($delevery);
            $deleveryArticle->setIsValid(true);

            if($deleveryArticle->getPt())
            {
                $deleveryArticle->setPu($deleveryArticle->getPt() / $deleveryArticle->getQuantity());
            }

            elseif ($deleveryArticle->getPu())
            {
                $deleveryArticle->setPt($deleveryArticle->getQuantity() * $deleveryArticle->getPu());
            }
            else
            {

            }

            $em->persist($deleveryArticle);
            $em->flush();


            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('delevery_cart');
            }


            return $this->redirectToRoute('delevery_cart', array('id' => $delevery->getId()));
        }

        // retour du formulaire et du tableau
        return $this->render('purchase/cart_delevery.html.twig', [
            'form' => $form->createView(),
            'delevery'=>$delevery
        ]);
    }

    /**
     * Displays a form to Supply request article edit an existing Family entity.
     *
     * @Route("{_locale}/delevery-cart/{id<\d+>}/edit",methods={"GET", "POST"}, name="delevery_cart_edit")
     *
     */
    public function editDeleveryCart(Request $request, DeleveryArticleRepository $deleveriesArticles, DeleveryArticle $deleveryArticle, ObjectManager $em): Response
    {
        $form = $this->createForm(DeleveryArticleType::class, $deleveryArticle)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('delevery_cart');
            }

            return $this->redirectToRoute('delevery_cart', array('id' => $deleveryArticle->getDelevery()->getId()));
        }
        $userDeleveryArticles = $deleveriesArticles->findBy(['delevery' => $deleveryArticle->getDelevery()], ['id' => 'DESC']);

        return $this->render('purchase/cart_delevery.html.twig', [
            'deleveryArticle' => $deleveryArticle,
            'form' => $form->createView(),
            'deleveriesArticles' => $userDeleveryArticles
        ]);
    }

    /**
     * Deletes a delevery cart entity.
     *
     * @Route("{_locale}/delevery-cart/{id<\d+>}/delete", methods={"GET", "POST"}, name="delevery_cart_delete")
     *
     */
    public function deleteDeleveryCart(Request $request,DeleveryArticle $deleveryArticle, ObjectManager $em): Response
    {
        $em->remove($deleveryArticle);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('delevery_cart', array('id' => $deleveryArticle->getDelevery()->getId()));
    }

    /**
     * Displays a form to invalid an existing quotation article entity.
     *
     * @Route("{_locale}/delevery-cart/{id<\d+>}/valid", methods={"GET", "POST"}, name="delevery_cart_valid")
     *
     */
    public function validDeleveryCart(DeleveryArticle $deleveryArticle, ObjectManager $em): Response
    {
        $deleveryArticle->setIsValid(true);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('delevery_cart', array('id' => $deleveryArticle->getDelevery()->getId()));
    }

    /**
     * Displays a form to invalid an existing delivery article entity.
     *
     * @Route("{_locale}/delevery-cart/{id<\d+>}/unvalid", methods={"GET", "POST"}, name="delevery_cart_reject")
     *
     */
    public function rejectDeleveryCart(DeleveryArticle $deleveryArticle, ObjectManager $em): Response
    {
        $deleveryArticle->setIsValid(false);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('delevery_cart', array('id' => $deleveryArticle->getDelevery()->getId()));
    }

    /**
     * Displays a form to reverse an existing delivery article entity.
     *
     * @Route("{_locale}/delevery-cart/{id<\d+>}/reverse", methods={"GET", "POST"}, name="delevery_cart_reverse")
     *
     */
    public function reverseDeleveryCart(DeleveryArticle $deleveryArticle, ObjectManager $em): Response
    {
        $deleveryArticle->setIsValid(null);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('delevery_cart', array('id' => $deleveryArticle->getDelevery()->getId()));
    }

    /**
     * Displays a form to reverse an existing delivery article entity.
     *
     * @Route("{_locale}/delevery-cart/{id<\d+>}/stock", methods={"GET", "POST"}, name="delevery_cart_stock")
     *
     */
    public function stockDeleveryCart(DeleveryArticle $deleveryArticle, ObjectManager $em): Response
    {
        $stock = new Stock();
        $stock->setArticle($deleveryArticle->getArticle());
        $stock->setQuantity($deleveryArticle->getQuantity());
        $stock->setStockprice($deleveryArticle->getPt());
        $stock->setCreatedAt($deleveryArticle->getCreatedAt());
        $stock->setStockAt($deleveryArticle->getCreatedAt());
        $stock->setUser($this->getUser());

        /*$deleveryArticle->getStockage()->setArticle($deleveryArticle->getArticle());
        $deleveryArticle->getStockage()->setQuantity($deleveryArticle->getQuantity());
        $deleveryArticle->getStockage()->setStockprice($deleveryArticle->getPt());
        $deleveryArticle->getStockage()->setStockAt($deleveryArticle->getCreatedAt());*/

        $em->persist($stock);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('delevery_cart', array('id' => $deleveryArticle->getDelevery()->getId()));
    }

    /**
     * @Route("{_locale}/order", methods={"GET", "POST"}, name="order")
     * @return Response
     */
    public function showOder(OrderRepository $orders)
    {
        $userOrders = $orders->findBy([], ['id' => 'DESC']);

        return $this->render('purchase/show_order.html.twig',
            [
                'orders' => $userOrders
            ]);
    }

    /**
     * Creates a new Order entity.
     *
     * @Route("{_locale}/order-new", methods={"GET", "POST"}, name="order_new")
     *
     */
    public function newOrder(Request $request, QuotationArticleRepository $quotationArts, ObjectManager $em): Response
    {
        $order = new Order();

        // On Instancie le formulaire
        $form = $this->createForm(OrderType::class, $order)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $order->setCreatedAt(new\DateTime());
            $order->setUser($this->getUser());

            $em->persist($order);


            // Get quotation id then object
            $quotation = $form->get('quotation')->getData();
            if ($quotation){
                $order->setProvider($order->getQuotation()->getProvider());
                $quotationArticles = $quotationArts->findBy(['quotation'=> $quotation, 'isValid' => '1']);
                //dump($supplyRequestArticles); exit();

                foreach($quotationArticles as $quotationArticle){
                    $orderArticle = new OrderArticle();

                    // Prepare object for persistence
                    $orderArticle->setQuantity($quotationArticle->getQuantity());
                    $orderArticle->setArticle($quotationArticle->getArticle());
                    $orderArticle->setCommande($order);
                    $orderArticle->setCreatedAt(new\DateTime());
                    $orderArticle->setIsValid(true);

                    $em->persist($orderArticle);
                }
            }

            $em->flush();

            $this->addFlash('success', 'created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('order_new');
            }

            return $this->redirectToRoute('order');
        }

        return $this->render('purchase/edit_order.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Order entity.
     *
     * @Route("{_locale}/order/{id<\d+>}/edit",methods={"GET", "POST"}, name="order_edit")
     *
     */
    public function editOrder(Request $request, OrderRepository $orders, Order $order, ObjectManager $em): Response
    {
        $form = $this->createForm(OrderType::class, $order)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('order_new');
            }

            return $this->redirectToRoute('order');
        }
        $userOders = $orders->findBy(['user' => $this->getUser()]);

        return $this->render('purchase/edit_order.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
            'orders' => $userOders,
        ]);
    }

    /**
     * Deletes a Order entity.
     *
     * @Route("{_locale}/order/{id}/delete", methods={"GET", "POST"}, name="order_delete")
     *
     */
    public function deleteOrder(Request $request, Order $order, ObjectManager $em): Response
    {
        $em->remove($order);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('order');
    }

    /**
     * Displays a form to Valid an existing Order entity.
     *
     * @Route("{_locale}/order/{id<\d+>}/valid",methods={"GET", "POST"}, name="order_valid")
     *
     */
    public function validOrder(Request $request, Order $order, ObjectManager $em): Response
    {
        $order->setIsvalid(true);
        $em->flush();

        $this->addFlash('success', 'valid_successfully');

        return $this->redirectToRoute('order');
    }

    /**
     * Displays a form to Reject an existing Order entity.
     *
     * @Route("{_locale}/order/{id<\d+>}/unvalid", methods={"GET", "POST"}, name="order_unvalid")
     *
     */
    public function rejectOder(Order $order, ObjectManager $em): Response
    {
        $order->setIsvalid(false);
        $em->flush();

        $this->addFlash('success', 'successfully');

        return $this->redirectToRoute('order');
    }

    /**
     * Displays a form to invalid an existing order article entity.
     *
     * @Route("{_locale}/order/{id<\d+>}/reverse", methods={"GET", "POST"}, name="order_reverse")
     *
     */
    public function reverseOrder(Order $order, ObjectManager $em): Response
    {
        $order->setIsValid(null);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('order');
    }

    /**
     * Displays a form to edit an existing Supply request entity.
     *
     * @Route("{_locale}/order/{id<\d+>}/cart", methods={"GET", "POST"}, name="order_cart")
     *
     */
    public function showCartOrder(OrderArticleRepository $orderArticles, Order $order)
    {
        // repository pour l'affichage du tableau
        $userOrderArticles = $orderArticles->findBy(['commande' => $order], ['id' => 'DESC']);
        return $this->render('purchase/show_cart_order.html.twig',
            [
                'orderArticles' => $userOrderArticles,
                'commande' => $order
            ]);
    }

    /**
     * Displays a form to edit an existing delevery Article (the cart of delevery) entity.
     *
     * @Route("{_locale}/order/{id<\d+>}/cart-new", methods={"GET", "POST"}, name="order_cart_new")
     *
     */
    public function orderCart(Request $request, Order $order, ObjectManager $em): Response
    {

        $orderArticle = new OrderArticle();


        $form = $this->createForm(OrderArticleType::class, $orderArticle)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $orderArticle->setCreatedAt(new\DateTime());
            $orderArticle->setCommande($order);
            $orderArticle->setIsValid(true);

            if($orderArticle->getPt())
            {
                $orderArticle->setPu($orderArticle->getPt() / $orderArticle->getQuantity());
            }

            elseif ($orderArticle->getPu())
            {
                $orderArticle->setPt($orderArticle->getQuantity() * $orderArticle->getPu());
            }
            else
            {

            }

            $em->persist($orderArticle);
            $em->flush();


            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('order_cart');
            }


            return $this->redirectToRoute('order_cart', array('id' => $order->getId()));
        }

        // retour du formulaire et du tableau
        return $this->render('purchase/cart_order.html.twig', [
            'form' => $form->createView(),
            'commande'=>$order
        ]);
    }

    /**
     * Displays a form to Supply request article edit an existing Order Article entity.
     *
     * @Route("{_locale}/order-cart/{id<\d+>}/edit",methods={"GET", "POST"}, name="order_cart_edit")
     *
     */
    public function editOrderCart(Request $request, OrderArticleRepository $orderArticles, OrderArticle $orderArticle, ObjectManager $em): Response
    {
        $form = $this->createForm(OrderArticleType::class, $orderArticle)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('order_cart');
            }

            return $this->redirectToRoute('order_cart', array('id' => $orderArticle->getCommande()->getId()));
        }
        $userOrderArticles = $orderArticles->findBy(['commande' => $orderArticle->getCommande()], ['id' => 'DESC']);

        return $this->render('purchase/cart_order.html.twig', [
            'orderArticle' => $orderArticle,
            'form' => $form->createView(),
            'orderArticles' => $userOrderArticles,
        ]);
    }

    /**
     * Deletes a delevery cart entity.
     *
     * @Route("{_locale}/order-cart/{id<\d+>}/delete", methods={"GET", "POST"}, name="order_cart_delete")
     *
     */
    public function deleteOrderCart(OrderArticle $orderArticle, ObjectManager $em): Response
    {
        $em->remove($orderArticle);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('order_cart', array('id' => $orderArticle->getCommande()->getId()));
    }

    /**
     * Displays a form to valid an existing order article entity.
     *
     * @Route("{_locale}/order-cart/{id<\d+>}/valid", methods={"GET", "POST"}, name="order_cart_valid")
     *
     */
    public function validOrderArticle(OrderArticle  $orderArticle, ObjectManager $em): Response
    {
        $orderArticle->setIsValid(true);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('order_cart', array('id' => $orderArticle->getCommande()->getId()));
    }

    /**
     * Displays a form to invalid an existing order article entity.
     *
     * @Route("{_locale}/order-cart/{id<\d+>}/unvalid", methods={"GET", "POST"}, name="order_cart_reject")
     *
     */
    public function rejectOrderArticle(OrderArticle $orderArticle, ObjectManager $em): Response
    {
        $orderArticle->setIsValid(false);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('order_cart', array('id' => $orderArticle->getCommande()->getId()));
    }

    /**
     * Displays a form to reverse an existing order article entity.
     *
     * @Route("{_locale}/order-cart/{id<\d+>}/reverse", methods={"GET", "POST"}, name="order_cart_reverse")
     *
     */
    public function reverseOrderArticle(OrderArticle $orderArticle, ObjectManager $em): Response
    {
        $orderArticle->setIsValid(null);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('order_cart', array('id' => $orderArticle->getCommande()->getId()));
    }

    /**
     * Creates a new Invoice entity.
     *
     * @Route("{_locale}/invoice", methods={"GET", "POST"}, name="invoice")
     *
     */
    public function showInvoice(InvoiceRepository $invoices)
    {
        $userInvoices = $invoices->findBy([], ['id' => 'DESC']);
        return $this->render('purchase/show_invoice.html.twig',
            [
                'invoices' => $userInvoices
            ]);
    }

    /**
     * Creates a new Invoice entity.
     *
     * @Route("{_locale}/invoice-new", methods={"GET", "POST"}, name="invoice_new")
     *
     */
    public function newInvoice(Request $request, Invoice $invoice = null, ObjectManager $em): Response
    {
        if(!$invoice){$invoice = new Invoice();}

        // On Instancie le formulaire
        $form = $this->createForm(InvoiceType::class, $invoice)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $invoice->setCreatedAt(new\DateTime());
            $invoice->setIsValid(true);
            $invoice->setIsLock(true);
            //$delevery->setUser($this->getUser());

            $em->persist($invoice);
            $em->flush();

            $this->addFlash('success', 'created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('invoice_new');
            }

            return $this->redirectToRoute('invoice');
        }

        return $this->render('purchase/edit_invoice.html.twig', [
            'invoice' => $invoice,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Order entity.
     *
     * @Route("{_locale}/invoice/{id<\d+>}/edit",methods={"GET", "POST"}, name="invoice_edit")
     *
     */
    public function editInvoice(Request $request, InvoiceRepository $invoices, Invoice $invoice, ObjectManager $em): Response
    {
        $form = $this->createForm(InvoiceType::class, $invoice)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('invoice_new');
            }

            return $this->redirectToRoute('invoice');
        }
        $userInvoices = $invoices->findBy([], ['id' => 'DESC']);

        return $this->render('purchase/edit_invoice.html.twig', [
            'invoice' => $invoice,
            'form' => $form->createView(),
            'invoices' => $userInvoices,
        ]);
    }

    /**
     * Deletes a Invoice entity.
     *
     * @Route("{_locale}/invoice/{id}/delete", methods={"GET", "POST"}, name="invoice_delete")
     *
     */
    public function deleteInvoice(Invoice $invoice, ObjectManager $em): Response
    {
        $em->remove($invoice);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('invoice');
    }

    /**
     * Displays a form to edit an existing Invoice entity.
     *
     * @Route("/invoice/{id<\d+>}/valid",methods={"GET", "POST"}, name="invoice_valid")
     *
     */
    public function validInvoice(Invoice $invoice, ObjectManager $em): Response
    {
        $invoice->setIsValid(true);
        $em->flush();

        $this->addFlash('success', 'valid_successfully');

        return $this->redirectToRoute('invoice');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("/invoice/{id<\d+>}/unvalid", methods={"GET", "POST"}, name="invoice_reject")
     *
     */
    public function rejectInvoice(Invoice $invoice, ObjectManager $em): Response
    {
        $invoice->setIsValid(false);
        $em->flush();

        $this->addFlash('success', 'successfully');

        return $this->redirectToRoute('invoice');
    }

    /**
     * Displays a form to edit an existing Invoice entity.
     *
     * @Route("/invoice/{id<\d+>}/lock",methods={"GET", "POST"}, name="invoice_lock")
     *
     */
    public function lockInvoice(Invoice $invoice, ObjectManager $em): Response
    {
        $invoice->setIsLock(false);
        $em->flush();

        $this->addFlash('success', 'valid_successfully');

        return $this->redirectToRoute('invoice');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("/invoice/{id<\d+>}/unlock", methods={"GET", "POST"}, name="invoice_unlock")
     *
     */
    public function unlockInvoice(Invoice $invoice, ObjectManager $em): Response
    {
        $invoice->setIsLock(true);
        $em->flush();

        $this->addFlash('success', 'successfully');

        return $this->redirectToRoute('invoice');
    }

    /**
     *
     * @Route("{_locale}/invoice/{id<\d+>}/settlement", methods={"GET", "POST"}, name="invoice_settle")
     */
    public function showSettlement(SettlementRepository $settlements, Invoice $invoice)
    {
        // repository pour l'affichage du tableau
        $userSettlements = $settlements->findBy(['invoice' => $invoice], ['id' => 'DESC']);
        return $this->render('purchase/show_settlement.html.twig',
            [
                'invoice' => $invoice,
                'settlements' => $userSettlements
            ]);
    }

    /**
     *
     * @Route("{_locale}/invoice/{id<\d+>}/settlement/new", methods={"GET", "POST"}, name="invoice_settle_new")
     */
    public function editSettlement(Request $request, CashDeskRepository $cash, Invoice $invoice, ObjectManager $em):Response
    {
        $settlement = new Settlement();


        $form = $this->createForm(SettlementType::class, $settlement)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $settlement->setCreatedAt(new\DateTime());
            $settlement->setInvoice($invoice);
            $settlement->setAmount($invoice->getAmountTva());
            $settlement->setIsApproved(true);
            $settlement->setUser($this->getUser());

            $conUser = $this->getUser();


            $bank = $form->get('bank')->getData();
            //$amount = $form->get('amount')->getData();
            //$tva = $form->get('tva')->getData();
            //$bankSrc = $banks->findOneBy(['id' => $bSrc]);
            if (!$bank)
            {
                $cashDesk = $cash->findOneBy(['user' => $conUser]);
                if (!$cashDesk) {
                    $this->addFlash('warning', 'No cash desk assigned to you!');
                } /*elseif (!(($cashDesk->getBranch()) == $branch)) {
                    $this->addFlash('warning', 'This cash desk is not configured in your branch!');
                }*/ elseif (!$cashDesk->getIsOpen()) {
                    $this->addFlash('warning', 'Your cash desk is not open!');
                } elseif ((($cashDesk->getBalance()) < ($settlement->getAmount() + $settlement->getTva()))) {
                    $this->addFlash('warning', 'Your cash balance is insufficient!');
                } elseif (!$cashDesk->getAccount()) {
                    $this->addFlash('warning', 'Cash desk account not configured!');
                } elseif (!$cashDesk->getJournal()) {

                    $this->addFlash('warning', 'Cash desk journal not configured!');
                } else {
                    //

                }

                $settlement->setCashier($cashDesk);

            }

            $em->persist($settlement);
            $em->flush();


            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('invoice_settle_new');
            }

            return $this->redirectToRoute('invoice_settle', array('id' => $invoice->getId()));
        }

        return $this->render('purchase/new_settlement.html.twig', [
            'form' => $form->createView(),
            'invoice' => $invoice,
            //'settlement' => $settlements
        ]);

    }


    /**
     * Deletes a Settlement entity.
     *
     * @Route("{_locale}/invoice-settle/{id<\d+>}}/delete", methods={"GET", "POST"}, name="invoice_settle_delete")
     *
     */
    public function deleteSettlement(Request $request, Settlement $settlement, ObjectManager $em): Response
    {
        $em->remove($settlement);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('invoice_settle');
    }


    /**
     * Displays a form to edit an existing invoice Article (the cart of invoice) entity.
     *
     * @Route("{_locale}/invoice/{id<\d+>}/cart", methods={"GET", "POST"}, name="invoice_cart")
     *
     */
    public function invoiceCart(Request $request, Delevery $delevery, DeleveryArticleRepository $deleveriesArticles, DeleveryArticle $deleveryArticle = null, ObjectManager $em): Response
    {

        if(!$deleveryArticle) {
            $deleveryArticle = new DeleveryArticle();
        }

        $form = $this->createForm(DeleveryArticleType::class, $deleveryArticle)
                    ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $deleveryArticle->setCreeatedAt(new\DateTime());
            $deleveryArticle->setDelevery($delevery);

            $em->persist($deleveryArticle);
            $em->flush();


            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('invoice_cart');
            }


            return $this->redirectToRoute('delevery_cart', array('id' => $delevery->getId()));
        }


        // repository pour l'affichage du tableau
        $userDeleveryArticles = $deleveriesArticles->findBy(['delevery' => $delevery], ['id' => 'DESC']);

        // retour du formulaire et du tableau
        return $this->render('purchase/cart_delevery.html.twig', [
            'deleveryarticle' => $deleveryArticle,
            'form' => $form->createView(),
            'deleveriesArticles' => $userDeleveryArticles,
            'delevery'=>$delevery
        ]);
    }

    /**
     * Displays a form to Supply request article edit an existing Family entity.
     *
     * @Route("{_locale}/invoice-cart/{id<\d+>}/edit",methods={"GET", "POST"}, name="invoice_cart_edit")
     *
     */
    public function editInvoiceCart(Request $request, DeleveryArticleRepository $deleveriesArticles, DeleveryArticle $deleveryArticle, ObjectManager $em): Response
    {
        $form = $this->createForm(DeleveryArticleType::class, $deleveryArticle)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('invoice_cart');
            }

            return $this->redirectToRoute('delevery_cart', array('id' => $deleveryArticle->getDelevery()->getId()));
        }
        $userDeleveryArticles = $deleveriesArticles->findBy(['delevery' => $deleveryArticle->getDelevery()], ['id' => 'DESC']);

        return $this->render('purchase/cart_delevery.html.twig', [
            'deleveryArticle' => $deleveryArticle,
            'form' => $form->createView(),
            'deleveriesArticles' => $userDeleveryArticles,
        ]);
    }

    /**
     * Deletes a delevery cart entity.
     *
     * @Route("{_locale}/invoice-cart/{id<\d+>}/delete", methods={"GET", "POST"}, name="invoice_cart_delete")
     *
     */
    public function deleteInvoiceCart(Request $request,DeleveryArticle $deleveryArticle, ObjectManager $em): Response
    {
        $em->remove($deleveryArticle);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('delevery_cart', array('id' => $deleveryArticle->getDelevery()->getId()));
    }


    /**
     * Creates a new Order entity.
     *
     * @Route("{_locale}/invoice", methods={"GET", "POST"}, name="invoice")
     *
     */
    /*public function newSettlment(Request $request, SettlementRepository $settlements, Settlement $settlement = null, ObjectManager $em): Response
    {
        if(!$settlement){$settlement = new Settlement();}

        // On Instancie le formulaire
        $form = $this->createForm(DeleveryType::class, $settlement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $settlement->setCreatedAt(new\DateTime());
            //$delevery->setUser($this->getUser());

            $em->persist($settlement);
            $em->flush();

            $this->addFlash('success', 'created_successfully');

            return $this->redirectToRoute('settlement');
        }
        $userSettlements = $settlements->findBy([], ['id' => 'DESC']);

        return $this->render('purchase/edit_settlement.html.twig', [
            'settlement' => $settlement,
            'form' => $form->createView(),
            'settlements' => $userSettlements,
        ]);
    }*/

    /**
     * Displays a form to edit an existing Order entity.
     *
     * @Route("{_locale}/settlement/{id<\d+>}/edit",methods={"GET", "POST"}, name="settlement_edit")
     *
     */
    /*public function editSettlement(Request $request, SettlementRepository $settlements, Settlement $settlement, ObjectManager $em): Response
    {
        $form = $this->createForm(DeleveryType::class, $settlement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            return $this->redirectToRoute('invoice');
        }
        $userSettlements = $settlements->findBy([], ['id' => 'DESC']);

        return $this->render('purchase/edit_settlement.html.twig', [
            'settlement' => $settlement,
            'form' => $form->createView(),
            'settlements' => $userSettlements,
        ]);
    }*/


    /**
     * Displays a form to edit an existing Invoice entity.
     *
     * @Route("/settlement/{id<\d+>}/valid",methods={"GET", "POST"}, name="settlement_valid")
     *
     */
    public function validSettlement(Request $request, Settlement $settlement, ObjectManager $em): Response
    {
        $settlement->setStatus(true);
        $em->flush();

        $this->addFlash('success', 'valid_successfully');

        return $this->redirectToRoute('settlement');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("/settlement/{id<\d+>}/unvalid", methods={"GET", "POST"}, name="settlement_reject")
     *
     */
    public function rejectSettlement(Request $request, Settlement $settlement, ObjectManager $em): Response
    {
        $settlement->setStatus(false);
        $em->flush();

        $this->addFlash('success', 'successfully');

        return $this->redirectToRoute('settlement');
    }

    /**
     * Displays a form to edit an existing invoice Article (the cart of invoice) entity.
     *
     * @Route("{_locale}/settlement/{id<\d+>}/cart", methods={"GET", "POST"}, name="settlement_cart")
     *
     */
    public function settlementCart(Request $request, Delevery $delevery, DeleveryArticleRepository $deleveriesArticles, DeleveryArticle $deleveryArticle = null, ObjectManager $em): Response
    {

        if(!$deleveryArticle) {
            $deleveryArticle = new DeleveryArticle();
        }

        $form = $this->createForm(DeleveryArticleType::class, $deleveryArticle);
        //->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $deleveryArticle->setCreeatedAt(new\DateTime());
            $deleveryArticle->setDelevery($delevery);

            $em->persist($deleveryArticle);
            $em->flush();


            $this->addFlash('success', 'updated_successfully');

            //if ($form->get('saveAndCreateNew')->isClicked()) {
            //    return $this->redirectToRoute('delevery_cart', array('id' => $delevery->getId()));
            //}


            return $this->redirectToRoute('delevery_cart', array('id' => $delevery->getId()));
        }


        // repository pour l'affichage du tableau
        $userDeleveryArticles = $deleveriesArticles->findBy(['delevery' => $delevery], ['id' => 'DESC']);

        // retour du formulaire et du tableau
        return $this->render('purchase/cart_delevery.html.twig', [
            'deleveryarticle' => $deleveryArticle,
            'form' => $form->createView(),
            'deleveriesArticles' => $userDeleveryArticles,
            'delevery'=>$delevery
        ]);
    }

    /**
     * Displays a form to Supply request article edit an existing Family entity.
     *
     * @Route("{_locale}/settlement-cart/{id<\d+>}/edit",methods={"GET", "POST"}, name="settlement_cart_edit")
     *
     */
    public function editSettlementCart(Request $request, DeleveryArticleRepository $deleveriesArticles, DeleveryArticle $deleveryArticle, ObjectManager $em): Response
    {
        $form = $this->createForm(DeleveryArticleType::class, $deleveryArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            return $this->redirectToRoute('delevery_cart', array('id' => $deleveryArticle->getDelevery()->getId()));
        }
        $userDeleveryArticles = $deleveriesArticles->findBy(['delevery' => $deleveryArticle->getDelevery()], ['id' => 'DESC']);

        return $this->render('purchase/cart_delevery.html.twig', [
            'deleveryArticle' => $deleveryArticle,
            'form' => $form->createView(),
            'deleveriesArticles' => $userDeleveryArticles,
        ]);
    }

    /**
     * Deletes a settlement cart entity.
     *
     * @Route("{_locale}/settlement-cart/{id<\d+>}/delete", methods={"GET", "POST"}, name="settlement_cart_delete")
     *
     */
    public function deleteSettlementCart(Request $request,DeleveryArticle $deleveryArticle, ObjectManager $em): Response
    {
        $em->remove($deleveryArticle);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('delevery_cart', array('id' => $deleveryArticle->getDelevery()->getId()));
    }

}
