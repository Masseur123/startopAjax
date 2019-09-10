<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\StockAddRemoveType;
use App\Repository\ArticleRepository;
use App\Repository\SupplyRequestRepository;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Context\NullContext;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Tests\Extension\Core\EventListener\MergeCollectionListenerArrayObjectTest;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Stock;
use App\Entity\Store;
use App\Form\StockType;
use App\Form\StoreType;
use App\Repository\StockRepository;
use App\Repository\StoreRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Twig\Node\Expression\Binary\BitwiseOrBinary;
use Twig\Node\Expression\Binary\NotEqualBinary;

class StockController extends AbstractController
{
    /**
     * Lists all Store.
     *
     * @Route("/store", methods={"GET"}, name="store")
     *
     */
    public function showStore(StoreRepository $stores): Response
    {
        $userStores = $stores->findBy([], ['id' => 'DESC']);

        return $this->render('stock/show_store.html.twig', ['stores' => $userStores]);
    }

    /**
     * Creates a new Store entity.
     *
     * @Route("/store-new", methods={"GET", "POST"}, name="store_new")
     *
     */
    public function newStore(Request $request, StoreRepository $stores, Store $store = null, ObjectManager $em): Response
    {
        if(!$store){$store = new Store();}

        // On Instancie le formulaire
        $form = $this->createForm(StoreType::class, $store)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $store->setCreatedAt(new\DateTime());
            $store->setUser($this->getUser());

            $em->persist($store);
            $em->flush();

            $this->addFlash('success', 'created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('store_new');
            }

            return $this->redirectToRoute('store');
        }

