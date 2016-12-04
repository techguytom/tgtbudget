<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use /** @noinspection PhpUndefinedClassInspection */
    Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/** @noinspection PhpUndefinedClassInspection */

/**
 * Class Builder
 *
 * @package AppBundle\Menu
 */
class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Main Menu
     *
     * @param FactoryInterface $factory
     * @param array            $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root')
                        ->setChildrenAttribute(
                            'class',
                            'nav navbar-nav'
                        );

        $menu->addChild('Home', ['route' => 'userHomepage', 'attributes' => ['id' => 'back_to_homepage']]);
        $menu->addChild('Transactions', ['route' => 'transaction']);
        $menu->addChild('Accounts', ['route' => 'account']);
        $menu->addChild('Bills', ['route' => 'bills']);
        $menu->addChild('Settings', ['uri' => '#'])
             ->setAttribute('dropdown', true);
        $menu['Settings']->addChild('Account Types', ['route' => 'accountTypes']);
        $menu['Settings']->addChild('Budget Categories', ['route' => 'categories']);

        return $menu;
    }
}
