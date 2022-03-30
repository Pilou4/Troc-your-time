<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    private $uploadedFileDirectory;

    // je demande au conteneur de service de m'envoyer un parametetre qui s'appel $uploadedFileDirectory
    public function __construct($uploadedFileDirectory)
    {
        $this->uploadedFileDirectory = $uploadedFileDirectory;
    }

    // en entrée on prend un fichier uploadé (UploadedFile)
    // on crée un nom aléatoire 
    // on deplace depuis le dossier temporaire vers un dossier de notre application
    // en sortie on transmet le nom du fichier généré pour pouvoir le stocker et retrouver le fichier
    public function upload(UploadedFile $file) 
    {
        // je genere un nom de fichier aléatoire pour éviter que deux fichiers ai le meme nom et s'écrasent 
        // $pictureFilename = 6515611321561.jpg
        $pictureFilename = uniqid() . "." . $file->guessExtension();
        // je deplace le fichier (qui à été mis dans un dossier temporaire par PHP)
        // je le met dans mon dossier public avec le nom que je vient de generer
        $file->move(
            $this->uploadedFileDirectory,
            $pictureFilename
        );

        return $pictureFilename;
    }
} 