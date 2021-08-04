<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AdminPostController extends AbstractController
{

    /**
     * @var Security
     */
    private $security;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em, PostRepository $postRepository, Security $security)
    {
        $this->em=$em;
        $this->postRepository=$postRepository;
        $this->security = $security;
    }


    /**
     * @Route("/admin/read-post", name="admin_read_post")
     */
    public function adminReadPosts(): Response
    {    
        $posts=$this->postRepository->findAll();

        // faire form de filtre HERE voir:PostFilterType
        return $this->render('post/admin_post.html.twig', [
            'controller_name' => 'AdminPostController',
            'posts'=> $posts
        ]);
    }

    /**
     * @Route("/admin/create-post", name="admin_create_post")
     */
    public function adminCreatePost(Request $request): Response
    {    
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreatedAt(new DateTime());
            $post->setStatus(0);
            $post->setNumberView(0);
            $user=$this->security->getUser();
            // $user=$this->getUser();
            $post->setUser($user);
            $this->em->persist($post);
            $this->em->flush();
            return $this->redirectToRoute('admin_read_post');

        }
        return $this->render('post/admin_create_post.html.twig', [
            'controller_name' => 'AdminPostController',
            'form'=> $form->createView()
        ]);
    }


    /**
     * @Route("/admin/update-post/{id}", name="admin_update_post")
     */
    public function adminUpdatePost(Request $request, int $id): Response
    {    
        $post = $this->postRepository->find($id);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($post);
            $this->em->flush();
            return $this->redirectToRoute('admin_read_post');
        }
        return $this->render('post/admin_create_post.html.twig', [
            'controller_name' => 'AdminPostController',
            'form'=> $form->createView()
        ]);

    }

    /**
     * @Route("/admin/delete-post/{id}", name="admin_delete_post")
     */
    public function adminDeletePost(int $id): Response
    {    
        $post = $this->postRepository->find($id);

        $this->em->remove($post);
        $this->em->flush();

        return $this->redirectToRoute('admin_read_post');       

    }

}
