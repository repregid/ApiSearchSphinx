<?php

namespace Repregid\ApiSearchSphinx;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class RepregidApiSearchSphinxBundle
 * @package Repregid\ApiSearchSphinx
 */
class RepregidApiSearchSphinxBundle extends Bundle
{
    /**
     * @param ContainerBuilder $builder
     */
    public function build(ContainerBuilder $builder)
    {
        parent::build($builder);
    }
}