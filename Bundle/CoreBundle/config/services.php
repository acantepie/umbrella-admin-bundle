<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Umbrella\CoreBundle\Ckeditor\CkeditorConfiguration;
use Umbrella\CoreBundle\Ckeditor\CkeditorExtension;
use Umbrella\CoreBundle\Command\IndexEntityCommand;
use Umbrella\CoreBundle\DataTable\Adapter\CallableAdapter;
use Umbrella\CoreBundle\DataTable\Adapter\EntityAdapter;
use Umbrella\CoreBundle\DataTable\Adapter\NestedEntityAdapter;
use Umbrella\CoreBundle\DataTable\DataTableBuilerHelper;
use Umbrella\CoreBundle\DataTable\DataTableFactory;
use Umbrella\CoreBundle\DataTable\DataTableRegistry;
use Umbrella\CoreBundle\DataTable\DataTableRenderer;
use Umbrella\CoreBundle\DataTable\DataTableType;
use Umbrella\CoreBundle\DataTable\Twig\DataTableExtension;
use Umbrella\CoreBundle\Form\Extension\FormTypeExtension;
use Umbrella\CoreBundle\Form\UmbrellaSelect\UmbrellaSelectConfigurator;
use Umbrella\CoreBundle\JsResponse\JsResponseBuilder;
use Umbrella\CoreBundle\JsResponse\JsResponseViewListener;
use Umbrella\CoreBundle\Menu\MenuRegistry;
use Umbrella\CoreBundle\Menu\MenuProvider;
use Umbrella\CoreBundle\Menu\Twig\MenuExtension;
use Umbrella\CoreBundle\Menu\Visitor\MenuCurrentVisitor;
use Umbrella\CoreBundle\Menu\Visitor\MenuVisibilityVisitor;
use Umbrella\CoreBundle\Search\Annotation\SearchableAnnotationReader;
use Umbrella\CoreBundle\Search\EntityIndexer;
use Umbrella\CoreBundle\Search\SearchableEntitySubscriber;
use Umbrella\CoreBundle\Twig\CoreExtension;
use Umbrella\CoreBundle\Widget\Twig\WidgetExtension;
use Umbrella\CoreBundle\Widget\WidgetFactory;
use Umbrella\CoreBundle\Widget\WidgetRegistry;
use Umbrella\CoreBundle\Widget\WidgetRenderer;

return static function (ContainerConfigurator $configurator): void {

    $services = $configurator->services();

    $services->defaults()
        ->private()
        ->autowire(true)
        ->autoconfigure(false);

    // -- Menu -- //
    $services->set(MenuRegistry::class);
    $services->set(MenuProvider::class);
    $services->set(MenuVisibilityVisitor::class)
        ->tag('umbrella.menu.visitor');
    $services->set(MenuCurrentVisitor::class)
        ->tag('umbrella.menu.visitor');
    $services->set(MenuExtension::class)
        ->tag('twig.extension');

    // -- Js Response -- //

    $services->set(JsResponseBuilder::class);
    $services->set(JsResponseViewListener::class)
        ->tag('kernel.event_subscriber');


    // -- Widget -- //

    $services->set(WidgetFactory::class);
    $services->set(WidgetRegistry::class);
    $services->set(WidgetRenderer::class);
    $services->set(WidgetExtension::class)
        ->tag('twig.extension');
    $services->load('Umbrella\\CoreBundle\\Widget\\Type\\', '../src/Widget/Type/*')
        ->tag('umbrella.widget.type');

    // -- DataTable -- //
    $services->set(DataTableBuilerHelper::class);
    $services->set(DataTableFactory::class);
    $services->set(DataTableRegistry::class);
    $services->set(DataTableRenderer::class);
    $services->set(DataTableType::class)
        ->tag(DataTableRegistry::TAG_TYPE);

    $services->set(DataTableExtension::class)
        ->tag('twig.extension');

    $services->set(CallableAdapter::class)
        ->tag(DataTableRegistry::TAG_ADAPTER);
    $services->set(EntityAdapter::class)
        ->tag(DataTableRegistry::TAG_ADAPTER);
    $services->set(NestedEntityAdapter::class)
        ->tag(DataTableRegistry::TAG_ADAPTER);

    $services->load('Umbrella\\CoreBundle\\DataTable\\Column\\', '../src/DataTable/Column/*')
        ->tag(DataTableRegistry::TAG_COLUMN_TYPE);

    // -- Ckeditor -- //
    $services->set(CkeditorConfiguration::class);
    $services->set(CkeditorExtension::class)
        ->tag('twig.extension');

    // -- Search -- //
    $services->set(IndexEntityCommand::class)
        ->tag('console.command');
    $services->set(SearchableAnnotationReader::class);
    $services->set(EntityIndexer::class);
    $services->set(SearchableEntitySubscriber::class)
        ->tag('doctrine.event_subscriber');

    // -- Core -- //
    $services->set(CoreExtension::class)
        ->arg(0, service('twig.form.renderer'))
        ->tag('twig.extension');

    // -- Form -- //
    $services->set(UmbrellaSelectConfigurator::class);
    $services->load('Umbrella\\CoreBundle\\Form\\', '../src/Form/*')
        ->exclude('../src/Form/UmbrellaSelect')
        ->tag('form.type');

    $services->set(FormTypeExtension::class)
        ->tag('form.type_extension', [
            'extended_type' => FormType::class
        ]);
};
