<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController','articles'=>$articles
        ]);
    }

    /**
    *@route("/",name="home")
    */
    public function home()
    {
        return $this->render('blog/home.html.twig');
    }

    /**
     * @route("/blog/new",name="blog_create")
     * @route("/blog/{id}/edit",name="blog_edit")
     */
    public function form(Article $article=null, Request $request,ObjectManager $manager){
       
        if(!$article){
             $article=new Article();
        } 
    
        $form=$this->createFormBuilder($article)
        
                   ->add('title')
                   ->add('content',TextType::class,[
                       'attr'=>[
                           'placeholder'=>'le texte a afficher'
                       ]
                   ])
                   ->add('image')
                   ->add('save',SubmitType::class)
                   ->getForm();
                    
        $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid()){
           if(!$article->getId()){
               $article->setCreatedAt(new \DateTime());
           }
           $manager->persist($article);
           $manager->flush();
           return $this->redirectToRoute('blog_show',['id'=>$article->getId()]);
       }
              return $this->render('/blog/create.html.twig',['formArticle'=>$form->createView(),'editMode'=>$article->getId()!==null]);
            }
            
    /**
     * @route("/blog/{id}",name="blog_show")
     *
     * @return void
     */
    public function show(Article $article){
        return $this->render('blog/show.html.twig',['article'=>$article]);
    }


}
 