<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Formatter\ApiResponseFormatter;
use App\Repository\InvoiceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/invoices')]
class InvoiceController extends AbstractController
{
    public function __construct(
        private ApiResponseFormatter $apiResponseFormatter,
        private EntityManagerInterface $entityManager,
        private InvoiceRepository $invoiceRepository,
        private UserRepository $UserRepository
    )
    {

    }

    #[Route(name: 'app_invoice', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $invoices = $this->invoiceRepository->findAll();

        $transformedInvoices = [];
        foreach ($invoices as $invoice) {
            $transformedInvoices[] = $invoice->toArray();
        }


        return $this->apiResponseFormatter
            ->withData($transformedInvoices)
            ->response();
    }

    #[Route(name: 'app_invoice_new', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $invoiceInformation = json_decode($request->getContent(), true);
        if(empty($invoiceInformation)){
            return $this->apiResponseFormatter
                ->withMessage('Invalid request')
                ->withStatus(Response::HTTP_BAD_REQUEST)
                ->response();
        }

        $user = $this->UserRepository->findOneBy(['id' => $invoiceInformation['user_id']]);

        $invoice = new Invoice();
        $invoice->setCompanyName($invoiceInformation['name']);
        $invoice->setCompanyStreet($invoiceInformation['street']);
        $invoice->setCompanyStreetNumber($invoiceInformation['street_number']);
        $invoice->setCompanyStreetFlatNumber($invoiceInformation['street_flat_number']);
        $invoice->setCompanyCity($invoiceInformation['city']);
        $invoice->setCompanyPostCode($invoiceInformation['post_code']);
        $invoice->setTaxNumber($invoiceInformation['tax_number']);
        $invoice->setPhone($invoiceInformation['phone']);
        $invoice->setEmail($invoiceInformation['email']);
        $invoice->setCreated(new \DateTime());
        $invoice->setUpdated(new \DateTime());
        $invoice->setUserId($user);

        $this->entityManager->persist($invoice);
        $this->entityManager->flush();

        return $this->apiResponseFormatter
            ->withData($invoice->toArray())
            ->response();
    }
}
