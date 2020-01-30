<?php

namespace App\CatalogBundle\Security\Voter;

use App\CatalogBundle\Interfaces\AuthorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthorVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    /**
     * @param string|\Symfony\Component\Security\Core\Authorization\Voter\string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject):bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::VIEW, self::EDIT], true)
            && $subject instanceof AuthorInterface;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($subject, $user);
                break;
            case self::VIEW:
                return $this->canView($subject, $user);
                break;
        }

        return false;
    }

    /**
     * @param AuthorInterface $subject
     * @param UserInterface $user
     * @return bool
     */
    private function canView(AuthorInterface $subject, UserInterface $user):bool
    {
        return $this->canEdit($subject, $user)
            || true; //add public attribute
    }

    /**
     * @param AuthorInterface $subject
     * @param UserInterface $user
     * @return bool
     */
    private function canEdit(AuthorInterface $subject, UserInterface $user):bool
    {
        return $user === $subject->getAuthor()
            || $this->security->isGranted('ROLE_ADMIN');
    }
}
