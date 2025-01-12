<?php

namespace App\Controller;

use App\Formatter\ApiResponseFormatter;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api')]
class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $UserRepository,
        private ApiResponseFormatter $apiResponseFormatter
    )
    {
    }

    #[Route('/users',
        name: 'app_user',
        methods: ['GET'])
    ]
    #[IsGranted('ROLE_SUPER_ADMIN',
        message: 'You are not allowed to access the admin dashboard.',
        statusCode: 403,
        exceptionCode: 10010)
    ]
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

    #[Route('/users/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(int $id){
        $user = $this->UserRepository->findOneBy(['id' => $id]);

        return $this->apiResponseFormatter
            ->withData($user->toArray())
            ->response();
    }

    #[Route('/users', name: 'create_user', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        dd($request->getContent());
       // return new JsonResponse();
    }

    #[Route('/users', name: 'update_user', methods: ['PATCH'])]
    public function update(int $id): JsonResponse
    {
        return new JsonResponse();
    }

    #[Route('/users', name: 'delete_user', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        return new JsonResponse();
    }
}
