<?php

namespace App\Controller;

use App\Entity\PostCategory;
use App\Form\PostCategoryType;
use App\Repository\PostCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPostCategoryController extends AbstractController
{
    /**
     * @var PostCategoryRepository
     */
    private $postCategoryRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em, PostCategoryRepository $postCategoryRepository)
    {
        $this->em=$em;
        $this->postCategoryRepository=$postCategoryRepository;
    }

    /**
     * @Route("/admin/read-post-category", name="admin_read_post_category")
     */
    public function adminReadPostCategories(): Response
    {    
        $postCategories=$this->postCategoryRepository->findAll();
        return $this->render('post_category/admin_post_category.html.twig', [
            'controller_name' => 'AdminPostCategoryController',
            'postCategories'=> $postCategories
        ]);
    }

    /**
     * @Route("/admin/create-post-category", name="admin_create_post_category")
     */
    public function adminCreatePostCategories(Request $request): Response
    {    
        $postCategory = new PostCategory();
        $form = $this->createForm(PostCategoryType::class, $postCategory);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($postCategory);
            $this->em->flush();
            return $this->redirectToRoute('admin_read_post_category');
        }
        return $this->render('post_category/admin_create_post_category.html.twig', [
            'controller_name' => 'AdminPostCategoryController',
            'form'=> $form->createView()
        ]);

    }

    /**
     * @Route("/admin/update-post-category/{id}", name="admin_update_post_category")
     */
    public function adminUpdatePostCategories(Request $request, int $id): Response
    {    
        $postCategory = $this->postCategoryRepository->find($id);
        $form = $this->createForm(PostCategoryType::class, $postCategory);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($postCategory);
            $this->em->flush();
            return $this->redirectToRoute('admin_read_post_category');
        }
        return $this->render('post_category/admin_create_post_category.html.twig', [
            'controller_name' => 'AdminPostCategoryController',
            'form'=> $form->createView()
        ]);

    }

    /**
     * @Route("/admin/delete-post-category/{id}", name="admin_delete_post_category")
     */
    public function adminDeletePostCategories(int $id): Response
    {    
        $postCategory = $this->postCategoryRepository->find($id);

        $this->em->remove($postCategory);
        $this->em->flush();

        return $this->redirectToRoute('admin_read_post_category');       

    }

}
