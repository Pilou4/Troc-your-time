<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalMentionController extends AbstractController
{

    #[Route('/legal-mention', name: 'mention')]
    public function mention(): Response
    {
        return $this->render('legal_mention/legal-mention.html.twig');
    }

    #[Route('/confidentiality', name: 'confidentiality')]
    public function confidentiality(): Response
    {
        return $this->render('legal_mention/confidentiality.html.twig');
    }
}
