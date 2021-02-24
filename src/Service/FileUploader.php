<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $targetDirectory;
    private $slugger;


    public function __construct($targetDirectory, SluggerInterface $slugger)
    {
       $this->targetDirectory = $targetDirectory;
       $this->slugger = $slugger;

    }

    public function upload(UploadedFile $photoFile, SluggerInterface $sl)
    {


            $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $sl->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();



                $photoFile->move($this->getTargetDirectory(), $newFilename);


        return $newFilename;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
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