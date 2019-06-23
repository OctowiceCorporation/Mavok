<?php


namespace App\Service;


use App\Entity\Blog;
use App\Repository\BlogRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BlogService
{
    private $blogRepository;
    private $imageDirectory;
    private $manager;

    public function __construct(BlogRepository$blogRepository, string $imageDirectory, EntityManagerInterface $manager)
    {
        $this->blogRepository = $blogRepository;
        $this->imageDirectory = $imageDirectory;
        $this->manager = $manager;
    }

    public function addPost(array $form)
    {
        $post = new Blog();
        $post->setTitle($form['title'])
            ->setDescription($form['description'])
            ->setIsVisible($form['is_visible'])
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime())
            ->setImage($this->uploadImage($form['image']));
        $this->manager->persist($post);
        $this->manager->flush();
    }

    public function uploadImage(UploadedFile $image): string
    {
        $fileName = md5(uniqid()).'.'.$image->guessExtension();
        $image->move(
            $this->imageDirectory,
            $fileName
        );
        return $fileName;
    }

    public function deleteImage(string $image)
    {
        unlink($this->imageDirectory.'/'.$image);
    }
}