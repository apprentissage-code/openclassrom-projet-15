<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\User;
use App\Repository\AlbumRepository;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
  #[Route('/', name: 'home')]
  public function home()
  {
    return $this->render('front/home.html.twig');
  }

  #[Route('/guests', name: 'guests')]
  public function guests(UserRepository $userRepository)
  {
    $guests = $userRepository->getGuestActiveWithMediaCount();

    return $this->render('front/guests.html.twig', [
      'guests' => $guests
    ]);
  }

  #[Route('/guests/{id}', name: 'guest')]
  public function guest(User $guest)
  {
    return $this->render('front/guest.html.twig', [
      'guest' => $guest
    ]);
  }

  #[Route('/portfolio/{id?}', name: 'portfolio')]
  public function portfolio(
    AlbumRepository $albumRepository,
    UserRepository $userRepository,
    MediaRepository $mediaRepository,
    ?Album $album = null
  ) {
    $albums = $albumRepository->findAll();
    $user = $userRepository->getAdmin();

    $medias = $album
      ? $mediaRepository->findByAlbum($album)
      : $mediaRepository->findByUser($user);
    return $this->render('front/portfolio.html.twig', [
      'albums' => $albums,
      'album' => $album,
      'medias' => $medias
    ]);
  }

  #[Route('/about', name: 'about')]
  public function about()
  {
    return $this->render('front/about.html.twig');
  }
}
