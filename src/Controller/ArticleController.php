<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginator): Response
    {
        // $articles = $articleRepo->findBy([], ['created_at' => 'desc'], 9, 1);
        $data = $articleRepository->findBy([],['created_at' => 'desc']);
        $articles = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            9
        );

        // $articles->setCustomParameters([
        //     'align' => 'center', # center|right (for template: twitter_bootstrap_v4_pagination)
        //     'size' => 'large', # small|large (for template: twitter_bootstrap_v4_pagination)
        // ]);

        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/new", name="article_new", methods={"GET","POST"})
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     */
    public function newOrEdit(Article $article = null, Request $request) {
        if (!$article) {
            $article = new Article();
        }

        $form = $this->createForm(ArticleType::class, $article);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if (!$article->getId()) {
                $article->setCreatedAt(new \DateTime);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_index');
        }
        // dump($request);
        return $this->render('article/newOrEdit.html.twig', [
            'form' => $form->createView(),
            'editMode' => $article->getId() !== null,
            'article' => $article
        ]);
    }

    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $article->setCreatedAt(new \DateTime);
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show($id, articleRepository $articleRepository): Response
    {
        // $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $article = $articleRepository->find($id);
        $articles = $articleRepository->findThreeByCategoryId($article->getCategory());
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'articles' => $articles
        ]);
    }


    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     */
    public function delete($id, ArticleRepository $articleRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $articleRepository->find($id);
        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('article_index');
    }
}
