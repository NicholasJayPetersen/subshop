<?php

namespace App\Controller\Admin;

use App\Entity\EntireOrder;
use App\Entity\Menu;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/admin', name: 'admin')]
    #[\Override]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'orders' => $this->entityManager->getRepository(EntireOrder::class)->findBy(['completedAt' => null]),
        ]);
    }

    #[Route('/admin/order/{id}/start', name: 'admin_order_start')]
    public function orderStart(int $id): RedirectResponse
    {
        $order = $this->entityManager->getRepository(EntireOrder::class)->find($id);
        $order->setStartedAt(new \DateTimeImmutable());
        $order->setStatus('in_progress');
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin');
    }

    #[Route('/admin/order/{id}/complete', name: 'admin_order_complete')]
    public function orderComplete(int $id): RedirectResponse
    {
        $order = $this->entityManager->getRepository(EntireOrder::class)->find($id);
        $order->setCompletedAt(new \DateTimeImmutable());
        $order->setStatus('completed');
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin');
    }

    #[\Override]
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Dashboard');
    }

    #[\Override]
    public function configureMenuItems(): iterable
    {
        // Find icons at https://fontawesome.com/v5/search?m=free&s=solid
        yield MenuItem::linkToRoute('Return to Home', 'fa fa-home', 'app_return');
        if ($this->getUser()->isAdmin()) {
            yield MenuItem::section('Edit', 'fas fa-pen');
            yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
            yield MenuItem::linkToCrud('Menu', 'fas fa-bars', Menu::class);
            yield MenuItem::linkToCrud('Order', 'fas fa-bread-slice', EntireOrder::class);
            yield MenuItem::linkToRoute('Report', 'fas fa-chart-line', 'admin_report');
        }
    }

    #[\Override]
    public function configureCrud(): Crud
    {
        $crud = parent::configureCrud();

        return $crud->showEntityActionsInlined();
    }
}
