<?php

declare(strict_types=1);

namespace App\Voter;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EditArticleVoter extends Voter
{
    public const EDIT = 'can_edit_product';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
       return $subject instanceof Article && $attribute === self::EDIT;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        $article = $subject;

        $owner = $article->getUser();

        return $user === $owner;

    }

}
