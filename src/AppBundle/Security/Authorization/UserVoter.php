<?php

namespace AppBundle\Security\Authorization;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        /** @var User $player */
        $player = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($player, $user);
            case self::EDIT:
                return $this->canEdit($player, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(User $player, User $user)
    {
        if ($this->canEdit($player, $user)) {
            return true;
        }

        return !$player->isPrivate();
    }

    private function canEdit(User $player, User $user)
    {
        return $user === $player;
    }
}