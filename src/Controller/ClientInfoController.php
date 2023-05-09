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
    public function new(ManagerRegistry $doctrine, Request $request, ClientInfoRepository $ClientInfoRepository): Response
    {
        $daneKlientum = new ClientInfo();
        $form = $this->createForm(ClientInfoType::class, $daneKlientum);
        $form->handleRequest($request);
        $entityManager = $doctrine->getManager();
        $id=($_GET["id"]);
        $crud = $entityManager->getRepository(Crud::class)->find($id);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $ClientInfoRepository->add($daneKlientum, true);
            if ($crud->getStock_Level()>= 1) {
                $crud->setStock_Level($crud->getStock_Level() - 1);
                $entityManager->flush();
            }
            return $this->redirectToRoute('app_thankyou', ['Name'=>$daneKlientum->getName()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client_info/new.html.twig', [
            'client_info' => $daneKlientum,
            'form' => $form,
            'id'=> $id,
        ]);
    }

    #[Route('/{id}', name: 'app_client_info_show', methods: ['GET'])]
    public function show(ClientInfo $daneKlientum): Response
    {
        return $this->render('client_info/show.html.twig', [
            'client_info' => $daneKlientum,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_client_info_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ClientInfo $daneKlientum, ClientInfoRepository $ClientInfoRepository): Response
    {
        $form = $this->createForm(ClientInfoType::class, $daneKlientum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ClientInfoRepository->add($daneKlientum, true);

            return $this->redirectToRoute('app_Client_Info_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client_info/edit.html.twig', [
            'client_info' => $daneKlientum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_info_delete', methods: ['POST'])]
    public function delete(Request $request, ClientInfo $daneKlientum, ClientInfoRepository $ClientInfoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$daneKlientum->getId(), $request->request->get('_token'))) {
            $ClientInfoRepository->remove($daneKlientum, true);
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
