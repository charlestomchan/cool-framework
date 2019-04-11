<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/4
 * Time: 13:40
 */

namespace Cool\Auth;


use Cool\Foundation\Component\AbstractComponent;

class JWT extends AbstractComponent
{

    /**
     * 签名算法常量
     */
    const ALGORITHM_HS256 = 'HS256';
    const ALGORITHM_HS384 = 'HS384';
    const ALGORITHM_HS512 = 'HS512';
    const ALGORITHM_RS256 = 'RS256';
    const ALGORITHM_RS384 = 'RS384';
    const ALGORITHM_RS512 = 'RS512';

    /**
     * 钥匙
     * @var string
     */
    public $key = '';

    /**
     * 私钥
     * @var string
     */
    public $privateKey = '';

    /**
     * 公钥
     * @var string
     */
    public $publicKey = '';

    /**
     * 签名算法
     * @var string
     */
    public $algorithm = self::ALGORITHM_HS256;

    /**
     * 获取有效载荷
     * @param $token
     * @return object
     */
    public function parser($token)
    {
        switch ($this->algorithm) {
            case self::ALGORITHM_HS256:
            case self::ALGORITHM_HS384:
            case self::ALGORITHM_HS512:
                return \Firebase\JWT\JWT::decode($token, $this->key, [$this->algorithm]);
                break;
            case self::ALGORITHM_RS256:
            case self::ALGORITHM_RS384:
            case self::ALGORITHM_RS512:
                return \Firebase\JWT\JWT::decode($token, $this->publicKey, [$this->algorithm]);
                break;
            default:
                throw new \InvalidArgumentException('Invalid signature algorithm.');
        }
    }

    /**
     * 创建Token
     * @param $payload
     * @return string
     */
    public function create($payload)
    {
        switch ($this->algorithm) {
            case self::ALGORITHM_HS256:
            case self::ALGORITHM_HS384:
            case self::ALGORITHM_HS512:
                return \Firebase\JWT\JWT::encode($payload, $this->key, $this->algorithm);
                break;
            case self::ALGORITHM_RS256:
            case self::ALGORITHM_RS384:
            case self::ALGORITHM_RS512:
                return \Firebase\JWT\JWT::encode($payload, $this->privateKey, $this->algorithm);
                break;
            default:
                throw new \InvalidArgumentException('Invalid signature algorithm.');
        }
    }

}