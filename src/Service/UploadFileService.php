<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadFileService
{

    private $uploadDir;

    public function __construct(string $uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    public function upload(UploadedFile $file): ?string
    {
        try {
            $newFileName = \md5(\uniqid($file->getClientOriginalName()));
            $file->move($this->uploadDir, $newFileName);
            return $newFileName;
        } catch (FileException $e) {
            return null;
        }
    }
}