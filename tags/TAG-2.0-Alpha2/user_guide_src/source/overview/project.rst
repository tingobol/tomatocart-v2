#####################
TomatoCart源码工程结构
#####################

TomatoCart系统整体采用CodeIgniter的重载，扩展结构，并在CI基础上扩充了视图层的加载机制，
将原先CI的view层扩展为Template处理方式，便于页面模板的扩充添加；

TomatoCart工程目录如下：

- *admin*，基于CI的框架，Extjs表现层的后台管理系统；
    * *system* ，基于CI的后台管理工程目录
    - *templates/default* ，替换CI的视图层
        + *mobile* ，为手机版试图模板
        + *web* ，为PC版视图模板
            + *css* ，样式文件目录
            + *images* ，图片目录
            + *javascript* ，extjs脚本目录
            + *views* ，php的视图层文件
        + *index.php* ，管理页面视图主页面
- *images* ， web站点图片
- *intall* ， 数据库脚本
- *local* ， 自定义扩展开发目录，所有基于TomatoCart的开发均放在此目录下，相对于CodeIgniter的Application目录，子目录结构同CodeIgniter目录定义相同。
- *system* ， CodeIgniter框架和TomatoCart系统源码目录
- *templates* ， 视图模板目录，放置默认及自定义模板
    * *base*，不同终端设备上，每个Modules的基本视图模块
    * *default*，当前安装的模板视图
- *user_guide_src* ， 文档目录
- *index.php* ， CI入口index.php文件。



