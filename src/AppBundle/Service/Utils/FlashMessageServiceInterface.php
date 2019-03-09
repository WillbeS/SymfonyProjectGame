<?php

namespace AppBundle\Service\Utils;


interface FlashMessageServiceInterface
{
    public function addMessage(string $message, string $type = null);
}