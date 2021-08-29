<?php
namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

class AccueilController extends AbstractController
{
    public function accueil(TranslatorInterface $translator)
    {
        // look for *all* Article objects
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render('accueil.html.twig', ['articles' => $articles]);

    }

}