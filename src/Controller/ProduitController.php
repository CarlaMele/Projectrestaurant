<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Service\AppService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * Class ProduitController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 * @Route("/produit", name="produit_")
 *
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="liste")
     * @param AppService $service
     * @param Request $request
     * @return Response
     */
    public function liste(AppService $service, Request $request)
    {
        return $this->render(
            'produit/liste.html.twig',
            [
                'titre_page' => $service->getTitre("liste des produits"),
                'produits' => $service->getListeProduit($request),
                'lignesCmds' => $service->contenuDuPanier(),
                'total' => $service->getTotalPanier(),
            ]
        );
    }
    /**
     * @Route("/ajouter", name="ajouter")
     * @param Request $request
     * @param AppService $service
     * @return RedirectResponse|Response
     */

    public function ajouter(Request $request, AppService $service)
    {
            $produit = new Produit();
            $form = $this->createForm(ProduitType::class, $produit);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $nom = $form->get('nomProduit')->getData();
                $produit->setNomProduit($service->capitalize($nom));

                $em = $this->getDoctrine()->getManager();
                $em->persist($produit);
                $em->flush();

                return $this->redirectToRoute('produit_liste');
            }
        return $this->render(
            'produit/editer.html.twig',
            [
                'titre_page' => $service->getTitre("Ajouter un produit"),
                'produit' => $produit,
                'form' => $form->createView(),
                'lignesCmds' => $service->contenuDuPanier(),
                'total' => $service->getTotalPanier(),
            ]
        );

    }

    /**
     * @param Produit $produit
     * @param Request $request
     * @param AppService $service
     * @Route("/{id}/modifier", name="modifier", methods={"GET","POST"})
     */
    public function modifier(Produit $produit, Request $request, AppService $service)
    {

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        //check si le form est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //on récupère le nom saisi
            $nom = $form->get("nomProduit")->getData();
            //On met le nom en majuscule
            $produit->setNomProduit(mb_strtoupper($nom));

            //on persiste en db
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            //on redirige vers la liste
            return $this->redirectToRoute("produit_liste");
        }
        return $this->render("produit/editer.html.twig", [
            'produit' => $produit,
            'form' => $form->createView(),
            'titre_page' => $service->getTitre("Modification des produits"),
            'lignesCmds' => $service->contenuDuPanier(),
            'total' => $service->getTotalPanier(),

        ]);
    }
    /**
     * @param Produit $produit
     * @param Request $request
     * @Route("/{id}/supprimer", name="supprimer")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();

        $produit = $em->getRepository(Produit::class)->find($id);

        if (!$produit) {
            return $this->redirectToRoute('produit_liste');
        }

        $em->remove($produit);
        $em->flush();

        return $this->redirectToRoute('produit_liste');
    }
}
