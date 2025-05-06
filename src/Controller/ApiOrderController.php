<?php

namespace App\Controller;

use App\Entity\EntireOrder;
use App\Entity\Menu;
use App\Entity\OrderItem;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Attribute\Route;

final class ApiOrderController extends AbstractController
{
    private string $error;

    private User $user;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly RequestStack $requestStack,
    ) {
    }

    #[Route('/api/my-orders', name: 'app_api_myorders')]
    public function myOrders(): JsonResponse
    {
        if (!$this->checkToken()) {
            return $this->json(['error' => $this->error]);
        }

        return $this->json(
            data: [
                'data' => $this->entityManager->getRepository(EntireOrder::class)->findBy(['user' => $this->user->getId()]),
            ],
            context: [
                'groups' => 'get_order',
            ]
        );
    }

    #[Route('/api/place-order', name: 'api_place_order', methods: ['POST'])]
    public function placeOrder(Request $request): JsonResponse
    {
        if (!$this->checkToken()) {
            return $this->json(['error' => $this->error]);
        }

        $payload = $request->getPayload()->all();

        if ([] == $payload) {
            return $this->json(['No payload to deliver']);
        }

        if ([] == $payload['orderItems']) {
            return $this->json(['error' => 'There are no items in the order']);
        }

        $items = [];
        $totalPrice = 0;
        foreach ($payload['orderItems'] as $item) {
            $menuId = $this->entityManager->getRepository(Menu::class)->find($item['id']);
            $orderItem = new OrderItem();
            $orderItem->setMenu($menuId);
            $orderItem->setQuantity($item['Quantity']);
            $orderItem->setPrice($menuId->getPrice());
            $items[] = $orderItem;
            $totalPrice += $item['Quantity'] * $menuId->getPrice();
        }

        $user = $this->user;
        $now = new \DateTimeImmutable();
        $order = new EntireOrder();
        $order->setUser($user);
        $order->setCreatedAt($now);
        $order->setTax($totalPrice * 0.06);
        $order->setTotalPrice($totalPrice * 1.06);

        foreach ($items as $item) {
            $order->addOrderItem($item);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $this->json(
            data: [
                'data' => [
                    'order' => [
                        'userId' => $user->getId(),
                        'user' => $user->getUserName(),
                        'id' => $order->getId(),
                        'totalPrice' => $order->getTotalPrice(),
                        'tax' => $order->getTax(),
                        'createdAt' => $order->getCreatedAt(),
                    ],
                    'items' => [
                        'menuId' => $item->getMenu()->getId(),
                        'Quantity' => $item->getQuantity(),
                        'price' => $item->getPrice(),
                    ],
                ],
            ]
        );
    }

    #[Route('/api/order-status', name: 'app_api_order_status')]
    public function myOrderStatus(): JsonResponse
    {
        if (!$this->checkToken()) {
            return $this->json(['error' => $this->error]);
        }

        $order = $this->entityManager->getRepository(EntireOrder::class)->findOneBy(
            ['user' => $this->user->getId()],
            ['id' => 'DESC']
        );
        if (!$order instanceof EntireOrder) {
            return $this->json(['error' => 'No Orders Found.']);
        }

        return $this->json(
            data: [
                'data' => [
                    'id' => $order->getId(),
                    'status' => $order->getStatus() ?? 'new',
                ],
            ],
            context: [
                'groups' => 'get_order',
            ]
        );
    }

    public function checkToken(): bool
    {
        $headers = $this->requestStack->getCurrentRequest()->headers;
        $authorization = $headers->get('Authorization');
        $authorization = preg_replace('/^Bearer /', '', (string) $authorization);
        $key = $_ENV['JWT_SECRET'];

        try {
            $token = JWT::decode($authorization, new Key($key, 'HS256'));
        } catch (\Exception $e) {
            $this->error = $e->getMessage();

            return false;
        }
        if (($user = $this->entityManager->getRepository(User::class)->find($token->sub)) === null) {
            $this->error = 'cannot load user';

            return false;
        }

        $this->user = $user;

        return true;
    }
}
