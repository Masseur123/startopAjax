<?php

namespace App\Controller\Security;


use App\Entity\Role;
use App\Entity\Component;

use App\Repository\RoleRepository;
use App\Repository\ComponentRepository;
use App\Repository\GroupComponentRoleRepository;

use App\Form\RoleType;
use App\Form\ComponentType;


use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Component\DependencyInjection\Compiler\AutoAliasServicePass;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Tests\DependencyInjection\RoutingResolverPassTest;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Twig\Node\EmbedNode;
use Twig\Node\Expression\Binary\RangeBinary;

/**
 * Controller used to manage roles in StarTop.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class StartopController extends AbstractController
{
	/**
     * Lists all Components.
     *
     * @Route("{_locale}/component", methods={"GET"}, name="component")
     *
     */
    public function showComponent(ComponentRepository $components): Response
    {
		$userComponents = $components->findBy([], ['position' => 'ASC']);

        return $this->render('startop/show_component.html.twig', ['components' => $userComponents]);
    }
	
	/**
     * Create a new Component entity.
     *
     * @Route("{_locale}/component/new", methods={"GET", "POST"}, name="component_new")
     *
     */
    public function newComponent(Request $request, Component $component = null, ObjectManager $em): Response
    {
        if (!$component) {
            $component = new Component();
        }

        // On Instancie le formulaire
        $form = $this->createForm(ComponentType::class, $component)
				->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $component->setIsEnabled(true);

            $em->persist($component);
            $em->flush();

            $this->addFlash('success', 'Success!');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('component_new');
            }

            return $this->redirectToRoute('component');
        }

        return $this->render('startop/edit_component.html.twig', [
            'component' => $component,
            'form' => $form->createView(),
        ]);
    }
	
	/**
     * Displays a form to edit an existing Component entity.
     *
     * @Route("{_locale}/component/{id<\d+>}/edit", methods={"GET", "POST"}, name="component_edit")
     *
     */
    public function editComponent(Request $request, Component $component, ObjectManager $em): Response
    {
        $form = $this->createForm(ComponentType::class, $component)
				->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('component_new');
            }

            return $this->redirectToRoute('component');
        }

        return $this->render('startop/edit_component.html.twig', [
            'component' => $component,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Component entity.
     *
     * @Route("{_locale}/component/{id}/delete", methods={"GET", "POST"}, name="component_delete")
     *
     */
    public function deleteComponent(Component $component, ObjectManager $em): Response
    {
        $em->remove($component);
        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('component');
    }
	
	/**
     * Enabled a component entity.
     *
     * @Route("_locale}/component/{id}/enable", methods={"GET", "POST"}, name="component_enable")
     *
     */
    public function enableComponent(Component $component, ObjectManager $em): Response
    {
        $component->setIsEnabled(true);

        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('component');
    }

    /**
     * Blocked a component entity.
     *
     * @Route("_locale}/component/{id}/block", methods={"GET", "POST"}, name="component_block")
     *
     */
    public function blockComponent(Component $component, ObjectManager $em): Response
    {
        $component->setIsEnabled(false);

        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('component');
    }
	
	/**
     * Lists all Users.
     *
     * @Route("{_locale}/role", methods={"GET"}, name="role")
     *
     */
    public function showRole(RoleRepository $roles): Response
    {
		$userRoles = $roles->findBy([], ['id' => 'DESC']);

        return $this->render('startop/show_role.html.twig', ['roles' => $userRoles]);
    }
	
	/**
     * Creates a new Role entity.
     *
     * @Route("{_locale}/role/new", methods={"GET", "POST"}, name="role_new")
     *
     */
    public function newRole(Request $request, Role $role = null, ObjectManager $em): Response
    {
        if (!$role) {
            $role = new Role();
        }

        // On Instancie le formulaire 
        $form = $this->createForm(RoleType::class, $role)
				->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister 
            $role->setCreatedAt(new \DateTime());
            $role->setIsEnabled(true);

            $em->persist($role);
            $em->flush();

            $this->addFlash('success', 'Success!');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('role_new');
            }

            return $this->redirectToRoute('role');
        }

        return $this->render('startop/edit_role.html.twig', [
            'role' => $role,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Role entity.
     *
     * @Route("{_locale}/role/{id<\d+>}/edit",methods={"GET", "POST"}, name="role_edit")
     * 
     */
    public function editRole(Request $request, Role $role, ObjectManager $em): Response
    {
        $form = $this->createForm(RoleType::class, $role)
				->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('role_new');
            }

            return $this->redirectToRoute('role');
        }

        return $this->render('startop/edit_role.html.twig', [
            'role' => $role,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Role entity.
     *
     * @Route("{_locale}/role/{id}/delete", methods={"GET", "POST"}, name="role_delete")
     * 
     */
    public function deleteRole(Role $role, ObjectManager $em): Response
    {
        $em->remove($role);
        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('role');
    }
	
	/**
     * Enabled a role entity.
     *
     * @Route("_locale}/role/{id}/enable", methods={"GET", "POST"}, name="role_enable")
     *
     */
    public function enableRole(Role $role, ObjectManager $em): Response
    {
        $role->setIsEnabled(true);

        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('role');
    }

    /**
     * Blocked a role entity.
     *
     * @Route("_locale}/role/{id}/block", methods={"GET", "POST"}, name="role_block")
     *
     */
    public function blockRole(Role $role, ObjectManager $em): Response
    {
        $role->setIsEnabled(false);

        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('role');
    }
	
    /**
     * @Route("{_locale}/account_setting", methods={"GET", "POST"}, name="account_setting")
     * 
     */
    public function componentSetting(): Response
    {
        // Get current user's groups : [user_group_user] getUserGroups()
        $userGroups = $this->getUser()->getUserGroups();

        // Create an array collection for user's components 
        $userComponents = new ArrayCollection();

        // Load all components assigned to each group : [user_group_component] getComponents
        foreach ($userGroups as $userGroup) {
            // For each user's group get it's components  
            $groupComponents = $userGroup->getComponents();

            // For each group's component check if it is in $userComponents array colletion 
            foreach ($groupComponents as $groupComponent) {
                if (!$userComponents->contains($groupComponent) && $groupComponent->getIsEnabled()) {
                    $userComponents[] = $groupComponent;
                }
            }
        }

        return $this->render('startop/account-setting.html.twig', [
            'userComponents' => $userComponents,
        ]);
    }


    /**
     * Load one Component with all the roles.
     *
     * @Route("{_locale}/{id<\d+>}/component-loading", methods={"GET", "POST"}, name="component_loading")
     *
     */
    public function componentLoading(Component $component, GroupComponentRoleRepository $groupComponentRoles): Response
    {
        // We need group - component - > to query corresponding roles in [groupcomponentrole]
        // We already have component as id 
        // So it is remaining group ?

        // Get current user's groups : [user_group_user] getUserGroups()
        $userGroups = $this->getUser()->getUserGroups();

        // Create an array collection for component's menus 
        $menus = new ArrayCollection();

        // Create an array collection for component's roles 
        $roles = new ArrayCollection();

        foreach ($userGroups as $userGroup) {
            // Query in groupcomponentrole where $userGroup - $component
            $groupComponentRoleResults = $groupComponentRoles->findByGroupAndComponent($userGroup, $component);

            foreach ($groupComponentRoleResults as $groupComponentRoleResult) {
                if (!$groupComponentRoleResult->getRole()->getMenu() && !$menus->contains($groupComponentRoleResult->getRole())) {
                    $menus[] = $groupComponentRoleResult->getRole();
                }

                if ($groupComponentRoleResult->getRole()->getMenu() && !$roles->contains($groupComponentRoleResult->getRole())) {
                    $roles[] = $groupComponentRoleResult->getRole();
                }
            }
        }


        $session = new Session();
        //$session->start();

        // set menu and role session attributes
        $session->set('menu', $menus);
        $session->set('role', $roles);
        //$sessMenus = $session->get('menu');

        /*foreach ($sessMenus as $sessMenu) {
            var_dump($sessMenu->getName());
        }
        die;*/

        /*return $this->render('startop/component-loading.html.twig', [
            'menus' => $menus,
            'roles' => $roles,
        ]);*/
        return $this->redirectToRoute('transit_dashboard');
    }
}
