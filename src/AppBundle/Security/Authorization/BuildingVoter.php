<?php

namespace AppBundle\Security\Authorization;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BuildingVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        if (!$subject instanceof Building) {
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

        /** @var Building $platform */
        $building = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($building, $user);
            case self::EDIT:
                return $this->canEdit($building, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Building $building, User $user)
    {
        if ($this->canEdit($building, $user)) {
            return true;
        }

        return !$building->isPrivate();
    }

    private function canEdit(Building $building, User $user)
    {
        return $user === $building->getOwner();
    }
}