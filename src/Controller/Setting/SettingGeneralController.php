<?php

namespace App\Controller\Setting;

use App\Entity\Customer;
use App\Entity\Person;
use App\Entity\Priority;
use App\Entity\ModeTransport;
use App\Entity\Port;
use App\Entity\BusinessType;
use App\Entity\Civility;
use App\Entity\GeneralArea;
use App\Entity\Language;


use App\Form\CustomerType;
use App\Form\PriorityType;
use App\Form\ModeTransportType;
use App\Form\PortType;
use App\Form\BusinessTypeType;
use App\Form\CivilityType;
use App\Form\GeneralAreaType;
use App\Form\LanguageType;

use App\Repository\CountryRepository;
use App\Repository\DivisionRepository;
use App\Repository\PersonRepository;
use App\Repository\PriorityRepository;
use App\Repository\ModeTransportRepository;
use App\Repository\PortRepository;
use App\Repository\BusinessTypeRepository;
use App\Repository\CivilityRepository;
use App\Repository\GeneralAreaRepository;
use App\Repository\LanguageRepository;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Internal\Hydration\ArrayHydrator;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategy;
use Twig\Node\BlockNode;
use Twig\Node\BodyNode;
use Twig\Node\Expression\Binary\PowerBinary;
use Twig\Node\Expression\Filter\DefaultFilter;
use Twig\Node\Expression\TempNameExpression;
use Twig\Sandbox\SecurityNotAllowedPropertyError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig_Extension_Debug;
use Twig_Extension_Sandbox;
use Twig_Node_Expression_Binary_Or;
use Twig_Node_Expression_Test_Sameas;
use Twig_Sandbox_SecurityNotAllowedFunctionError;
use Twig_TokenParser_Embed;

