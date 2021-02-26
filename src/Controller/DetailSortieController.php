<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/sortie")
 */
class DetailSortieController extends AbstractController
{
    /**
     * @Route("/detail/{id}", name="detailSortie_detail", requirements={"id" : "\d+"})
     * Controller de la vue détaillée d'une sortie
     */
    public function detail(SortieRepository $sortieRepo, $id): Response
    {

        $sortie = $sortieRepo->find($id);

        return $this->render('sortie/DetailSortie.html.twig', [
            'sortie' => $sortie,

        ]);
    }



}
