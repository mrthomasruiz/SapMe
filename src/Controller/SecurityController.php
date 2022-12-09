<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{

        #[Route('/register', name: 'register')]
            public function register(Request $request, EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher): Response
            {


                $user =new User;

                $form=$this->createForm(RegistrationType::class, $user);

                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid())
                {
                    $user->setPassword($hasher->hashPassword($user, $user->getPassword()));

                    $manager->persist($user);
                    $manager->flush();
                    $this->addFlash('success', 'Félicitations, vous êtes inscrit. Connectez-vous à présent');

                    return $this->redirectToRoute('login');


                }

                return $this->render('security/register.html.twig', [
                    'form'=>$form->createView()

                ]);
            }

            #[Route('/login', name: 'login')]
                public function login(): Response
                {


                    return $this->render('security/login.html.twig', [

                    ]);
                }

                #[Route('/logout', name: 'logout')]
                    public function logout()
                    {


                    }



}
