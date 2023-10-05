<?php

namespace App\Controller;
use App\Entity\Crud;
use App\Entity\ClientInfo;
use App\Form\ClientInfoType;
use App\Repository\ClientInfoRepository;
use App\Repository\CrudRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
#[Route('/client/info')]
class ClientInfoController extends AbstractController
{
    #[Route('/', name: 'app_Client_Info_index', methods: ['GET'])]
    public function index(ClientInfoRepository $ClientInfoRepository): Response
    {
        return $this->render('client_info/index.html.twig', [
            'client_info' => $ClientInfoRepository->findAll(),
        ]);
    }
    #[Route('/thankyou', name: 'app_thankyou', methods: ['GET'])]
    public function thankyou()
    {
        $Name=$_GET["Name"];
        return $this->render('client_info/thankyou.html.twig', [
            'Name'=>$Name,
        ]);
    }
    #[Route('/new', name: 'app_client_info_new', methods: ['GET', 'POST'])]
    public function new(ManagerRegistry $doctrine, Request $request, ClientInfoRepository $clientInfoRepository): Response
    {
        $clientInfo = new ClientInfo();
        $form = $this->createForm(ClientInfoType::class, $clientInfo);
        $form->handleRequest($request);
        $entityManager = $doctrine->getManager();

        // Użyj obiektu Request do pobrania parametru 'id' z zapytania GET
        $id = $request->query->get('id');

        // Tutaj zdefiniuj zmienną $crud na podstawie ID lub inaczej, w zależności od twoich potrzeb
        $crud = $entityManager->getRepository(Crud::class)->find($id);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientInfoRepository->add($clientInfo, true);
            if ($crud && $crud->getStock_Level() >= 1) {
                $crud->setStock_Level($crud->getStock_Level() - 1);
                $entityManager->flush();
            }
            return $this->redirectToRoute('app_thankyou', ['Name' => $clientInfo->getName()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client_info/new.html.twig', [
            'client_info' => $clientInfo,
            'form' => $form,
            'id' => $id,
        ]);
    }

    #[Route('/{id}', name: 'app_client_info_show', methods: ['GET'])]
    public function show(ClientInfo $clientInfo): Response
    {
        return $this->render('client_info/show.html.twig', [
            'client_info' => $clientInfo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_client_info_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ClientInfo $clientInfo, ClientInfoRepository $ClientInfoRepository): Response
    {
        $form = $this->createForm(ClientInfoType::class, $clientInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ClientInfoRepository->add($clientInfo, true);

            return $this->redirectToRoute('app_Client_Info_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client_info/edit.html.twig', [
            'client_info' => $clientInfo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_info_delete', methods: ['POST'])]
    public function delete(Request $request, ClientInfo $clientInfo, ClientInfoRepository $ClientInfoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clientInfo->getId(), $request->request->get('_token'))) {
            $ClientInfoRepository->remove($clientInfo, true);
        }

        return $this->redirectToRoute('app_Client_Info_index', [], Response::HTTP_SEE_OTHER);
    }
/**
     * @Route("/api", methods={"GET"})
     */
    public function getClientInfo(EntityManagerInterface $entityManager): Response
    {
        $clientinfo = $entityManager->getRepository(ClientInfo::class)->findAll();

        return $this->json($clientinfo);
    }
}
