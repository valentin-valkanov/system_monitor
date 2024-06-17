<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Position;
use App\Entity\PositionState;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PositionController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    #[Route('position/show', name: 'app_closed_position_show_all')]
    public function showAllPositions(): Response
    {
        $positions = $this->entityManager->getRepository(PositionState::class)->findAll();

        return $this->render('position/closed_positions.html.twig', [
            'positions' => $positions,
        ]);
    }

}