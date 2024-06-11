<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReviewController extends AbstractController
{
    #[Route('review/show', name: 'app_show_all_reviews')]
    public function showReviewsList():Response
    {
        $reviews = [
            'W01' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.',
            'W02' => 'Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.',
            'W03' => 'Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.'
        ];
        return $this->render('review/review.html.twig', [
            'reviewEntries' => $reviews,
        ]);
    }
}