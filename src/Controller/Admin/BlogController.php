<?php


namespace App\Controller\Admin;


use App\Form\AddPostForm;
use App\Form\EditPostForm;
use App\Mappers\Blog;
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
    private $manager;

    public function __construct(BlogRepository $blogRepository, BlogService $service, EntityManagerInterface $manager)
    {
        $this->blogRepository = $blogRepository;
        $this->service = $service;
        $this->manager = $manager;
    }

    public function index()
    {
        $posts = $this->blogRepository->findAll();
        return $this->render('admin/admin_blog.html.twig',[
            'posts' => $posts
        ]);
    }
    public function addBlogpost(Request $request)
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

    public function editBlogpost(int $id, Request $request, BlogRepository $blogRepository)
    {
        $post = $blogRepository->findOneBy(['id' => $id]);
        $formDto = Blog::entityToFormDto($post);
        $image = $post->getImage();
        if (empty($post))
            return new Response('Post not found',404);
        $form = $this->createForm(EditPostForm::class, $formDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setTitle($formDto->getTitle())
                ->setDescription($formDto->getDescription())
                ->setUpdatedAt(new \DateTime())
                ->setIsVisible($formDto->getIsVisible());
            if ($formDto->getImage() != null) {
                $post->setImage($this->service->uploadImage($formDto->getImage()));
                $this->service->deleteImage($image);
            }
            $this->manager->persist($post);
            $this->manager->flush();

            return $this->redirectToRoute('showBlogPost',['slug' => $post->getSlug()]);
        }
        return $this->render('admin/edit_post.html.twig',[
            'form' => $form->createView(),
            'image' => $image
        ]);

    }

}