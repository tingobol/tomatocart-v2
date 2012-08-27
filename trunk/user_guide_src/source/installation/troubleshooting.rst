###############
疑难解答
###############

.. contents:: 疑难解答


Linux/Mac环境下Cache目录无法写入
=============================

以文本编辑的形式打开local/config/config.php，查看$config['cache_path']配置cache路径

在shell命令行模式，设置$config['log_path']日志路径对应的目录为读写权限，如执行命令如下::

    chmod 777 local/logs



Linux/Mac环境下log无法写入
=============================

以文本编辑的形式打开local/config/config.php，查看$config['log_path']配置log日志路径，

查看$config['log_threshold']配置日志输出级别，当$config['log_threshold']=0时为不输入任何日志。

在shell命令行模式，设置$config['log_path']日志路径对应的目录为读写权限，如执行命令如下::

    chmod 777 local/logs

