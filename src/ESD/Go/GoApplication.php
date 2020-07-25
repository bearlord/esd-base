<?php
/**
 * ESD framework
 * @author tmtbe <896369042@qq.com>
 */

namespace ESD\Go;

use ESD\Core\PlugIn\AbstractPlugin;
use ESD\Core\Plugins\Logger\GetLogger;
use ESD\Core\Server\Config\ServerConfig;
use ESD\Core\Server\Process\Process;
use ESD\Plugins\Actuator\ActuatorPlugin;
use ESD\Plugins\Aop\AopConfig;
use ESD\Plugins\Aop\AopPlugin;
use ESD\Plugins\Aop\OrderAspect;
use ESD\Plugins\AutoReload\AutoReloadPlugin;
use ESD\Plugins\Blade\BladePlugin;
use ESD\Plugins\Cache\CachePlugin;
use ESD\Plugins\Console\ConsolePlugin;
use ESD\Plugins\CsvReader\CsvReaderPlugin;
use ESD\Plugins\EasyRoute\EasyRoutePlugin;
use ESD\Plugins\EasyRoute\RouteConfig;
use ESD\Plugins\Mysql\MysqlPlugin;
use ESD\Plugins\PHPUnit\PHPUnitPlugin;
use ESD\Plugins\ProcessRPC\ProcessRPCPlugin;
use ESD\Plugins\Redis\RedisPlugin;
use ESD\Plugins\Saber\SaberPlugin;
use ESD\Plugins\Scheduled\ScheduledPlugin;
use ESD\Plugins\Security\SecurityPlugin;
use ESD\Plugins\Session\SessionPlugin;
use ESD\Plugins\Topic\TopicPlugin;
use ESD\Plugins\Uid\UidPlugin;
use ESD\Plugins\Whoops\WhoopsPlugin;
use ESD\Server\Co\Server;
use ESD\Yii\Plugin\YiiPlugin;
use ESD\Yii\Yii;

/**
 * Class GoApplication
 * @package ESD\Go
 */
class GoApplication extends Server
{
    use GetLogger;

    /**
     * @var OrderAspect[]
     */
    protected $aspects = [];

    /**
     * Application constructor.
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function __construct(ServerConfig $serverConfig = null)
    {
        parent::__construct($serverConfig, GoPort::class, GoProcess::class);
        $this->prepareNormalPlugins();
    }

    /**
     * Prepare normal plugins
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ESD\Core\Exception
     * @throws \ESD\Core\Plugins\Config\ConfigException
     * @throws \ReflectionException
     */
    public function prepareNormalPlugins()
    {
        $this->addNormalPlugins();
    }

    /**
     * Run
     *
     * @param $mainClass
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ESD\Core\Exception
     * @throws \ESD\Core\Plugins\Config\ConfigException
     * @throws \ReflectionException
     */
    public function run($mainClass)
    {

        $this->configure();
        $this->getContainer()->get($mainClass);
        $this->start();
    }

    /**
     * Add default plugin
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ESD\Core\Exception
     * @throws \ESD\Core\Plugins\Config\ConfigException
     * @throws \ReflectionException
     */
    protected function addNormalPlugins()
    {
        $this->addPlugin(new ConsolePlugin());
        $this->addPlugin(new YiiPlugin());
        $routeConfig = new RouteConfig();
        $routeConfig->setErrorControllerName(GoController::class);

        $this->addPlugin(new EasyRoutePlugin($routeConfig));
        $this->addPlugin(new ScheduledPlugin());
        $this->addPlugin(new RedisPlugin());
        $this->addPlugin(new AutoreloadPlugin());
        $this->addPlugin(new AopPlugin());
        $this->addPlugin(new SaberPlugin());
        $this->addPlugin(new ActuatorPlugin());
        $this->addPlugin(new WhoopsPlugin());
        $this->addPlugin(new SessionPlugin());
        $this->addPlugin(new CachePlugin());
        $this->addPlugin(new SecurityPlugin());
        $this->addPlugin(new PHPUnitPlugin());
        $this->addPlugin(new ProcessRPCPlugin());
        $this->addPlugin(new UidPlugin());
        $this->addPlugin(new TopicPlugin());
        $this->addPlugin(new BladePlugin());

        //Add aop of Go namespace by default
        $aopConfig = new AopConfig(__DIR__);
        $aopConfig->merge();
    }

    /**
     * @param AbstractPlugin $plugin
     * @throws \ESD\Core\Exception
     */
    public function addPlugin(AbstractPlugin $plugin)
    {
        $this->getPlugManager()->addPlugin($plugin);
    }

    /**
     * All configuration plugins have been initialized
     *
     * @return mixed|void
     * @throws \Exception
     */
    public function configureReady()
    {
        $this->info(Yii::t('esd', 'Configure ready'));
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function onStart()
    {
        $this->info(Yii::t('esd', 'Application start'));
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function onShutdown()
    {
        $this->info(Yii::t('esd', 'Application shutdown'));
    }

    /**
     * @inheritDoc
     * @param Process $process
     * @param int $exit_code
     * @param int $signal
     */
    public function onWorkerError(Process $process, int $exit_code, int $signal)
    {
        return;
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function onManagerStart()
    {
        $this->info(Yii::t('esd', 'Manager process start'));
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function onManagerStop()
    {
        $this->info(Yii::t('esd', 'Manager process stop'));
    }

    /**
     * Plugin initialization is complete
     * @return mixed
     * @throws \Exception
     */
    public function pluginInitialized()
    {
        $this->addAspects();
    }

    /**
     * Add AOP aspect
     * @return mixed
     * @throws \Exception
     */
    protected function addAspects()
    {
        foreach ($this->aspects as $aspect){
            /** @var AopConfig $aopConfig */
            $aopConfig = DIGet(AopConfig::class);
            $aopConfig->addAspect($aspect);
        }
    }

    /**
     * Add AOP aspect
     * @param OrderAspect $orderAspect
     */
    public function addAspect(OrderAspect $orderAspect)
    {
        $this->aspects[] = $orderAspect;
    }

    /**
     * @param $primarySource
     * @throws \ESD\Core\Exception
     * @throws \ESD\Core\Plugins\Config\ConfigException
     * @throws \ReflectionException
     */
    public static function runApp($primarySource)
    {
        $app = new GoApplication();
        $app->run($primarySource);
    }
}