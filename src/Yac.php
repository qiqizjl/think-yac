<?php
/**
 *
 *
 * @author 耐小心<i@naixiaoxin.com>
 * @copyright 2017-2017 耐小心
 */


namespace naixiaoxin\yac;

use think\cache\Driver;

class Yac extends Driver
{


    /**
     * 架构函数
     * @param array $options 缓存参数
     * @access public
     */
    public function __construct($options = [])
    {
        if (!extension_loaded('yac'))
        {
            throw new \BadFunctionCallException('not support: yac');
        }
        $this->handler = new \Yac();
        $this->options = $options;
    }

    /**
     * 判断缓存
     * @access public
     * @param string $name 缓存变量名
     * @return bool
     */
    public function has($name)
    {
        return $this->handler->get($this->getCacheKey($name)) ? true : false;
    }

    /**
     * MD5当前的KEY
     * @access public
     * @param string $name 缓存变量名
     * @return string
     */
    public function getCacheKey($name)
    {
          return $this->options['prefix'] . md5($name);
    }
    

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed $default 默认值
     * @return mixed
     */
    public function get($name, $default = false)
    {
        $value = $this->handler->get($this->getCacheKey($name));
        if (is_null($value))
        {
            return $default;
        }

        return $data;
    }

    /**
     * 写入缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed $value 存储数据
     * @param integer $expire 有效时间（秒）
     * @return boolean
     */
    public function set($name, $value, $expire = null)
    {
        if (is_null($expire))
        {
            $expire = $this->options['expire'];
        }
        if ($this->tag && !$this->has($name))
        {
            $first = true;
        }
        $key    = $this->getCacheKey($name);
        $result = $this->handler->set($key, $value);
        isset($first) && $this->setTagItem($key);

        return $result;
    }


    /**
     * 自增缓存（针对数值缓存）
     * @access public
     * @param string $name 缓存变量名
     * @param int $step 步长
     * @return false|int
     */
    public function inc($name, $step = 1)
    {
        $key = $this->getCacheKey($name);
        if ($this->has($key))
        {
            $value = $this->get($key) + $step;
        }
        else
        {
            $value = $step;
        }

        return $this->set($name, $value, 0) ? $value : false;
    }


    /**
     * 自减缓存（针对数值缓存）
     * @access public
     * @param string $name 缓存变量名
     * @param int $step 步长
     * @return false|int
     */
    public function dec($name, $step = 1)
    {
        $key = $this->getCacheKey($name);
        if ($this->has($key))
        {
            $value = $this->get($key) - $step;
        }
        else
        {
            $value = $step;
        }

        return $this->set($name, $value, 0) ? $value : false;
    }


    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function rm($name)
    {
        return $this->handler->delete($this->getCacheKey($name));
    }


    /**
     * 清除缓存
     * @access public
     * @param string $tag 标签名
     * @return boolean
     */
    public function clear($tag = null)
    {
        if ($tag)
        {
            // 指定标签清除
            $keys = $this->getTagItem($tag);
            foreach ($keys as $key)
            {
                $this->handler->delete($key);
            }
            $this->rm('tag_' . md5($tag));

            return true;
        }

        return $this->handler->flush();
    }


}
