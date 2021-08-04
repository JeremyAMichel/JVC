<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

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
        // $posts=$this->postRepository->findAll();
        return $this->render('home/index.html.twig', [
            // 'controller_name' => 'HomeController',
            // 'posts' => $posts
        ]);
    }

    /**
     * @Route("/test-ajax", name="test-ajax")
     */
    public function testAjax(PaginatorInterface $paginator, Request $request): Response
    {
        $qb = $this->postRepository->getQbAll();
        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page',1),
            6
        );

        $html = $this->renderView('partial/post.html.twig', [
            'pagination' => $pagination,

        ]);

        $response = new JsonResponse();
        $response->setData([
            'html' => $html,
        ]);
        return $response;
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
