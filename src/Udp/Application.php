<?php

namespace Cool\Udp;

use Cool\Foundation\Traits\ComponentInitializeTrait;
use Cool\Foundation\Application  as BaseApplication;

/**
 * Class Application
 * @package Cool\Udp
 */
class Application extends BaseApplication
{

    use ComponentInitializeTrait;

    /**
     * 执行监听数据
     * @param $udp
     * @param $data
     * @param $clientInfo
     */
    public function runPacket($udp, $data, $clientInfo)
    {
        $handler = \Cool::$app->registry->getHandler();
        $handler->packet($udp, $data, $clientInfo);
    }

}
