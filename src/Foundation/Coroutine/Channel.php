<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/19
 * Time: 19:18
 */

namespace Cool\Foundation\Coroutine;

/**
 * Class Channel
 * @package Cool\Foundation\Coroutine
 *
 * @method bool push($data)
 * @method mixed pop()
 * @method bool isEmpty()
 * @method bool isFull()
 * @method array stats()
 * @method int length()
 * @method close()
 */
class Channel extends \Swoole\Coroutine\Channel
{

}