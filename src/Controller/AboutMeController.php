<?php

namespace App\Controller;

use App\Entity\AboutMe;
use App\Formatter\ApiResponseFormatter;
use App\Repository\AboutMeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class AboutMeController extends AbstractController
{
    public function __construct(
        private AboutMeRepository $aboutMeRepository,
        private ApiResponseFormatter $apiResponseFormatter,
        private EntityManagerInterface $entityManager
    )
    {

    }

    #[Route('/about-me', name: 'app_about_me', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $abutMeData = $this->aboutMeRepository->findAll();

        foreach ($abutMeData as $key => $value) {
            $transferedData[] = $value->toArray();
        }

         return $this->apiResponseFormatter
             ->withData($transferedData)
             ->response();
    }

    #[Route('/about-me', name: 'create_about_me', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $aboutMe = new AboutMe();
        $aboutMeInformation = json_decode($request->getContent(), true);
        if(empty($aboutMeInformation)){
            return $this->apiResponseFormatter
                ->withMessage('Invalid request')
                ->withStatus(Response::HTTP_BAD_REQUEST)
                ->response();
        }

        $ok = 1;
        (empty($aboutMeInformation['key'])) ? $ok = 0 : $aboutMe->setKey($aboutMeInformation['key']);
        (empty($aboutMeInformation['value'])) ? $ok = 0 : $aboutMe->setValue($aboutMeInformation['value']);
        (empty($aboutMeInformation['user_id'])) ? $ok = 0 : $aboutMe->setUserId($aboutMeInformation['user_id']);

        if($ok == 0){
            return $this->apiResponseFormatter
                ->withMessage('Invalid request')
                ->withStatus(Response::HTTP_BAD_REQUEST)
                ->response();
        }

        $this->entityManager->persist($aboutMe);
        $this->entityManager->flush();

        return $this->apiResponseFormatter
            ->withData($aboutMe->toArray())
            ->withStatus(Response::HTTP_CREATED)
            ->response();
    }

    #[Route('/about-me/{id}', name: 'app_about_me_show', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $abutMePosition = $this->aboutMeRepository->find($id);

        if(empty($abutMePosition)){
            return $this->apiResponseFormatter
                ->withMessage('About me position not found')
                ->withStatus(Response::HTTP_NOT_FOUND)
                ->response();
        }

        $this->entityManager->remove($abutMePosition);
        $this->entityManager->flush();

        return $this->apiResponseFormatter
            ->withMessage('About me deleted successfully')
            ->response();
    }

    #[Route('/about-me/{id}', name: 'app_about_me_update', methods: ['PUT'])]
    public function update(int $id, Request $request, AboutMe $aboutMe): JsonResponse
    {
        $request = json_decode($request->getContent(), true);
        (empty($request['key'])) ? : $aboutMe->setKey($request['key']);
        (empty($request['value'])) ? : $aboutMe->setValue($request['value']);
        (empty($request['user_id'])) ? : $aboutMe->setUserId($request['user_id']);

        $this->entityManager->persist($aboutMe);
        $this->entityManager->flush();

        return $this->apiResponseFormatter
            ->withMessage('About me updated successfully')
            ->withData($aboutMe->toArray())
            ->response();
    }
}
