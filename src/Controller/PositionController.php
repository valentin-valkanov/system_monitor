<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Position;
use App\Entity\PositionState;
use App\Factory\PositionDTOFactory;
use App\Form\PositionStateType;
use App\Repository\PositionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PositionController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PositionRepository $positionRepository,
    )
    {
    }
    #[Route('position/show', name: 'app_closed_position_show_all')]
    public function showAllPositions(PositionDTOFactory $factory): Response
    {
        $closedPositions = $this->positionRepository->findAllClosedPositions($factory);

        return $this->render('position/closed_positions.html.twig', [
            'closedPositions' => $closedPositions,
        ]);
    }
}