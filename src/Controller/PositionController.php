<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PositionController extends AbstractController
{
    #[Route('position/show', name: 'app_closed_position_show_all')]
    public function showAllPositions(): Response
    {
        $positions = [
            'GOLD' => ['open_at' => 2330.00, 'order' => 'buy', 'closed_at' => 2450.25, 'profit' => 380.30],
            'SP500' => ['open_at' => 5300.00, 'order' => 'buy', 'closed_at' => 5428.01, 'profit' => 483.55],
            'NAS100' => ['open_at' => 10300.00, 'order' => 'buy', 'closed_at' => 10428.01, 'profit' => 183.55]
        ];

        return $this->render('position/closed_positions.html.twig', [
            'positions' => $positions,
        ]);
    }
}