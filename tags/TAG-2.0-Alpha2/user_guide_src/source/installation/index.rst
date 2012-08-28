#########################
安装文档
#########################

安装步骤
--------------

#. 将TomatoCart压缩包上传至Web服务器对应的Web服务目录下，或从gitHub上检出最新代码
    `TomatoCart <https://github.com/EllisLab/CodeIgniter>`_.
#. 解压缩压缩包，请确认解压后的文件夹中的index.php文件在Web服务目录的根目录下；
#. 创建数据库，导入数据库表脚本初始化数据库，install/toc2.sql
#. 以文本编辑的方式打开local/config/config.php文件
    - 修改配置项$config['base_url']，改为自己的根域名,格式为http://www.your-domain.com/，注意结尾的‘/’
    - 修改配置项$config['cookie_domain']，修改站点的cookie domain配置，格式为.your-domain.com
#. 以文本编辑的方式打开config/database.php文件，修改数据库连接配置
    - 数据库主机IP地址，$db['default']['hostname'] 
    - 数据库访问用户名，$db['default']['username']
    - 数据库访问密码，$db['default']['password']
    - 数据库名 $db['default']['database']
   

如果要去掉访问地址中的index.php，操作如下：
    - 修改$config['index_page'] = "index.php"，改为$config['index_page'] = "";
    - 修改apache conf下的配置文件 httpd.conf；
        # LoadModule rewrite_module modules/mod_rewrite.so，去掉前面的#。（开启改功能）
    - 找到web目录对应的配置<Directory "/dir"></Directory>，将AllowOverride None改为 AllowOverride All
 
