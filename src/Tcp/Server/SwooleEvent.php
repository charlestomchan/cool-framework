<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 19:07
 */

namespace Cool\Tcp\Server;

/**
 * Class SwooleEvent
 * @package Cool\Tcp\Server
 */
class SwooleEvent
{
    /**
     * Start
     */
    const START = 'start';

    /**
     * ManagerStart
     */
    const MANAGER_START = 'managerStart';

    /**
     * WorkerStart
     */
    const WORKER_START = 'workerStart';

    /**
     * Connect
     */
    const CONNECT = 'connect';

    /**
     * Receive
     */
    const RECEIVE = 'receive';

    /**
     * Close
     */
    const CLOSE = 'close';

}