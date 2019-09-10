<?php

namespace App\Controller\Security;


use App\Repository\UserRepository;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Bridge\Doctrine\Tests\Form\Type\EntityTypeTest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Stopwatch\StopwatchEvent;

/**
 * Controller used to manage security in ZoZoo.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="starTop_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * This is the route the user can use to logout.
     *
     * But, this will never be executed. Symfony will intercept this first
     * and handle the logout automatically. See logout in config/packages/security.yaml
     *
     * @Route("/logout", name="starTop_logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }


    /**
     * Display a form to reset password.
     *
     * @Route("/password/reset", methods={"GET", "POST"}, name="login_password_reset")
     *
     */
    public function resetPassword(Request $request, ObjectManager $em, UserPasswordEncoderInterface $encoder, UserRepository $user): Response
    {
        $form = $this->createFormBuilder()
            ->add("username", TextType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $username = $form->get('username')->getData();

            // Check if that username exist

            if($user->findOneBy(['username' => $username]))
            {
                // User found
                $user = $user->findOneBy(['username' => $username]);

                // $user->setUsername($username);
                $plainPassword = "123456789";
                $encoded = $encoder->encodePassword($user, $plainPassword);
                $user->setPassword($encoded);

                $em->flush();

                // $this->addFlash('success', 'Success!');

                return $this->redirectToRoute('starTop_login');
            }
            else{
                // User not found
                // $this->addFlash('danger', 'Wrong!');

                return $this->redirectToRoute('login_password_reset');
            }
        }

        return $this->render('security/login_password_reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
