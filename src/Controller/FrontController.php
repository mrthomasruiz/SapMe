<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{


            #[Route('/', name: 'home')]
            public function home(ProductRepository $productRepository): Response
            {
                $products=$productRepository->findAll();


                return $this->render('front/home.html.twig', [

                    'products'=>$products

                ]);
            }



}
