<?php

namespace App\Controller;

use App\Formatter\ApiResponseFormatter;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $UserRepository,
        private ApiResponseFormatter $apiResponseFormatter
    )
    {
    }

    #[Route('/users', name: 'app_user')]
    public function index(): Response
    {
        $users = $this->UserRepository->findAll();

        $transformedUsers = [];
        foreach ($users as $user) {
            $transformedUsers[] = $user->toArray();
        }

        return $this->apiResponseFormatter
            ->withData($transformedUsers)
            ->response();
        }

    #[Route('/users/{id}', name: 'app_user_show')]
    public function show(int $id){
        $user = $this->UserRepository->findOneBy(['id' => $id]);

        return $this->apiResponseFormatter
            ->withData($user->toArray())
            ->response();
    }
}
