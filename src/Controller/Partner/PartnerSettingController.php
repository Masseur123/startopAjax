<?php

namespace App\Controller\Partner;

use App\Entity\CustomerGroup;

use App\Repository\CustomerGroupRepository;

use App\Form\CustomerGroupType;

use Doctrine\Common\Persistence\ObjectManager;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Twig\Profiler\Node\LeaveProfileNode;
use Twig_TokenParser_From;

/**
 * Controller used to manage Partner Settings.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class PartnerSettingController extends AbstractController
{
    /**
     * Creates a new Customer Group entity.
     *
     * @Route("{_locale}/customer-group", methods={"GET", "POST"}, name="customer_group")
     *
     */
    public function newCustomerGroup(Request $request, CustomerGroupRepository $customerGroups, ObjectManager $em): Response
    {
        $customerGroup = new CustomerGroup();

        // On Instancie le formulaire
        $form = $this->createForm(CustomerGroupType::class, $customerGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $customerGroup->setCreatedAt(new \DateTime());
            $customerGroup->setUser($this->getUser());

            $em->persist($customerGroup);
            $em->flush();

            $this->addFlash('success', 'Success!');
            return $this->redirectToRoute('customer_group');
        }
        $userCustomerGroups = $customerGroups->findBy([], ['id' => 'DESC']);

        return $this->render('partner_setting/edit_customer-group.html.twig', [
            'form' => $form->createView(),
            'custgroups' => $userCustomerGroups,
        ]);
    }

    /**
     * Displays a form to edit an existing Customer Group entity.
     *
     * @Route("{_locale}/customer-group/{id<\d+>}/edit",methods={"GET", "POST"}, name="customer_group_edit")
     *
     */
    public function editCustomerGroup(Request $request, CustomerGroup $customerGroup, CustomerGroupRepository $customerGroups, ObjectManager $em): Response
    {
        $form = $this->createForm(CustomerGroupType::class, $customerGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');
            return $this->redirectToRoute('customer_group');
        }
        $userCustomerGroups = $customerGroups->findBy([], ['id' => 'DESC']);

        return $this->render('partner_setting/edit_customer-group.html.twig', [
            'custgroup' => $customerGroup,
            'form' => $form->createView(),
            'custgroups' => $userCustomerGroups,
        ]);
    }

    /**
     * Deletes a Customer Group entity.
     *
     * @Route("{_locale}/customer-group/{id}/delete", methods={"GET", "POST"}, name="customer_group_delete")
     *
     */
    public function deleteCustomerGroup(CustomerGroup $customerGroup, ObjectManager $em): Response
    {
        $em->remove($customerGroup);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('customer_group');
    }
}
