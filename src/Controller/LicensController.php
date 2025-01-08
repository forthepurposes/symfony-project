<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LicensController extends AbstractController
{
    #[Route('/licens', name: 'app_licens')]
    public function index(): Response
    {
        return $this->render('licens/index.html.twig', [
            'controller_name' => 'LicensController',
        ]);
    }
}
