<?php
namespace AppBundle\Service\Utils;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService implements FileServiceInterface
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getTargetDirectory(), $fileName);

        return $fileName;
    }

    public function delete($fileName)
    {
        $filesystem = new Filesystem();
        $path = $this->getTargetDirectory() . '/' . $fileName;
        $filesystem->remove($path);
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}