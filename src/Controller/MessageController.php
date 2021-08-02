<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/message")
 */
class MessageController extends AbstractController
{
    


    // /**
    //  * @Route("/{id}/edit", name="message_edit", methods={"GET","POST"})
    //  */
    // public function edit(Request $request, Message $message): Response
    // {
    //     $form = $this->createForm(MessageType::class, $message);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->getDoctrine()->getManager()->flush();

    //         return $this->redirectToRoute('message_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('message/edit.html.twig', [
    //         'message' => $message,
    //         'form' => $form,
    //     ]);
    // }

    // /**
    //  * @Route("/{id}", name="message_delete", methods={"POST"})
    //  */
    // public function delete(Request $request, Message $message): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->remove($message);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('message_index', [], Response::HTTP_SEE_OTHER);
    // }
}
