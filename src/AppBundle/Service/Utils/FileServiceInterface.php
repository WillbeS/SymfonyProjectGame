<?php
namespace AppBundle\Service\Utils;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileServiceInterface
{
    public function upload(UploadedFile $file);

    public function delete($fileName);

    public function getTargetDirectory();
}