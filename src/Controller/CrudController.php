<?php

namespace App\Controller;

use App\Entity\Crud;
use App\Entity\DaneKlienta;
use App\Form\CrudType;
use App\Repository\CrudRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class CrudController extends AbstractController
{
    #[Route('/', name: 'app_crud_index', methods: ['GET'])]
    public function index2(CrudRepository $crudRepository, Request $request): Response
    {

        $search = $request->get('search');
        $sell = $request->get('sell');


        return $this->render('crud/index.html.twig', [
            'cruds' => ($search) ? $crudRepository->search($search) : $crudRepository->findAll(),
            'search' => $search,
            'sell' => $sell,
        ]);

    }


    #[Route('/new', name: 'app_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CrudRepository $crudRepository): Response
    {
        $crud = new Crud();
        $form = $this->createForm(CrudType::class, $crud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $crudRepository->add($crud, true);

            return $this->redirectToRoute('app_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud/new.html.twig', [
            'crud' => $crud,
            'form' => $form,
        ]);
    }
    #[Route('/ostronie', name: 'app_ostronie', methods: ['GET'])]
    public function ostronie()
    {
        return $this->render('ostronie.html.twig', [

        ]);
    }
    #[Route('/niedostepny', name: 'app_niedostepny', methods: ['GET'])]
    public function niedostepny()
    {
        return $this->render('dane_klienta/brakproduktu.html.twig', [

        ]);
    }
    #[Route('/zamowienia', name: 'app_polaczone', methods: ['GET'])]
    public function polaczone(CrudRepository $crudRepository): Response
    {
        return $this->render('polaczonedane.html.twig', [
            'polaczone'=>$crudRepository->selectAll(),
        ]);
    }
    #[Route('/kontakt', name: 'app_kontakt', methods: ['GET'])]
    public function kontakt()
    {
        return $this->render('kontakt.html.twig', [

        ]);
    }
    #[Route('/{id}', name: 'app_crud_show', methods: ['GET'])]
    public function show(Crud $crud): Response
    {
        return $this->render('crud/show.html.twig', [
            'crud' => $crud,
        ]);
    }

    #[Route('{id}/sell', name: 'app_crud_sell', methods: ['GET', 'POST'])]
    public function sellproduct(ManagerRegistry $doctrine, int $id): Response
    {
        {
            $entityManager = $doctrine->getManager();
            $crud = $entityManager->getRepository(Crud::class)->find($id);

            if (!$crud) {
                throw $this->createNotFoundException(
                    'No product found for id '.$id
                );
            }
            if ($crud->getStanMagazynowy()>= 1) {
                $crud->setStanMagazynowy($crud->getStanMagazynowy() - 1);
                $entityManager->flush();
            }
            elseif($crud->getStanMagazynowy()==0){
                return $this->redirectToRoute('app_niedostepny');
            }
            return $this->redirectToRoute('app_dane_klienta_new', [
                'id' => $crud->getId(),
            ]);
            
           
           
    }
}
    #[Route('/{id}/edit', name: 'app_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Crud $crud, CrudRepository $crudRepository): Response
    {
        $form = $this->createForm(CrudType::class, $crud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $crudRepository->add($crud, true);

            return $this->redirectToRoute('app_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud/edit.html.twig', [
            'crud' => $crud,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Crud $crud, CrudRepository $crudRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$crud->getId(), $request->request->get('_token'))) {
            $crudRepository->remove($crud, true);
        }

        return $this->redirectToRoute('app_crud_index', [], Response::HTTP_SEE_OTHER);
    }


}
