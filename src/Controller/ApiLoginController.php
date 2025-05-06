<?php

namespace App\Controller;

use App\Entity\User;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

final class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function apiLogin(#[CurrentUser] ?User $user): JsonResponse
    {
        if (!$user instanceof User) {
            return $this->json([
                'error' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $key = $_ENV['JWT_SECRET'];
        $payload = [
            'sub' => $user->getId(),
            'name' => $user->getUserIdentifier(),
            'iat' => time(),
            'exp' => time() + 20800,
        ];
        $token = JWT::encode($payload, $key, 'HS256');

        return $this->json(
            data: [
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ],
            ],
            context: [
                'groups' => 'get_user',
            ]
        );
    }
}
