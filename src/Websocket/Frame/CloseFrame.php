<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 16:38
 */
namespace Cool\WebSocket\Frame;

use Cool\Contracts\Foundation\ObjectInterface;
use Cool\Foundation\Bean\ObjectTrait;

/**
 * Class CloseFrame
 * @package Cool\WebSocket\Frame
 */
class CloseFrame extends \Swoole\WebSocket\CloseFrame implements ObjectInterface
{

    use ObjectTrait;

    /**
     * @var int
     */
    public $opcode = 8;

    /**
     * @var bool
     */
    public $finish = true;

    /**
     * @var string
     */
    public $data = '';

    /**
     * @var int
     */
    public $code = 1000;

    /**
     * @var string
     */
    public $reason = '';

}