<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/4
 * Time: 13:42
 */

namespace Cool\Auth;


use Cool\Foundation\Component\AbstractComponent;

class Authorization extends AbstractComponent
{
    /**
     * token提取器
     * @var \Cool\Contracts\Auth\TokenExtractorInterface
     */
    public $tokenExtractor;

    /**
     * jwt
     * @var \Cool\Auth\JWT
     */
    public $jwt;

    /**
     * 获取有效荷载
     * @return object
     */
    public function getPayload()
    {
        $token = $this->tokenExtractor->extractToken();
        if (!$token) {
            throw new \InvalidArgumentException('Failed to extract token.');
        }
        return $this->jwt->parser($token);
    }

    /**
     * 创建token
     * @param $payload
     * @return string
     */
    public function createToken($payload)
    {
        return $this->jwt->create($payload);
    }

}