<?php

namespace App\Controller\Transit;

use App\Entity\Batch;
use App\Entity\Container;
use App\Entity\Loading;

use App\Form\BatchType;
use App\Form\ContainerType;
use App\Form\FillContainerType;
use App\Form\LoadingType;

use App\Repository\UserRepository;
use App\Repository\BatchRepository;
use App\Repository\ContainerRepository;
use App\Repository\LoadingRepository;

use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Monolog\Handler\GelfMockMessagePublisher;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Stopwatch\StopwatchEvent;
use Twig\Node\Expression\Binary\MatchesBinary;
use Twig\Node\ImportNode;
use Webmozart\Assert\Assert;

/**
 * Controller used to manage container.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class TransitContainerController extends AbstractController
{
    /**
     * Lists all Batch .
     *
     *
     * @Route("{_locale}/batch", methods={"GET"}, name="batch")
     *
     */
    public function showBatch(BatchRepository $batchs): Response
    {
        // $userBatchs = $batchs->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);
		$userBatchs = $batchs->findBy([], ['createdAt' => 'DESC']);

        return $this->render('transit_container/show_batch.html.twig', ['batchs' => $userBatchs]);
    }

    /**
     * Creates a new batch   entity.
     *
     * @Route("{_locale}/batch/new", methods={"GET", "POST"}, name="batch_new")
     *
     */
    public function newBatch(Request $request, Batch $batch = null, ObjectManager $em, UserRepository $user): Response
    {
        if(!$batch){$batch = new Batch();}

        // On Instancie le formulaire
        $form = $this->createForm(BatchType::class, $batch)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();


            //$branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();
            // On prépare l'objet à persister
            $batch->setCreatedAt(new\DateTime());
            $batch->setUser($this->getUser());
            //$log->setBranch($branch);
            //$season->setIsEnabled(true);

            $em->persist($batch);
            $em->flush();

            $this->addFlash('success', 'batch.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('batch_new');
            }

            return $this->redirectToRoute('batch');
        }

        return $this->render('transit_container/edit_batch.html.twig', [
            'batch' => $batch,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing batch entity.
     *
     * @Route("{_locale}/batch/{id<\d+>}/edit",methods={"GET", "POST"}, name="batch_edit")
     *
     */
    public function editBatch(Request $request, Batch $batch, ObjectManager $em): Response
    {
        $form = $this->createForm(BatchType::class, $batch) 
                                ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'batch.updated_successfully');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('batch_new');
            }

            return $this->redirectToRoute('batch');
        }

        return $this->render('transit_container/edit_batch.html.twig', [
            'batch' => $batch,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a Batch entity.
     *
     * @Route("/batch/{id}/delete", methods={"GET", "POST"}, name="batch_delete")
     *
     */
    public function deleteBatch(Batch $batch, ObjectManager $em): Response
    {
        $em->remove($batch);
        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('batch');
    }

    /**
     * Display a form to generate container for an existing Batch entity.
     *
     * @Route("{_locale}/batch/{id<\d+>}/generate", methods={"GET", "POST"}, name="container_generate")
     *
     */
    public function generateContainer(Request $request, Batch $batch, ObjectManager $em, UserRepository $user): Response
    {
        $form = $this->createFormBuilder()
            ->add("number", IntegerType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get Branch 
            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            $nbContainers = $form->get('number')->getData();

            for ($i = 0; $i < $nbContainers; $i++) {
                $container = new Container();

                $container->setCreatedAt(new \DateTime());
                $container->setUser($this->getUser());
                $container->setBatch($batch);
                $container->setLength($batch->getContainerlength());
                $container->setBranch($branch);

                $em->persist($container);
            }

            $em->flush();

            $this->addFlash('success', 'Success!');

            return $this->redirectToRoute('container');
        }

        return $this->render('transit_container/generate_container.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     /**
     * Lists all Container .
     *
     *
     * @Route("{_locale}/container", methods={"GET"}, name="container")
     *
     */
    public function showContainer(ContainerRepository $containers): Response
    {
        $userContainers = $containers->findBy([], ['createdAt' => 'DESC']);

        return $this->render('transit_container/show_container.html.twig', ['containers' => $userContainers]);
    }

    /**
     * Creates a new containers   entity.
     *
     * @Route("{_locale}/container/new", methods={"GET", "POST"}, name="container_new")
     *
     */
    public function newContainer(Request $request, Container $container = null, ObjectManager $em, UserRepository $user): Response
    {
        if(!$container){$container = new Container();}

        // On Instancie le formulaire
        $form = $this->createForm(ContainerType::class, $container)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            // On prépare l'objet à persister
            $container->setCreatedAt(new\DateTime());
            $container->setUser($this->getUser());

            $em->persist($container);
            $em->flush();

            $this->addFlash('success', 'container.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('container_new');
            }

            return $this->redirectToRoute('container');
        }

        return $this->render('transit_container/edit_container.html.twig', [
            'container' => $container,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing container entity.
     *
     * @Route("{_locale}/container/{id<\d+>}/edit",methods={"GET", "POST"}, name="container_edit")
     *
     */
    public function editContainer(Request $request, Container $container, ObjectManager $em): Response
    {
        $form = $this->createForm(ContainerType::class, $container) 
                                ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'container.updated_successfully');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('container_new');
            }

            return $this->redirectToRoute('container');
        }

        return $this->render('transit_container/edit_container.html.twig', [
            'container' => $container,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Container entity.
     *
     * @Route("/container/{id}/delete", methods={"GET", "POST"}, name="container_delete")
     *
     */
    public function deleteContainer(Container $container, ObjectManager $em): Response
    {
        $em->remove($container);
        $em->flush();

        $this->addFlash('success', 'Successful');

        return $this->redirectToRoute('container');
    }

    /**
     * List all Container UnFill.
     *
     * @Route("{_locale}/unfill-container", methods={"GET", "POST"}, name="unfill_container")
     *
     */
    public function showContainerToFill(ContainerRepository $containers): Response
    {
        $userContainers = $containers->findBy(['plumb' => null], ['id' => 'DESC']);
        return $this->render('transit_container/show_container_to_fill.html.twig', ['containers' => $userContainers]);
    }

    /**
     * Add Plumb to a Container entity.
     *
     * @Route("{_locale}/plumb-container/{id<\d+>}", methods={"GET", "POST"}, name="plumb_container")
     *
     */
    public function plumbContainer(Request $request, Container $container, ObjectManager $em): Response
    {
        $form = $this->createForm(FillContainerType::class, $container);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');

            return $this->redirectToRoute('unfill_container');
        }

        return $this->render('transit_container/plumb_container.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     /**
     * Lists all Loading .
     *
     *
     * @Route("{_locale}/loading", methods={"GET"}, name="loading")
     *
     */
    public function showLoading(LoadingRepository $loadings): Response
    {
        // $userLoadings = $loadings->findBy(['user' => $this->getUser()]);
		$userLoadings = $loadings->findBy([], ['id' => 'DESC']);

        return $this->render('transit_container/show_loading.html.twig', ['loadings' => $userLoadings]);
    }

    /**
     * Creates a new loading   entity.
     *
     * @Route("{_locale}/loading/new", methods={"GET", "POST"}, name="loading_new")
     *
     */
    public function newLoading(Request $request, Loading $loading = null, ObjectManager $em, UserRepository $user): Response
    {
        if(!$loading){$loading = new Loading();}

        // On Instancie le formulaire
        $form = $this->createForm(LoadingType::class, $loading)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            // On prépare l'objet à persister
            $loading->setUser($this->getUser());
            $loading->setBranch($branch);

            $em->persist($loading);
            $em->flush();

            $this->addFlash('success', 'loading.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('loading_new');
            }

            return $this->redirectToRoute('loading');
        }

        return $this->render('transit_container/edit_loading.html.twig', [
            'loading' => $loading,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing loading entity.
     *
     * @Route("{_locale}/loading/{id<\d+>}/edit",methods={"GET", "POST"}, name="loading_edit")
     *
     */
    public function editLoading(Request $request, Loading $loading, ObjectManager $em): Response
    {
        $form = $this->createForm(LoadingType::class, $loading)
                                 ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'loading.updated_successfully');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('loading_new');
            }

            return $this->redirectToRoute('loading');
        }

        return $this->render('transit_container/edit_loading.html.twig', [
            'loading' => $loading,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Loading entity.
     *
     * @Route("/loading/{id}/delete", methods={"GET", "POST"}, name="loading_delete")
     *
     */
    public function deleteLoading(Loading $loading, ObjectManager $em): Response
    {
        $em->remove($loading);
        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('loading');
    }
}
