<?php

declare(strict_types=1);

namespace App\Controller;

use App\Formatter\ApiResponseFormatter;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class ResetPasswordController extends AbstractController
{

    public function __construct(
        private ApiResponseFormatter        $apiResponseFormatter,
        private UserRepository              $userRepository,
        private MailerInterface             $mailer,
        private UserPasswordHasherInterface $passwordHasher
    )
    {

    }

    #[Route('/reset-password',
        name: 'reset_password',
        methods: ['POST'])
    ]
    public function index(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $user = $this->userRepository->findOneBy(['email' => $requestData['email']]);
        $newPassword = $this->generatePassword();

        if (!$user) {
            return $this->apiResponseFormatter
                ->withMessage('User not found')
                ->response();
        }

        if (!empty($newPassword)) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($hashedPassword);

            $this->userRepository->save($user);
            $this->sendEmail($requestData['email'], $newPassword);
        }

        return $this->apiResponseFormatter
            ->withMessage('Reset password')
            ->response();
    }

    public function generatePassword(int $lenght = 12): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($characters), 0, $lenght);
    }

    public function sendEmail(string $email, string $newPassword): void
    {
        $email = (new Email())
            ->from('test_account@craftertechnologies.pl')
            ->to($email)
            ->subject('New password')
            ->text('Your new password is: ' . $newPassword)
            ->html('<p>Your new password is: ' . $newPassword . '</p>');

        $this->mailer->send($email);
    }
}
