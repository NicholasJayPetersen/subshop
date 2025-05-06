<?php

namespace App\Controller;

use App\Entity\EntireOrder;
use App\Entity\Menu;
use App\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PagesController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly RequestStack $requestStack,
    ) {
    }

    // When making a new page make sure to make a new route only the names and '/' change everything else stays the same!!
    #[Route('/', name: 'app_frontpage')]
    public function frontPage(): Response
    {
        return $this->render('frontpage.html.twig');
    }

    #[Route('/about', name: 'app_aboutpage')]
    public function aboutPage(): Response
    {
        return $this->render('aboutpage.html.twig');
    }

    #[Route('/menu', name: 'app_menu')]
    public function menuPage(EntityManagerInterface $entityManager): Response
    {
        return $this->render('menupage.html.twig', [
            'sandwiches' => $this->entityManager->getRepository(Menu::class)->findBy(['category' => 'sandwiches']),
            'sides' => $this->entityManager->getRepository(Menu::class)->findBy(['category' => 'sides']),
            'beverages' => $this->entityManager->getRepository(Menu::class)->findBy(['category' => 'beverages']),
        ]);
    }

    #[Route('/order', name: 'app_orderpage')]
    public function orderPage(EntityManagerInterface $entityManager): Response
    {
        return $this->render('orderpage.html.twig', [
            'sandwiches' => $this->entityManager->getRepository(Menu::class)->findBy(['category' => 'sandwiches']),
            'sides' => $this->entityManager->getRepository(Menu::class)->findBy(['category' => 'sides']),
            'beverages' => $this->entityManager->getRepository(Menu::class)->findBy(['category' => 'beverages']),
        ]);
    }

    #[Route('/order/add/{id}', name: 'app_order_add')]
    public function orderAdd(int $id): RedirectResponse
    {
        $cart = $this->getCart();
        $item = $this->entityManager->getRepository(Menu::class)->find($id);

        if (!$item instanceof Menu) {
            $this->addFlash('error', "Item $id not in menu!");

            return $this->redirectToRoute('app_orderpage');
        }

        if (!$item->getIsAvailable()) {
            $this->addFlash('warning', "{$item->getName()} is currently unavailable!");

            return $this->redirectToRoute('app_orderpage');
        }

        $cart[$id] = isset($cart[$id]) ? ++$cart[$id] : 1;
        $this->saveCart($cart);
        $this->addFlash('notice', "Order item {$item->getName()}");

        return $this->redirectToRoute('app_orderpage');
    }

    #[Route('/checkout', name: 'app_checkout')]
    public function checkout(): Response
    {
        return $this->render('checkout.html.twig', [
            'cart' => $this->getCartItems(),
        ]);
    }

    #[Route('/order/process', name: 'app_process')]
    public function orderProcess(): RedirectResponse
    {
        $user = $this->getUser();
        $now = new \DateTimeImmutable();
        $cart = $this->getCartItems();
        $order = new EntireOrder();
        $order->setUser($user);
        $order->setCreatedAt($now);
        $order->setTax($cart['totalPrice'] * 0.06);
        $order->setTotalPrice($cart['totalPrice'] * 1.06);

        foreach ($cart['orderItems'] as $item) {
            $order->addOrderItem($item);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        $this->clearCart();

        return $this->redirectToRoute('app_orderconfirmation');
    }

    #[Route('/order-confirmation', name: 'app_orderconfirmation')]
    public function orderConfirmationPage(): Response
    {
        return $this->render('orderconfirmation.html.twig');
    }

    #[Route('/order-status', name: 'app_order_status')]
    public function orderStatus(): Response
    {
        return $this->render('orderstatus.html.twig');
    }

    #[Route('/return', name: 'app_return')]
    public function return(): RedirectResponse
    {
        return $this->redirectToRoute('app_frontpage');
    }

    private function clearCart(): array
    {
        $cart = [];
        $this->requestStack->getSession()->set('cart', $cart);

        return $cart;
    }

    private function saveCart(array $cart): void
    {
        $this->requestStack->getSession()->set('cart', $cart);
    }

    private function getCart(): array
    {
        return $this->requestStack->getSession()->get('cart') ?? $this->clearCart();
    }

    private function getCartItems(): array
    {
        $cart = $this->getCart();
        $totalPrice = 0;

        foreach ($cart as $id => $qty) {
            $menu = $this->entityManager->getRepository(Menu::class)->find($id);
            $orderItem = new OrderItem();
            $orderItem->setMenu($menu);
            $orderItem->setQuantity($qty);
            $orderItem->setPrice($menu->getPrice());
            $items[] = $orderItem;
            $totalPrice += $qty * $menu->getPrice();
        }

        return [
            'orderItems' => $items,
            'totalPrice' => $totalPrice,
        ];
    }
}
