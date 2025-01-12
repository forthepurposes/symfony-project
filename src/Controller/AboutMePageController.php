<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AboutMePageController extends AbstractController
{
    #[Route('/about-me', name: 'app_about_me_page')]
    public function index(): JsonResponse
    {
        return new JsonResponse();
    }
}
