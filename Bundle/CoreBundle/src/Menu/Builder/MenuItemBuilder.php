<?php

namespace Umbrella\CoreBundle\Menu\Builder;

use Umbrella\CoreBundle\Menu\DTO\MenuItem;

class MenuItemBuilder
{
    protected MenuBuilder $menuBuilder;
    protected ?MenuItemBuilder $parentBuilder = null;

    protected MenuItem $item;

    protected bool $current = false;

    /**
     * MenuItemBuilder constructor.
     */
    public function __construct(MenuItem $item, MenuBuilder $menuBuilder, ?MenuItemBuilder $parentBuilder = null)
    {
        $this->item = $item;
        $this->menuBuilder = $menuBuilder;
        $this->parentBuilder = $parentBuilder;
    }

    public function add(string $id): MenuItemBuilder
    {
        $child = new MenuItem($this->item->getMenu(), $id);
        $this->item->addChild($child);

        return new MenuItemBuilder($child, $this->menuBuilder, $this);
    }

    public function label(string $label): MenuItemBuilder
    {
        $this->item->setLabel($label);

        return $this;
    }

    public function route(string $route, array $routeParams = []): MenuItemBuilder
    {
        $this->item->setRoute($route, $routeParams);
        return $this;
    }

    public function matchRoute(string $route, array $routeParams = []): MenuItemBuilder
    {
        $this->item->addMatchingRoute($route, $routeParams);

        return $this;
    }

    public function icon(string $icon): MenuItemBuilder
    {
        $this->item->setIcon($icon);

        return $this;
    }

    public function translationDomain(?string $translationDomain): MenuItemBuilder
    {
        $this->item->setTranslationDomain($translationDomain);

        return $this;
    }

    public function show(bool $show = true): MenuItemBuilder
    {
        $this->item->setVisible($show);

        return $this;
    }

    public function current(bool $current = true): MenuItemBuilder
    {
        if ($current) {
            $this->menuBuilder->setCurrent($this->item);
        }
        return $this;
    }

    public function end(): MenuItemBuilder
    {
        return $this->parentBuilder ?: $this;
    }
}
