<?php

namespace Cool\Udp\Handler;

use Cool\Udp\UdpSender;

/**
 * Interface UdpHandlerInterface
 * @package Cool\Udp\Handler
 */
interface UdpHandlerInterface
{

    /**
     * 监听数据
     * @param UdpSender $udp
     * @param string $data
     * @param array $clientInfo
     */
    public function packet(UdpSender $udp, string $data, array $clientInfo);

}
