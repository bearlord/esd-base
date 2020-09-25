<?php
/**
 * ESD framework
 * @author tmtbe <896369042@qq.com>
 */

namespace ESD\Plugins\Amqp;

use ESD\Core\Context\Context;
use ESD\Core\Plugin\AbstractPlugin;
use ESD\Core\Plugins\Config\ConfigException;
use ESD\Core\Plugins\Logger\GetLogger;
use ESD\Core\Server\Server;
use ESD\Yii\Yii;

/**
 * Class AmqpPlugin
 * @package ESD\Plugins\Amqp
 */
class AmqpPlugin extends AbstractPlugin
{
    use GetLogger;

    use GetAmqp;

    /**
     * @var AmqpConfig
     */
    protected $amqpConfig;

    /**
     * AmqpPlugin constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->configs = new Configs();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return "AmqpPlugin";
    }

    /**
     * @inheritDoc
     * @param Context $context
     * @throws ConfigException
     * @throws \Exception
     */
    public function beforeServerStart(Context $context)
    {
        $configs = Server::$instance->getConfigContext()->get('amqp');

        foreach ($configs as $key => $config) {
            $configObject = new Config($key);
            $configObject->setHosts($config['hosts']);
            $this->configs->addConfig($configObject->buildFromConfig($config));
        }
    }

    /**
     * @inheritDoc
     * @param Context $context
     * @throws \Exception
     */
    public function beforeProcessStart(Context $context)
    {
        $amqpPool = new AmqpPool();

        $configs = $this->configs->getConfigs();
        if (empty($configs)) {
            $this->warn(Yii::t('esd', 'Amqp configuration not found'));
            return false;
        }

        foreach ($configs as $key => $config) {
            $connection = new Connection($config);
            $amqpPool->addConnection($connection);
            $this->debug(Yii::t('esd', '{driverName} connection pool named {name} created', [
                'driverName' => 'Amqp',
                'name' => $config->getName()
            ]));
        }

        $context->add("amqpPool", $amqpPool);
        $this->setToDIContainer(AmqpPool::class, $amqpPool);
        $this->ready();
    }
}