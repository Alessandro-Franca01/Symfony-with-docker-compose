<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;

// Fazer o CRUD pelo controller mesmo só para ir testando:
// Comandos: sudo chmod 777 nome-do-arquivo ( falta o modo recursivo )
class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/product/list', name: 'list_products')]
    public function list(ManagerRegistry $doctrine): Response
    {
    	// Listando produtos testando com redenrização: 
    	$entityManager = $doctrine->getManager();
    	$products = $entityManager->getRepository(Product::class)->findAll();
    	//dump($products);
    	//die();

    	return $this->render('product/list.html.twig', [
            'products' => $products,
            //'mensagem' => "Lista de Produtos",
        ]);
    }

    #[Route('/product/create/form/', name: 'create_product_form')]
    public function create_form(ManagerRegistry $doctrine, Request $request): Response
    {
    	// TESTANDO FORMULAIO COM SYMFONY: OK, BASICO!!
    	$produto = new Product();

    	$form = $this->createFormBuilder($produto)
    		->add('name', TextType::class, ['required' => true, 'label' => "Nome"])
            ->add('price', IntegerType::class, ['label' => "Preço"])
            ->add('save', SubmitType::class, ['label' => "Salvar"])
            ->getForm();

        $form->handleRequest($request);

        // Testando cadastro na base de dados com form: OK,POREM TEM QUE MELHORAR!!!
        if ($form->isSubmitted() && $form->isValid()) {
        	
        	$produto = new Product();
        	$produto = $form->getData();
        
        	$entityManager = $doctrine->getManager();
        	// Levantando 1 Exeption de 'ProdutoRepository' não existe!
            $entityManager->persist($produto);
            $entityManager->flush();

            $this->addFlash(
	            'notice',
	            'Produto salvo com sucesso!'
        	);
            
            //$this->addFlash('success', "Produto cadastrado!");
            return $this->redirectToRoute('list_products');
        }

        return $this->renderForm("product/create.html.twig", [
            'form' => $form,
        ]);
    }

	#[Route('/product/create', name: 'create_product')]
    public function create(ManagerRegistry $doctrine, Request $request): Response
    {
    	// Testando a rota de criação de produtos:OK!!
        $product = new Product();

        //$product->setName('Tablet Galaxy T220');
        //$product->setPrice(899);

        //$entityManager = $doctrine->getManager();
        //$entityManager->persist($product);
        //$entityManager->flush();

        return new Response('Metodo não esta sendo mais utilizado!');
    }


    #[Route('/product/show/{id}', name: 'show_product')]
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
    	// Testando a busca pelo id e exibição: Faltou esse!!!
    	$entityManager = $doctrine->getManager();

    	$product = $doctrine->getRepository(Product::class)->find($id);

    	if (!$product) {
            throw $this->createNotFoundException(
                'Não encontrado produto para id: '.$id
            );
        }

    	return new Response('Produto encontrado, nome:'.$product->getName().' preço: '.$product->getPrice());
    }

    #[Route('/product/update/{id}', name: 'update_product')]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
    	// Fazendo o update usando: melhorado, Ok!
    	$produto = new Product();

    	$form = $this->createFormBuilder($produto)
    		->add('name', TextType::class, ['required' => true, 'label' => "Nome"])
            ->add('price', IntegerType::class, ['label' => "Preço"])
            ->add('edit', SubmitType::class, ['label' => "Editar"])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        	
        	$produto_request = new Product();
        	$produto_request = $form->getData();

        	$entityManager = $doctrine->getManager();
    		$product_encontrado = $entityManager->getRepository(Product::class)->find($id);
    		
    		if (!$product_encontrado) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            	);
        	}

        	$product_encontrado->setName($produto_request->getName());
        	$product_encontrado->setPrice($produto_request->getPrice());

            $entityManager->flush();

            $this->addFlash(
	            'update_product',
	            'Produto editado com sucesso!'
        	);

            return $this->redirectToRoute("list_products");
        }

        return $this->renderForm("product/update.html.twig", [
            'form' => $form,
        ]);

    }

    #[Route('/product/delete/{id}', name: 'delete_product')]
    public function destroy(ManagerRegistry $doctrine, int $id): Response
    {
    	// Fazendo a remoção de um produto: OK
    	$entityManager = $doctrine->getManager();
    	$productRepository = $entityManager->getRepository(Product::class);
    	$product = $productRepository->find($id);

    	if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return new Response('Produto removido com sucesso!');

    }


}
