=================
tomatocart的配置
=================

./tomatocart/core/TOC_Config.php,
继承CI_Config，扩展引入了local/config下config的加载功能；

tomatocart为了实现系统和扩展开发的分离，加入了扩展开发的local目录，
tomatocart/config下的所有定义类，均通过require的方式引入local/config目录下的对应配置类的定义；