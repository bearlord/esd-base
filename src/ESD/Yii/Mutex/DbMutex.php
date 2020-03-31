<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace ESD\Yii\Mutex;

use ESD\Yii\Base\InvalidConfigException;
use ESD\Yii\Db\Connection;
use ESD\Yii\Di\Instance;

/**
 * DbMutex is the base class for classes, which relies on database while implementing mutex "lock" mechanism.
 *
 * @see Mutex
 *
 * @author resurtm <resurtm@gmail.com>
 * @since 2.0
 */
abstract class DbMutex extends Mutex
{
    /**
     * @var Connection|array|string the DB connection object or the application component ID of the DB connection.
     * After the Mutex object is created, if you want to change this property, you should only assign
     * it with a DB connection object.
     * Starting from version 2.0.2, this can also be a configuration array for creating the object.
     */
    public $db = 'db';


    /**
     * Initializes generic database table based mutex implementation.
     * @throws InvalidConfigException if [[db]] is invalid.
     */
    public function init()
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::className());
    }
}
