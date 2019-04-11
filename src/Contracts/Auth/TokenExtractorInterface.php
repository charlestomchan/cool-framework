<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/4
 * Time: 13:40
 */

namespace Cool\Contracts\Auth;

/**
 * Interface TokenExtractorInterface
 * @package Cool\Contracts\Auth
 */
interface TokenExtractorInterface
{

    /**
     * 提取token
     * @return bool|string
     */
    public function extractToken();
}