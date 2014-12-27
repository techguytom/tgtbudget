<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Builder
 *
 * @package AppBundle\Menu
 */
class Builder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * Constructor
     *
     * @param $factory FactoryInterface
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Main Menu
     *
     * @param $request Request
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Home', array('route' => 'homepage'));
        $menu->addChild('Account', array('route' => 'account'));
        $menu->addChild('Settings', array('uri' => '#'));
        $menu['Settings']->addChild('Account Types', array('route' => 'accountTypes'));
        $menu['Settings']->addChild('Billing Categories', array('route' => 'categories'));
//        $menu['Settings']->addChild('General', array('route' => 'general_settings'));

        return $menu;
    }
}
