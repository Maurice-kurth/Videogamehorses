<?php
namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class AccueilController extends AbstractController
{
    public function accueil(Request $request, TranslatorInterface $translator)
    {

        // $locale est le string de la langue actuelle
        $locale = $request->getLocale();
        // look for *all* Article objects
        //$articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(
            ['language' => $locale],
            null, 3, null
        );

        return $this->render('accueil.html.twig', ['articles' => $articles]);

    }

}