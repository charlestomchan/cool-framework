<?php

namespace Cool\Database;

/**
 * Interface ExecuteListenerInterface
 * @package Cool\Database
 */
interface ExecuteListenerInterface
{

    /**
     * 监听
     * @param array $data
     */
    public function listen($data);

}
