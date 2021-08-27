<?php
namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    public function accueil()
    {
        // look for *all* Article objects
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render('accueil.html.twig', ['articles' => $articles]);

    }
}