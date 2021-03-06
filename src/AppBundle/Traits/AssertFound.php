<?php

namespace AppBundle\Traits;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait AssertFound
{
    private function assertFound($entity)
    {
        if(!$entity) {
            throw new NotFoundHttpException('Page Not Found');
        }
    }
}