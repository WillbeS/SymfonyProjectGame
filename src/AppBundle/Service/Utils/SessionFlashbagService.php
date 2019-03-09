<?php

namespace AppBundle\Service\Utils;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionFlashbagService implements FlashMessageServiceInterface
{
    const SUCCESS = 'success';

    const ERROR = 'danger';

    const INFO = 'info';

    const WARNING = 'warning';

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * SessionFlashbagService constructor.
     * @param Session $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }


    public function addMessage(string $message, string $type = null)
    {
        if (null === $type) {
            $type = self::INFO;
        }

        $this->session
            ->getFlashBag()
            ->add($type, $message);
    }
}