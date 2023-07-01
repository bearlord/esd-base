<?php

declare(strict_types=1);
/**
 * Parser Reflection API
 *
 * @copyright Copyright 2015, Lisachenko Alexander <lisachenko.it@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace ESD\Goaop\ParserReflection\NodeVisitor;

use ESD\Goaop\ParserReflection\ValueResolver\NodeExpressionResolver;
use ESD\Nikic\PhpParser\Node;
use ESD\Nikic\PhpParser\NodeTraverser;
use ESD\Nikic\PhpParser\NodeVisitorAbstract;

/**
 * Visitor to collect static variables in the method/function body and resolve them
 */
class StaticVariablesCollector extends NodeVisitorAbstract
{
    /**
     * Reflection context, eg. ReflectionClass, ReflectionMethod, etc
     *
     * @var mixed
     */
    private $context;

    private $staticVariables = [];

    /**
     * Default constructor
     *
     * @param mixed $context Reflection context, eg. ReflectionClass, ReflectionMethod, etc
     */
    public function __construct($context)
    {
        $this->context = $context;
    }

    /**
     * {@inheritDoc}
     */
    public function enterNode(Node $node)
    {
        // There may be internal closures, we do not need to look at them
        if ($node instanceof Node\Expr\Closure) {
            return NodeTraverser::DONT_TRAVERSE_CHILDREN;
        }

        if ($node instanceof Node\Stmt\Static_) {
            $expressionSolver = new NodeExpressionResolver($this->context);
            $staticVariables  = $node->vars;
            foreach ($staticVariables as $staticVariable) {
                $expr = $staticVariable->default;
                if ($expr) {
                    $expressionSolver->process($expr);
                    $value = $expressionSolver->getValue();
                } else {
                    $value = null;
                }

                if ($staticVariable->var->name instanceof Node\Expr) {
                    $expressionSolver->process($staticVariable->var->name);
                    $name = $expressionSolver->getValue();
                } else {
                    $name = $staticVariable->var->name;
                }
                $this->staticVariables[$name] = $value;
            }
        }

        return null;
    }

    /**
     * Returns an associative map of static variables in the method/function body
     */
    public function getStaticVariables(): array
    {
        return $this->staticVariables;
    }
}
