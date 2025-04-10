<?php

namespace Umbrella\AdminBundle\Utils;

use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;

class DoctrineUtils
{
    private function __construct()
    {
    }

    public static function matchAll(QueryBuilder $qb, array $fields, string $search, string $parameterPrefix = '_match'): void
    {
        preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $search, $matches);
        $terms = array_map(static fn ($match) => trim($match, '" '), $matches[0]);

        if (0 === \count($terms) || 0 === \count($fields)) {
            return;
        }

        $parameterIndex = 0;

        foreach ($terms as $term) {
            $parameterName = \sprintf('%s_%d', $parameterPrefix, $parameterIndex++);

            $orX = new Orx();
            foreach ($fields as $field) {
                $orX->add(\sprintf('LOWER(CONCAT(%s, \'\')) LIKE :%s', $field, $parameterName));
            }

            $qb->andWhere($orX);
            $qb->setParameter($parameterName, '%' . $term . '%');
        }
    }

    /**
     * @see https://github.com/dustin10/VichUploaderBundle/blob/master/src/Util/ClassUtils.php
     */
    public static function getClass(object $object): string
    {
        $className = $object::class;
        $positionPm = 0;

        // see original code @ https://github.com/api-platform/core/blob/6e9ccf7418bf973d273b125d55ccc521b89afb06/src/Util/ClassInfoTrait.php#L38
        // __CG__: Doctrine Common Marker for Proxy (ODM < 2.0 and ORM < 3.0)
        // __PM__: Ocramius Proxy Manager (ODM >= 2.0)
        if ((false === $positionCg = strrpos($className, '\\__CG__\\'))
            && (false === $positionPm = strrpos($className, '\\__PM__\\'))) {
            return $className;
        }

        if (false !== $positionCg) {
            return substr($className, $positionCg + 8);
        }

        $className = ltrim($className, '\\');

        return substr(
            $className,
            8 + $positionPm,
            strrpos($className, '\\') - ($positionPm + 8)
        );
    }
}
