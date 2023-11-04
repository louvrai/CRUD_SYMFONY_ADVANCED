<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Form\SearchAuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function redirectAuthor(): Response
    {
       return $this->redirectToRoute('author.add');
    }
    #[Route('/showauthor', name: 'author.show')]
    public function showAuthor(AuthorRepository $repo,Request $request): Response
    {

        $form = $this->createForm(SearchAuthorType::class);
        $form->handleRequest($request);

        $data = $form->getData();
        if($form->isSubmitted() and $form->isValid()){
            $max = $data['max'];
            $min = $data['min'];
            $x = $repo->findAuthorsByBookCountRange($min,$max);
        }else{
            $x= $repo->findAll();
        }

        return $this->renderForm('author/show.html.twig', [
            'x' => $x,
            'f' => $form
        ]);
    }
    #[Route('/delauthor/{id}', name: 'author.delete')]
    public function DeleteAuthor(AuthorRepository $repo,$id,ManagerRegistry $manager): Response
    {
            $em = $manager->getManager();
        $x= $repo->find($id);
        $em->remove($x);
        $em->flush();

        return $this->redirectToRoute('author.show');

    }
    #[Route('/addauthor', name: 'author.add')]
    public function AddAuthor(ManagerRegistry $manager,Request $req): Response
    {
        $em = $manager->getManager();
         $author = new Author();
         $author->setNbBook(0);
         $form = $this->createForm(AuthorType::class,$author);
        $form->handleRequest($req);
      if($form->isSubmitted() and $form->isValid()){
          $em->persist($author);
          $em->flush();
          return $this->redirectToRoute('author.show');
      }



        return $this->renderForm('author/add.html.twig',[
            'f' => $form,
        ]);

    }
    #[Route('/editauthor/{id}', name: 'author.edit')]
    public function EditAuthor(AuthorRepository $repo,ManagerRegistry $manager,Request $req,$id): Response
    {
        $em = $manager->getManager();
        $x = $repo->find($id);
        $form = $this->createForm(AuthorType::class,$x);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $em->persist($x);
            $em->flush();
            return $this->redirectToRoute('author.show');
        }



        return $this->renderForm('author/edit.html.twig',[
            'f' => $form,
        ]);

    }

    #[Route('/delete0author}', name: 'author.delete0')]
    public function del0Author(AuthorRepository $repo): Response
    {
           $repo->deleteAuthorsWithZeroBooks();

            return $this->redirectToRoute('author.show');

    }




}