/**
 * Controller used to manage Financial Settings.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class SettingGeneralController extends AbstractController
{
    /**
     * Lists all Port .
     *
     *
     * @Route("{_locale}/port", methods={"GET"}, name="port")
     *
     */
    public function showPort(PortRepository $ports): Response
    {
        $userPorts = $ports->findBy([], ['id' => 'DESC']);

        return $this->render('setting_general/show_port.html.twig', [ 'ports' => $userPorts,]);
    }

    /**
     * Creates a new Port entity.
     *
     * @Route("/port/new", methods={"GET", "POST"}, name="port_new")
     *
     */
    public function newPort(Request $request, ObjectManager $em): Response
    {
        $port = new Port();

        // On Instancie le formulaire
        $form = $this->createForm(PortType::class, $port)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $port->setCreatedAt(new \DateTime());
            $port->setUser($this->getUser());

            $em->persist($port);
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('port_new');
            }

            return $this->redirectToRoute('port');
        }

        return $this->render('setting_general/edit_port.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Port entity.
     *
     * @Route("/port/{id<\d+>}/edit", methods={"GET", "POST"}, name="port_edit")
     *
     */
    public function editPort(Request $request, PortRepository $ports, Port $port, ObjectManager $em): Response
    {
        $form = $this->createForm(PortType::class, $port)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('port_new');
            }

            return $this->redirectToRoute('port');
        }
        $userPorts = $ports->findBy([], ['id' => 'DESC']);

        return $this->render('setting_general/edit_port.html.twig', [
            'form' => $form->createView(),
            'ports' => $userPorts,
        ]);
    }

    /**
     * Deletes a Port entity.
     *
     * @Route("/port/{id}/delete", methods={"GET", "POST"}, name="port_delete")
     *
     */
    public function deletePort(Port $port, ObjectManager $em): Response
    {
        $em->remove($port);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('port');
    }

    /**
     * Lists all Business type .
     *
     *
     * @Route("{_locale}/business-type", methods={"GET"}, name="business_type")
     *
     */
    public function showBusinessType(BusinessTypeRepository $businessTypes): Response
    {
        $userBusinessTypes = $businessTypes->findBy([], ['id' => 'DESC']);

        return $this->render('setting_general/show_business-type.html.twig', [ 'businessTypes' => $userBusinessTypes,]);
    }

    /**
     * Creates a new business type entity.
     *
     * @Route("/business-type-new", methods={"GET", "POST"}, name="business_type_new")
     *
     */
    public function newBusinessType(Request $request, ObjectManager $em): Response
    {
        $businessType = new BusinessType();

        // On Instancie le formulaire
        $form = $this->createForm(BusinessTypeType::class, $businessType)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $businessType->setCreatedAt(new \DateTime());
            $businessType->setUser($this->getUser());

            $em->persist($businessType);
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('business_type_new');
            }

            return $this->redirectToRoute('business_type');
        }

        return $this->render('setting_general/edit_business-type.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing business type entity.
     *
     * @Route("/business-type/{id<\d+>}/edit",methods={"GET", "POST"}, name="business_type_edit")
     *
     */
    public function editBusinessType(Request $request, BusinessTypeRepository $businessTypes, BusinessType $businessType, ObjectManager $em): Response
    {
        $form = $this->createForm(BusinessTypeType::class, $businessType)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('business_type_new');
            }
            return $this->redirectToRoute('business_type');
        }
        $userBusinessTypes = $businessTypes->findBy([], ['id' => 'DESC']);

        return $this->render('setting/edit_business-type.html.twig', [
            'form' => $form->createView(),
            'businessTypes' => $userBusinessTypes,
        ]);
    }

    /**
     * Deletes a business type entity.
     *
     * @Route("/business-type/{id}/delete", methods={"GET", "POST"}, name="business_type_delete")
     *
     */
    public function deleteBusinessType(BusinessType $businessType, ObjectManager $em): Response
    {
        $em->remove($businessType);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('business_type');
    }

    /**
     * Lists all Mode trans .
     *
     *
     * @Route("{_locale}/mode-transport", methods={"GET"}, name="mode_transport")
     *
     */
    public function showModeTransport(ModeTransportRepository $modeTransports): Response
    {
        $userModeTransport = $modeTransports->findBy(['user' => $this->getUser()]);

        return $this->render('setting_general/show_modetransport.html.twig', [ 'modetransports' => $userModeTransport]);
    }

    /**
     * Creates a new Mode trans entity.
     *
     * @Route("/mode-transport-new", methods={"GET", "POST"}, name="mode_transport_new")
     *
     */
    public function newModeTransport(Request $request, ObjectManager $em): Response
    {
        $modeTransport = new ModeTransport();

        // On Instancie le formulaire
        $form = $this->createForm(ModeTransportType::class, $modeTransport)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $modeTransport->setCreatedAt(new \DateTime());
            $modeTransport->setUser($this->getUser());

            $em->persist($modeTransport);
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('business_type_new');
            }

            return $this->redirectToRoute('mode_transport_new');
        }

        return $this->render('setting_general/edit_modetransport.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Mode trans entity.
     *
     * @Route("/mode-transport/{id<\d+>}/edit",methods={"GET", "POST"}, name="mode_transport_edit")
     *
     */
    public function editModeTransport(Request $request, ModeTransportRepository $modeTransports, ModeTransport $modeTransport, ObjectManager $em): Response
    {
        $form = $this->createForm(ModeTransportType::class, $modeTransport)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('business_type_new');
            }
            return $this->redirectToRoute('mode_transport');
        }
        $userModeTransport = $modeTransports->findBy(['user' => $this->getUser()]);

        return $this->render('setting_general/edit_modetransport.html.twig', [
            'form' => $form->createView(),
            'modetransports' => $userModeTransport,
        ]);
    }

    /**
     * Deletes a Mode trans entity.
     *
     * @Route("/mode-transport/{id}/delete", methods={"GET", "POST"}, name="mode_transport_delete")
     *
     */
    public function deleteModeTransport(ModeTransport $modeTransport, ObjectManager $em): Response
    {
        $em->remove($modeTransport);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('mode_transport');
    }

    /**
     * Lists all Priority .
     *
     *
     * @Route("{_locale}/priority", methods={"GET"}, name="priority")
     *
     */
    public function showPriority(PriorityRepository $priorities): Response
    {
        $userPriorities = $priorities->findBy(['user' => $this->getUser()]);

        return $this->render('setting_general/show_priority.html.twig', ['priorities' => $userPriorities]);
    }

    /**
     * Creates a new Priority entity.
     *
     * @Route("/priority/new", methods={"GET", "POST"}, name="priority_new")
     *
     */
    public function newPriority(Request $request, ObjectManager $em): Response
    {
        $priority = new Priority();

        // On Instancie le formulaire 
        $form = $this->createForm(PriorityType::class, $priority)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister 
            $priority->setCreatedAt(new \DateTime());
            $priority->setUser($this->getUser());

            $em->persist($priority);
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('priority_new');
            }

            return $this->redirectToRoute('priority');
        }

        return $this->render('setting_general/edit_priority.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Displays a form to edit an existing Priority entity.
     *
     * @Route("/priority/{id<\d+>}/edit",methods={"GET", "POST"}, name="priority_edit")
     * 
     */
    public function editPriority(Request $request, PriorityRepository $priorities, Priority $priority, ObjectManager $em): Response
    {
        $form = $this->createForm(PriorityType::class, $priority)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('priority_new');
            }
            return $this->redirectToRoute('priority');
        }
        $userPriorities = $priorities->findBy(['user' => $this->getUser()]);

        return $this->render('setting_general/edit_priority.html.twig', [
            'form' => $form->createView(),
            'priorities' => $userPriorities,
        ]);
    }

    /**
     * Deletes a Priority entity.
     *
     * @Route("/priority/{id}/delete", methods={"GET", "POST"}, name="priority_delete")
     * 
     */
    public function deletePriority(Priority $priority, ObjectManager $em): Response
    {
        $em->remove($priority);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('priority');
    }

    /**
     * Lists all Civility .
     *
     *
     * @Route("{_locale}/civility", methods={"GET"}, name="civility")
     *
     */
    public function showCivility(CivilityRepository $civilities): Response
    {
        $userCivilities = $civilities->findBy(['user' => $this->getUser()]);

        return $this->render('setting_general/show_civility.html.twig', ['civilities' => $userCivilities]);
    }

    /**
     * Creates a new Civility entity.
     *
     * @Route("/civility/new", methods={"GET", "POST"}, name="civility_new")
     *
     */
    public function newCivility(Request $request, ObjectManager $em): Response
    {
        $civility = new Civility();

        // On Instancie le formulaire
        $form = $this->createForm(CivilityType::class, $civility)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $civility->setCreatedAt(new \DateTime());
            $civility->setUser($this->getUser());

            $em->persist($civility);
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('civility_new');
            }

            return $this->redirectToRoute('civility');
        }

        return $this->render('setting_general/edit_civility.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Civility entity.
     *
     * @Route("/civility/{id<\d+>}/edit",methods={"GET", "POST"}, name="civility_edit")
     *
     */
    public function editCivility(Request $request, CivilityRepository $civilities, Civility $civility, ObjectManager $em): Response
    {
        $form = $this->createForm(CivilityType::class, $civility)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('civility_new');
            }
            return $this->redirectToRoute('civility');
        }
        $userCivilities = $civilities->findBy(['user' => $this->getUser()]);

        return $this->render('setting_general/edit_civility.html.twig', [
            'form' => $form->createView(),
            'civilities' => $userCivilities,
        ]);
    }

    /**
     * Deletes a Civility entity.
     *
     * @Route("/civility/{id}/delete", methods={"GET", "POST"}, name="civility_delete")
     *
     */
    public function deleteCivility(Civility $civility, ObjectManager $em): Response
    {
        $em->remove($civility);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('civility');
    }

    /**
     * Lists all Language .
     *
     *
     * @Route("{_locale}/language", methods={"GET"}, name="language")
     *
     */
    public function showLanguage(LanguageRepository $languages): Response
    {
        $userLanguages = $languages->findBy(['user' => $this->getUser()]);

        return $this->render('setting_general/show_language.html.twig', ['languages' => $userLanguages]);
    }

    /**
     * Creates a new Language entity.
     *
     * @Route("/language/new", methods={"GET", "POST"}, name="language_new")
     *
     */
    public function newLanguage(Request $request, ObjectManager $em): Response
    {
        $language = new Language();

        // On Instancie le formulaire
        $form = $this->createForm(LanguageType::class, $language)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $language->setCreatedAt(new \DateTime());
            $language->setUser($this->getUser());

            $em->persist($language);
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('language_new');
            }

            return $this->redirectToRoute('language');
        }


        return $this->render('setting_general/edit_language.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Language entity.
     *
     * @Route("/language/{id<\d+>}/edit",methods={"GET", "POST"}, name="language_edit")
     *
     */
    public function editLanguage(Request $request, LanguageRepository $languages, Language $language, ObjectManager $em): Response
    {
        $form = $this->createForm(LanguageType::class, $language)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('language_new');
            }
            return $this->redirectToRoute('language');
        }
        $userLanguages = $languages->findBy(['user' => $this->getUser()]);

        return $this->render('setting_general/edit_language.html.twig', [
            'form' => $form->createView(),
            'languages' => $userLanguages,
        ]);
    }

    /**
     * Deletes a Language entity.
     *
     * @Route("/language/{id}/delete", methods={"GET", "POST"}, name="language_delete")
     *
     */
    public function deleteLanguage(Language $language, ObjectManager $em): Response
    {
        $em->remove($language);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('language');
    }

    /**
     * Lists all Port .
     *
     *
     * @Route("{_locale}/customer", methods={"GET"}, name="customer")
     *
     */
    public function showCustomer(PersonRepository $persons): Response
    {
        $userCustomers = $persons->findPeopleAsCustomer();

        return $this->render('setting_general/show_customer.html.twig', [ 'customers' => $userCustomers,]);
    }

    /**
     * Creates a new Customer.
     *
     * @Route("{_locale}/customer/new", methods={"GET", "POST"}, name="customer_new")
     *
     */
    public function newCustomer(Request $request, Customer $customer = null, Person $person = null, ObjectManager $em): Response
    {
        if (!$customer) {
            $customer = new Customer();
        }
        if (!$person) {
            $person = new Person();
        }

        // On Instancie le formulaire
        $form = $this->createForm(CustomerType::class, $person)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $person->setCreatedAt(new \DateTime());
            $person->setUser($this->getUser());

            $customer->setPerson($person);
            $em->persist($customer);

            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('customer_new');
            }

            return $this->redirectToRoute('customer');
        }

        return $this->render('setting_general/edit_customer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Customer entity.
     *
     * @Route("{_locale}/customer/{id<\d+>}/edit", methods={"GET", "POST"}, name="customer_edit")
     *
     */
    public function editCustomer(Request $request, PersonRepository $people, Customer $customer, ObjectManager $em): Response
    {
        $person = $customer->getPerson();
        $form = $this->createForm(CustomerType::class, $person)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('customer_new');
            }

            return $this->redirectToRoute('customer');
        }
        $userCustomers = $people->findPeopleAsCustomer();

        return $this->render('setting_general/edit_customer.html.twig', [
            'person' => $person,
            'form' => $form->createView(),
            'customer' => $userCustomers,
        ]);
    }

    /**
     * Deletes a Customer entity.
     *
     * @Route("{_locale}/customer/{id}/delete", methods={"GET", "POST"}, name="customer_delete")
     *
     */
    public function deleteCustomer(Customer $customer, ObjectManager $em): Response
    {
        $em->remove($customer);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('customer');
    }

    /**
     * Lists all Port .
     *
     *
     * @Route("{_locale}/area", methods={"GET"}, name="area")
     *
     */
    public function showArea(GeneralAreaRepository $areas): Response
    {
        $userAreas = $areas->findBy([], ['id' => 'DESC']);

        return $this->render('setting_general/show_area.html.twig', [ 'areas' => $userAreas,]);
    }

    /**
     * Creates a new Port entity.
     *
     * @Route("/area/new", methods={"GET", "POST"}, name="area_new")
     *
     */
    public function newArea(Request $request, ObjectManager $em): Response
    {
        $area = new GeneralArea();

        // On Instancie le formulaire
        $form = $this->createForm(GeneralAreaType::class, $area)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $area->setCreatedAt(new \DateTime());
            $area->setUser($this->getUser());

            $em->persist($area);
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('area_new');
            }

            return $this->redirectToRoute('area');
        }

        return $this->render('setting_general/edit_area.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Port entity.
     *
     * @Route("/area/{id<\d+>}/edit", methods={"GET", "POST"}, name="area_edit")
     *
     */
    public function editArea(Request $request, GeneralArea $area, ObjectManager $em): Response
    {
        $form = $this->createForm(GeneralAreaType::class, $area)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('area_new');
            }

            return $this->redirectToRoute('area');
        }

        return $this->render('setting_general/edit_area.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Port entity.
     *
     * @Route("/area/{id}/delete", methods={"GET", "POST"}, name="area_delete")
     *
     */
    public function deleteArea(GeneralArea $area, ObjectManager $em): Response
    {
        $em->remove($area);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('area');
    }

}
