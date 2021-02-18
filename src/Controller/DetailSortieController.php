<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/sortie")
 */
class DetailSortieController extends AbstractController
{
    /**
     * @Route("/detail", name="detail_deatil")
     */
    public function detail(): Response
    {
        return $this->render('detail/index.html.twig', [
            'controller_name' => 'DetailController',
        ]);
    }
}
