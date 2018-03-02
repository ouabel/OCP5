<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root', array(
            'childrenAttributes'    => array(
                'class'             => 'nav navbar-nav',
            )
        ));


        $menu->addChild('Accueil', array('route' => 'home_page'));

        $em = $this->container->get('doctrine')->getManager();
        $pages = $em->getRepository('AppBundle:Page')->findMenuPages();

        foreach ($pages as $page) {
            $menu->addChild($page->getTitle(), array(
                'route' => 'page_show',
                'routeParameters' => array('slug' => $page->getSlug())
            ));
        }

        $menu->addChild('Contact', array('route' => 'contact_page'));

        return $menu;
    }
}