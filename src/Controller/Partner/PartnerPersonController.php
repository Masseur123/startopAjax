<?php

namespace App\Controller\Partner;

use App\Entity\Customer;
use App\Entity\Exporter;
use App\Entity\Person;
use App\Entity\Provider;
use App\Entity\ShippingLine;

use App\Form\CompanyType;

use App\Repository\PersonRepository;


use Doctrine\Common\Persistence\ObjectManager;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Twig\Node\Expression\Filter\DefaultFilter;

/**
 * Controller used to manage Partner morale or physycal person.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class PartnerPersonController extends AbstractController
{
    /**
     * Creates a new Customer.
     *
     * @Route("{_locale}/customer", methods={"GET", "POST"}, name="customer")
     *
     */
    public function newCustomer(Request $request, PersonRepository $people, Customer $customer = null, Person $person = null, ObjectManager $em): Response
    {
        if (!$customer) {
            $customer = new Customer();
        }
        if (!$person) {
            $person = new Person();
        }

        // On Instancie le formulaire
        $form = $this->createForm(CompanyType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $person->setCreatedAt(new \DateTime());
            $person->setUser($this->getUser());

            $customer->setPerson($person);
            $em->persist($customer);

            $em->flush();

            $this->addFlash('success', 'Success!');

            return $this->redirectToRoute('customer');
        }
        $userCustomers = $people->findPeopleAsCustomer();

        return $this->render('partner_person/edit_customer.html.twig', [
            'form' => $form->createView(),
            'people' => $userCustomers,
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
        $form = $this->createForm(CompanyType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');

            return $this->redirectToRoute('customer');
        }
        $userCustomers = $people->findPeopleAsCustomer();

        return $this->render('partner_person/edit_customer.html.twig', [
            'person' => $person,
            'form' => $form->createView(),
            'people' => $userCustomers,
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
     * Creates a new Provider entity.
     *
     * @Route("{_locale}/provider", methods={"GET", "POST"}, name="provider")
     *
     */
    public function newProvider(Request $request, PersonRepository $people, Provider $provider = null, Person $person = null, ObjectManager $em): Response
    {
        if (!$provider) {
            $provider = new Provider();
        }
        if (!$person) {
            $person = new Person();
        }

        // On Instancie le formulaire
        $form = $this->createForm(CompanyType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $person->setCreatedAt(new \DateTime());
            $person->setUser($this->getUser());

            $provider->setPerson($person);
            $em->persist($provider);

            $em->flush();

            $this->addFlash('success', 'Success!');

            return $this->redirectToRoute('provider');
        }
        $userProviders = $people->findPeopleAsProvider();

        return $this->render('partner_person/edit_provider.html.twig', [
            'form' => $form->createView(),
            'people' => $userProviders,
        ]);
    }

    /**
     * Displays a form to edit an existing Exporter entity.
     *
     * @Route("{_locale}/provider/{id<\d+>}/edit", methods={"GET", "POST"}, name="provider_edit")
     *
     */
    public function editProvider(Request $request, PersonRepository $people, Provider $provider, ObjectManager $em): Response
    {
        $person = $provider->getPerson();
        $form = $this->createForm(CompanyType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');

            return $this->redirectToRoute('provider');
        }
        $userExporters = $people->findPeopleAsProvider();

        return $this->render('partner_person/edit_provider.html.twig', [
            'person' => $person,
            'form' => $form->createView(),
            'people' => $userExporters,
        ]);
    }

    /**
     * Deletes a Exporter entity.
     *
     * @Route("{_locale}/provider/{id}/delete", methods={"GET", "POST"}, name="provider_delete")
     *
     */
    public function deleteProvider(Provider $provider, ObjectManager $em): Response
    {
        $em->remove($provider);
        $em->flush();

        $this->addFlash('success', 'deleted_successfully');
        return $this->redirectToRoute('provider');
    }

    /**
     * Creates a new Shipping line entity.
     *
     * @Route("{_locale}/shippingline", methods={"GET", "POST"}, name="shippingline")
     *
     */
    public function newShippingLine(Request $request, PersonRepository $people, ShippingLine $shippingLine = null, Person $person = null, ObjectManager $em): Response
    {
        if (!$shippingLine) {
            $shippingLine = new ShippingLine();
        }
        if (!$person) {
            $person = new Person();
        }

        // On Instancie le formulaire
        $form = $this->createForm(CompanyType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $person->setCreatedAt(new \DateTime());
            $person->setUser($this->getUser());

            $shippingLine->setPerson($person);
            $em->persist($shippingLine);

            $em->flush();

            $this->addFlash('success', 'Success!');

            return $this->redirectToRoute('shippingline');
        }
        $userShippingLines = $people->findShippingLineAsCompany();

        return $this->render('partner_person/edit_shippingline.html.twig', [
            'form' => $form->createView(),
            'people' => $userShippingLines,
        ]);
    }

    /**
     * Displays a form to edit an existing Shipping Line entity.
     *
     * @Route("{_locale}/shippingline/{id<\d+>}/edit",methods={"GET", "POST"}, name="shippingline_edit")
     *
     */
    public function editShippingLine(Request $request, PersonRepository $people, ShippingLine $shippingLine, ObjectManager $em): Response
    {
        $person = $shippingLine->getPerson();
        $form = $this->createForm(CompanyType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');
            return $this->redirectToRoute('shippingline');
        }
        $userShippingLines = $people->findShippingLineAsCompany();

        return $this->render('partner_person/edit_shippingline.html.twig', [
            'person' => $person,
            'form' => $form->createView(),
            'people' => $userShippingLines,
        ]);
    }

    /**
     * Deletes a Shipping Line entity.
     *
     * @Route("{_locale}/shippingline/{id}/delete", methods={"GET", "POST"}, name="shippingline_delete")
     *
     */
    public function deleteShippingLine(ShippingLine $shippingLine, ObjectManager $em): Response
    {
        $em->remove($shippingLine);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('shippingline');
    }

    /**
     * Creates a new Exporter entity.
     *
     * @Route("{_locale}/exporter", methods={"GET", "POST"}, name="exporter")
     *
     */
    public function newExporter(Request $request, PersonRepository $people, Exporter $exporter = null, Person $person = null, ObjectManager $em): Response
    {
        if (!$exporter) {
            $exporter = new Exporter();
        }
        if (!$person) {
            $person = new Person();
        }

        // On Instancie le formulaire
        $form = $this->createForm(CompanyType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $person->setCreatedAt(new \DateTime());
            $person->setUser($this->getUser());

            $exporter->setPerson($person);
            $em->persist($exporter);

            $em->flush();

            $this->addFlash('success', 'Success!');

            return $this->redirectToRoute('exporter');
        }
        $userExporters = $people->findExporterAsCompany();

        return $this->render('partner_person/edit_exporter.html.twig', [
            'form' => $form->createView(),
            'people' => $userExporters,
        ]);
    }

    /**
     * Displays a form to edit an existing Exporter entity.
     *
     * @Route("{_locale}/exporter/{id<\d+>}/edit", methods={"GET", "POST"}, name="exporter_edit")
     *
     */
    public function editExporter(Request $request, PersonRepository $people, Exporter $exporter, ObjectManager $em): Response
    {
        $person = $exporter->getPerson();
        $form = $this->createForm(CompanyType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');

            return $this->redirectToRoute('exporter');
        }
        $userExporters = $people->findExporterAsCompany();

        return $this->render('partner_person/edit_exporter.html.twig', [
            'person' => $person,
            'form' => $form->createView(),
            'people' => $userExporters,
        ]);
    }

    /**
     * Deletes a Exporter entity.
     *
     * @Route("{_locale}/exporter/{id}/delete", methods={"GET", "POST"}, name="exporter_delete")
     *
     */
    public function deleteExporter(Exporter $exporter, ObjectManager $em): Response
    {
        $em->remove($exporter);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('exporter');
    }
}
