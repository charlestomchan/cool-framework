<?php

namespace Cool\Contracts\Redis;

/**
 * Interface ExecuteListenerInterface
 * @package Cool\Contracts\Redis
 */
interface ExecuteListenerInterface
{

    /**
     * 监听
     * @param array $data
     */
    public function listen($data);

}
