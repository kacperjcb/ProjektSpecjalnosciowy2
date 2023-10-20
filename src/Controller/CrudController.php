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

#[Route('/')]
class CrudController extends AbstractController
{

    #[Route('/', name: 'app_crud_index', methods: ['GET'])]
    public function index2(CrudRepository $crudRepository, Request $request): Response
    {
        $search = $request->query->get('search');
        $sortBy = $request->query->get('sortBy', 'Product_Name');
        $sortOrder = $request->query->get('sortOrder', 'asc');

        $allowedSortFields = ['Id', 'Product_Name', 'Product_Price', 'Stock_Level', 'Description']; // Define the sortable fields

        if (!in_array($sortBy, $allowedSortFields)) {
            // Handle invalid sortBy parameters or set a default value
            $sortBy = 'Product_Name';
        }

        $products = ($search) ? $crudRepository->search($search) : $crudRepository->findAll();

        // Sorting products based on the selected parameters
        usort($products, function ($a, $b) use ($sortOrder, $sortBy) {
            if ($sortBy === 'Id') {
                // Handle sorting by "Id" separately
                $fieldA = $a->getId();
                $fieldB = $b->getId();
            } else {
                // For other fields, use dynamic method calls
                $fieldA = $a->{'get' . $sortBy}();
                $fieldB = $b->{'get' . $sortBy}();
            }

            if ($fieldA === $fieldB) {
                return 0;
            }

            if ($sortOrder === 'asc') {
                return ($fieldA < $fieldB) ? -1 : 1;
            } else {
                return ($fieldA > $fieldB) ? -1 : 1;
            }
        });

        return $this->render('crud/index.html.twig', [
            'cruds' => $products,
            'search' => $search,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
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

    #[Route('/about', name: 'app_about', methods: ['GET'])]
    public function about()
    {
        return $this->render('about.html.twig', [

        ]);
    }

    #[Route('/unavailable', name: 'app_unavailable', methods: ['GET'])]
    public function unavailable()
    {
        return $this->render('client_info/noproduct.html.twig', [

        ]);
    }
    #[Route('/export-txt', name: 'app_export_txt')]
    public function exportTxt(CrudRepository $crudRepository): Response
    {
        // Get the data to export, e.g., from your repository
        $data = $crudRepository->selectAll();


        // Check if $data is an array
        if (!is_array($data)) {
            throw new \RuntimeException('Invalid data for export');
        }

        // Create a TXT response
        $response = new Response($this->generateTxt($data));

        // Set response headers for TXT download
        $response->headers->set('Content-Type', 'text/plain');
        $response->headers->set('Content-Disposition', 'attachment; filename="exported_data.txt"');

        return $response;
    }

    private function generateTxt($data)
    {
        $txtContent = '';

        // Generate the plain text content
        foreach ($data as $item) {
            $txtContent .= "ID: " . $item['id'] . "\n";
            $txtContent .= "Name: " . $item['Name'] . "\n";
            $txtContent .= "Surname: " . $item['Surname'] . "\n";
            $txtContent .= "City: " . $item['City'] . "\n";
            $txtContent .= "Post Code: " . $item['PostCode'] . "\n";
            $txtContent .= "Address: " . $item['Address'] . "\n";
            $txtContent .= "Product Name: " . $item['Product_Name'] . "\n";
            $txtContent .= "Description: " . $item['Description'] . "\n\n";
        }

        return $txtContent;
    }
    #[Route('/export-csv', name: 'app_export_csv')]
    public function exportCsv(CrudRepository $crudRepository): Response
    {
        // Get the data to export, e.g., from your repository
        $data = $crudRepository->selectAll();

        // Create a CSV response
        $response = new Response($this->generateCsv($data));

        // Set response headers for CSV download
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="exported_data.csv"');

        return $response;
    }

    private function generateCsv($data)
    {
        $output = fopen('php://temp', 'r+');

        // Add headers to the CSV
        fputcsv($output, ['ID', 'Name', 'Surname', 'City', 'Post Code', 'Address', 'Product Name', 'Description']);

        // Add data rows
        foreach ($data as $item) {
            fputcsv($output, [
                $item['id'],
                $item['Name'],
                $item['Surname'],
                $item['City'],
                $item['PostCode'],
                $item['Address'],
                $item['Product_Name'],
                $item['Description'],
            ]);
        }

        rewind($output);

        // Read the CSV content
        $csvContent = stream_get_contents($output);

        fclose($output);

        return $csvContent;
    }



    #[Route('/orders', name: 'app_orders', methods: ['GET', 'POST'])]
    public function orders(CrudRepository $crudRepository, Request $request): Response
    {
        $exportRequest = $request->query->get('export');

        if ($exportRequest === 'txt') {
            // Export to text file
            return $this->exportTxt($crudRepository);
        }

        // Display orders
        return $this->render('orders.html.twig', [
            'orders' => $crudRepository->selectAll(),
        ]);
    }


    #[Route('/contact', name: 'app_contact', methods: ['GET'])]
    public function CONTACT()
    {
        return $this->render('contact.html.twig', [

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
    public function sellProduct(int $id, CrudRepository $crudRepository): Response
    {
        $crud = $crudRepository->find($id);

        if (!$crud) {
            return $this->createNotFoundException('No product found for id ' . $id);
        }

        if ($crud->getStock_Level() == 0) {
            return $this->redirectToRoute('app_unavailable');
        }

        return $this->redirectToRoute('app_client_info_new', [
            'id' => $crud->getId(),
        ]);
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
        if ($this->isCsrfTokenValid('delete' . $crud->getId(), $request->request->get('_token'))) {
            $crudRepository->remove($crud, true);
        }

        return $this->redirectToRoute('app_crud_index', [], Response::HTTP_SEE_OTHER);
    }









}
