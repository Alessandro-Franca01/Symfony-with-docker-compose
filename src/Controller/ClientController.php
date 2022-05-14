<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Client;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;

class ClientController extends AbstractController
{
    #[Route('/client', name: 'client_list')]
    public function index(ManagerRegistry $doctrine): Response
    {
    	$entityManager = $doctrine->getManager();
    	$clients = $entityManager->getRepository(Client::class)->findAll();

    	return $this->render('client/index.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/client/create/', name: 'create_client')]
    public function create(ManagerRegistry $doctrine, Request $request): Response
    {
    	$client = new Client();

    	$form = $this->createFormBuilder($client)
    		->add('name', TextType::class, ['required' => true, 'label' => "Nome"])
            ->add('cpf', TextType::class, ['label' => "Cpf"])
            ->add('age', IntegerType::class, ['label' => "Idade"])
            ->add('date', DateTimeType::class, ['label' => "Data de Nascimento"])
            ->add('save', SubmitType::class, ['label' => "Salvar"])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        	
        	$client = new Client();
        	$client = $form->getData();
        
        	$entityManager = $doctrine->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash(
	            'notice',
	            'Cliente salvo com sucesso!'
        	);
            
            return $this->redirectToRoute('client_list');
        }

        return $this->renderForm("client/create.html.twig", [
            'form' => $form,
        ]);
    }

    #[Route('/client/update/{id}', name: 'update_client')]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
    	$client = new Client();

    	$form = $this->createFormBuilder($client)
    		->add('name', TextType::class, ['required' => true, 'label' => "Nome"])
            ->add('cpf', TextType::class, ['label' => "Cpf"])
            ->add('age', IntegerType::class, ['label' => "Idade"])
            ->add('date', DateTimeType::class, ['label' => "Data de Nascimento"])
            ->add('edit', SubmitType::class, ['label' => "Editar"])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        	
        	$client_request = new Client();
        	$client_request = $form->getData();
        
        	$entityManager = $doctrine->getManager();
    		$client_encontrado = $entityManager->getRepository(Client::class)->find($id);
    		
    		if (!$client_encontrado) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            	);
        	}

        	$client_encontrado->setName($client_request->getName());
        	$client_encontrado->setCpf($client_request->getCpf());
        	$client_encontrado->setDate($client_request->getDate());
        	$client_encontrado->setAge($client_request->getAge());

            $entityManager->flush();

            $this->addFlash(
	            'update_client',
	            'Cliente editado com sucesso!'
        	);
            
            return $this->redirectToRoute('client_list');
        }

        return $this->renderForm("client/update.html.twig", [
            'form' => $form,
        ]);
    }


    #[Route('/client/product/', name: 'add_product_client')]
    public function add_product(ManagerRegistry $doctrine, Request $request):Response
    {
    	// Implementar depois de ver as outras alunas do CRUD: 
    	//return $this->redirectToRoute('client_list');

    	$entityManager = $doctrine->getManager();
    	$client_encontrado = $entityManager->getRepository(Client::class)->find(1);
    	$product_encontrado = $entityManager->getRepository(Product::class)->find(2);
    	$client_encontrado->addProduct($product_encontrado);

    	$entityManager->flush();

    	//$products = $entityManager->getRepository(Product::class)->findAll();
    	dump($client_encontrado);
    	die();

    }


}
