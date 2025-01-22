<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Formatter\ApiResponseFormatter;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class NewUserController extends AbstractController
{
    public function __construct(
        private UserRepository       $userRepository,
        private ApiResponseFormatter $apiResponseFormatter
    )
    {
    }

    #[Route('/users',
        name: 'app_user',
        methods: ['GET'])
    ]
    public function index(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        $transformedUser = [];

        foreach ($users as $user) {
            $transformedUser[] = $user->toArray();
        }

        return $this->apiResponseFormatter
            ->withData($transformedUser)
            ->response();
    }

    #[Route('/users/{id}',
        name: 'app_user_show',
        methods: ['GET'])
    ]
    public function show(int $id) : JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);

        return $this->apiResponseFormatter
            ->withData($user->toArray())
            ->response();
    }

    #[Route('/users',
        name: 'create_user',
        methods: ['POST'])
    ]
    public function create(Request $request) : JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (empty($requestData)) {
            return $this->apiResponseFormatter
                ->withMessage('Invalid request')
                ->withStatus(Response::HTTP_BAD_REQUEST)
                ->response();
        }

        $user = new User();
        $user->setEmail($requestData['email']);
        $user->setPassword($requestData['password']);

        $this->userRepository->save($user);

        return $this->apiResponseFormatter
            ->withData($user->toArray())
            ->withStatus(200)
            ->response();
    }

    #[Route('/users/{id}',
        name: 'update_user',
        methods: ['PATCH'])
    ]
    public function update(Request $request, int $id, UserPasswordHasherInterface $passwordHasher) : JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);
        $newUserData = json_decode($request->getContent(), true);

        (empty($newUserData['email'])) ?  : $user->setEmail($newUserData['email']);
        if(!empty($newUserData['password'])) {
            $hashedPassword = $passwordHasher->hashPassword($user, $newUserData['password']);
            $user->setPassword($hashedPassword);
        }

        $this->userRepository->save($user);

        return $this->apiResponseFormatter
            ->withData($user->toArray())
            ->response();

    }

    #[Route('/users/{id}',
        name: 'delete_user',
        methods: ['DELETE'])
    ]
    public function delete(int $id) : JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);
        $this->userRepository->remove($user);

        return $this->apiResponseFormatter
            ->withMessage('User deleted successfully')
            ->withData($user->toArray())
            ->response();
    }
}
