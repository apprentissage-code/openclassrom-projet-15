<?php

namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MediaFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    $data = [
      [51, 2, 'uploads/0051.jpg', 'Titre 0', null],
      [
        52,
        2,
        'uploads/0052.jpg',
        'Titre 1',
        1
      ],
      [
        53,
        2,
        'uploads/0053.jpg',
        'Titre 2',
        1
      ],
      [
        54,
        2,
        'uploads/0054.jpg',
        'Titre 3',
        null
      ],
      [
        55,
        2,
        'uploads/0055.jpg',
        'Titre 4',
        null
      ],
      [
        56,
        2,
        'uploads/0056.jpg',
        'Titre 5',
        null
      ],
      [
        57,
        2,
        'uploads/0057.jpg',
        'Titre 6',
        3
      ],
      [
        58,
        2,
        'uploads/0058.jpg',
        'Titre 7',
        2
      ],
      [
        59,
        2,
        'uploads/0059.jpg',
        'Titre 8',
        4
      ],
      [
        60,
        2,
        'uploads/0060.jpg',
        'Titre 9',
        3
      ],
      [
        61,
        2,
        'uploads/0061.jpg',
        'Titre 10',
        2
      ],
      [
        62,
        2,
        'uploads/0062.jpg',
        'Titre 11',
        4
      ],
      [
        63,
        2,
        'uploads/0063.jpg',
        'Titre 12',
        1
      ],
      [
        64,
        2,
        'uploads/0064.jpg',
        'Titre 13',
        1
      ],
      [
        65,
        2,
        'uploads/0065.jpg',
        'Titre 14',
        1
      ],
      [
        66,
        2,
        'uploads/0066.jpg',
        'Titre 15',
        null
      ],
      [
        67,
        2,
        'uploads/0067.jpg',
        'Titre 16',
        null
      ],
      [
        68,
        2,
        'uploads/0068.jpg',
        'Titre 17',
        null
      ],
      [
        69,
        2,
        'uploads/0069.jpg',
        'Titre 18',
        null
      ],
      [
        70,
        2,
        'uploads/0070.jpg',
        'Titre 19',
        null
      ],
      [
        71,
        2,
        'uploads/0071.jpg',
        'Titre 20',
        null
      ],
    ];

    foreach ($data as $item) {
      $media = new Media();
      $media->setId($item[0]);
      $user = $manager->getRepository(User::class)->find($item[1]);
      $media->setUser($user);
      $media->setPath($item[2]);
      $media->setTitle($item[3]);
      $album = $manager->getRepository(Album::class)->find($item[4]);
      $media->setAlbum($album);

      $manager->persist($media);
    }

    $manager->flush();
  }
}
