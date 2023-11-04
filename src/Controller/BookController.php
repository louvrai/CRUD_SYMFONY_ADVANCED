<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\BookType;
use App\Form\SearchType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function redirectBook(): Response
    {
       return $this->redirectToRoute('book.add');
    }
    #[Route('/showbook', name: 'book.show')]
    public function showBook(BookRepository $repo): Response
    {       $x = $repo->findAll();
           $y = $repo->nbBookScienceFiction();
        return $this->render('book/show.html.twig', [
            'x' => $x,
            'y' => $y
        ]);
    }
    #[Route('/searchbook', name: 'book.search')]
    public function searchBook(BookRepository $repo,Request $request): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        $form1 = $form->getData();

        //$req = $request->request->get('search');
        if($form->isSubmitted() and $form->isValid()) {
            $req = $form1['search'];
            $y = $repo->findByRef($req);

        }else {
            $y = $repo->OrederByBookNumber();
        }
        return $this->renderForm('book/search.html.twig', [
            'l' => $form,
            'x' => $y
        ]);
    }
    #[Route('/addbook', name: 'book.add')]
    public function addBook(ManagerRegistry $manager, Request $req ): Response
    {
        $em = $manager->getManager();
        $book = new Book();
        $form = $this->createForm(BookType::class,$book);
        $form->handleRequest($req);

        if($form->isSubmitted() and $form->isValid()){
            $author = $book->getAuthor();
            if($author instanceof Author){
                $author->setNbBook($author->getNbBook()+1);
            }
            $em->persist($author);
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('book.show');
        }
        return $this->renderForm('book/add.html.twig', [
            'f' => $form,
        ]);
    }
    #[Route('/delbook/{id}', name: 'book.delete')]
    public function delBook(BookRepository $repo,$id,ManagerRegistry $manager): Response
    {    $em = $manager->getManager();
        $x = $repo->find($id);
        $author =$x->getAuthor();
        if ($author instanceof Author){
            $author->setNbBook($author->getNbBook()-1);
        }
        $em->persist($author);
        $em->remove($x);
        $em->flush();
        return $this->redirectToRoute('book.show');

    }
    #[Route('/editbook/{id}', name: 'book.edit')]
    public function editBook(ManagerRegistry $manager, Request $req,BookRepository $repo,$id ): Response
    {
        $em = $manager->getManager();
        $book = $repo->find($id);
        $form = $this->createForm(BookType::class,$book);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('book.show');
        }
        return $this->renderForm('book/edit.html.twig', [
            'f' => $form,
        ]);
    }
    #[Route('/update1book', name: 'book.up')]
    public function updatequery(ManagerRegistry $manager, Request $req,BookRepository $repo): Response
    {
        $repo->modificationQuery();
        return $this->redirectToRoute('book.show');

    }
    #[Route('/list1book', name: 'book.listDate')]
    public function listOfBookByDate(BookRepository $repo): Response
    {
       $x = $repo->listeBookByDate();
        return $this->render('book/list1.html.twig',[
             'y' => $x
        ]);

    }
}
