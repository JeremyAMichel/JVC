<?php

namespace App\Service;

use App\Entity\Forum;
use App\Entity\Message;
use App\Entity\Topic;
use App\Repository\ForumRepository;
use App\Repository\MessageRepository;
use App\Repository\TopicRepository;
use DateTime;

/**
 * Class ForumService
 */
class ForumService
{
    /**
     * @var ForumRepository
     */
    private ForumRepository $forumRepository;

    /**
     * @var MessageRepository
     */
    private MessageRepository $messageRepository;

    /**
     * @var TopicRepository
     */
    private TopicRepository $topicRepository;

    public function __construct(ForumRepository $forumRepository, MessageRepository $messageRepository, TopicRepository $topicRepository)
    {
        $this->forumRepository=$forumRepository;
        $this->messageRepository=$messageRepository;
        $this->topicRepository=$topicRepository;
    }

    public function getAllForum(): array
    {
        return $this->forumRepository->findAll();
    }

    public function getForumById($idForum): Forum
    {
        return $this->forumRepository->find($idForum);
    }

    public function getTopicsOfForum($idForum):array
    {
        return $this->topicRepository->findBy(['forum'=>$idForum]);
    }

    public function getTopicById($idTopic): Topic
    {
        return $this->topicRepository->find($idTopic);
    }

    public function getLastMessageOfTopic($idTopic): ?Message
    {
        return $this->messageRepository->findOneBy(['topic'=>$idTopic],['created_at' => 'DESC']);
    }


    public function getMessagesOfTopic($idTopic):array
    {
        return $this->messageRepository->findBy(['topic'=>$idTopic],['created_at' => 'ASC']);
    }

    public function getMessage($idMessage): Message
    {
        return $this->messageRepository->find($idMessage);
    }
    
    
}
