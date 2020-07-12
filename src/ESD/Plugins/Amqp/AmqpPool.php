<?php
/**
 * ESD framework
 * @author tmtbe <896369042@qq.com>
 */

namespace ESD\Plugins\Amqp;

use PhpAmqpLib\Channel\AMQPChannel;

/**
 * Class AmqpPool
 * @package ESD\Plugins\Amqp
 */
class AmqpPool
{
    protected $poolList = [];

    /**
     * Add amqp connection
     *
     * @param AmqpConnection $amqpConnection
     */
    public function addConnection(AmqpConnection $amqpConnection)
    {
        $this->poolList[$amqpConnection->getAmqpPoolConfig()->getName()] = $amqpConnection;
    }

    /**
     * Get channel
     *
     * @param string $name
     * @param int $channel_id
     * @return AMQPChannel
     * @throws \Exception
     */
    public function channel($name = "default", $channel_id = null): AMQPChannel
    {
        $connection = $this->getConnection($name);
        return $connection->channel($channel_id);
    }

    /**
     * Get connection
     * 
     * @param $name
     * @return AmqpConnection|null
     */
    public function getConnection($name = "default")
    {
        return $this->poolList[$name] ?? null;
    }
}