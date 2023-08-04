<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use DateTimeImmutable;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;


final class ArticleFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function getDefaults(): array
    {
        return [
            'title' => self::faker()->text(20),
            'intro' => self::faker()->text(30),
            'description' => self::faker()->text(),
            'home' => self::faker()->boolean,
            'trending' => self::faker()->boolean,
            'createdAt' =>  DateTimeImmutable::createFromMutable(self::faker()->dateTime),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Product $product): void {})
            ;
    }

    protected static function getClass(): string
    {
        return Article::class;
    }
}