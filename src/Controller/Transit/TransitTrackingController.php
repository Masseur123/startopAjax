<?php

namespace App\Controller\Transit;

use App\Service\FileUploader;

use App\Entity\Container;
use App\Entity\LogRouting;
use App\Entity\ContainerTracking;
use App\Entity\AttachDocument;
use App\Entity\TransitHist;
use App\Entity\Transit;
use App\Entity\DocumentFile;

use App\Form\LogRoutingType;
use App\Form\ContainerTrackingType;
use App\Form\AttachDocumentType;
use App\Form\TransitHistType;

use App\Repository\UserRepository;
use App\Repository\LogRoutingRepository;
use App\Repository\AttachDocumentRepository;
use App\Repository\TransitHistRepository;
use App\Repository\YearRepository;
use App\Repository\CurrencyRepository;

use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ContainerTrackingRepository;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Stopwatch\StopwatchEvent;
use Twig\Node\DeprecatedNode;
use Twig\Node\Expression\Binary\ConcatBinary;
use Twig\Node\Expression\MethodCallExpression;
use Twig\Sandbox\SecurityPolicy;
use Twig_TokenParser_Import;


/**
 * Controller used to manage container and log tracking.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class TransitTrackingController extends AbstractController
{
    /**
     * Displays a form to track an existing Container entity.
     *
     * @Route("{_locale}/container/{id<\d+>}/tracking", methods={"GET", "POST"}, name="container_tracking")
     *
     */
    public function trackContainer(Request $request, UserRepository $user, Container $container, ObjectManager $em): Response
    {
        $containerTracking = new ContainerTracking();

        $containerTracking->setContainer($container);

        $form = $this->createForm(ContainerTrackingType::class, $containerTracking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get Branch 
            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            // On prépare l'objet à persister
            $containerTracking->setCreatedAt(new \DateTime());
            $containerTracking->setUser($this->getUser());
            $containerTracking->setContainer($container);
            $containerTracking->setBranch($branch);

            $em->persist($containerTracking);
            $em->flush();

            $this->addFlash('success', 'Success!');

            return $this->redirectToRoute('container');
        }

        return $this->render('transit_tracking/edit_container_tracking.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Lists all COntainer Routing .
     *
     *
     * @Route("{_locale}/container-routing", methods={"GET"}, name="container_routing")
     *
     */
    public function showContainerRouting(ContainerTrackingRepository $containerRoutings): Response
    {
        $userContainerRoutings = $containerRoutings->findBy([], ['createdAt' => 'DESC']);

        return $this->render('transit_tracking/show_container_routing.html.twig', ['containerRoutings' => $userContainerRoutings]);
    }

    /**
     * Creates a new Container Touting   entity.
     *
     * @Route("{_locale}/Container-routing/new", methods={"GET", "POST"}, name="container_routing_new")
     *
     */
    public function newContainerRouting(Request $request, ContainerTracking $containerRouting = null, ObjectManager $em, UserRepository $user): Response
    {
        if(!$containerRouting){$containerRouting = new ContainerTracking();}

        // On Instancie le formulaire
        $form = $this->createForm(ContainerTrackingType::class, $containerRouting)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();


            //$branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();
            // On prépare l'objet à persister
            $containerRouting->setCreatedAt(new\DateTime());
            $containerRouting->setUser($this->getUser());
            $containerRouting->setBranch($branch);

            $em->persist($containerRouting);
            $em->flush();

            $this->addFlash('success', 'container.routing.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('container_routing_new');
            }

            return $this->redirectToRoute('container_routing');
        }

        return $this->render('transit_tracking/edit_container_routing.html.twig', [
            'containerRouting' => $containerRouting,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Log  entity.
     *
     * @Route("{_locale}/container-routing/{id<\d+>}/edit",methods={"GET", "POST"}, name="container_routing_edit")
     *
     */
    public function editContainerRouting(Request $request, ContainerTracking $containerRouting, ObjectManager $em): Response
    {
        $form = $this->createForm(ContainerTrackingType::class, $containerRouting)
                             ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'container.routing.updated_successfully');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('container_routing_new');
            }

            return $this->redirectToRoute('container_routing');
        }

        return $this->render('transit_tracking/edit_container_routing.html.twig', [
            'containerRouting' => $containerRouting,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Transit container routing entity.
     *
     * @Route("/container-routing/{id}/delete", methods={"GET", "POST"}, name="container_routing_delete")
     *
     */
    public function deleteContainerRouting(ContainerTracking $containerTracking, ObjectManager $em): Response
    {
        $em->remove($containerTracking);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('container_routing');
    }

    /**
     * Lists all log Routing .
     *
     *
     * @Route("{_locale}/log-routing", methods={"GET"}, name="log_routing")
     *
     */
    public function showLogRouting(logRoutingRepository $logRoutings): Response
    {
        $userLogRoutings = $logRoutings->findBy([], ['createdAt' => 'DESC']);

        return $this->render('transit_tracking/show_log_routing.html.twig', ['logRoutings' => $userLogRoutings]);
    }

    /**
     * Creates a new log Touting   entity.
     *
     * @Route("{_locale}/log-routing/new", methods={"GET", "POST"}, name="log_routing_new")
     *
     */
    public function newlogRouting(Request $request, LogRouting $logRouting = null, ObjectManager $em, UserRepository $user): Response
    {
        if(!$logRouting){$logRouting = new LogRouting();}

        // On Instancie le formulaire
        $form = $this->createForm(LogRoutingType::class, $logRouting)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();


            //$branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();
            // On prépare l'objet à persister
            $logRouting->setCreatedAt(new\DateTime());
            $logRouting->setUser($this->getUser());
            $logRouting->setBranch($branch);

            $em->persist($logRouting);
            $em->flush();

            $this->addFlash('success', 'log.routing.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('log_routing_new');
            }

            return $this->redirectToRoute('log_routing');
        }

        return $this->render('transit_tracking/edit_log_routing.html.twig', [
            'logRouting' => $logRouting,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Log  entity.
     *
     * @Route("{_locale}/log-routing/{id<\d+>}/edit",methods={"GET", "POST"}, name="log_routing_edit")
     *
     */
    public function editlogRouting(Request $request, LogRouting $logRouting, ObjectManager $em): Response
    {
        $form = $this->createForm(LogRoutingType::class, $logRouting)
                             ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'log.routing.updated_successfully');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('log_routing_new');
            }

            return $this->redirectToRoute('log_routing');
        }

        return $this->render('transit_tracking/edit_log_routing.html.twig', [
            'logRouting' => $logRouting,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Transit log routing entity.
     *
     * @Route("/log-routing/{id}/delete", methods={"GET", "POST"}, name="log_routing_delete")
     *
     */
    public function deleteLogRouting(LogRouting $logRouting, ObjectManager $em): Response
    {
        $em->remove($logRouting);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('log_routing');
    }

    /*
     * Creates a new file upload entity.
     *
     * @Route("{_locale}/upload", methods={"GET", "POST"}, name="upload")
     *
    public function newUploadFile(Request $request, UserRepository $user, AttachDocumentRepository $attachDocuments, FileUploader $fileUploader, ObjectManager $em): Response
    {
        $attachDocument = new AttachDocument();

        // On Instancie le formulaire
        $form = $this->createForm(AttachDocumentType::class, $attachDocument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get Branch 
            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            // Stores the uploaded File
            $file = $form->get('fileupload')->getData();
            $fileName = $fileUploader->upload($file);

            $attachDocument->setFileupload($fileName);

            // On prépare l'objet à persister
            $attachDocument->setCreatedAt(new \DateTime());
            $attachDocument->setUser($this->getUser());
            $attachDocument->setBranch($branch);

            $em->persist($attachDocument);
            $em->flush();

            $this->addFlash('success', 'Success!');

            return $this->redirectToRoute('upload');
        }
        $userAttachDocuments = $attachDocuments->findBy([], ['id' => 'DESC']);

        return $this->render('transit_tracking/edit_upload_file.html.twig', [
            'form' => $form->createView(),
            'attachDocuments' => $userAttachDocuments,
        ]);
    }*/

    /*
     * Displays a form to edit an existing file upload entity.
     *
     * @Route("{_locale}/upload/{id<\d+>}/edit",methods={"GET", "POST"}, name="upload_edit")
     *
    public function editUploadFile(Request $request, AttachDocumentRepository $attachDocuments, AttachDocument $attachDocument, FileUploader $fileUploader, ObjectManager $em): Response
    {
        /*$attachDocument->setFileupload(
            new File($this->getParameter('upload_directory').'/'.$attachDocument->getFileupload())
        );

        $form = $this->createForm(AttachDocumentType::class, $attachDocument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Remove the old File
            /*$directory = $this->getParameter('upload_directory').'/'.$attachDocument->getFileupload();
            $filename = $attachDocument->getFileupload();
            $filesystem = new Filesystem();
            $filesystem->remove(['file', $directory, $filename]);*

            // Stores the new uploaded File
            $file = $form->get('fileupload')->getData();
            $fileName = $fileUploader->upload($file);

            $attachDocument->setFileupload($fileName);

            $em->flush();

            $this->addFlash('success', 'Success!');

            return $this->redirectToRoute('upload');
        }
        $userAttachDocuments = $attachDocuments->findBy([], ['id' => 'DESC']);

        return $this->render('transit_tracking/edit_upload_file.html.twig', [
            'form' => $form->createView(),
            'attachDocuments' => $userAttachDocuments,
        ]);
    }*/

    /**
     * Lists all transit file upload .
     *
     * @Route("{_locale}/upload", methods={"GET"}, name="upload")
     *
     */
    public function showUploadFile(AttachDocumentRepository $attachDocuments): Response
    {
        $userAttachDocuments = $attachDocuments->findBy([], ['createdAt' => 'DESC']);

        return $this->render('transit_tracking/show_upload_file.html.twig', ['attachDocuments' => $userAttachDocuments]);
    }

    /**
     * Creates a new transit file upload   entity.
     *
     * @Route("{_locale}/upload/new", methods={"GET", "POST"}, name="upload_new")
     *
     */
    public function newUploadFile(Request $request, AttachDocument $attachDocument = null, FileUploader $fileUploader, ObjectManager $em, UserRepository $user): Response
    {
        if(!$attachDocument){$attachDocument = new AttachDocument();}

        // On Instancie le formulaire
        $form = $this->createForm(AttachDocumentType::class, $attachDocument)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            $file = $form->get('fileupload')->getData();
            $fileName = $fileUploader->upload($file);

            $attachDocument->setFileupload($fileName);
            //$branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();
            // On prépare l'objet à persister
            $attachDocument->setCreatedAt(new\DateTime());
            $attachDocument->setUser($this->getUser());
            $attachDocument->setBranch($branch);
            //$season->setIsEnabled(true);

            $em->persist($attachDocument);
            $em->flush();

            $this->addFlash('success', 'upload.file.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('upload_new');
            }

            return $this->redirectToRoute('upload');
        }

        return $this->render('transit_tracking/edit_upload_file.html.twig', [
            'attachDocument' => $attachDocument,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Upload File  entity.
     *
     * @Route("{_locale}upload/{id<\d+>}/edit",methods={"GET", "POST"}, name="upload_edit")
     *
     */
    public function editUploadFile(Request $request, AttachDocument $attachDocument, FileUploader $fileUploader, ObjectManager $em): Response
    {
        $form = $this->createForm(AttachDocumentType::class, $attachDocument)
                                ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('fileupload')->getData();
            $fileName = $fileUploader->upload($file);

            $attachDocument->setFileupload($fileName);

            $em->flush();

            $this->addFlash('success', 'Upload.file.updated_successfully');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('upload_new');
            }

            return $this->redirectToRoute('upload');
        }

        return $this->render('transit_tracking/edit_upload_file.html.twig', [
            'attachDocument' => $attachDocument,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Transit file upload entity.
     *
     * @Route("/upload/{id}/delete", methods={"GET", "POST"}, name="upload_delete")
     *
     */
    public function deleteUploadFile(AttachDocument $attachDocument, ObjectManager $em): Response
    {
        $directory = $this->getParameter('upload_directory') . '/' . $attachDocument->getFileupload();
        $filename = $attachDocument->getFileupload();
        $filesystem = new Filesystem();
        $filesystem->remove(['file', $directory, $filename]);

        $em->remove($attachDocument);
        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('upload');
    }

    /**
     * Displays a form to add expense to a transit file and document.
     *
     * @Route("{_locale}/transit/{transit<\d+>}/document/{document<\d+>}",methods={"GET", "POST"}, name="transit_expense")
     *
     */
    public function addTransitExpense(Request $request, Transit $transit, DocumentFile $document, ObjectManager $em, YearRepository $year, CurrencyRepository $currency, UserRepository $user): Response
    {
        $transitHist = new TransitHist();

        // On Instancie le formulaire
        $form = $this->createForm(TransitHistType::class, $transitHist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Current values : year and currency 
            $currentYear = $year->findOneBy(['is_current' => true]);
            $currentCurrency = $currency->findOneBy(['is_current' => true]);

            // Get Branch 
            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            $transitHist->setCurrency($currentCurrency);
            $transitHist->setYear($currentYear);
            $transitHist->setUser($this->getUser());
            $transitHist->setBranch($branch);

            // $transitHist->setIsValid(false);
            // $transitHist->setCreatedAt(new \DateTime());

            $transitHist->setTransit($transit);
            $transitHist->setDocument($document);

            $em->persist($transitHist);
            $em->flush();

            $this->addFlash('success', 'Success!');
            return $this->redirectToRoute('transit_expense_show');
        }

        return $this->render('transit_tracking/edit_transit_expense.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Displays a form to add expense to a transit file and document.
     *
     * @Route("{_locale}/transit-expense",methods={"GET", "POST"}, name="transit_expense_show")
     *
     */
    public function showTransitExpense(TransitHistRepository $transitHists): Response
    {
        $userTransitHists = $transitHists->findBy([], ['id' => 'DESC']);

        return $this->render('transit_tracking/show_transit_expense.html.twig', [
            'transitHists' => $userTransitHists
        ]);
    }

    /**
     * Displays a form to edit an existing transit log routing entity.
     *
     * @Route("{_locale}/transit-expense/{id<\d+>}/edit",methods={"GET", "POST"}, name="transit_expense_edit")
     *
     */
    public function editTransitExpense(Request $request, TransitHist $transitHist, ObjectManager $em): Response
    {
        $form = $this->createForm(TransitHistType::class, $transitHist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');
            return $this->redirectToRoute('transit_expense_show');
        }

        return $this->render('transit_tracking/edit_transit_expense.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a Transit expense entity.
     *
     * @Route("/transit-expense/{id}/delete", methods={"GET", "POST"}, name="transit_expense_delete")
     *
     */
    public function deleteTransitExpense(TransitHist $transitHist, ObjectManager $em): Response
    {
        $em->remove($transitHist);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('transit_expense_show');
    }
}
