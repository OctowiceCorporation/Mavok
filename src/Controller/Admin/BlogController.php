<?php


namespace App\Controller\Admin;


use App\Entity\Blog;
use App\Form\AddPostForm;
use App\Repository\BlogRepository;
use App\Service\BlogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends AbstractController
{
    private $blogRepository;
    private $service;

    public function __construct(BlogRepository $blogRepository, BlogService $service)
    {
        $this->blogRepository = $blogRepository;
        $this->service = $service;
    }

    public function index()
    {
        $posts = $this->blogRepository->findAll();
        return $this->render('admin/admin_blog.html.twig',[
            'posts' => $posts
        ]);
    }
    public function addBlogpost(Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AddPostForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->addPost($form->getData());
            return $this->redirectToRoute('blog');
        }
        return $this->render('admin/add_post.html.twig', [
            'form' => $form->createView()
        ]);
    }

}