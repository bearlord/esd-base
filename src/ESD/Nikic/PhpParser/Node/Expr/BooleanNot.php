<?php declare(strict_types=1);

namespace ESD\Nikic\PhpParser\Node\Expr;

use ESD\Nikic\PhpParser\Node\Expr;

class BooleanNot extends Expr
{
    /** @var Expr Expression */
    public $expr;

    /**
     * Constructs a boolean not node.
     *
     * @param Expr $expr       Expression
     * @param array               $attributes Additional attributes
     */
    public function __construct(Expr $expr, array $attributes = []) {
        $this->attributes = $attributes;
        $this->expr = $expr;
    }

    public function getSubNodeNames() : array {
        return ['expr'];
    }
    
    public function getType() : string {
        return 'Expr_BooleanNot';
    }
}
