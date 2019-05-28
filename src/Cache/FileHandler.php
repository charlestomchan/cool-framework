<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/20
 * Time: 15:42
 */

namespace Cool\Cache;


use Cool\Contracts\Cache\HandlerInterface;
use Cool\Foundation\Component\AbstractComponent;
use Cool\Support\FileSystem;

class FileHandler extends AbstractComponent implements HandlerInterface
{
    /**
     * 缓存目录
     * @var string
     */
    public $dir = 'cache';

    /**
     * 分区
     * @var int
     */
    public $partitions = 64;

    /**
     * 获取缓存
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $file = $this->getCacheFile($key);
        $data = @file_get_contents($file);
        if (empty($data)) {
            return $default;
        }
        $data = unserialize($data);
        if (!is_array($data) || count($data) !== 2) {
            return $default;
        }
        list($value, $expire) = $data;
        if ($expire > 0 && $expire < time()) {
            $this->delete($key);
            return $default;
        }
        return $value;
    }

    /**
     * 设置缓存
     * @param $key
     * @param $value
     * @param null $ttl
     * @return bool
     */
    public function set($key, $value, $ttl = null)
    {
        $file   = $this->getCacheFile($key);
        $expire = is_null($ttl) ? 0 : time() + $ttl;
        $data   = [
            $value,
            $expire,
        ];
        // 创建目录
        $dir = dirname($file);
        is_dir($dir) or mkdir($dir, 0777, true);
        // 写入
        $bytes = file_put_contents($file, serialize($data), LOCK_EX);
        return $bytes ? true : false;
    }

    /**
     * 删除缓存
     * @param $key
     * @return bool
     */
    public function delete($key)
    {
        $file = $this->getCacheFile($key);
        return @unlink($file);
    }

    /**
     * 清除缓存
     * @return bool
     */
    public function clear()
    {
        $dir = $this->getCacheDir();
        return FileSystem::deleteFolder($dir);
    }

    /**
     * 判断缓存是否存在
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        $value = $this->get($key);
        return is_null($value) ? false : true;
    }

    /**
     * 获取缓存目录
     * @return string
     */
    protected function getCacheDir()
    {
        $cacheDir = $this->dir;
        if (!FileSystem::isAbsolute($cacheDir)) {
            $cacheDir = \Cool::$app->getRuntimePath() . DIRECTORY_SEPARATOR . $this->dir;
        }
        return $cacheDir;
    }

    /**
     * 获取缓存文件
     * @param $key
     * @return string
     */
    protected function getCacheFile($key)
    {
        $dir    = $this->getCacheDir();
        $subDir = crc32($key) % $this->partitions;
        $name   = md5($key);
        return $dir . DIRECTORY_SEPARATOR . $subDir . DIRECTORY_SEPARATOR . $name;
    }

}