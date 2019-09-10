<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\SalesDelevery;
use App\Entity\SalesDeleveryArticle;
use App\Entity\SalesInvoice;
use App\Entity\SalesOrder;
use App\Entity\SalesOrderArticle;
use App\Entity\SalesQuotation;
use App\Entity\SalesQuotationArticle;
use App\Entity\SalesSettlement;
use App\Entity\SupplyRequest;
use App\Entity\SupplyRequestArticle;
use App\Form\SalesDeleveryArticleType;
use App\Form\SalesDeleveryType;
use App\Form\SalesInvoiceType;
use App\Form\SalesOrderArticleType;
use App\Form\SalesOrderType;
use App\Form\SalesQuotationArticleType;
use App\Form\SalesQuotationType;
use App\Form\SalesSettlementType;
use App\Form\SupplyRequestType;
use App\Form\SupplyRequestArticleType;
use App\Repository\SalesDeleveryArticleRepository;
use App\Repository\SalesDeleveryRepository;
use App\Repository\SalesInvoiceRepository;
use App\Repository\SalesOrderArticleRepository;
use App\Repository\SalesOrderRepository;
use App\Repository\SalesQuotationArticleRepository;
use App\Repository\SalesQuotationRepository;
use App\Repository\SalesSettlementRepository;
use App\Repository\SupplyRequestRepository;
use App\Repository\SupplyRequestArticleRepository;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Psr\Container\ContainerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
use Twig\Node\BlockReferenceNode;
use Twig\Node\Expression\NullCoalesceExpression;
use Twig\Profiler\NodeVisitor\ProfilerNodeVisitor;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig_TokenParser_Sandbox;

