<?php
/**
 * ESD framework
 * @author bearload <565364226@qq.com>
 */

namespace ESD\LoadBalance\Algorithm;
use ESD\LoadBalance\AbstractLoadBalancer;
use ESD\LoadBalance\Node;

/**
 * Class Random
 * @package ESD\LoadBalance\Algorithm
 */
class Random extends AbstractLoadBalancer
{
    /**
     * Select an item via the load balancer.
     */
    public function select(array ...$parameters): Node
    {
        if (empty($this->nodes)) {
            throw new \RuntimeException('Cannot select any node from load balancer.');
        }
        $key = array_rand($this->nodes);
        return $this->nodes[$key];
    }
}
