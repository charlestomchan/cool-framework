<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/5/2
 * Time: 4:33
 */

namespace Cool\Udp\Server;

/**
 * Class SwooleEvent
 * @package Cool\Udp\Server
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
     * Packet
     */
    const PACKET = 'packet';
}