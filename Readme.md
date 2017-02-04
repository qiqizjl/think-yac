ThinkPHP 5.0 Yac驱动
=========================

首先安装官方的yac扩展：

http://pecl.php.net/package/yac

然后，配置应用的配置文件`config.php`的`cache['type']`参数为：

~~~
'type'  =>  '\naixiaoxin\yac\Yac',
~~~

即可正常使用Yac，例如：
~~~
Cache::get('test');
Cache::get('test','test');
~~~
