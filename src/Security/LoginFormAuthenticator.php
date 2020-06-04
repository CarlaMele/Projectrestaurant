<?php

namespace App\Security;
use App\Repository\UserRepository;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{

    use TargetPathTrait;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrfTokenManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * LoginFormAuthenticator constructor
     *
     * @param UserRepository $userRepository
     * @param RouterInterface $router
     * @param CsrfTokenManagerInterface $csrfTokenManager
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */

    public function __construct(UserRepository $userRepository, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function supports(Request $request)
    {
        //Première méthode qui ce déclenchera dans l'app
        //die("Notre process de connection démarre ici !!!");
        return $request->attributes->get('_route') === "app_login" && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'codeInterface' => $request->request->get('code_interface'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        return  $this->userRepository->findOneBy(['email' => $credentials['email']]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $codeInterface = $credentials['codeInterface'];
        $codeInDB = $this->userRepository->findOneBy(['code_interface' => $codeInterface]);
        if ($codeInDB != $codeInterface) {
            return false;
        }
        return true;


    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return parent::onAuthenticationFailure($request, $exception);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        //permet de ciblé la dernière url taper dans le navigateur
        //$cheminCible = $this->getTargetPath($request->getSession(), $providerKey);
        // if($cheminCible) {
        //  return new RedirectResponse($cheminCible);
        //}
        //dd("Authentification Réussi");
        //return new RedirectResponse($this->router->generate('resto_accueil'));

        // }
        //permet de récupèrer le user en session 
        $role = $token->getUser()->getRoles();

        //condition qui verifie si le role du user se trouve dans in_array et retourne la page qu'il faut !
        if(in_array('ROLE_ADMIN', $role)) {

            return new RedirectResponse($this->router->generate('resto_admin'));

        } elseif(in_array('ROLE_CUISTOT', $role)) {

            return new RedirectResponse($this->router->generate('resto_cuistot'));

        } elseif(in_array('ROLE_SERVEUR', $role)) {

            return new RedirectResponse($this->router->generate('resto_serveur'));

        }elseif(in_array('ROLE_CAISSIER', $role)) {

            return new RedirectResponse($this->router->generate('resto_caissier'));

          } elseif(in_array('ROLE_USER', $role)) {

            return new RedirectResponse($this->router->generate('resto_client'));
          }

        $role = $token->getUser()->getRoles();
        //condition qui verifie si le role du user se trouve dans in_array et retourne la page qu'il faut !
        if (in_array('ROLE_ADMIN', $role)) {

            return new RedirectResponse($this->router->generate('resto_admin'));

        } elseif (in_array('ROLE_CUISTOT', $role)) {

            return new RedirectResponse($this->router->generate('resto_cuistot'));

        } elseif (in_array('ROLE_USER', $role)) {

            return new RedirectResponse($this->router->generate('resto_client'));
        }
    }
    protected function getLoginUrl()
    {
        return $this->router->generate('app_login');
    }
}
