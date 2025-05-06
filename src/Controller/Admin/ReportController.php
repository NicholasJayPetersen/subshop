<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    #[Route('/admin/report', name: 'admin_report')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Use raw SQL for accurate calculation
        $conn = $entityManager->getConnection();

        // Get total sandwiches and revenue
        $totalsSql = "
            SELECT
                SUM(oi.quantity) AS total_sandwiches,
                SUM(oi.quantity * m.price) AS total_revenue
            FROM order_item oi
            JOIN menu m ON oi.menu_id = m.id
            WHERE m.category = 'sandwiches'
        ";
        $totals = $conn->prepare($totalsSql)->executeQuery()->fetchAssociative();

        // Get sandwich breakdown
        $breakdownSql = "
            SELECT m.name AS sandwich_name, SUM(oi.quantity) AS quantity_sold
            FROM order_item oi
            JOIN menu m ON oi.menu_id = m.id
            WHERE m.category = 'sandwiches'
            GROUP BY m.name
            ORDER BY quantity_sold DESC
        ";
        $breakdown = $conn->prepare($breakdownSql)->executeQuery()->fetchAllAssociative();

        return $this->render('admin/report.html.twig', [
            'totalOrders' => $totals['total_sandwiches'] ?? 0,
            'totalRevenue' => $totals['total_revenue'] ?? 0.0,
            'sandwichBreakdown' => $breakdown,
        ]);
    }
}
