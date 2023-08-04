<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use App\Factory\ArticleFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private const COUNT_CATEGORIES = 4;
    private const COUNT_ARTICLE = 20;

    private const COUNT_USERS = 8;

    public function load(ObjectManager $manager): void
    {
        CategoryFactory::createMany(self::COUNT_CATEGORIES);

        UserFactory::createMany(self::COUNT_USERS);

        ArticleFactory::createMany(
            self::COUNT_ARTICLE,
            function () {
                return [
                    'category' => CategoryFactory::random(),
                    'user' => UserFactory::random(),
                ];
            },
        );

        $manager->flush();
    }
}