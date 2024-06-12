<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlaybookController extends AbstractController
{
    #[Route('playbook/show', name: 'app_show_all_playbooks')]
    public function showPlaybooksList():Response
    {
        $playbooks = [
            'W01 Playbook' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.',
            'W02 Playbook' => 'Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.',
            'W03 Playbook' => 'Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.'
        ];
        return $this->render('playbook/playbook.html.twig', [
            'playbookEntries' => $playbooks,
        ]);
    }
}