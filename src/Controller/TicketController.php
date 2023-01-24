<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    #[Route('ticket', name: 'app_ticket')]
    public function index(TicketRepository $ticketRepository): Response
    {
        $tickets = $ticketRepository->findAll();
        return $this->render('ticket/index.html.twig', [
            'controller_name' => 'TicketController',
            "tickets" => $tickets
        ]);
    }

    #[Route('ticket/{id}', name: 'app_ticket_view')]
    public function ticketId(Ticket $ticket): Response
    {
        return $this->render('ticket/ticket.html.twig', [
            'controller_name' => 'TicketController',
            "ticket" => $ticket
        ]);
    }

    #[Route('ticket/edit/{id}', name: 'app_ticket_edit')]
    public function editTicket(Ticket $ticket, Request $request, TicketRepository $ticketRepository): Response
    {
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted()){
            $ticket = $form->getData();
            $ticketRepository->save($ticket, true);
            return $this->redirectToRoute('app_ticket_view', [
                'id'=> $ticket->getId()
            ]);
        }
        return $this->render('ticket/ticket_edit.html.twig', [
            'controller_name' => 'TicketController',
            "ticket" => $ticket,
            "form"=>$form
        ]);
    }
}
