<?php

namespace App\Controller;

use App\Entity\Menu;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ApiMenuController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/api/menu', name: 'api_menu', methods: ['GET'])]
    public function apiMenu(EntityManagerInterface $entityManager): JsonResponse
    {
        return $this->json(
            data: [
                'data' => $this->entityManager->getRepository(Menu::class)->findAll(),
            ],
            context: [
                'groups' => 'get_menu',
            ]
        );
    }
}
