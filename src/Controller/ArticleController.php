<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
    // ============= Cette fonction permet de créer un nouvel article  ============= //

    /**
     * @Route("/article/new", name="create_article")
     */
    public function createArticle(): Response
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createArticle(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $article = new Article();
        $article->setTitre('Article 1 - The Legend of Zelda : Breath of the Wild');
        $article->setIntroduction('Sorti en 2017, Zelda BotW a impressionné le monde du jeu vidéo avec ses innovations. Et l\'équitation dans tout ça ?');
        $article->setContenu('Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet');
        $article->setNote(5);
        // tell Doctrine you want to (eventually) save the Article (no queries yet)
        $entityManager->persist($article);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new article with id ' . $article->getId());

    }

    /**
     * @Route("/article/list", name="article_list")
     */
    function list(ArticleRepository $articleRepository): Response {
        $repository = $this->getDoctrine()->getRepository(Article::class);

        // look for *all* Article objects
        $articles = $repository->findAll();

    }

    // ============= Cette fonction récupère l'article à partir de la BDD ============= //
    /**
     * @Route("/article/id/{id}", name="article_show")
     */
    public function show(int $id): Response
    {
        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article found for id ' . $id
            );
        }

        return $this->render('article/show.html.twig', ['article' => $article]);

        // or render a template
        // in the template, print things with {{ article.name }}
        // return $this->render('article/show.html.twig', ['article' => $article]);
    }

    // ============= Cette fonction Affiche tous les articles dans la BDD ============= //

}