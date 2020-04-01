<?php
/**
 * ESD framework
 * @author tmtbe <896369042@qq.com>
 */

namespace ESD\Plugins\MQTT;


class PacketIdentifierStore_Static implements IfPacketIdentifierStore
{
    protected $id = 0;

    /**
     * Get Current Packet Identifier
     *
     * @return int
     */
    public function get()
    {
        return $this->id;
    }

    /**
     * Next Packet Identifier
     *
     * @return int
     */
    public function next()
    {
        return ++$this->id;
    }

    /**
     * Set A New ID
     *
     * @param $new_id
     * @return void
     */
    public function set($new_id)
    {
        $this->id = (int) $new_id;
    }
}