/**
 * Controller used to manage Purchase.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class SaleController extends AbstractController
{

    /**
     * @Route("{_locale}/sale-quotation", methods={"GET", "POST"}, name="sale_quotation")
     * @return Response
     */
    public function showQuotation(SalesQuotationRepository $quotations)
    {
        $userQuotations = $quotations->findBy([], ['id' => 'DESC']);

        return $this->render('sale/show_quotation.html.twig',
            [
                'quotations' => $userQuotations,
            ]);
    }

    /**
     * Creates a new Quotation entity.
     *
     * @Route("{_locale}/sale-quotation/new", methods={"GET", "POST"}, name="sale_quotation_new")
     *
     */
    public function newQuotation(Request $request, ObjectManager $em): Response
    {
        $quotation = new SalesQuotation();

        // On Instancie le formulaire
        $form = $this->createForm(SalesQuotationType::class, $quotation)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quotation->setCreatedAt(new\DateTime());
            $quotation->setUser($this->getUser());

            $em->persist($quotation);

            $em->flush();

            $this->addFlash('success', 'created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('sale_quotation_new');
            }

            return $this->redirectToRoute('sale_quotation');
        }

        return $this->render('sale/edit_quotation.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Quotation entity.
     *
     * @Route("{_locale}/sale-quotation/{id<\d+>}/edit",methods={"GET", "POST"}, name="sale_quotation_edit")
     *
     */
    public function editQuotation(Request $request, SalesQuotationRepository $quotations, SalesQuotation $quotation, ObjectManager $em): Response
    {
        $form = $this->createForm(SalesQuotationType::class, $quotation)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('sale_quotation_new');
            }

            return $this->redirectToRoute('sale_quotation');
        }
        $userQuotations = $quotations->findBy(['user' => $this->getUser()]);

        return $this->render('sale/edit_quotation.html.twig', [
            'quotation' => $quotation,
            'form' => $form->createView(),
            'quotations' => $userQuotations,
        ]);
    }

    /**
     * Deletes a Quotation entity.
     *
     * @Route("{_locale}/sale-quotation/{id}/delete", methods={"GET", "POST"}, name="sale_quotation_delete")
     *
     */
    public function deleteQuotation(SalesQuotation $quotation, ObjectManager $em): Response
    {
        $em->remove($quotation);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('sale_quotation');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("/sale-quotation/{id<\d+>}/valid",methods={"GET", "POST"}, name="sale_quotation_valid")
     *
     */
    public function validQuotation(SalesQuotation $quotation, ObjectManager $em): Response
    {
        $quotation->setIsValid(true);
        $em->flush();

        $this->addFlash('success', 'valid_successfully');

        return $this->redirectToRoute('sale_quotation');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("/sale-quotation/{id<\d+>}/unvalid", methods={"GET", "POST"}, name="sale_quotation_reject")
     *
     */
    public function rejectQuotation(SalesQuotation $quotation, ObjectManager $em): Response
    {
        $quotation->setIsValid(false);
        $em->flush();

        $this->addFlash('success', 'successfully');

        return $this->redirectToRoute('sale_quotation');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("/sale-quotation/{id<\d+>}/reverse", methods={"GET", "POST"}, name="sale_quotation_reverse")
     *
     */
    public function reverseQuotation(SalesQuotation $quotation, ObjectManager $em): Response
    {
        $quotation->setIsValid(null);
        $em->flush();

        $this->addFlash('success', 'successfully');

        return $this->redirectToRoute('sale_quotation');
    }

    /**
     * Displays a form to edit an existing Supply request entity.
     *
     * @Route("{_locale}/sale-quotation/{id<\d+>}/cart", methods={"GET", "POST"}, name="sale_quotation_cart")
     *
     */
    public function showCartQuotation(SalesQuotationArticleRepository $quotationArticles, SalesQuotation $quotation)
    {
        $userQuotationArticles = $quotationArticles->findBy(['quotation' => $quotation], ['id' => 'DESC']);
        return $this->render('sale/show_cart_quotation.html.twig',
            [
                'quotationArticles' => $userQuotationArticles,
                'quotation'=>$quotation
            ]);
    }

    /**
     * Displays a form to edit an existing Quotation Article (the cart of quotation) entity.
     *
     * @Route("{_locale}/sale-quotation/{id<\d+>}/cart-new", methods={"GET", "POST"}, name="sale_quotation_cart_new")
     *
     */
    public function quotationCart(Request $request, SalesQuotation $quotation, SalesQuotationArticleRepository $quotationArticles, ObjectManager $em): Response
    {

        $quotationArticle = new SalesQuotationArticle();

        $form = $this->createForm(SalesQuotationArticleType::class, $quotationArticle)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $quotationArticle->setCreatedAt(new\DateTime());
            $quotationArticle->setQuotation($quotation);

            $quotationArticle->setIsvalid(true);
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
                return $this->redirectToRoute('sale_quotation_cart');
            }

            return $this->redirectToRoute('sale_quotation_cart', array('id' => $quotation->getId()));
        }

        // retour du formulaire et du tableau
        return $this->render('sale/cart_quotation.html.twig', [
            'form' => $form->createView(),
            'quotation'=>$quotation,
        ]);
    }

    /**
     * Displays a form to Supply request article edit an existing Family entity.
     *
     * @Route("{_locale}/sale-quotation-cart/{id<\d+>}/edit",methods={"GET", "POST"}, name="sale_quotation_cart_edit")
     *
     */
    public function editQuotationCart(Request $request, SalesQuotationArticleRepository $quotationArticles, SalesQuotationArticle $quotationArticle, ObjectManager $em): Response
    {
        $form = $this->createForm(SalesQuotationArticleType::class, $quotationArticle)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('sale_quotation_cart');
            }

            return $this->redirectToRoute('sale_quotation_cart', array('id' => $quotationArticle->getQuotation()->getId()));
        }
        $userQuotationArticles = $quotationArticles->findBy(['quotation' => $quotationArticle->getQuotation()], ['id' => 'DESC']);

        return $this->render('sale/cart_quotation.html.twig', [
            'quotationArticle' => $quotationArticle,
            'form' => $form->createView(),
            'quotationArticles' => $userQuotationArticles,
        ]);
    }

    /**
     * Deletes a quotation cart entity.
     *
     * @Route("{_locale}/sale-quotation-cart/{id<\d+>}/delete", methods={"GET", "POST"}, name="sale_quotation_cart_delete")
     *
     */
    public function deleteQuotationCart(SalesQuotationArticle $quotationArticle, ObjectManager $em): Response
    {
        $em->remove($quotationArticle);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('sale_quotation_cart', array('id' => $quotationArticle->getQuotation()->getId()));
    }

    /**
     * Displays a form to invalid an existing quotation article entity.
     *
     * @Route("{_locale}/sale-quotation-cart/{id<\d+>}/valid", methods={"GET", "POST"}, name="sale_quotation_cart_valid")
     *
     */
    public function validQuotationArticle(SalesQuotationArticle $quotationArticle, ObjectManager $em): Response
    {
        $quotationArticle->setIsvalid(true);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('sale_quotation_cart', array('id' => $quotationArticle->getQuotation()->getId()));
    }

    /**
     * Displays a form to invalid an existing quotation article entity.
     *
     * @Route("{_locale}/sale-quotation-cart/{id<\d+>}/unvalid", methods={"GET", "POST"}, name="sale_quotation_cart_reject")
     *
     */
    public function rejectQuotationArticle(SalesQuotationArticle $quotationArticle, ObjectManager $em): Response
    {
        $quotationArticle->setIsvalid(false);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('sale_quotation_cart', array('id' => $quotationArticle->getQuotation()->getId()));
    }

    /**
     * Displays a form to reverse an existing quotation article entity.
     *
     * @Route("{_locale}/sale-quotation-cart/{id<\d+>}/reverse", methods={"GET", "POST"}, name="sale_quotation_cart_reverse")
     *
     */
    public function reverseQuotationArticle(SalesQuotationArticle $quotationArticle, ObjectManager $em): Response
    {
        $quotationArticle->setIsValid(null);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('sale_quotation_cart', array('id' => $quotationArticle->getQuotation()->getId()));
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
     * @Route("{_locale}/sale-delevery", methods={"GET", "POST"}, name="sale_delevery")
     */
    public function showDelevery(SalesDeleveryRepository $deleveries)
    {
        $userDeleveries = $deleveries->findBy([], ['id' => 'DESC']);
        return $this->render('sale/show_delevery.html.twig',
            [
                'deleveries'=>$userDeleveries
            ]);
    }

    /**
     * Creates a new Delevery entity.
     *
     * @Route("{_locale}/sale-delevery-new", methods={"GET", "POST"}, name="sale_delevery_new")
     *
     */
    public function newDelevery(Request $request, SalesOrderArticleRepository $orderArts,ObjectManager $em): Response
    {
        $delevery = new SalesDelevery();

        // On Instancie le formulaire
        $form = $this->createForm(SalesDeleveryType::class, $delevery)
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

                $orderArticles = $orderArts->findBy(['commande'=> $order, 'isvalid' => '1']);
                //dump($supplyRequestArticles); exit();

                foreach($orderArticles as $orderArticle){
                    $deleverArticle = new SalesDeleveryArticle();

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
                return $this->redirectToRoute('sale_delevery_new');
            }

            return $this->redirectToRoute('sale_delevery');
        }

        return $this->render('sale/edit_delevery.html.twig', [
            'delevery' => $delevery,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Order entity.
     *
     * @Route("{_locale}/sale-delevery/{id<\d+>}/edit",methods={"GET", "POST"}, name="sale_delevery_edit")
     *
     */
    public function editDelevery(Request $request, SalesDeleveryRepository $deleveries, SalesDelevery $delevery, ObjectManager $em): Response
    {
        $form = $this->createForm(SalesDeleveryType::class, $delevery)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('sale_delevery_new');
            }

            return $this->redirectToRoute('sale_delevery');
        }
        $userDeleveries = $deleveries->findBy([], ['id' => 'DESC']);

        return $this->render('sale/edit_delevery.html.twig', [
            'delevery' => $delevery,
            'form' => $form->createView(),
            'deleveries' => $userDeleveries,
        ]);
    }

    /**
     * Deletes a Order entity.
     *
     * @Route("{_locale}/sale-delevery/{id}/delete", methods={"GET", "POST"}, name="sale_delevery_delete")
     *
     */
    public function deleteDelevery(SalesDelevery $delevery, ObjectManager $em): Response
    {
        $em->remove($delevery);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('sale_delevery');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("/sale-delevery/{id<\d+>}/valid",methods={"GET", "POST"}, name="sale_delevery_valid")
     *
     */
    public function validDelevery(SalesDelevery $delevery, ObjectManager $em): Response
    {
        $delevery->setIsvalid(true);
        $em->flush();

        $this->addFlash('success', 'valid_successfully');

        return $this->redirectToRoute('sale_delevery');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("/sale-delevery/{id<\d+>}/unvalid", methods={"GET", "POST"}, name="sale_delevery_reject")
     *
     */
    public function rejectDelevery(SalesDelevery $delevery, ObjectManager $em): Response
    {
        $delevery->setIsvalid(false);
        $em->flush();

        $this->addFlash('success', 'successfully');

        return $this->redirectToRoute('sale_delevery');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("/sale-delevery/{id<\d+>}/reverse", methods={"GET", "POST"}, name="sale_delevery_reverse")
     *
     */
    public function reverseDelevery(SalesDelevery $delevery, ObjectManager $em): Response
    {
        $delevery->setIsvalid(null);
        $em->flush();

        $this->addFlash('success', 'successfully');

        return $this->redirectToRoute('sale_delevery');
    }


    /**
     * Displays a form to edit an existing Supply request entity.
     *
     * @Route("{_locale}/sale-delevery/{id<\d+>}/cart", methods={"GET", "POST"}, name="sale_delevery_cart")
     *
     */
    public function showCartDelevery(SalesDeleveryArticleRepository $deleveriesArticles, SalesDelevery $delevery)
    {
        // repository pour l'affichage du tableau
        $userDeleveryArticles = $deleveriesArticles->findBy(['delevery' => $delevery], ['id' => 'DESC']);
        return $this->render('sale/show_cart_delevery.html.twig',
            [
                'deleveriesArticles' => $userDeleveryArticles,
                'delevery'=>$delevery
            ]);
    }

    /**
     * Displays a form to edit an existing delevery Article (the cart of delevery) entity.
     *
     * @Route("{_locale}/sale-delevery/{id<\d+>}/cart-new", methods={"GET", "POST"}, name="sale_delevery_cart_new")
     *
     */
    public function deleveryCart(Request $request, Delevery $delevery, ObjectManager $em): Response
    {

        $deleveryArticle = new SalesDeleveryArticle();

        $form = $this->createForm(SalesDeleveryArticleType::class, $deleveryArticle)
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
                return $this->redirectToRoute('sale_delevery_cart');
            }


            return $this->redirectToRoute('sale_delevery_cart', array('id' => $delevery->getId()));
        }

        // retour du formulaire et du tableau
        return $this->render('sale/cart_delevery.html.twig', [
            'form' => $form->createView(),
            'delevery'=>$delevery
        ]);
    }

    /**
     * Displays a form to Supply request article edit an existing Family entity.
     *
     * @Route("{_locale}/sale-delevery-cart/{id<\d+>}/edit",methods={"GET", "POST"}, name="sale_delevery_cart_edit")
     *
     */
    public function editDeleveryCart(Request $request, SalesDeleveryArticleRepository $deleveriesArticles, SalesDeleveryArticle $deleveryArticle, ObjectManager $em): Response
    {
        $form = $this->createForm(DeleveryArticleType::class, $deleveryArticle)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('sale_delevery_cart');
            }

            return $this->redirectToRoute('sale_delevery_cart', array('id' => $deleveryArticle->getDelevery()->getId()));
        }
        $userDeleveryArticles = $deleveriesArticles->findBy(['delevery' => $deleveryArticle->getDelevery()], ['id' => 'DESC']);

        return $this->render('sale/cart_delevery.html.twig', [
            'deleveryArticle' => $deleveryArticle,
            'form' => $form->createView(),
            'deleveriesArticles' => $userDeleveryArticles
        ]);
    }

    /**
     * Deletes a delevery cart entity.
     *
     * @Route("{_locale}/sale-delevery-cart/{id<\d+>}/delete", methods={"GET", "POST"}, name="sale_delevery_cart_delete")
     *
     */
    public function deleteDeleveryCart(SalesDeleveryArticle $deleveryArticle, ObjectManager $em): Response
    {
        $em->remove($deleveryArticle);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('sale_delevery_cart', array('id' => $deleveryArticle->getDelevery()->getId()));
    }

    /**
     * Displays a form to invalid an existing quotation article entity.
     *
     * @Route("{_locale}/sale-delevery-cart/{id<\d+>}/valid", methods={"GET", "POST"}, name="sale_delevery_cart_valid")
     *
     */
    public function validDeleveryCart(SalesDeleveryArticle $deleveryArticle, ObjectManager $em): Response
    {
        $deleveryArticle->setIsValid(true);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('sale_delevery_cart', array('id' => $deleveryArticle->getDelevery()->getId()));
    }

    /**
     * Displays a form to invalid an existing delivery article entity.
     *
     * @Route("{_locale}/sale-delevery-cart/{id<\d+>}/unvalid", methods={"GET", "POST"}, name="sale_delevery_cart_reject")
     *
     */
    public function rejectDeleveryCart(SalesDeleveryArticle $deleveryArticle, ObjectManager $em): Response
    {
        $deleveryArticle->setIsValid(false);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('sale_delevery_cart', array('id' => $deleveryArticle->getDelevery()->getId()));
    }

    /**
     * Displays a form to reverse an existing delivery article entity.
     *
     * @Route("{_locale}/sale-delevery-cart/{id<\d+>}/reverse", methods={"GET", "POST"}, name="sale_delevery_cart_reverse")
     *
     */
    public function reverseDeleveryCart(SalesDeleveryArticle $deleveryArticle, ObjectManager $em): Response
    {
        $deleveryArticle->setIsValid(null);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('sale_delevery_cart', array('id' => $deleveryArticle->getDelevery()->getId()));
    }

    /**
     * Displays a form to reverse an existing delivery article entity.
     *
     * @Route("{_locale}/sale-delevery-cart/{id<\d+>}/stock", methods={"GET", "POST"}, name="sale_delevery_cart_stock")
     *
     */
    public function stockDeleveryCart(SalesDeleveryArticle $deleveryArticle, ObjectManager $em): Response
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
        return $this->redirectToRoute('sale_delevery_cart', array('id' => $deleveryArticle->getDelevery()->getId()));
    }

    /**
     * @Route("{_locale}/sale-order", methods={"GET", "POST"}, name="sale_order")
     * @return Response
     */
    public function showOder(SalesOrderRepository $orders)
    {
        $userOrders = $orders->findBy([], ['id' => 'DESC']);

        return $this->render('sale/show_order.html.twig',
            [
                'orders' => $userOrders
            ]);
    }

    /**
     * Creates a new Order entity.
     *
     * @Route("{_locale}/sale-order/new", methods={"GET", "POST"}, name="sale_order_new")
     *
     */
    public function newOrder(Request $request, SalesQuotationArticleRepository $quotationArts, ObjectManager $em): Response
    {
        $order = new SalesOrder();

        // On Instancie le formulaire
        $form = $this->createForm(SalesOrderType::class, $order)
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
                $quotationArticles = $quotationArts->findBy(['quotation'=> $quotation, 'isvalid' => '1']);
                //dump($supplyRequestArticles); exit();

                foreach($quotationArticles as $quotationArticle){
                    $orderArticle = new SalesOrderArticle();

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
                return $this->redirectToRoute('sale_order_new');
            }

            return $this->redirectToRoute('sale_order');
        }

        return $this->render('sale/edit_order.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Order entity.
     *
     * @Route("{_locale}/sale-order/{id<\d+>}/edit",methods={"GET", "POST"}, name="sale_order_edit")
     *
     */
    public function editOrder(Request $request, SalesOrderRepository $orders, SalesOrder $order, ObjectManager $em): Response
    {
        $form = $this->createForm(SalesOrderType::class, $order)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('sale_order_new');
            }

            return $this->redirectToRoute('sale_order');
        }
        $userOders = $orders->findBy(['user' => $this->getUser()]);

        return $this->render('sale/edit_order.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
            'orders' => $userOders,
        ]);
    }

    /**
     * Deletes a Order entity.
     *
     * @Route("{_locale}/sale-order/{id}/delete", methods={"GET", "POST"}, name="sale_order_delete")
     *
     */
    public function deleteOrder(SalesOrder $order, ObjectManager $em): Response
    {
        $em->remove($order);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('sale_order');
    }

    /**
     * Displays a form to Valid an existing Order entity.
     *
     * @Route("{_locale}/sale-order/{id<\d+>}/valid",methods={"GET", "POST"}, name="sale_order_valid")
     *
     */
    public function validOrder(SalesOrder $order, ObjectManager $em): Response
    {
        $order->setIsvalid(true);
        $em->flush();

        $this->addFlash('success', 'valid_successfully');

        return $this->redirectToRoute('sale_order');
    }

    /**
     * Displays a form to Reject an existing Order entity.
     *
     * @Route("{_locale}/sale-order/{id<\d+>}/unvalid", methods={"GET", "POST"}, name="sale_order_unvalid")
     *
     */
    public function rejectOder(SalesOrder $order, ObjectManager $em): Response
    {
        $order->setIsvalid(false);
        $em->flush();

        $this->addFlash('success', 'successfully');

        return $this->redirectToRoute('sale_order');
    }

    /**
     * Displays a form to invalid an existing order article entity.
     *
     * @Route("{_locale}/sale-order/{id<\d+>}/reverse", methods={"GET", "POST"}, name="sale_order_reverse")
     *
     */
    public function reverseOrder(SalesOrder $order, ObjectManager $em): Response
    {
        $order->setIsValid(null);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('sale_order');
    }

    /**
     * Displays a form to edit an existing Sale Order entity.
     *
     * @Route("{_locale}/sale-order/{id<\d+>}/cart", methods={"GET", "POST"}, name="sale_order_cart")
     *
     */
    public function showCartOrder(SalesOrderArticleRepository $orderArticles, SalesOrder $order)
    {
        // repository pour l'affichage du tableau
        $userOrderArticles = $orderArticles->findBy(['commande' => $order], ['id' => 'DESC']);
        return $this->render('sale/show_cart_order.html.twig',
            [
                'orderArticles' => $userOrderArticles,
                'commande' => $order
            ]);
    }

    /**
     * Displays a form to edit an existing delevery Article (the cart of delevery) entity.
     *
     * @Route("{_locale}/sale-order/{id<\d+>}/cart-new", methods={"GET", "POST"}, name="sale_order_cart_new")
     *
     */
    public function orderCart(Request $request, SalesOrder $order, ObjectManager $em): Response
    {

        $orderArticle = new SalesOrderArticle();


        $form = $this->createForm(SalesOrderArticleType::class, $orderArticle)
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
                return $this->redirectToRoute('sale_order_cart');
            }


            return $this->redirectToRoute('sale_order_cart', array('id' => $order->getId()));
        }

        // retour du formulaire et du tableau
        return $this->render('sale/cart_order.html.twig', [
            'form' => $form->createView(),
            'commande'=>$order
        ]);
    }

    /**
     * Displays a form to Supply request article edit an existing Order Article entity.
     *
     * @Route("{_locale}/sale-order-cart/{id<\d+>}/edit",methods={"GET", "POST"}, name="sale_order_cart_edit")
     *
     */
    public function editOrderCart(Request $request, SalesOrderArticleRepository $orderArticles, SalesOrderArticle $orderArticle, ObjectManager $em): Response
    {
        $form = $this->createForm(SalesOrderArticleType::class, $orderArticle)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('sale_order_cart');
            }

            return $this->redirectToRoute('sale_order_cart', array('id' => $orderArticle->getCommande()->getId()));
        }
        $userOrderArticles = $orderArticles->findBy(['commande' => $orderArticle->getCommande()], ['id' => 'DESC']);

        return $this->render('sale/cart_order.html.twig', [
            'orderArticle' => $orderArticle,
            'form' => $form->createView(),
            'orderArticles' => $userOrderArticles,
        ]);
    }

    /**
     * Deletes a delevery cart entity.
     *
     * @Route("{_locale}/sale-order-cart/{id<\d+>}/delete", methods={"GET", "POST"}, name="sale_order_cart_delete")
     *
     */
    public function deleteOrderCart(SalesOrderArticle $orderArticle, ObjectManager $em): Response
    {
        $em->remove($orderArticle);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('sale_order_cart', array('id' => $orderArticle->getCommande()->getId()));
    }

    /**
     * Displays a form to valid an existing order article entity.
     *
     * @Route("{_locale}/sale-order-cart/{id<\d+>}/valid", methods={"GET", "POST"}, name="sale_order_cart_valid")
     *
     */
    public function validOrderArticle(SalesOrderArticle  $orderArticle, ObjectManager $em): Response
    {
        $orderArticle->setIsValid(true);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('sale_order_cart', array('id' => $orderArticle->getCommande()->getId()));
    }

    /**
     * Displays a form to invalid an existing order article entity.
     *
     * @Route("{_locale}/sale-order-cart/{id<\d+>}/unvalid", methods={"GET", "POST"}, name="sale_order_cart_reject")
     *
     */
    public function rejectOrderArticle(SalesOrderArticle $orderArticle, ObjectManager $em): Response
    {
        $orderArticle->setIsValid(false);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('sale_order_cart', array('id' => $orderArticle->getCommande()->getId()));
    }

    /**
     * Displays a form to reverse an existing order article entity.
     *
     * @Route("{_locale}/sale-order-cart/{id<\d+>}/reverse", methods={"GET", "POST"}, name="sale_order_cart_reverse")
     *
     */
    public function reverseOrderArticle(SalesOrderArticle $orderArticle, ObjectManager $em): Response
    {
        $orderArticle->setIsValid(null);
        $em->flush();

        $this->addFlash('success', 'successfully');
        return $this->redirectToRoute('sale_order_cart', array('id' => $orderArticle->getCommande()->getId()));
    }

    /**
     * Creates a new Invoice entity.
     *
     * @Route("{_locale}/sale-invoice", methods={"GET", "POST"}, name="sale_invoice")
     *
     */
    public function showInvoice(SalesInvoiceRepository $invoices)
    {
        $userInvoices = $invoices->findBy([], ['id' => 'DESC']);
        return $this->render('sale/show_invoice.html.twig',
            [
                'invoices' => $userInvoices
            ]);
    }

    /**
     * Creates a new Invoice entity.
     *
     * @Route("{_locale}/sale-invoice-new", methods={"GET", "POST"}, name="sale_invoice_new")
     *
     */
    public function newInvoice(Request $request, SalesInvoice $invoice = null, ObjectManager $em): Response
    {
        if(!$invoice){$invoice = new SalesInvoice();}

        // On Instancie le formulaire
        $form = $this->createForm(SalesInvoiceType::class, $invoice)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $invoice->setCreatedAt(new\DateTime());
            $invoice->setUser($this->getUser());
            $invoice->setIsValid(true);
            $invoice->setIsLock(true);

            $em->persist($invoice);
            $em->flush();

            $this->addFlash('success', 'created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('sale_invoice_new');
            }

            return $this->redirectToRoute('sale_invoice');
        }

        return $this->render('sale/edit_invoice.html.twig', [
            'invoice' => $invoice,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Order entity.
     *
     * @Route("{_locale}/sale-invoice/{id<\d+>}/edit",methods={"GET", "POST"}, name="sale_invoice_edit")
     *
     */
    public function editInvoice(Request $request, SalesInvoiceRepository $invoices, SalesInvoice $invoice, ObjectManager $em): Response
    {
        $form = $this->createForm(SalesInvoiceType::class, $invoice)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('sale_invoice_new');
            }

            return $this->redirectToRoute('sale_invoice');
        }
        $userInvoices = $invoices->findBy([], ['id' => 'DESC']);

        return $this->render('sale/edit_invoice.html.twig', [
            'invoice' => $invoice,
            'form' => $form->createView(),
            'invoices' => $userInvoices,
        ]);
    }

    /**
     * Deletes a Invoice entity.
     *
     * @Route("{_locale}/sale-invoice/{id}/delete", methods={"GET", "POST"}, name="sale_invoice_delete")
     *
     */
    public function deleteInvoice(SalesInvoice $invoice, ObjectManager $em): Response
    {
        $em->remove($invoice);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('sale_invoice');
    }

    /**
     * Displays a form to edit an existing Invoice entity.
     *
     * @Route("/sale-invoice/{id<\d+>}/valid",methods={"GET", "POST"}, name="sale_invoice_valid")
     *
     */
    public function validInvoice(SalesInvoice $invoice, ObjectManager $em): Response
    {
        $invoice->setIsValid(true);
        $em->flush();

        $this->addFlash('success', 'valid_successfully');

        return $this->redirectToRoute('sale_invoice');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("/sale-invoice/{id<\d+>}/unvalid", methods={"GET", "POST"}, name="sale_invoice_reject")
     *
     */
    public function rejectInvoice(SalesInvoice $invoice, ObjectManager $em): Response
    {
        $invoice->setIsValid(false);
        $em->flush();

        $this->addFlash('success', 'successfully');

        return $this->redirectToRoute('sale_invoice');
    }

    /**
     * Displays a form to edit an existing Invoice entity.
     *
     * @Route("/sale-invoice/{id<\d+>}/lock",methods={"GET", "POST"}, name="sale_invoice_lock")
     *
     */
    public function lockInvoice(SalesInvoice $invoice, ObjectManager $em): Response
    {
        $invoice->setIsLock(false);
        $em->flush();

        $this->addFlash('success', 'valid_successfully');

        return $this->redirectToRoute('sale_invoice');
    }

    /**
     * Displays a form to edit an existing Item type entity.
     *
     * @Route("/sale-invoice/{id<\d+>}/unlock", methods={"GET", "POST"}, name="sale_invoice_unlock")
     *
     */
    public function unlockInvoice(SalesInvoice $invoice, ObjectManager $em): Response
    {
        $invoice->setIsLock(true);
        $em->flush();

        $this->addFlash('success', 'successfully');

        return $this->redirectToRoute('sale_invoice');
    }


    /**
     * Displays a form to edit an existing invoice Article (the cart of invoice) entity.
     *
     * @Route("{_locale}/sale-invoice/{id<\d+>}/cart", methods={"GET", "POST"}, name="sale_invoice_cart")
     *
     */
    public function invoiceCart(Request $request, SalesDelevery $delevery, SalesDeleveryArticleRepository $deleveriesArticles, SalesDeleveryArticle $deleveryArticle = null, ObjectManager $em): Response
    {

        if(!$deleveryArticle) {
            $deleveryArticle = new SalesDeleveryArticle();
        }

        $form = $this->createForm(SalesDeleveryArticleType::class, $deleveryArticle)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $deleveryArticle->setCreatedAt(new\DateTime());
            $deleveryArticle->setDelevery($delevery);

            $em->persist($deleveryArticle);
            $em->flush();


            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('sale_invoice_cart');
            }


            return $this->redirectToRoute('sale_delevery_cart', array('id' => $delevery->getId()));
        }


        // repository pour l'affichage du tableau
        $userDeleveryArticles = $deleveriesArticles->findBy(['delevery' => $delevery], ['id' => 'DESC']);

        // retour du formulaire et du tableau
        return $this->render('sale/cart_delevery.html.twig', [
            'deleveryarticle' => $deleveryArticle,
            'form' => $form->createView(),
            'deleveriesArticles' => $userDeleveryArticles,
            'delevery'=>$delevery
        ]);
    }

    /**
     * Displays a form to Supply request article edit an existing Family entity.
     *
     * @Route("{_locale}/sale-invoice-cart/{id<\d+>}/edit",methods={"GET", "POST"}, name="sale_invoice_cart_edit")
     *
     */
    public function editInvoiceCart(Request $request, SalesDeleveryArticleRepository $deleveriesArticles, SalesDeleveryArticle $deleveryArticle, ObjectManager $em): Response
    {
        $form = $this->createForm(SalesDeleveryArticleType::class, $deleveryArticle)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('sale_invoice_cart');
            }

            return $this->redirectToRoute('sale_delevery_cart', array('id' => $deleveryArticle->getDelevery()->getId()));
        }
        $userDeleveryArticles = $deleveriesArticles->findBy(['delevery' => $deleveryArticle->getDelevery()], ['id' => 'DESC']);

        return $this->render('sale/cart_delevery.html.twig', [
            'deleveryArticle' => $deleveryArticle,
            'form' => $form->createView(),
            'deleveriesArticles' => $userDeleveryArticles,
        ]);
    }

    /**
     * Deletes a invoice cart entity.
     *
     * @Route("{_locale}/sale-invoice-cart/{id<\d+>}/delete", methods={"GET", "POST"}, name="sale_invoice_cart_delete")
     *
     */
    public function deleteInvoiceCart(SalesDeleveryArticle $deleveryArticle, ObjectManager $em): Response
    {
        $em->remove($deleveryArticle);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('sale_delevery_cart', array('id' => $deleveryArticle->getDelevery()->getId()));
    }


    /**
     *
     * @Route("{_locale}/sale-invoice/{id<\d+>}/settlement", methods={"GET", "POST"}, name="sale_invoice_settle")
     */
    public function showSettlement(SalesSettlementRepository $settlements, SalesInvoice $invoice)
    {
        // repository pour l'affichage du tableau
        $userSettlements = $settlements->findBy(['invoice' => $invoice], ['id' => 'DESC']);
        return $this->render('sale/show_settlement.html.twig',
            [
                'invoice' => $invoice,
                'settlements' => $userSettlements
            ]);
    }

    /**
     *
     * @Route("{_locale}/sale-invoice/{id<\d+>}/settlement/new", methods={"GET", "POST"}, name="sale_invoice_settle_new")
     */
    public function newSettlement(Request $request, CashDeskRepository $cash, SalesInvoice $invoice, ObjectManager $em):Response
    {
        $settlement = new SalesSettlement();


        $form = $this->createForm(SalesSettlementType::class, $settlement)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $settlement->setCreatedAt(new\DateTime());
            $settlement->setInvoice($invoice);
            $settlement->setAmount($invoice->getAmountTva());
            $settlement->setUser($this->getUser());
            $settlement->setIsApproved(true);

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
                } elseif ((($cashDesk->getBalance()) < ($settlement->getAmount() /*+ $settlement->getTva()*/))) {
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
                return $this->redirectToRoute('sale_invoice_settle_new');
            }

            return $this->redirectToRoute('sale_invoice_settle', array('id' => $invoice->getId()));
        }

        return $this->render('sale/new_settlement.html.twig', [
            'form' => $form->createView(),
            'invoice' => $invoice,
            //'settlement' => $settlements
        ]);

    }


    /**
     * Deletes a Settlement entity.
     *
     * @Route("{_locale}/sale-invoice-settle/{id<\d+>}}/delete", methods={"GET", "POST"}, name="sale_invoice_settle_delete")
     *
     */
    public function deleteSettlement(SalesSettlement $settlement, ObjectManager $em): Response
    {
        $em->remove($settlement);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('sale_invoice_settle');
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
     * @Route("/sale-settlement/{id<\d+>}/valid",methods={"GET", "POST"}, name="sale_settlement_valid")
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
     * @Route("/sale-settlement/{id<\d+>}/unvalid", methods={"GET", "POST"}, name="sale_settlement_reject")
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
     * @Route("{_locale}/sale-settlement/{id<\d+>}/cart", methods={"GET", "POST"}, name="sale_settlement_cart")
     *
     */
    public function settlementCart(Request $request, SalesDelevery $delevery, SalesDeleveryArticleRepository $deleveriesArticles, SalesDeleveryArticle $deleveryArticle = null, ObjectManager $em): Response
    {

        if(!$deleveryArticle) {
            $deleveryArticle = new SalesDeleveryArticle();
        }

        $form = $this->createForm(SalesDeleveryArticleType::class, $deleveryArticle);
        //->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            //$deleveryArticle->setCreeatedAt(new\DateTime());
            $deleveryArticle->setDelevery($delevery);

            $em->persist($deleveryArticle);
            $em->flush();


            $this->addFlash('success', 'updated_successfully');

            //if ($form->get('saveAndCreateNew')->isClicked()) {
            //    return $this->redirectToRoute('delevery_cart', array('id' => $delevery->getId()));
            //}


            return $this->redirectToRoute('sale_delevery_cart', array('id' => $delevery->getId()));
        }


        // repository pour l'affichage du tableau
        $userDeleveryArticles = $deleveriesArticles->findBy(['delevery' => $delevery], ['id' => 'DESC']);

        // retour du formulaire et du tableau
        return $this->render('sale/cart_delevery.html.twig', [
            'deleveryarticle' => $deleveryArticle,
            'form' => $form->createView(),
            'deleveriesArticles' => $userDeleveryArticles,
            'delevery'=>$delevery
        ]);
    }

    /**
     * Displays a form to Supply request article edit an existing Family entity.
     *
     * @Route("{_locale}/sale-settlement-cart/{id<\d+>}/edit",methods={"GET", "POST"}, name="sale_settlement_cart_edit")
     *
     */
    public function editSettlementCart(Request $request, SalesDeleveryArticleRepository $deleveriesArticles, SalesDeleveryArticle $deleveryArticle, ObjectManager $em): Response
    {
        $form = $this->createForm(SalesDeleveryArticleType::class, $deleveryArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            return $this->redirectToRoute('sale_delevery_cart', array('id' => $deleveryArticle->getDelevery()->getId()));
        }
        $userDeleveryArticles = $deleveriesArticles->findBy(['delevery' => $deleveryArticle->getDelevery()], ['id' => 'DESC']);

        return $this->render('sale/cart_delevery.html.twig', [
            'deleveryArticle' => $deleveryArticle,
            'form' => $form->createView(),
            'deleveriesArticles' => $userDeleveryArticles,
        ]);
    }

    /**
     * Deletes a settlement cart entity.
     *
     * @Route("{_locale}/sale-settlement-cart/{id<\d+>}/delete", methods={"GET", "POST"}, name="sale_settlement_cart_delete")
     *
     */
    public function deleteSettlementCart(SalesDeleveryArticle $deleveryArticle, ObjectManager $em): Response
    {
        $em->remove($deleveryArticle);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('sale_delevery_cart', array('id' => $deleveryArticle->getDelevery()->getId()));
    }

}
