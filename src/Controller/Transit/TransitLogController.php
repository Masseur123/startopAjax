<?php

namespace App\Controller\Transit;

use App\Entity\Log;
use App\Entity\LoadingLog;
use App\Entity\LogProcurement;
use App\Entity\StockWood;
use App\Entity\Park;

use App\Form\LogProcurementType;
use App\Form\LogType;
use App\Form\LoadingLogType;

use App\Repository\LogProcurementRepository;
use App\Repository\LogRepository;
use App\Repository\LoadingLogRepository;
use App\Repository\StockWoodRepository;

use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\SqlResultSetMappings;
use Monolog\Handler\GelfMockMessagePublisher;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Bridge\Doctrine\Tests\Fixtures\GroupableEntity;
use Symfony\Component\DependencyInjection\Tests\Compiler\SetterInjectionCollision;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Stopwatch\StopwatchEvent;
use Twig\Node\CheckToStringNode;
use Twig\Node\Expression\Binary\MulBinary;
use Twig\Node\SpacelessNode;
use Twig_Filter;
use Twig_Sandbox_SecurityNotAllowedTagError;

/**
 * Controller used to manage transit log.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class TransitLogController extends AbstractController
{
     /**
     * Lists all LogProcurement .
     *
     * @Route("{_locale}/log-procurement", methods={"GET"}, name="log_procurement")
     *
     */
    public function showLogProcurement(LogProcurementRepository $logProcurements): Response
    {
        // $userLogProcurements = $logProcurements->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);
		$userLogProcurements = $logProcurements->findBy([], ['createdAt' => 'DESC']);

        return $this->render('transit_log/show_log-procurement.html.twig', ['logProcurements' => $userLogProcurements]);
    }

    /**
     * Creates a new Log Procurement  entity.
     *
     * @Route("{_locale}/log-procurements/new", methods={"GET", "POST"}, name="log_procurement_new")
     *
     */
    public function newLogProcurement(Request $request, LogProcurement $logProcurement = null, ObjectManager $em, UserRepository $user): Response
    {
        if(!$logProcurement){$logProcurement = new LogProcurement();}

        // On Instancie le formulaire
        $form = $this->createForm(LogProcurementType::class, $logProcurement)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

			$qty = $form->get('quantity')->getData();
			
            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            // On prépare l'objet à persister
            $logProcurement->setQuantityToDelever(0);
			$logProcurement->setCreatedAt(new\DateTime());
            $logProcurement->setUser($this->getUser());
            $logProcurement->setBranch($branch);

            $em->persist($logProcurement);
            $em->flush();

            $this->addFlash('success', 'log.Procurements.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('log_procurement_new');
            }

            return $this->redirectToRoute('log_procurement');
        }

        return $this->render('transit_log/edit_log-procurement.html.twig', [
            'logProcurement' => $logProcurement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Log Procurement  entity.
     *
     * @Route("{_locale}/log-procurement/{id<\d+>}/edit",methods={"GET", "POST"}, name="log_procurement_edit")
     *
     */
    public function editLogProcurement(Request $request, LogProcurement $logProcurement, ObjectManager $em): Response
    {
        $form = $this->createForm(LogProcurementType::class, $logProcurement)
        ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			
			$qty = $form->get('quantity')->getData();
			
			// Check if update quantity is less than delevered quantity
			if($qty < $logProcurement->getQuantityToDelever())
			{
				$this->addFlash('warning', 'You can not update quantity less than '. $logProcurement->getQuantityToDelever());
				return $this->redirectToRoute('log_procurement_edit', array('id' => $logProcurement->getId()));
			}			
			
            $em->flush();

            $this->addFlash('success', 'Updated_successfully');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('log_procurement_new');
            }

            return $this->redirectToRoute('log_procurement');
        }

        return $this->render('transit_log/edit_log-procurement.html.twig', [
            'logProcurement' => $logProcurement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Transit log procurement entity.
     *
     * @Route("/log-procurement/{id}/delete", methods={"GET", "POST"}, name="log_procurement_delete")
     *
     */
    public function deleteLogProcurement(LogProcurement $logProcurement, ObjectManager $em): Response
    {
		// 
        $em->remove($logProcurement);
        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('log_procurement');
    }
	
	/**
     * Displays a form to add log in stock.
     *
     * @Route("{_locale}/log-procurement/{id<\d+>}/add-stock",methods={"GET", "POST"}, name="log_procurement_add_stock")
     *
     */
    public function addStockLog(Request $request, LogProcurement $logProcurement, ObjectManager $em, UserRepository $user): Response
    {
		$form = $this->createFormBuilder()
			->add('quantity', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'quantity',
                ),
                'required' => true,
            ))
			->add('volume', NumberType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'volume',
                ),
                'trim' => true,
                'required' => false,
            ))
			->add('stockAt', DateType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'anytime-month-numeric',
                ),
                'required' => true,
            ))
			->add('park', EntityType::class, array(
                'class' => Park::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
                'required' => true,
            ))
			->add('note', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'remark',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			
			$qty = $form->get('quantity')->getData();
			
			// Check if purchase quantity is more than available quantity
			if($logProcurement->getQuantity() == $logProcurement->getQuantityToDelever())
			{
				$this->addFlash('warning', 'You can not add more than '. $logProcurement->getQuantity() .' in stock');
                return $this->redirectToRoute('log_procurement');
			}
			// Check if request quantity is more than available quantity
			elseif($qty > ($logProcurement->getQuantity() - $logProcurement->getQuantityToDelever()))
			{
				$this->addFlash('warning', 'You can not add more than '. ($logProcurement->getQuantity() - $logProcurement->getQuantityToDelever()) .' in stock');
                return $this->redirectToRoute('log_procurement_add_stock', array('id' => $logProcurement->getId()));
			}
			
			$branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();
			
			$vol = $form->get('volume')->getData(); if(!$vol){$vol = 0;}
			$date = $form->get('stockAt')->getData();
			$park = $form->get('park')->getData();
			$note = $form->get('note')->getData();
			
			// Add stock 
			$stock = new StockWood(); 
			
			$stock->setWood($logProcurement->getWood());
			$stock->setQuantity($qty);
			$stock->setVolume($vol);
			$stock->setStockAt($date);
			$stock->setIsAdd(true);
			$stock->setProcurement($logProcurement);
			$stock->setStatus(true);
			
            $stock->setUser($this->getUser());
			$stock->setBranch($branch);
			
			$stock->setPark($park);
			$stock->setNote($note);
			
			$em->persist($stock);
			
			// Update available stock in log procurement 
			$logProcurement->setQuantityToDelever($logProcurement->getQuantityToDelever() + $qty);
			
            
            $em->flush();

            $this->addFlash('success', 'Success!');

            return $this->redirectToRoute('log_procurement');
        }

        return $this->render('transit_log/edit_log_stock.html.twig', [
            'logProcurement' => $logProcurement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Lists all Log .
     *
     * @Route("{_locale}/log", methods={"GET"}, name="log")
     *
     */
    public function showLog(LogRepository $logs): Response
    {
        $userLogs = $logs->findBy([], ['createdAt' => 'DESC']);

        return $this->render('transit_log/show_log.html.twig', ['logs' => $userLogs]);
    }

    /**
     * Creates a new Log   entity.
     *
     * @Route("{_locale}/log/new", methods={"GET", "POST"}, name="log_new")
     *
     */
    public function newLog(Request $request, Log $log = null, ObjectManager $em, UserRepository $user): Response
    {
        if(!$log){$log = new Log();}

        // On Instancie le formulaire
        $form = $this->createForm(LogType::class, $log)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            // On prépare l'objet à persister
            $log->setCreatedAt(new\DateTime());
            $log->setUser($this->getUser());
            $log->setBranch($branch);

            $em->persist($log);
            $em->flush();

            $this->addFlash('success', 'log.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('log_new');
            }

            return $this->redirectToRoute('log');
        }

        return $this->render('transit_log/edit_log.html.twig', [
            'log' => $log,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Log  entity.
     *
     * @Route("{_locale}/log/{id<\d+>}/edit",methods={"GET", "POST"}, name="log_edit")
     *
     */
    public function editLog(Request $request, Log $log, ObjectManager $em): Response
    {
        $form = $this->createForm(LogType::class, $log)
        ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'log.updated_successfully');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('log_new');
            }

            return $this->redirectToRoute('log');
        }

        return $this->render('transit_log/edit_log.html.twig', [
            'log' => $log,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a log entity.
     *
     * @Route("/log/{id}/delete", methods={"GET", "POST"}, name="log_delete")
     *
     */
    public function deleteLog(Log $log, ObjectManager $em): Response
    {
        $em->remove($log);
        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('log');
    }
	
	/**
     * Lists all Log Loading.
     *
     *
     * @Route("{_locale}/log/loading", methods={"GET"}, name="loading_log")
     *
     */
    public function showLogLoading(LoadingLogRepository $loadings): Response
    {
        // $userLoadings = $loadings->findBy(['user' => $this->getUser()]);
		$userLoadings = $loadings->findBy([], ['id' => 'DESC']);

        return $this->render('transit_log/show_log_loading.html.twig', ['loadings' => $userLoadings]);
    }

    /**
     * Creates a new loading   entity.
     *
     * @Route("{_locale}/log/loading/new", methods={"GET", "POST"}, name="loading_log_new")
     *
     */
    public function newLogLoading(Request $request, LoadingLog $loading = null, ObjectManager $em, UserRepository $user): Response
    {
        if(!$loading){$loading = new LoadingLog();}

        // On Instancie le formulaire
        $form = $this->createForm(LoadingLogType::class, $loading)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            // On prépare l'objet à persister
			$loading->setQuantityToDelever(0);
            $loading->setUser($this->getUser());
            $loading->setBranch($branch);			

            $em->persist($loading);
            $em->flush();

            $this->addFlash('success', 'loading.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('loading_log_new');
            }

            return $this->redirectToRoute('loading_log');
        }

        return $this->render('transit_log/edit_log_loading.html.twig', [
            'loading' => $loading,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing loading entity.
     *
     * @Route("{_locale}/log/loading/{id<\d+>}/edit",methods={"GET", "POST"}, name="loading_log_edit")
     *
     */
    public function editLogLoading(Request $request, LoadingLog $loading, ObjectManager $em): Response
    {
        $form = $this->createForm(LoadingLogType::class, $loading)
                                 ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			
			$qty = $form->get('nbrofpiece')->getData();
			
			// Check if update quantity is less than delevered quantity
			if($qty < $loading->getQuantityToDelever())
			{
				$this->addFlash('warning', 'You can not update quantity less than '. $loading->getQuantityToDelever());
				return $this->redirectToRoute('loading_log_edit', array('id' => $loading->getId()));
			}	
			
            $em->flush();

            $this->addFlash('success', 'Updated_successfully');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('loading_log_new');
            }

            return $this->redirectToRoute('loading_log');
        }

        return $this->render('transit_log/edit_log_loading.html.twig', [
            'loading' => $loading,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Loading entity.
     *
     * @Route("/log/loading/{id}/delete", methods={"GET", "POST"}, name="loading_log_delete")
     *
     */
    public function deleteLoading(LoadingLog $loading, ObjectManager $em): Response
    {
		// 
        $em->remove($loading);
        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('loading_log');
    }
	
	/**
     * Displays a form to remove log in stock.
     *
     * @Route("{_locale}/log-loading/{id<\d+>}/remove-stock", methods={"GET", "POST"}, name="loading_log_remove_stock")
     *
     */
    public function removeStockLog(Request $request, LoadingLog $loading, ObjectManager $em, UserRepository $user): Response
    {
		$form = $this->createFormBuilder()
			->add('quantity', IntegerType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'quantity',
                ),
                'required' => true,
            ))
			->add('volume', NumberType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'volume',
                ),
                'trim' => true,
                'required' => false,
            ))
			->add('stockAt', DateType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'anytime-month-numeric',
                ),
                'required' => true,
            ))
			->add('park', EntityType::class, array(
                'class' => Park::class,
                'placeholder' => 'Choose an option',
                'expanded' => false,
                'multiple' => false,
                'attr' => array(
                    'class' => 'form-control select-search',
                ),
                'required' => true,
            ))
			->add('note', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'remark',
                ),
                'trim' => true,
                'required' => false,
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			
			$qty = $form->get('quantity')->getData();
			
			// Check if load quantity is equal to delevered quantity
			if($loading->getNbrofpiece() == $loading->getQuantityToDelever())
			{
				$this->addFlash('warning', 'You can not remove more than '. $loading->getNbrofpiece() .' from stock');
                return $this->redirectToRoute('loading_log');
			}
			// Check if request quantity is more than available quantity
			elseif($qty > ($loading->getNbrofpiece() - $loading->getQuantityToDelever()))
			{
				$this->addFlash('warning', 'You can not remove more than '. ($loading->getNbrofpiece() - $loading->getQuantityToDelever()) .' from stock');
                return $this->redirectToRoute('loading_log_remove_stock', array('id' => $loading->getId()));
			}
			
			$branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();
			
			$vol = $form->get('volume')->getData(); if(!$vol){$vol = 0;}
			$date = $form->get('stockAt')->getData(); 
			$park = $form->get('park')->getData();
			$note = $form->get('note')->getData();
			
			// Add stock 
			$stock = new StockWood(); 
			
			$stock->setWood($loading->getWood());
			$stock->setQuantity($qty);
			$stock->setVolume($vol);
			$stock->setStockAt($date);
			$stock->setIsAdd(false);
			$stock->setLoading($loading);
			$stock->setStatus(true);
			
            $stock->setUser($this->getUser());
			$stock->setBranch($branch);
			
			$stock->setPark($park);
			$stock->setNote($note);
			
			$em->persist($stock);
			
			// Update available stock in log procurement 
			$loading->setQuantityToDelever($loading->getQuantityToDelever() + $qty);
			
            
            $em->flush();

            $this->addFlash('success', 'Success!');

            return $this->redirectToRoute('loading_log');
        }

        return $this->render('transit_log/edit_log_stock.html.twig', [
            'logProcurement' => $loading,
            'form' => $form->createView(),
        ]);
    }
	
	/**
     * Lists all Log stock.
     *
     *
     * @Route("{_locale}/log/stock", methods={"GET"}, name="stock_log")
     *
     */
    public function showLogStock(StockWoodRepository $stocks): Response
    {
		$userStocks = $stocks->findBy([], ['id' => 'DESC']);

        return $this->render('transit_log/show_log_stock.html.twig', ['stocks' => $userStocks]);
    }
}