        return $this->render('stock/edit_store.html.twig', [
            'store' => $store,
            'form' => $form->createView()
        ]);
    }

    /**
     * Displays a form to edit an existing Store entity.
     *
     * @Route("/store/{id<\d+>}/edit",methods={"GET", "POST"}, name="store_edit")
     *
     */
    public function editStore(Request $request, StoreRepository $stores, Store $store, ObjectManager $em): Response
    {
        $form = $this->createForm(StoreType::class, $store)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('store_new');
            }

            return $this->redirectToRoute('store');
        }

        return $this->render('stock/edit_store.html.twig', [
            'store' => $store,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a Store entity.
     *
     * @Route("/store/{id}/delete", methods={"GET", "POST"}, name="store_delete")
     *
     */
    public function deleteStore(Request $request, Store $store, ObjectManager $em): Response
    {
        $em->remove($store);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('store');
    }


    /**
     * Lists all Stock.
     *
     * @Route("/stock", methods={"GET"}, name="stock")
     *
     */
    public function showStock(StockRepository $stocks): Response
    {
        $userStocks = $stocks->findBy(['user' => $this->getUser()]);

        return $this->render('stock/show_stock.html.twig', ['stocks' => $userStocks]);
    }

    /**
     * Creates a new Stock entity.
     *
     * @Route("/stock/new", methods={"GET", "POST"}, name="stock_new")
     *
     */
    public function newStock(Request $request, StockRepository $stocks, Stock $stock = null, ObjectManager $em): Response
    {
        if(!$stock){$stock = new Stock();}

        // On Instancie le formulaire
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $stock->setCreatedAt(new\DateTime());
            $stock->setUser($this->getUser());

            $em->persist($stock);
            $em->flush();

            $this->addFlash('success', 'created_successfully');

            return $this->redirectToRoute('stock');
        }
        $userStocks = $stocks->findBy(['user' => $this->getUser()]);

        return $this->render('stock/edit_stock.html.twig', [
            'stock' => $stock,
            'form' => $form->createView(),
            'stocks' => $userStocks
        ]);
    }

    /**
     * Displays a form to edit an existing Stock entity.
     *
     * @Route("/stock/{id<\d+>}/edit",methods={"GET", "POST"}, name="stock_edit")
     *
     */
    public function editStock(Request $request, StockRepository $stocks, Stock $stock, ObjectManager $em): Response
    {
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            return $this->redirectToRoute('stock');
        }
        $userStocks = $stocks->findBy(['user' => $this->getUser()]);

        return $this->render('stock/edit_stock.html.twig', [
            'stock' => $stock,
            'form' => $form->createView(),
            'stocks' => $userStocks,
        ]);
    }

    /**
     * Deletes a Stock entity.
     *
     * @Route("/stock/{id}/delete", methods={"GET", "POST"}, name="stock_delete")
     *
     */
    public function deleteStock(Request $request, Stock $stock, ObjectManager $em): Response
    {
        $em->remove($stock);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('stock');
    }

    /**
     * Lists all Articles.
     *
     * @Route("/article-list", methods={"GET"}, name="article_list")
     *
     */
    public function showArticle(ArticleRepository $articles, StockRepository $stocks): Response
    {
        //$userArticles = $articles->findBy(['user' => $this->getUser()]);
        $userStocks = $stocks->findBy(['user' => $this->getUser()]);


        return $this->render('stock/show_articlelist.html.twig', [//'articles' => $userArticles,
        'stocks' => $userStocks]);
    }

    /**
     * Creates a new Stock entry.
     *
     * @Route("/stock/add", methods={"GET", "POST"}, name="stock_add")
     *
     */
    public function addStock(Request $request, Stock $stock = null, ObjectManager $em): Response
    {
        $article = new Article();
        if(!$stock){$stock = new Stock();}

        // On Instancie le formulaire
        $form = $this->createForm(StockAddRemoveType::class, $stock)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $stock->setCreatedAt(new\DateTime());
            $stock->setUser($this->getUser());
            $stock->setType(true);
            $stock->setArticle($article);

            $em->merge($stock);
            $em->flush();

            $this->addFlash('success', 'successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('stock_add');
            }

            return $this->redirectToRoute('stock');
        }

        return $this->render('stock/add_stock.html.twig', [
            'stock' => $stock,
            'form' => $form->createView(),
            'article'  => $article
        ]);
    }

    /**
     * Creates a new Stock remove.
     *
     * @Route("/stock//remove", methods={"GET", "POST"}, name="stock_remove")
     *
     */
    public function removeStock(Request $request, Article $article, Stock $stock = null, ObjectManager $em): Response
    {
        if(!$stock){$stock = new Stock();}

        // On Instancie le formulaire
        $form = $this->createForm(StockAddRemoveType::class, $stock)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $stock->setCreatedAt(new\DateTime());
            $stock->setUser($this->getUser());
            $stock->setType(false);

            $em->persist($stock);
            $em->flush();
            $stock->setArticle($article);

            $this->addFlash('success', 'successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('stock_remove');
            }

            return $this->redirectToRoute('stock');
        }

        return $this->render('stock/remove_stock.html.twig', [
            'stock' => $stock,
            'form' => $form->createView(),
        ]);
    }


    /**
     * Creates a new Stock remove.
     *
     * @Route("/stock/{id<\d+>}/move", methods={"GET", "POST"}, name="stock_move")
     *
     */
    public function moveStock(Request $request, Article $article, Stock $stock = null, ObjectManager $em): Response
    {
        if(!$stock){$stock = new Stock();}

        // On Instancie le formulaire
        $form = $this->createForm(StockAddRemoveType::class, $stock)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $stock->setCreatedAt(new\DateTime());
            $stock->setUser($this->getUser());
            $stock->setType(false);

            $stock->setStockprice('');
            $stock->setStockAt(new\DateTime());


            $em->persist($stock);
            $em->flush();
            $stock->setArticle($article);

            $this->addFlash('success', 'successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('stock_move');
            }

            return $this->redirectToRoute('stock');
        }

        return $this->render('stock/move_stock.html.twig', [
            'stock' => $stock,
            'form' => $form->createView(),
        ]);
    }


}
