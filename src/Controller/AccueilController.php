<?php

namespace App\Controller;

use App\Service\AppService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Commande;

/**
 * class AccueilController
 * @package App\Controller
 * @Route("/", name="resto_")
 */
class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     * @param AppService $service
     * @param Request $request
     * @return Response
     */
    public function index(AppService $service,Request $request)
    {
        return $this->render('accueil/index.html.twig', [
            'message' => 'Bienvenue a Fresh&Eat!!!',
            //'produits' => $service->getListeProduitAccueil($request),
            //'lignesCmds' => $service->contenuDuPanier(),
            //'total' => $service->getTotalPanier()
        ]);
    }
    /**
     * @Route("/admin", name="admin")
     * @isGranted("ROLE_ADMIN", statusCode=404, message="Vous n'etes pas un admin dégager d'ici !!!")
     * @param Request $request
     * @return Response
     */
    public function admin(AppService $service, Request $request)
    {
        return $this->render('accueil/admin.html.twig', [
            'titre_page' => $service->getTitre("Espace admin"),
            'message' => 'Bienvenue admin',
            'produits' => $service->getListeProduitAccueil($request),
            'lignesCmds' => $service->contenuDuPanier(),
            'total' => $service->getTotalPanier()
        ]);
    }

    /**
     * @Route("/client", name="client", methods={"GET","POST"})
     * @param AppService $service
     * @param Request $request
     * @return Response
     * @isGranted("ROLE_USER")
     */
    public function client(AppService $service, Request $request)
    {
        return $this->render('accueil/client.html.twig', [
            'titre_page' => $service->getTitre("Espace Client"),
            'message' => 'Bienvenue cher Client',
            'produits' => $service->getListeProduitAccueil($request),
            'lignesCmds' => $service->contenuDuPanier(),
            'total' => $service->getTotalPanier()
        ]);
    }

    /**
     * @Route("/cuistot", name="cuistot", methods={"GET","POST"})
     * @param AppService $service
     * @param Request $request
     * @return Response
     * @isGranted("ROLE_CUISTOT")
     */
    public function cuistot(AppService $service, Request $request)
    {
        return $this->render('accueil/cuistot.html.twig', [
            'titre_page' => $service->getTitre("Espace Cuistot"),
            'cmds' => $service->getListeCommande($request),

        ]);
    }
/**
     * @Route("/serveur", name="serveur", methods={"GET","POST"})
     * @param Request $request
     * @param AppService $service
     * @isGranted("ROLE_SERVEUR")
     * @return Response
     */
    public function serveur(AppService $service, Request $request)
    {
        return $this->render('accueil/serveur.html.twig', [
            'titre_page' => $service->getTitre("Espace Serveur"),
            'cmds' => $service->getListeCommande($request),
            'clients' => $service->getListeClient($request)
          
        ]);
    }

 /**
     * @Route("/caissier", name="caissier", methods={"GET","POST"})
     * @param Request $request
     * @param AppService $service
     * @isGranted("ROLE_CAISSIER")
     * @return Response
     */
    public function caissier(AppService $service, Request $request)
    {
        return $this->render('accueil/caissier.html.twig', [
            'titre_page' => $service->getTitre("Espace Caissier"),
            'cmds' => $service->getListeCommande($request),
          
        ]);
    }

    /**
     * @param Commande $cmd
     * @isGranted("ROLE_CUISTOT")
     * @Route("/{id}/pret", name="pret")
     */
    public function CommandePrete($id)
    {
        //initialisation msg flash
        $this->addFlash('success', 'Commande client prête à envoi');

        //On initialise entity manager pour acceder au repository de "Commande"
        $em = $this->getDoctrine()->getManager();

        //On récupère une code par son id
        $cmd = $em->getRepository(Commande::class)->find($id);

        if (!$cmd) {
            return $this->redirectToRoute('resto_cuistot');
        } 
            return $this->redirectToRoute('resto_cuistot');

        

    }

 /**
     * @param Commande $cmd
     * @isGranted("ROLE_CAISSIER")
     * @Route("/{id}/reglement", name="reglement")
     */
    public function reglementCommande($id)
    {
        //initialisation msg flash
        $this->addFlash('success', 'Commande client réglé');

        //On initialise entity manager pour acceder au repository de "Commande"
        $em = $this->getDoctrine()->getManager();

        //On récupère une code par son id
        $cmd = $em->getRepository(Commande::class)->find($id);

        if (!$cmd) {
            return $this->redirectToRoute('resto_caissier');
        } 
            $em->remove($cmd);
            $em->flush();
            return $this->redirectToRoute('resto_caissier');
    }

    /**
     * @param Commande $cmd
     * @isGranted("ROLE_SERVEUR")
     * @Route("/{id}/envoi", name="envoi")
     */
    public function pretEnvoiReglement($id)
    {
        //initialisation msg flash
        $this->addFlash('success', 'Commande client envoyer en caisse !!');

        //On initialise entity manager pour acceder au repository de "Commande"
        $em = $this->getDoctrine()->getManager();

        //On récupère une code par son id
        $cmd = $em->getRepository(Commande::class)->find($id);

        if (!$cmd) {
            return $this->redirectToRoute('resto_serveur');
        } 
            return $this->redirectToRoute('resto_serveur');

    }
}