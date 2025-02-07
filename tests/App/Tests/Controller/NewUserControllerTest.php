<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class NewUserControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->userRepository = static::getContainer()->get(UserRepository::class);
    }

    public function testIndexReturnsAllUsers(): void
    {
        $this->client->request('GET', '/api/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($this
            ->client
            ->getResponseContent());
    }

    public function testShowReturnsUserById(): void
    {
        $user = $this->userRepository->findOneBy([]);
        $this->client->request('GET', '/api/users/' . $user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($this->client->getResponseContent());
    }

    public function testCreateUser(): void
    {
        $this->client->request('POST', '/api/users',
            [], [],
            ['CONTENT_TYPE' => 'application/json'], json_encode([
                'email' => 'test@example.com',
                'password' => 'securepassword'])
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($this->
            client->getResponseContent());
    }

    public function testUpdateUser(): void
    {
        $user = $this->userRepository->findOneBy([]);
        $this->client
            ->request(
            'PATCH',
            '/api/users/' . $user->getId(),
            [], [],
            ['CONTENT_TYPE' => 'application/json'], json_encode([
                'email' => 'updated@example.com'
            ])
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($this
            ->client
            ->getResponseContent()
        );
    }

    public function testDeleteUser(): void
    {
        $user = $this->userRepository
            ->findOneBy([]);

        $this->client->request('DELETE', '/api/users/' . $user->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($this
            ->client
            ->getResponseContent());
    }
}
