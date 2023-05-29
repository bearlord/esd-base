<?php

declare(strict_types=1);
/*
 * Go! AOP framework
 *
 * @copyright Copyright 2012, Lisachenko Alexander <lisachenko.it@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace ESD\Goaop\Core;

use ESD\Goaop\Aop\Advisor;
use ESD\Goaop\Aop\Aspect;
use ESD\Goaop\Aop\Pointcut;
use ReflectionClass;

/**
 * Extension interface that defines an API for aspect loaders
 */
interface AspectLoaderExtension
{
    /**
     * Loads definition from specific point of aspect into the container
     *
     * @param Aspect          $aspect           Instance of aspect
     * @param ReflectionClass $reflectionAspect Reflection of aspect
     *
     * @return array<string,Pointcut>|array<string,Advisor>
     */
    public function load(Aspect $aspect, ReflectionClass $reflectionAspect): array;
}
