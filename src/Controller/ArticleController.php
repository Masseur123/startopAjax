<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Family;
use App\Entity\Itemtype;
use App\Entity\Serial;
use App\Entity\Unity;

use App\Form\ArticleType;
use App\Form\FamilyType;
use App\Form\ItemTypeType;
use App\Form\SerialType;
use App\Form\UnityType;

use App\Repository\ArticleRepository;
use App\Repository\FamilyRepository;
use App\Repository\ItemtypeRepository;
use App\Repository\SerialRepository;
use App\Repository\UnityRepository;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Tests\Extension\Core\EventListener\MergeCollectionListenerArrayObjectTest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Twig\Node\Expression\Binary\BitwiseXorBinary;
use Twig\Node\Expression\Binary\LessEqualBinary;
use Twig\Node\Expression\TestExpression;
use Twig\Node\WithNode;

/**
 * Controller used to manage article in ZoZoo.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/item-type", methods={"GET", "POST"}, name="item_type")
     */
    public function showItem(Request $request, ItemtypeRepository $itemtypes):Response
    {
        $userItems = $itemtypes->findBy(['user' => $this->getUser()]);
        return $this->render('article/show_item_type.html.twig',
            [
                'itemTypes' => $userItems
            ]);
    }

    /**
     * Creates a new Item entity.
     *
     * @Route("/item-type/new", methods={"GET", "POST"}, name="item_type_new")
     *
     */
    public function newItem(Request $request, Itemtype $itemtype = null, ObjectManager $em) : Response
    {
        if (!$itemtype) {
            $itemtype = new Itemtype();
        }

        // On Instancie le formulaire
        $form = $this->createForm(ItemTypeType::class, $itemtype)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $itemtype->setCreatedAt(new \DateTime());
            $itemtype->setUser($this->getUser());

            $em->persist($itemtype);
            $em->flush();

            $this->addFlash('success', 'created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('item_type_new');
            }

            return $this->redirectToRoute('item_type');
        }

        return $this->render('article/edit_item_type.html.twig', [
            'item_type' => $itemtype,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Item entity.
     *
     * @Route("/item-type/{id<\d+>}/edit",methods={"GET", "POST"}, name="item_type_edit")
     *
     */
    public function editItem(Request $request, Itemtype $itemtype, ObjectManager $em) : Response
    {
        $form = $this->createForm(ItemTypeType::class, $itemtype)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('item_type_new');
            }

            return $this->redirectToRoute('item_type');
        }

        return $this->render('article/edit_item_type.html.twig', [
            'item_type' => $itemtype,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Item entity.
     *
     * @Route("/item-type/{id}/delete", methods={"GET", "POST"}, name="item_type_delete")
     *
     */
    public function deleteItem(Itemtype $itemtype, ObjectManager $em) : Response
    {
        $em->remove($itemtype);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('item_type');
    }


    /**
     * @Route("/family", methods={"GET", "POST"}, name="family")
     * @return Response
     */
    public function showFamily(FamilyRepository $families)
    {
        $userFamilies = $families->findBy(['user' => $this->getUser()]);
        return $this->render('article/show_family.html.twig',
            [
                'families' => $userFamilies
            ]);
    }

    /**
     * Creates a new Family entity.
     *
     * @Route("/family/new", methods={"GET", "POST"}, name="family_new")
     *
     */
    public function newFamily(Request $request, Family $family = null, ObjectManager $em) : Response
    {
        if (!$family) {
            $family = new Family();
        }

        // On Instancie le formulaire
        $form = $this->createForm(FamilyType::class, $family)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $family->setCreatedAt(new \DateTime());
            $family->setUser($this->getUser());

            $em->persist($family);
            $em->flush();

            $this->addFlash('success', 'created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('family_new');
            }

            return $this->redirectToRoute('family');
        }

        return $this->render('article/edit_family.html.twig', [
            'family' => $family,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Family entity.
     *
     * @Route("/family/{id<\d+>}/edit",methods={"GET", "POST"}, name="family_edit")
     *
     */
    public function editFamily(Request $request, Family $family, ObjectManager $em) : Response
    {
        $form = $this->createForm(FamilyType::class, $family)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('family_new');
            }

            return $this->redirectToRoute('family');
        }

        return $this->render('article/edit_family.html.twig', [
            'family' => $family,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Family entity.
     *
     * @Route("/family/{id}/delete", methods={"GET", "POST"}, name="family_delete")
     *
     */
    public function deleteFamily(Family $family, ObjectManager $em) : Response
    {
        $em->remove($family);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('family');
    }

    /**
     * @Route("/unity", methods={"GET", "POST"}, name="unity")
     * @return Response
     */
    public function showUnity(Request $request, UnityRepository $unities)
    {
        $userUnities = $unities->findBy(['user' => $this->getUser()]);
        return $this->render('article/show_unity.html.twig',
            [
                'unities' => $userUnities
            ]);
    }

    /**
     * Creates a new Unity entity.
     *
     * @Route("/unity/new", methods={"GET", "POST"}, name="unity_new")
     *
     */
    public function newUnity(Request $request, Unity $unity = null, ObjectManager $em) : Response
    {
        if (!$unity) {
            $unity = new Unity();
        }

        // On Instancie le formulaire
        $form = $this->createForm(UnityType::class, $unity)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $unity->setCreatedAt(new \DateTime());
            $unity->setUser($this->getUser());

            $em->persist($unity);
            $em->flush();

            $this->addFlash('success', 'created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('unity_new');
            }

            return $this->redirectToRoute('unity');
        }

        return $this->render('article/edit_unity.html.twig', [
            'unity' => $unity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Unity entity.
     *
     * @Route("/unity/{id<\d+>}/edit",methods={"GET", "POST"}, name="unity_edit")
     *
     */
    public function editUnity(Request $request, UnityRepository $unities, Unity $unity, ObjectManager $em) : Response
    {
        $form = $this->createForm(UnityType::class, $unity)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('unity_new');
            }

            return $this->redirectToRoute('unity');
        }
        $userUnities = $unities->findBy(['user' => $this->getUser()]);

        return $this->render('article/edit_unity.html.twig', [
            'unity' => $unity,
            'form' => $form->createView(),
            'unities' => $userUnities,
        ]);
    }

    /**
     * Deletes a Unity entity.
     *
     * @Route("/unity/{id}/delete", methods={"GET", "POST"}, name="unity_delete")
     *
     */
    public function deleteUnity(Unity $unity, ObjectManager $em) : Response
    {
        $em->remove($unity);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('unity');
    }

    /**
     * Show all Articles in entity.
     *
     * @Route("/article", methods={"GET", "POST"}, name="article")
     *
     */
    public function showArticle(ArticleRepository $articles)
    {
        $userArticles = $articles->findBy(['user' => $this->getUser()]);
        return $this->render('article/show_article.html.twig',
            [
                'articles' => $userArticles,
            ]);
    }
    /**
     * Creates a new Article entity.
     *
     * @Route("/article/new", methods={"GET", "POST"}, name="article_new")
     *
     */
    public function newArticle(Request $request, Article $article = null, ObjectManager $em) : Response
    {
        if (!$article) {
            $article = new Article();
        }

        // On Instancie le formulaire
        $form = $this->createForm(ArticleType::class, $article)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $article->setCreatedAt(new \DateTime());
            $article->setUser($this->getUser());
            $article->setReserv($article);

            $em->persist($article);
            $em->flush();
            //dump($article);


            $this->addFlash('success', 'created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('article_new');
            }

            return $this->redirectToRoute('article');
        }

        return $this->render('article/edit_article.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Article entity.
     *
     * @Route("/article/{id<\d+>}/edit",methods={"GET", "POST"}, name="article_edit")
     *
     */
    public function editArticle(Request $request, Article $article, ObjectManager $em) : Response
    {
        $form = $this->createForm(ArticleType::class, $article)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('article_new');
            }

            return $this->redirectToRoute('article');
        }

        return $this->render('article/edit_article.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Article entity.
     *
     * @Route("/article/{id}/delete", methods={"GET", "POST"}, name="article_delete")
     *
     */
    public function deleteArticle(Article $article, ObjectManager $em) : Response
    {
        $em->remove($article);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('article');
    }

    /**
     * Show all in Serial entity.
     *
     * @Route("/serial", methods={"GET", "POST"}, name="serial")
     *
     */
    public function showSerial(SerialRepository $serials)
    {
        $userSerials = $serials->findBy(['user' => $this->getUser()]);
        return $this->render('article/show_serial.html.twig',
            [
                'serials' => $userSerials
            ]);
    }

    /**
     * Creates a new Serial entity.
     *
     * @Route("/serial/new", methods={"GET", "POST"}, name="serial_new")
     *
     */
    public function newSerial(Request $request, Serial $serial = null, ObjectManager $em) : Response
    {
        if (!$serial) {
            $serial = new Serial();
        }

        // On Instancie le formulaire
        $form = $this->createForm(SerialType::class, $serial)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $serial->setCreatedAt(new \DateTime());
            $serial->setUser($this->getUser());

            $em->persist($serial);
            $em->flush();

            $this->addFlash('success', 'created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('serial_new');
            }

            return $this->redirectToRoute('serial');
        }

        return $this->render('article/edit_serial.html.twig', [
            'serial' => $serial,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Unity entity.
     *
     * @Route("/serial/{id<\d+>}/edit",methods={"GET", "POST"}, name="serial_edit")
     *
     */
    public function editSerial(Request $request, SerialRepository $serials, Serial $serial, ObjectManager $em) : Response
    {
        $form = $this->createForm(SerialType::class, $serial)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'updated_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('serial_new');
            }

            return $this->redirectToRoute('serial');
        }
        $userSerials = $serials->findBy(['user' => $this->getUser()]);

        return $this->render('article/edit_serial.html.twig', [
            'serial' => $serial,
            'form' => $form->createView(),
            'serials' => $userSerials,
        ]);
    }

    /**
     * Deletes a Serial entity.
     *
     * @Route("/serial/{id}/delete", methods={"GET", "POST"}, name="serial_delete")
     *
     */
    public function deleteSerial(Serial $serial, ObjectManager $em) : Response
    {
        $em->remove($serial);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');

        return $this->redirectToRoute('serial');
    }
}
