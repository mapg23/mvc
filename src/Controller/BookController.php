<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BookRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    #[Route('/library', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/library/create', name: 'app_book_create')]
    public function createBook(): Response
    {
        return $this->render('book/create.html.twig');
    }

    #[Route('library/add', name: 'app_book_add', methods: ['POST'])]
    public function addBook(
        Request $request,
        ManagerRegistry $doctrine,
    ): Response {
        $entityManager = $doctrine->getManager();

        $data = $request->request->all();

        $book = new Book();
        $book->setTitle($data['title']);
        $book->setIsbn($data['isbn']);
        $book->setAuthor($data['author']);
        $book->setImage($data['image']);

        $entityManager->persist($book);

        $entityManager->flush();

        return $this->redirectToRoute("app_book");
    }

    #[Route('/library/all', name: 'app_book_show_all')]
    public function showAllBooks(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository->findAll();

        $data = [
            'books' => $books
        ];

        return $this->render('book/all.html.twig', $data);
    }

    #[Route("/library/specific/{isbn}", name: 'app_book_show_specific')]
    public function showSpecificBook(
        BookRepository $bookRepository,
        string $isbn
    ): Response {
        $book = $bookRepository->findByIsbnField($isbn);

        $data = [
            'book' => $book
        ];

        return $this->render('book/specific.html.twig', $data);
    }

    #[Route("/library/delete/{isbn}", name: 'app_book_delete')]
    public function deleteBook(
        ManagerRegistry $doctrine,
        string $isbn
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->findOneBy(['isbn' => $isbn]);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book with that isbn found'
            );
        }

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('app_book_show_all');
    }

    #[Route("/library/update/{isbn}", name: 'app_book_update', methods: ['GET'])]
    public function updateBook(
        ManagerRegistry $doctrine,
        string $isbn
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->findOneBy(['isbn' => $isbn]);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book with that isbn found'
            );
        }

        $data = [
            'book' => $book
        ];

        return $this->render('book/update.html.twig', $data);
    }

    #[Route("/library/update/{isbn}", name: 'app_book_append_update', methods: ['POST'])]
    public function appendUpdates(
        Request $request,
        ManagerRegistry $doctrine,
        string $isbn
    ): Response {
        $entityManager = $doctrine->getManager();
        $data = $request->request->all();

        $book = $entityManager->getRepository(Book::class)->findOneBy(['isbn' => $isbn]);

        $book->setTitle($data['title']);
        $book->setIsbn($data['isbn']);
        $book->setAuthor($data['author']);
        $book->setImage($data['image']);

        $entityManager->flush();

        return $this->redirectToRoute('app_book_show_all');
    }

}
