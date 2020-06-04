<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\ProduitRepository;
use App\Service\AppService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * class PanierController
 * @package App\Controller
 * @Route("/panier", name="panier_")
 */

class PanierController extends AbstractController
{
    /**
     * @Route("/", name="contenu")
     * @param SessionInterface $session
     * @param ProduitRepository $repository
     * @return Response
     */
    public function contenuDuPanier(AppService $service)
    {
        //on récupère le panier de la session
        //old way avec Request $request
        $contenuDuPanier = $service->contenuDuPanier();
        dd($contenuDuPanier);

        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }

    /**
     * @Route("/ajouter/{id}", name="ajouter", methods={"GET", "POST"})
     * @param int $id
     * @param AppService $service
     * @return RedirectResponse
     */
    public function ajouter(int $id, AppService $service)
    {

        //ajout dans le panier via :id
        $service->ajouterPanier($id);

        return $this->redirectToRoute('resto_client');
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer", methods={"GET", "POST"})
     * @param int $id
     * @param AppService $service
     * @return RedirectResponse
     */
    public function supprimer(int $id, AppService $service)
    {
        $service->supprimerDuPanier($id);
        return $this->redirectToRoute("resto_client");
    }


    /**
     * @Route("/valider", name="valider", methods={"GET", "POST"})
     * @param TokenStorageInterface $token
     * @return RedirectResponse
     */
    public function valider(TokenStorageInterface $token)
    {
        //on crée une nouvelle instance de Commande
        $cmd = new Commande();

        //on récupère le user actif dans la session en cours...
        $token = $this->getUser();

        //permet d'accèder a l'entité Reglement pour accèder au fonctions dans la "class Reglement"
        $idReglement = new \App\Entity\Reglement;
        $idReglement->getId();

        //Code qui permet d'initialiser la commande client

        //genere automatiquement le numero de table à faire !!
        $numeroTable = rand(1, 100);
        $cmd->setNumeroTable($numeroTable);
        $cmd->setDateCommande(new \DateTime());
        $cmd->setUser($token);
        $cmd->setReglement($idReglement);

        //Code qui permet l'enregistrement de la commande du client en base de donnée
        $em = $this->getDoctrine()->getManager();
        $em->persist($cmd);
        $em->flush();
        return $this->redirectToRoute("resto_client");
    }

}



