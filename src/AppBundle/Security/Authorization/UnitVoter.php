<?php

namespace AppBundle\Security\Authorization;

use AppBundle\Entity\Unit;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UnitVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        if (!$subject instanceof Unit) {
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

        /** @var Unit $platform */
        $unit = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($unit, $user);
            case self::EDIT:
                return $this->canEdit($unit, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Unit $unit, User $user)
    {
        if ($this->canEdit($unit, $user)) {
            return true;
        }

        return !$unit->isPrivate();
    }

    private function canEdit(Unit $unit, User $user)
    {
        return $user === $unit->getOwner();
    }
}