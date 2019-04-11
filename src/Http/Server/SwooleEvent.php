<?php

namespace Cool\Http\Server;

/**
 * Class SwooleEvent
 * @package Cool\Http\Server
 * @author charles <charlestomchan@gmail.com>
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
     * Request
     */
    const REQUEST = 'request';

}
