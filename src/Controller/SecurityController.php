<?php

namespace App\Controller;

use App\Service\AppService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\RegistrationType;


/**
 * Class SecurityController
 * @package App\Controller
 * @Route("/", name="app_")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login", methods={"GET","POST"})
     * @param AuthenticationUtils $authenticationUtils
     * @param AppService $appService
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils,AppService $appService ): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error,
        'titre_page'=> $appService->getTitre("login Page"),
        ]);
    }

    /**
     * @Route("/enregistrement", name="enregistrement", methods={"GET", "POST"})
     * @param Request $request
     * @param AppService $service
     * @param GuardAuthenticatorHandler $guardHandler
     * @param LoginFormAuthenticator $formAuthenticator
     * @return Response
     */
    public function register(Request $request,AppService $service, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $formAuthenticator)
    {
        // new instance de la Class User
        $user = new User();

        //On crée le form avec la class RegistrationType
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        //check si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            //on persiste en db
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            //permettra de logger directemet le client crée sans avoir à passer par un login
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $formAuthenticator,
                'main'
            );
        }
        
        $codeclient=1234;
        return $this->render('security/enregistrement.html.twig', [
    
            'titre_page' => $service->getTitre("Enregistrement"),
            'form' => $form->createView(),
            'code'=> $codeclient,
        ]);

    }


    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return new \Exception("sera intercepté avant d´arriver ici");
    }
}
