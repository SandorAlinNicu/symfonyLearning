<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="create_product")
     */
    public function createProduct(ValidatorInterface $validator): Response
    {


        //      $entityManager = $this->getDoctrine()->getManager();

        $product = new Product();
        // This will trigger an error: the column isn't nullable in the database
        $product->setName(null);
        // This will trigger a type mismatch error: an integer is expected
        $product->setPrice('1999');
        //  $product->setDescription("Ii chiar fain");

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        //  $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        //$entityManager->flush();

        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return new Response((string)$errors, 400);
        }
        //return new Response("Am adaugat produsul: ". $product->getName() . "cu pretul: " . $product->getPrice() . "si descrierea: " . $product->getDescription());

    }

    /**
     * @Route("/product/{id}", name="product_show")
     */
    public function show($id)
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        return new Response('Check out this great product: ' . $product->getName());

        // or render a template
        // in the template, print things with {{ product.name }}
        // return $this->render('product/show.html.twig', ['product' => $product]);
    }

    /**
     * @Route("/product/delete/{id}")
     */
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return new Response("Am sterg produsul: " . $product->getname());

    }

    /**
     * @Route("/product/edit/{id}")
     */
    public function update($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return new Response("Am modificat produsul cu id-ul: " . $product->getID());
    }

}