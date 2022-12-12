<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\SubCategory;
use App\Form\CategoryType;
use App\Form\EditProductType;
use App\Form\ProductType;
use App\Form\SubCategoryType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\SubCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Sodium\add;

class BackController extends AbstractController
{

    #[Route('/ajoutProduit', name: 'ajoutProduit')]
    public function ajoutProduit(Request $request, EntityManagerInterface $manager): Response
    {
        // Cette méthode doit nous permettre de créer un nouveau produit.
        // On instancie donc un objet Product d'App\Entity que l'on va remplir de toutes ses propriétés.
        $product = new Product();

        // On instancie un objet form via la méthode createForm() existante de notre abstractController.
        $form = $this->createForm(ProductType::class, $product);
        // Cette méthode attend en argument le formulaire à utiliser et l'objet Entité auquel il fait référence.
        // Ainsi il va contrôler la conformité entre les champs de formulaire et les propriétés présents dans l'entité pour pouvoir remplir l'objet Product par lui-même.

        // Grâce à la méthode handleRequest de notre objet formulaire, il charge à présent l'objet Product des données réceptionnées du formulaire présentes dans notre objet request (Request étant la classe de symfony qui récupère la majeure partie des données de superGlobale =>$_GET, $_POST ...)
        $form->handleRequest($request);

        // $request->request est la surcouche de $_POST.
        // ->get() permet d'accéder à une entrée de notre tableau de données
        // $request->request->get('title');

        // Pour accéder à la surcouche de $_GET on utilise $request->query qui possède les mêmes méthodes que $request->request

        if ($form->isSubmitted() && $form->isValid()) {

            //dd($product);
            //dd($form->get('picture')->getData());
            // On récupère les données de notre imput type file du formulaire qui a pour name 'picture'
            $picture = $form->get('picture')->getData();
            // Conditions d'upload de photo
            if ($picture) {

                $picture_bdd = date('YmhHis') . uniqid() . $picture->getClientOriginalName();

                $picture->move($this->getParameter('upload_directory'),
                    $picture_bdd);
                // move() est une méthode de notre objet File qui permet de déplacer notre fichier temporaire uploadé à un emplacement donné (le 1er paramètre) et de nommé ce fichier (le second paramètre de la méthode)

                $product->setPicture($picture_bdd);
                $manager->persist($product);
                $manager->flush();

                $this->addFlash('success', 'Produit ajouté');
                return $this->redirectToRoute('gestionProduit');


            }
        }

        return $this->render('back/ajoutProduit.html.twig', [

            'form' => $form->createView()
        ]);
    }

    #[Route('/gestionProduit', name: 'gestionProduit')]
    public function gestionProduit(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('back/gestionProduit.html.twig', [
            'products' => $products
        ]);
    }


    #[Route('/editProduct/{id}', name: 'editProduct')]
    public function editProduct(Product $product, Request $request, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(EditProductType::class,
            $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('editPicture')->getData()) {
                $picture = $form->get('editPicture')->getData();
                $picture_bdd = date('YmdHis') . uniqid() . $picture->getClientOriginalName();

                $picture->move($this->getParameter('upload_directory'), $picture_bdd);
                unlink($this->getParameter('upload_directory') . '/' . $product->getPicture());
                $product->setPicture($picture_bdd);


            }

            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', 'Produit modifié');
            return $this->redirectToRoute('gestionProduit');

        }

        return $this->render('back/editProduct.html.twig', [
            'form' => $form->createView(),
            'product' => $product
        ]);
    }

    #[Route('/deleteProduct/{id}', name: 'deleteProduct')]
    public function deleteProduct(Product $product, EntityManagerInterface $manager): Response
    {
        $manager->remove($product);
        $manager->flush();

        $this->addFlash('success', 'Produit supprimé !!!');

        return $this->redirectToRoute('gestionProduit');
    }


    #[Route('/category', name: 'category')]
    #[Route('/editCategory/{id}', name: 'editCategory')]
    public function category(CategoryRepository $repository, EntityManagerInterface $manager, Request $request, $id = null): Response
    {

        $categories = $repository->findAll();

        if ($id) {
            $category = $repository->find($id);
        } else {

            $category = new Category();
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($category);
            $manager->flush();
            if ($id) {
                $this->addFlash('success', 'Catégorie modifiée');
            } else {

                $this->addFlash('success', 'Catégorie ajoutée');
            }

            return $this->redirectToRoute('category');


        }


        return $this->render('back/category.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories

        ]);
    }

    #[Route('/deleteCategory/{id}', name: 'deleteCategory')]
    public function deleteCategory(CategoryRepository $repository, EntityManagerInterface $manager, $id): Response
    {
        $category = $repository->find($id);

        $manager->remove($category);
        $manager->flush();

        return $this->redirectToRoute('category');

    }

    #[Route('/ajoutSousCategorie', name: 'ajoutSousCategorie')]
    public function ajoutSousCategorie(Request $request, EntityManagerInterface $manager): Response
    {
        $subCategory = new subCategory();
        $form = $this->createForm(SubCategoryType::class, $subCategory);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($subCategory);
            $manager->flush();
        }
        return $this->render('back/ajoutSousCategorie.html.twig', [

            'form' => $form->createView()
        ]);
    }


    #[Route('/editSubCategory/{id}', name: 'editSubCategory')]
    public function editSousCategorie(Request $request, EntityManagerInterface $manager): Response
    {
        $subCategory = new subCategory();
        $form = $this->createForm(SubCategoryType::class, $subCategory);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($subCategory);
            $manager->flush();
        }
        return $this->render('back/editSubCategory.html.twig', [

            'form' => $form->createView()
        ]);

        return $this->render('back/editSubCategory.html.twig', [

        ]);
    }

    #[Route('/gestionSubCategory', name: 'gestionSubCategory')]
    public function gestionSubCategory(SubCategoryRepository $subCategoryRepository): Response
    {
        $subCategories = $subCategoryRepository->findAll();

        return $this->render('back/gestionSubCategory.html.twig', [

            'subCategories' => $subCategories
        ]);
    }

    //#[Route('/gestionProduit', name: 'gestionProduit')]
    // public function gestionProduit(ProductRepository $productRepository): Response
    // {
    //    $products = $productRepository->findAll();

    //    return $this->render('back/gestionProduit.html.twig', [
    //        'products' => $products
    //   ]);
    // }
    #[Route('/deleteSubCategory', name: 'deleteSubCategory')]
    public function deleteSubCategory(): Response
    {


        return $this->redirectToRoute('back/gestionSubCategory', [

        ]);
    }

}// Fermeture de controller