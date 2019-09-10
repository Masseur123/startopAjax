<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Entity\UserGroup;

use App\Form\UserProfileType;
use App\Form\UserType;
use App\Form\GroupType;

use App\Repository\UserRepository;
use App\Repository\UserGroupRepository;

use Doctrine\ORM\Internal\Hydration\IterableResult;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Stopwatch\StopwatchEvent;
use Twig\Node\SandboxNode;

/**
 * Controller used to manage User in StarTop.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class AdminUserController extends AbstractController
{
	/**
     * Lists all Users.
     *
     * @Route("{_locale}/user", methods={"GET"}, name="user")
     *
     */
    public function showUser(UserRepository $users): Response
    {
		$userUsers = $users->findBy([], ['id' => 'DESC']);

        return $this->render('admin_user/show_user.html.twig', ['users' => $userUsers]);
    }
	
    /**
     * Create a new User entity.
     *
     * @Route("{_locale}/user/new", methods={"GET", "POST"}, name="user_new")
     *
     */
    public function newUser(Request $request, UserRepository $users, UserPasswordEncoderInterface $encoder, ObjectManager $em): Response
    {
        $user = new User();

        // On Instancie le formulaire
        $form = $this->createForm(UserType::class, $user)
				->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = "0000";
            $encoded = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);

            $user->setIsEnabled(true);

            /*$groups = $form->get('userGroups')->getData();
            foreach ($groups as $group) {
                $user->addUserGroup($group);
            }*/

            $user->setIsLock(false);

            // firstLoginAt
            // lastLoginAt
            // loginCount

            $user->setUser($this->getUser());
            $user->setCreatedAt(new \DateTime());

            $branch = $users->findOneBy(['id' => $this->getUser()])->getBranch();
            $user->setBranch($branch);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Success!');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('user_new');
            }
			
            return $this->redirectToRoute('user');
        }

        return $this->render('admin_user/edit_user.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("{_locale}/user/{id<\d+>}/edit", methods={"GET", "POST"}, name="user_edit")
     * 
     */
    public function editUser(Request $request, User $user, ObjectManager $em): Response
    {
        $form = $this->createForm(UserType::class, $user)
				->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            $this->addFlash('success', 'Success!');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('user_new');
            }
			
            return $this->redirectToRoute('user');
        }

        return $this->render('admin_user/edit_user.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'users' => $userUsers,
        ]);
    }

    /**
     * Deletes a User entity.
     *
     * @Route("{_locale}/user/{id}/delete", methods={"GET", "POST"}, name="user_delete")
     * 
     */
    public function deleteUser(User $user, ObjectManager $em): Response
    {
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('user');
    }

	/**
     * Lists all Groups.
     *
     * @Route("{_locale}/usergroup", methods={"GET"}, name="usergroup")
     *
     */
    public function showUserGroup(UserGroupRepository $usergroups): Response
    {
		$userGroups = $usergroups->findBy([], ['id' => 'DESC']);

        return $this->render('admin_user/show_usergroup.html.twig', ['usergroups' => $userGroups]);
    }
	
    /**
     * Creates a new UserGroup entity.
     *
     * @Route("{_locale}/usergroup/new", methods={"GET", "POST"}, name="usergroup_new")
     *
     */
    public function newUserGroup(Request $request, ObjectManager $em, UserRepository $user): Response
    {
        $group = new UserGroup();

        // On Instancie le formulaire 
        $form = $this->createForm(GroupType::class, $group)
				->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister

            // Get Branch 
            $branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();

            $group->setCreatedAt(new \DateTime());
            $group->setIsEnabled(true);

            $group->setBranch($branch);

            $em->persist($group);
            $em->flush();

            $this->addFlash('success', 'Success!');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('usergroup_new');
            }

            return $this->redirectToRoute('usergroup');
        }
        
        return $this->render('admin_user/edit_usergroup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing UserGroup entity.
     *
     * @Route("{_locale}/usergroup/{id<\d+>}/edit", methods={"GET", "POST"}, name="usergroup_edit")
     * 
     */
    public function editUserGroup(Request $request, UserGroup $group, ObjectManager $em): Response
    {
        $form = $this->createForm(GroupType::class, $group)
				->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            $this->addFlash('success', 'Success!');
			
			if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('usergroup_new');
            }
			
            return $this->redirectToRoute('usergroup');
        }
		
        return $this->render('admin_user/edit_usergroup.html.twig', [
            'group' => $group,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a UserGroup entity.
     *
     * @Route("{_locale}/usergroup/{id}/delete", methods={"GET", "POST"}, name="usergroup_delete")
     * 
     */
    public function deleteUserGroup(UserGroup $group, ObjectManager $em): Response
    {
        $em->remove($group);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('usergroup');
    }

    /**
     * Displays a form to edit an existing UserGroup entity.
     *
     * @Route("{_locale}/password/update", methods={"GET", "POST"}, name="user_password_update")
     *
     */
    public function updateUserPassword(Request $request, UserPasswordEncoderInterface $encoder, ObjectManager $em): Response
    {
        $form = $this->createFormBuilder()
            ->add("current_password", PasswordType::class)
            ->add("new_password", PasswordType::class)
            ->add("repeat_password", PasswordType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            // Field data
            $currentPassword = $form->get('current_password')->getData();
            $newPassword = $form->get('new_password')->getData();
            $repeatPassword = $form->get('repeat_password')->getData();

            // $encoded = $encoder->encodePassword($user, $oldPassword); isPasswordValid($userObject, $currentPassword)
            //$match = $encoder->isPasswordValid($this->getUser(), $oldPassword);

            //dump($match); exit();

            // Check if old password is exact
            if(!$encoder->isPasswordValid($this->getUser(), $currentPassword)){
                $this->addFlash('warning', 'Current Password not found!');
                return $this->redirectToRoute('user_password_update');
            }

            // Check if repeat password is not equal to new password
            if($repeatPassword != $newPassword){
                $this->addFlash('warning', 'Repeat Password is not equal to New Password!');
                return $this->redirectToRoute('user_password_update');
            }

            // Update the current password
            $encoded = $encoder->encodePassword($user, $newPassword);
            $user->setPassword($encoded);

            $em->flush();

            //$this->addFlash('success', 'You successfully changed your password - Please Log In with the new one!');
            return $this->redirectToRoute('starTop_logout');
        }
        return $this->render('admin_user/update_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing UserGroup entity.
     *
     * @Route("{_locale}/profile/update", methods={"GET", "POST"}, name="user_profile_update")
     *
     */
    public function updateUserProfile(Request $request, ObjectManager $em): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Success!');

            return $this->redirectToRoute('user_profile_update');
        }
        return $this->render('admin_user/update_profile.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    public function passwordUnique($car)
    {
        $string = "";
        $chaine = "-/*+abcdefghjkmnpqrstuvwxy@ABCDEFGHJKMNPRSTUVWXY123456789";
        srand((double)microtime() * 1000000);
        for ($i = 0; $i < $car; $i++) {
            $string .= $chaine[rand() % strlen($chaine)];
        }
        return $string;
    }
}
