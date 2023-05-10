<?php

namespace App\Controller;

use App\Entity\Crud;
use App\Entity\ClientInfo;
use App\Form\CrudType;
use App\Repository\CrudRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api')]
class ApiController extends AbstractController
{


    #[Route("/client", methods: ["GET"])]
    public function getClientInfo(EntityManagerInterface $entityManager): Response
    {
        $clientinfo = $entityManager->getRepository(ClientInfo::class)->findAll();

        return $this->json($clientinfo);
    }
    #[Route("/client/{id}", methods: ["GET"])]
    public function getClientById(EntityManagerInterface $entityManager, $id): Response
    {
        $clientinfo = $entityManager->getRepository(ClientInfo::class)->find($id);

        if (!$clientinfo) {
            return new JsonResponse(['error' => 'Crud info not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json($clientinfo);
    }
    #[Route("/client", methods: ["POST"])]
    public function createClientInfo(ManagerRegistry $doctrine, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $clientInfo = new ClientInfo();
        $clientInfo->setName($data['Name']);
        $clientInfo->setSurname($data['Surname']);
        $clientInfo->setCity($data['City']);
        $clientInfo->setPostCode($data['PostCode']);
        $clientInfo->setAddress($data['Address']);
        $clientInfo->setOrderNumber($data['OrderNumber']);

        $entityManager = $doctrine->getManager();
        $entityManager->persist($clientInfo);
        $entityManager->flush();

        return new JsonResponse($clientInfo, JsonResponse::HTTP_CREATED);
    }
    #[Route("/client/{id}", methods: ["PUT"])]
    public function replaceClientInfo(ManagerRegistry $doctrine, Request $request, $id)
    {
        $data = json_decode($request->getContent(), true);

        $entityManager = $doctrine->getManager();
        $clientInfo = $entityManager->getRepository(ClientInfo::class)->find($id);

        if (!$clientInfo) {
            return new JsonResponse(['error' => 'Client info not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $clientInfo->setName($data['Name']);
        $clientInfo->setSurname($data['Surname']);
        $clientInfo->setCity($data['City']);
        $clientInfo->setPostCode($data['PostCode']);
        $clientInfo->setAddress($data['Address']);
        $clientInfo->setOrderNumber($data['OrderNumber']);

        $entityManager->flush();

        return new JsonResponse($clientInfo);
    }

    #[Route("/client/{id}", methods: ["PATCH"])]
    public function updateClientInfo(ManagerRegistry $doctrine, Request $request, $id)
    {
        $data = json_decode($request->getContent(), true);

        $entityManager = $doctrine->getManager();
        $clientInfo = $entityManager->getRepository(ClientInfo::class)->find($id);

        if (!$clientInfo) {
            return new JsonResponse(['error' => 'Client info not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $clientInfo->setName($data['Name'] ?? $clientInfo->getName());
        $clientInfo->setSurname($data['Surname'] ?? $clientInfo->getSurname());
        $clientInfo->setCity($data['City'] ?? $clientInfo->getCity());
        $clientInfo->setPostCode($data['PostCode'] ?? $clientInfo->getPostCode());
        $clientInfo->setAddress($data['Address'] ?? $clientInfo->getAddress());
        $clientInfo->setOrderNumber($data['OrderNumber'] ?? $clientInfo->getOrderNumber());

        $entityManager->flush();

        return new JsonResponse($clientInfo);
    }
    #[Route("/client/{id}", methods: ["DELETE"])]

    public function deleteClientInfo(ManagerRegistry $doctrine, ClientInfo $clientInfo): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($clientInfo);
        $entityManager->flush();

        return $this->json([
            'Client' => "Deleted",
        ]);
    }
    //API FOR CRUD IS STARTING HERE

    #[Route("/crud", methods: ["GET"])]
    public function getCrudInfo(EntityManagerInterface $entityManager): Response
    {
        $crud = $entityManager->getRepository(Crud::class)->findAll();

        return $this->json($crud);
    }
    #[Route("/crud/{id}", methods: ["GET"])]
    public function getCrudById(EntityManagerInterface $entityManager, $id): Response
    {
        $crud = $entityManager->getRepository(Crud::class)->find($id);

        if (!$crud) {
            return new JsonResponse(['error' => 'Crud info not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json($crud);
    }

    #[Route("/crud", methods: ["POST"])]
    public function createCrudInfo(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $crud = new Crud();
        $crud->setProduct_Name($data['Product_Name']);
        $crud->setProduct_Price($data['Product_Price']);
        $crud->setStock_Level($data['Stock_Level']);
        $crud->setDescription($data['Description']);

        $entityManager = $doctrine->getManager();
        $entityManager->persist($crud);
        $entityManager->flush();

        return new JsonResponse($crud, JsonResponse::HTTP_CREATED);
    }


    #[Route("/crud/{id}", methods: ["PUT"])]
    public function replaceCrudInfo(ManagerRegistry $doctrine, Request $request, $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $entityManager = $doctrine->getManager();
        $crud = $entityManager->getRepository(Crud::class)->find($id);

        if (!$crud) {
            return new JsonResponse(['error' => 'Crud info not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $crud->setProduct_Name($data['Product_Name']);
        $crud->setProduct_Price($data['Product_Price']);
        $crud->setStock_Level($data['Stock_Level']);
        $crud->setDescription($data['Description']);

        $entityManager->flush();

        return new JsonResponse($crud);
    }

    #[Route("/crud/{id}", methods: ["PATCH"])]
    public function updateCrudInfo(ManagerRegistry $doctrine, Request $request, $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $entityManager = $doctrine->getManager();
        $crud = $entityManager->getRepository(Crud::class)->find($id);

        if (!$crud) {
            return new JsonResponse(['error' => 'Crud info not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        if (isset($data['Product_Name'])) {
            $crud->setProduct_Name($data['Product_Name']);
        }

        if (isset($data['Product_Price'])) {
            $crud->setProduct_Price($data['Product_Price']);
        }

        if (isset($data['Stock_Level'])) {
            $crud->setStock_Level($data['Stock_Level']);
        }

        if (isset($data['Description'])) {
            $crud->setDescription($data['Description']);
        }

        $entityManager->flush();

        return new JsonResponse($crud);
    }

    #[Route("/crud/{id}", methods: ["DELETE"])]
    public function deleteCrud(ManagerRegistry $doctrine, Crud $crud): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($crud);
        $entityManager->flush();

        return $this->json([
            'Product' => "Deleted",
        ]);
    }
}
