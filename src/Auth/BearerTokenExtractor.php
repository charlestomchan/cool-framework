<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/4
 * Time: 13:41
 */

namespace Cool\Auth;


use Cool\Contracts\Auth\TokenExtractorInterface;

class BearerTokenExtractor implements TokenExtractorInterface
{

    /**
     * 提取token
     * @return bool|string
     */
    public function extractToken()
    {
        $authorization = app()->request->header('authorization');
        if (strpos($authorization, 'Bearer ') !== 0) {
            return false;
        }
        return substr($authorization, 7);
    }

}