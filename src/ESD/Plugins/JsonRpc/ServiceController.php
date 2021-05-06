<?php
/**
 * ESD framework
 * @author bearload <565364226@qq.com>
 */

namespace ESD\Plugins\JsonRpc;

use ESD\Go\GoController;
use ESD\Plugins\Pack\ClientData;
use ESD\Yii\Base\Action;
use ESD\Yii\Yii;

/**
 * Class JsonRpcController
 * @package ESD\Plugins\JsonRpc
 */
class ServiceController extends GoController
{
    /**
     * @inheritDoc
     * @param string|null $controllerName
     * @param string|null $methodName
     * @param array|null $params
     * @return mixed
     * @throws \Throwable
     */
    public function handle(?string $controllerName, ?string $methodName, ?array $params)
    {
        try {
            /** @var ClientData $clientData */
            $clientData = getContextValueByClassName(ClientData::class);
            $_clientData = $clientData->getData();

            //rpc call with an empty String or Array:
            if (empty($_clientData)) {
                return [
                    "jsonrpc" => "2.0",
                    "error" => [
                        "code" => -32600,
                        "message" => "Invalid Request"
                    ],
                    "id" => null
                ];
            }

            $json = json_decode($_clientData, true);
            //rpc call with invalid JSON:
            if (empty($json)) {
                return [
                    "jsonrpc" => "2.0",
                    "error" => [
                        "code" => -32700,
                        "message" => "Parse error"
                    ],
                    "id" => null
                ];
            }

            if (empty($json['method']) || empty($json['params']) || empty($json['id'])) {
                return [
                    "jsonrpc" => "2.0",
                    "error" => [
                        "code" => -32600,
                        "message" => "Invalid Request"
                    ],
                    "id" => null
                ];
            }

            //Method
            $callMethodName = $json['method'];
            //params
            $realParams = $json['params'];
            //id
            $id = $json['id'];

            $action = $this->createAction($callMethodName);

            //rpc call of non-existent method
            if (empty($action)) {
                return [
                    "jsonrpc" => "2.0",
                    "error" => [
                        "code" => -32601,
                        "message" => "Method not found"
                    ],
                    "id" => $id
                ];
            }

            $result = null;
            if ($this->beforeAction($action)) {
                // run the action
                $result = $action->runWithParams($realParams);
                $result = $this->afterAction($action, $result);
            }
            var_dump($result);
            return $result;
        } catch (\Throwable $exception) {
            setContextValue("lastException", $exception);
            return $this->onExceptionHandle($exception);
        }
    }

    /**
     * Creates an action based on the given action ID.
     * The method first checks if the action ID has been declared in [[actions()]]. If so,
     * it will use the configuration declared there to create the action object.
     * If not, it will look for a controller method whose name is in the format of `actionXyz`
     * where `xyz` is the action ID. If found, an [[InlineAction]] representing that
     * method will be created and returned.
     * @param string $id the action ID.
     * @return Action|null the newly created action instance. Null if the ID doesn't resolve into any action.
     */
    public function createAction($id)
    {
        $actionMap = $this->actions();

        if (isset($actionMap[$id])) {
            return Yii::createObject($actionMap[$id], [$id, $this]);
        }

        if (preg_match('/^(?:[a-zA-Z0-9_]+-)*[a-zA-Z0-9_]+$/', $id)) {
            $methodName = $id;
            if (method_exists($this, $methodName)) {
                $method = new \ReflectionMethod($this, $methodName);
                if (!$method->isPrivate() && $method->getName() === $methodName) {
                    return new InlineAction($id, $this, $methodName);
                }
            }
        }
        return null;
    }
}