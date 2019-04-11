<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 16:33
 */
namespace Cool\WebSocket\Frame;

use Cool\Contracts\Foundation\ObjectInterface;
use Cool\Foundation\Bean\ObjectTrait;
use \Swoole\WebSocket\Frame;

/**
 * Class TextFrame
 * @package Cool\WebSocket\Frame\
 */
class TextFrame extends Frame implements ObjectInterface
{

    use ObjectTrait;
    
    /**
     * @var int
     */
    public $opcode = WEBSOCKET_OPCODE_TEXT;

    /**
     * @var bool
     */
    public $finish = true;

    /**
     * @var string
     */
    public $data = '';
}