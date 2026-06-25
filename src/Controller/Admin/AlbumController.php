<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/album', name: 'admin_album_')]
class AlbumController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(AlbumRepository $albumRepository)
    {
        $albums = $albumRepository->findAll();

        return $this->render('admin/album/index.html.twig', ['albums' => $albums]);
    }


    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager)
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($album);
            $entityManager->flush();

            return $this->redirectToRoute('admin_album_index');
        }

        return $this->render('admin/album/add.html.twig', ['form' => $form->createView()]);
    }


    #[Route('/{id}/update', name: 'update', methods: ['GET', 'POST'])]
    public function update(Album $album, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_album_index');
        }

        return $this->render('admin/album/update.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/{id}/delete', name: 'delete')]
    public function delete(Album $album, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($album);
        $entityManager->flush();

        return $this->redirectToRoute('admin_album_index');
    }
}
