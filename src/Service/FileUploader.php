<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $fileTargetDirectory;
    private $targetDirectory;
    private $slugger;

    public function __construct($targetDirectory, $fileTargetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->fileTargetDirectory = $fileTargetDirectory;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file, SluggerInterface $sl)
    {

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);


        $safeFilename = $sl->slug($originalFilename);
        dump('upload');

        if ($file->guessExtension() === "png" || $file->guessExtension() === "jpg") {
            dump("png/jpg");
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
            $file->move($this->targetDirectory, $newFilename);

        } else {
            dump("autre extension" . " " . $file->guessExtension());
            $newFilename = $safeFilename . "." . "csv";
            $file->move($this->fileTargetDirectory, $newFilename);
        }

        return $newFilename;
    }

    /**
     * @return SluggerInterface
     */
    public function getSlugger(): SluggerInterface
    {
        return $this->slugger;
    }

    /**
     * @param SluggerInterface $slugger
     */
    public function setSlugger(SluggerInterface $slugger): void
    {
        $this->slugger = $slugger;
    }


}