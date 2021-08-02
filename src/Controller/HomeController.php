<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em, PostRepository $postRepository)
    {
        $this->em=$em;
        $this->postRepository=$postRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $posts=$this->postRepository->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/admin", name="homeAdmin")
     */
    public function indexAdmin(): Response
    {
        return $this->render('home/admin_index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/detailed-post/{id}", name="detailed-post")
     */
    public function detailedPost(int $id): Response
    {
        $post=$this->postRepository->find($id);
        $post->setNumberView($post->getNumberView()+1);
        $this->em->persist($post);
        $this->em->flush();
        return $this->render('post/detailed_post.html.twig', [
            'controller_name' => 'Detailed Post',
            'post' => $post
        ]);
    }
}
