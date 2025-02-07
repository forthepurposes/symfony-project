<?php

namespace App\Service;

use App\Entity\Blog;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;
use Predis\Client as RedisClient;

// TODO: Could've been async?
class MongoCachedService
{
    private RedisClient $redis;
    private DocumentManager $documentManager;

    public function __construct(RedisClient $redis, DocumentManager $documentManager)
    {
        $this->redis = $redis;
        $this->documentManager = $documentManager;
    }

    /**
     * Returns Cached data if found, otherwise retrieves it from mongo and then saves to cache temporarily
     * @param string $blogId Unique blog's identifier
     * @return ?Blog Existing blog or null if there isn't any
     */
    public function getData(string $blogId): ?Blog
    {
        $cachedData = $this->redis->get("blog:$blogId");
        if ($cachedData) {
            $data = json_decode($cachedData, true);
            return $this->arrayToBlog($data);
        }

        $blog = $this->documentManager->getRepository(Blog::class)->find($blogId);
        if ($blog) {
            $this->save($blog);
        }

        return $blog;
    }

    public function getAllBlogs(): array
    {
        $blogs = $this->documentManager->getRepository(Blog::class)->findAll();
        $blogsArray = [];

        foreach ($blogs as $blog) {
            $blogsArray[] = $blog->toArray();
        }

        return $blogsArray;
    }

    /**
     * @throws MongoDBException
     * @throws \Throwable
     */
    public function save(Blog $blog): void
    {
        $this->redis->setex("blog:{$blog->getId()}", 3600, json_encode($blog->toArray()));

        $this->documentManager->persist($blog);
        $this->documentManager->flush();
    }

    /**
     * @throws MappingException
     * @throws \Throwable
     * @throws MongoDBException
     * @throws LockException
     */
    public function delete(string $blogId): void
    {
        $this->redis->del("blog:$blogId");
        $blog = $this->documentManager->getRepository(Blog::class)->find($blogId);
        if ($blog) {
            $this->documentManager->remove($blog);
            $this->documentManager->flush();
        }
    }

    /**
     * @throws \DateMalformedStringException
     */
    private function arrayToBlog(array $data): Blog
    {
        $blog = new Blog();
        $blog->setTitle($data['title']);
        $blog->setContent($data['content']);
        $blog->setAuthor($data['author']);
        $blog->setCreatedAt(new \DateTime($data['created_at']));
        return $blog;
    }
}
