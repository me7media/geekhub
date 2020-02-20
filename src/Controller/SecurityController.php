<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class SecurityController extends AbstractController
{

//    private $encoder;
//
//    public function __construct($encoder)
//    {
//        $this->encoder = $encoder;
//    }

    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('category_index');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, AppAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/token", name="token")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param JWTEncoderInterface $jwt
     * @return JsonResponse
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException
     */
    public function token(Request $request, UserPasswordEncoderInterface $passwordEncoder, JWTEncoderInterface $jwt):JsonResponse
    {
//        $token = $this->encoder
//            ->encode([
//                'username' => 'admin@admin.admin',
//                'exp' => time() + 3600 // 1 hour expiration
//            ]);
//        return new JsonResponse(['token' => $token]);

        $email = $request->get('email') ?? $request->get('username') ?? $request->getUser() ?? 'no';

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        if (!$user) {
            throw $this->createNotFoundException();
        }

        $pass = $request->get('password') ?? $request->get('pass') ?? $request->getPassword() ?? 'no';

        $isValid = $passwordEncoder->isPasswordValid($user, $pass);

        if (!$isValid) {
            throw new BadCredentialsException('Bad, you are very bad!');
        }

        $token = $jwt->encode([
                'username' => $user->getEmail(),
                'exp' => time() + 3600 // 1 hour expiration
            ]);

        return new JsonResponse(['token' => $token]);
    }

}
