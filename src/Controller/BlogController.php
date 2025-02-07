<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Service\MongoCachedService;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/blogs', name: 'blogs')]
class BlogController extends AbstractController
{
    private MongoCachedService $cacheService;

    public function __construct(MongoCachedService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * @throws \Throwable
     * @throws MongoDBException
     */
    #[Route('/api/blogs/create', name: 'api_blog_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data || !isset($data['title'], $data['content'], $data['author'])) {
            return new JsonResponse(['error' => 'Invalid data'], 400);
        }

        $blog = new Blog();
        $blog->setTitle($data['title'])
            ->setContent($data['content'])
            ->setAuthor($data['author']);

        $this->cacheService->save($blog);
        return new JsonResponse(['message' => 'Blog created', 'id' => $blog->getId()], 201);
    }

    #[Route('/api/blogs/{id}', name: 'api_blog_get', methods: ['GET'])]
    public function getBlog(string $id): JsonResponse
    {
        $blog = $this->cacheService->getData($id);
        if (!$blog) {
            return new JsonResponse(['error' => 'Blog not found'], 404);
        }

        return new JsonResponse($blog->toArray());
    }

    #[Route('/api/blogs/all', name: 'api_blog_all', methods: ['GET'])]
    public function getAllBlogs(): JsonResponse
    {
        $blogs = $this->cacheService->getAllBlogs();
        if (empty($blogs)) {
            return new JsonResponse(['error' => 'No blogs found'], 404);
        }

        return new JsonResponse($blogs);
    }

    #[Route('/api/blogs/update/{id}', name: 'api_blog_update', methods: ['PUT'])]
    public function update(string $id, Request $request): JsonResponse
    {
        $blog = $this->cacheService->getData($id);
        if (!$blog) {
            return new JsonResponse(['error' => 'Blog not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['title'])) { $blog->setTitle($data['title']); }
        if (isset($data['content'])) { $blog->setContent($data['content']); }
        if (isset($data['author'])) { $blog->setAuthor($data['author']); }

        $this->cacheService->save($blog);
        return new JsonResponse(['message' => 'Blog updated']);
    }

    /**
     * @throws \Throwable
     * @throws MappingException
     * @throws MongoDBException
     * @throws LockException
     */
    #[Route('/api/blogs/delete/{id}', name: 'api_blog_delete', methods: ['DELETE'])]
    public function delete(string $id): JsonResponse
    {
        $blog = $this->cacheService->getData($id);
        if (!$blog) {
            return new JsonResponse(['error' => 'Blog not found'], 404);
        }

        $this->cacheService->delete($id);
        return new JsonResponse(['message' => 'Blog deleted']);
    }
}
