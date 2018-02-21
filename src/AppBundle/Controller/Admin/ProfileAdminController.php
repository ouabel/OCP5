<?php

namespace AppBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\HttpFoundation\Response;

class ProfileAdminController extends BaseAdminController
{

    public function persistEntity($profile)
    {
        $profile->setPermalink(uniqid('permalink'));
        $this->em->persist($profile);
        $this->em->flush();
    }
}