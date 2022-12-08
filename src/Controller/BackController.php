<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackController extends AbstractController
{

    #[Route('/ajoutProduit', name: 'ajoutProduit')]
    public function ajoutProduit(): Response
    {


        return $this->render('back/ajoutProduit.html.twig', [

        ]);
    }


}
