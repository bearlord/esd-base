<?php
/**
 * ESD framework
 * @author tmtbe <896369042@qq.com>
 * @author bearlord <565364226@qq.com>
 */

namespace ESD\Yii\Plugin\Queue;

use ESD\Core\Message\Message;
use ESD\Core\Server\Process\Process;

/**
 * Class HelperQueueProcess
 * @package ESD\Plugins\Queue
 */
class HelperQueueProcess extends Process
{

    /**
     * @inheritDoc
     * @return mixed
     */
    public function init()
    {

    }

    /**
     * @inheritDoc
     * @return mixed|void
     */
    public function onProcessStart()
    {

    }

    /**
     * @inheritDoc
     * @return mixed|void
     */
    public function onProcessStop()
    {

    }

    /**
     * @inheritDoc
     * @param Message $message
     * @param Process $fromProcess
     * @return mixed|void
     */
    public function onPipeMessage(Message $message, Process $fromProcess)
    {

    }
}