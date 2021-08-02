<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Service\ForumService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ForumService
     */
    private $fs;

    public function __construct(EntityManagerInterface $em, ForumService $fs)
    {
        $this->em=$em;
        $this->fs=$fs;
    }

    /**
     * @Route("/forum", name="forum")
     */
    public function forum(): Response
    {
        $forums=$this->fs->getAllForum();
        return $this->render('forum/forum.html.twig', [
            'forums' => $forums,
        ]);
    }

    /**
     * @Route("/topic/{idForum}", name="topic")
     */
    public function topic(int $idForum): Response
    {
        $forum=$this->fs->getForumById($idForum);
        $topics=$this->fs->getTopicsOfForum($idForum);
        return $this->render('topics/topics.html.twig', [
            'topics' => $topics,
            'forum' => $forum,
        ]);
    }

    /**
     * @Route("/topic/messages/{idTopic}", name="messages")
     */
    public function messages(Request $request, int $idTopic): Response
    {
        $messages=$this->fs->getMessagesOfTopic($idTopic);
        $topic=$messages[0]->getTopic();
        $newMessage = new Message();
        $form = $this->createForm(MessageType::class, $newMessage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newMessage->setCreatedAt(new DateTime());
            $newMessage->setUser($this->getUser());
            $newMessage->setTopic($topic);
            $this->em->persist($newMessage);
            $this->em->flush();

            return $this->redirectToRoute('messages', ['idTopic' => $topic->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('messages/messages.html.twig', [
            'messages' => $messages,
            'topic' => $topic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("message/{id}/edit", name="message_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, int $id): Response
    {
        $message = $this->fs->getMessage($id);
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('messages', ['idTopic'=>$message->getTopic()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message/edit.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="message_delete", methods={"POST"})
     */
    public function delete(Request $request, Message $message): Response
    {
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($message);
            $entityManager->flush();
        }

        return $this->redirectToRoute('messages', ['idTopic'=>$message->getTopic()->getId()], Response::HTTP_SEE_OTHER);
    }
}
