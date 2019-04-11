<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 16:36
 */
namespace Cool\WebSocket\Frame;

use Cool\Contracts\Foundation\ObjectInterface;
use Cool\Foundation\Bean\ObjectTrait;
use \Swoole\WebSocket\Frame;


class BinaryFrame extends Frame implements ObjectInterface
{
    
    use ObjectTrait;

    /**
     * @var int
     */
    public $opcode = WEBSOCKET_OPCODE_BINARY;

    /**
     * @var bool
     */
    public $finish = true;

    /**
     * @var string
     */
    public $data = '';
}