<?php

namespace Umbrella\AdminBundle\Lib\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class BooleanColumnType extends PropertyColumnType
{
    public function __construct(protected readonly TranslatorInterface $translator)
    {
    }

    public function renderProperty(mixed $value, array $options): string
    {
        if ($options['strict_comparison'] && !\is_bool($value)) {
            return '';
        }

        return $value
            ? \sprintf(
                '<span class="badge bg-success"><i class="%s"></i> %s</span>',
                $options['yes_icon'],
                $options['yes_value']
            )
            : \sprintf(
                '<span class="badge bg-danger"><i class="%s"></i> %s</span>',
                $options['no_icon'],
                $options['no_value']
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('is_safe_html', true)

            ->setDefault('strict_comparison', false)
            ->setAllowedTypes('strict_comparison', 'bool')

            ->setDefault('yes_value', $this->translator->trans('label.yes', [], 'UmbrellaAdmin'))
            ->setAllowedTypes('yes_value', 'string')

            ->setDefault('no_value', $this->translator->trans('label.no', [], 'UmbrellaAdmin'))
            ->setAllowedTypes('no_value', 'string')

            ->setDefault('yes_icon', 'mdi mdi-check me-1')
            ->setAllowedTypes('yes_icon', 'string')

            ->setDefault('no_icon', 'mdi mdi-cancel me-1')
            ->setAllowedTypes('no_icon', 'string');
    }
}
