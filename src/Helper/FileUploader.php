<?php

namespace App\Helper;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function __construct(
        EntityManagerInterface $entityManager,
        string $directory,
        LoggerInterface $logger
    ) {
//        $this->entityManager = $entityManager;
//        $this->directory = $directory;
        $logger->error($directory);
    }

    public function upload(UploadedFile $file, string $folder, string $name = ''): string {

        $fileName = ($name ? $name . '-' : $name) . uniqid() . '-' . $file->guessExtension();
        $file->move($folder, $fileName);

        return $fileName;
    }

    public function delete(string $fileName, string $folder): bool {

        if(file_exists($folder . DIRECTORY_SEPARATOR . $fileName)) {
            return unlink($folder . DIRECTORY_SEPARATOR . $fileName);
        }

        return false;
    }

}