<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
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
        $article->setTitre();
        $article->setIntroduction();
        $article->setContenu();
        $article->setNote();
        // tell Doctrine you want to (eventually) save the Article (no queries yet)
        $entityManager->persist($article);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Un nouvel article pour ' . $article->getTitre() . ' a été créé, son id est ' . $article->getId());

    }
// ============= Cette fonction Affiche tous les articles dans la BDD ============= //

    /**
     * @Route("/article/list", name="article_list")
     */
    function list(ArticleRepository $articleRepository): Response {
        $repository = $this->getDoctrine()->getRepository(Article::class);

        // look for *all* Article objects
        $articles = $repository->findAll();

        return $this->render('article/list.html.twig', ['articles' => $articles]);

    }

    // ============= Cette fonction récupère l'article à partir de l'ID dans la BDD ============= //
    /**
     * @Route("/article/{id}", name="article_show")
     */
    public function show(int $id, Request $request, EntityManagerInterface $manager): Response
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime())
                ->setArticle($article);
            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
        }
        if (!$article) {
            throw $this->createNotFoundException(
                'No article found for id ' . $id
            );
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'commentForm' => $form->createView(),

        ]);

        // or render a template
        // in the template, print things with {{ article.name }}
        // return $this->render('article/show.html.twig', ['article' => $article]);
    }

    // ============= Cette fonction Supprime l'article ============= //
    /**
     * @Route("/article/id/{id}/delete", name="article_delete")
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article found for id ' . $id
            );
        }

        $entityManager->remove($article);

        $entityManager->flush();

        return new Response('L\'article  a été supprimé');
    }

    // ============= Cette fonction permet de créer un nouvel article  ============= //

    /**
     * @Route("/article/new/form", name="create_article_form")
     */
    public function createArticleForm(Request $request)
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createArticle(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $article = new Article();
        $form = $this->createFormBuilder($article)
            ->add('Titre')
            ->add('Introduction', TextareaType::class)
            ->add('Contenu', CKEditorType::class) // Champ WYSIWIG
            ->add('Note')
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
            ])
            ->getForm();

        $form->handleRequest($request);
        dump($article);
        return $this->render('article/create.html.twig', [
            'formArticle' => $form->createView(),
        ]);
    }

}