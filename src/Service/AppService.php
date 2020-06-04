<?php

namespace App\Service;

use App\Entity\Composer;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

//création classe service 
class AppService 
{
    /**
     * @var ProduitRepository
     */
    private $produitRepository;

     // à rajouter dans le constructeur
    /**
     * @var CommandeRepository
     */
    private $commandeRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var SessionInterface 
     */
    private $session;

    /**
     * AppService Constructor
     * @param ProduitRepository $produitRepository
     * @param CommandeRepository $commandeRepository
     * @param UserRepository $userRepository
     * @param PaginatorInterface $paginator
     * @param SessionInterface $session
     */
    public function __construct( ProduitRepository $produitRepository, CommandeRepository $commandeRepository,UserRepository $userRepository ,SessionInterface $session, PaginatorInterface $paginator)
    {
        $this->produitRepository = $produitRepository;
        $this->commandeRepository = $commandeRepository;
        $this->userRepository = $userRepository;
        $this->paginator = $paginator;
        $this->session = $session;
    }

    public function getTitre(string $titre)
    {
        return $titre;
    }

    public function capitalize(string $mot)
    {
        return ucwords(mb_strtoupper($mot));
    }

    public function uppercase(string $mot)
    {
        return mb_strtoupper($mot);
    }

     //affichera la liste des produits sur la page Admin
     public function getListeProduit(Request $request)
     {
         //$donnees récuperent toutes les enseignes
         $donnees = $this->produitRepository->findAll();
        
         //$pagination gère le systeme de pagination
        $produits = $this->paginator->paginate(
             $donnees,
             $request->query->getInt('page', 1),
             5 //limit d'affichage par page /5 !
         );
 
         return $produits;
     }

     //affichera la liste des commandes sur pour le cuistot
     public function getListeCommande(Request $request)
     {
        //permet de récuperer les commandes
        $donnees = $this->commandeRepository->findAll();
        $cmds = $this->paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            5 //limit d'affichage par page /5 !
        );

        return $cmds;
     }

     //affichera la liste des clients sur pour le cuistot
     public function getListeClient(Request $request)
     {
         
        //permet de récuperer les clients !!!
        $donnees = $this->userRepository->findClient();
        $clients = $this->paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            5 //limit d'affichage par page /5 !
        );
     
        return $clients;
     }

     //affichera la liste des produits sur la page accueil
    public function getListeProduitAccueil(Request $request)
    {
        //$donnees récuperent toutes les enseignes
        $donnees = $this->produitRepository->findAll();

        //$pagination gère le systeme de pagination
        $produits = $this->paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            3 //limit d'affichage par page /5 !
        );

        return $produits;
    }
    
    ##################Code en rapport avec le panier###################
    public function ajouterPanier(int $id)
    {
        //on défini le panier à récupérer
        $panier = $this->session->get('panier', []);

        //ajout un produit si il n'existe pas ou il l'incrémente
        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {

            $panier[$id] = 1;
        }


        //toujours remettre le panier dans la session apres modif!!!
        $this->session->set('panier', $panier);
    }

    public function contenuDuPanier():array
    {
          //$session = $request->getSession();
          $panier = $this->session->get('panier',[]);
        
          $contenuDuPanier = [];
          foreach ($panier as $id => $quantite) {
             $ldc = new Composer($quantite, $this->produitRepository->find($id));
             $contenuDuPanier[] = [
                    'ligne_cmd' => $ldc
             ];
          }
          return $contenuDuPanier;

          
    }

    public function supprimerDuPanier(int $id)
    {
        $panier = $this->session->get('panier', []);
        if(!empty($panier[$id])) {
            unset($panier[$id]);
        }

        //on remet le panier en session apres chaque modif !!!!
        $this->session->set('panier', $panier);
    }

    public function getTotalPanier()
    {
        //récupération contenu panier
        $items = $this->contenuDuPanier();
        
        $total=0;
        foreach ($items as $item) {
            $sous_total = $item['ligne_cmd']->getSousTotal();
            $total+= $sous_total;
        }
        return $total;
    }

    

} 