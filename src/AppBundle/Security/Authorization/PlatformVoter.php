<?php

// src/AppBundle/Security/PostVoter.php
namespace AppBundle\Security\Authorization;

use AppBundle\Entity\Platform;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PlatformVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        // only vote on Platform objects inside this voter
        if (!$subject instanceof Platform) {
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

        // you know $subject is a Platform object, thanks to supports
        /** @var Platform $platform */
        $platform = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($platform, $user);
            case self::EDIT:
                return $this->canEdit($platform, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Platform $platform, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($platform, $user)) {
            return true;
        }

        // the Platform object could have, for example, a method isPrivate()
        // that checks a boolean $private property
        return !$platform->isPrivate();
    }

    private function canEdit(Platform $platform, User $user)
    {
        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object
        return $user === $platform->getUser();
    }
}