<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("", name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig');
    }

    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function form(Article $article = null, Request $request, ObjectManager $manager)
    {
        if (!$article) {
            $article = new Article();
        }
        $form = $this->createFormBuilder($article)
                        ->add('title')
                        ->add('category', EntityType::class, [
                            'class' => Category::class,
                            'choice_label' =>'title'
                        ])
                        ->add('content')
                        ->add('image')
                        ->getForm();

        $form->HandleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$article->getId()) {
                $article->setCreateAt(new \ DateTime());
            }

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('show_blog', ['id' => $article->getId()]);
        }

        return $this->render('blog/create.html.twig', [
            'formarticle' => $form->createView(),
            'editMod' => $article->getId() !== null,
        ]);
    }

    /**
     * @Route("/blog/{id}", name="show_blog")
     */
    public function show($id, ArticleRepository $repo)
    {
        $article = $repo->find($id);

        return $this->render('blog/show.html.twig', [
            'article' => $article,
        ]);
    }
}
