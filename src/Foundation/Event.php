<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/9
 * Time: 12:51
 */

namespace Cool\Foundation;
/**
 * Class Event
 * @package Cool\Foundation
 *  @method static add(mixed $sock, mixed $read_callback, mixed $write_callback = null, int $flags = null)
 * @method static set($fd, mixed $read_callback, mixed $write_callback, int $flags)
 * @method static isset(mixed $fd, int $events = SWOOLE_EVENT_READ | SWOOLE_EVENT_WRITE)
 * @method static write($fp, $data)
 * @method static exit()
 * @method static defer(mixed $callback_function)
 * @method static cycle(callable $callback, bool $before = false)
 * @method static wait()
 * @method static dispatch()
 *
 */
class Event extends \Swoole\Event
{
}