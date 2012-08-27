=======================
TomatoCart 核心扩展
=======================

TomatoCart已应用扩展的方式扩充了CodeIgniter的 **Config** , **Controller** , **Lang** , **Loader** 四个基础核心类。
核心类的扩展使TomatoCart可以像CodeIgniter开发那样，将开发人员自己开发的自定义业务代码同TomatoCart系统代码完全分离。
开发人员只需要在local目录下面放置自己的业务逻辑代码而无需修改system/tomatocart目录下的系统核心代码。
在国际化上，TomatoCart放弃了CodeIgniter的国际化实现机制，
将国际化数据放置在数据库中，使管理人员和开发人员更加便利地管理、扩充多语言模板和新增模块；

.. contents:: TomatoCart核心扩展

TOC_Controller
================

TOC_Controller继承了CI_Controller，在Controller初始化的时候，完成了下列功能调用：

- 从数据库中初始化语言包，根据当前浏览器环境或请求参数，从数据库中加载对应的语言包；
- 设置本地化语言环境和http请求头；
- 从数据库中加载general？
- 从数据库中加载、初始化页面中各模块（modules）的信息；
- 加载分类树类库和模板类库；
- 初始化视图模板基本信息；

初始化方法代码如下::

    public function __construct()
    {
        parent::__construct();
        
        $this->lang->initialize();
        $this->output->set_header('Content-Type: text/html; charset=' . $this->lang->get_character_set());
        setlocale(LC_TIME, explode(',', $this->lang->get_locale()));

        $this->lang->db_load('general');
        $this->lang->db_load('modules-boxes');
        $this->lang->db_load('modules-content');

        $this->load->library('category_tree');

        $this->settings = $this->settings_model->get_settings();

        if ($this->uri->rsegment(1) !== FALSE)
        {
            $this->lang->db_load($this->uri->segment(1));
        }

        /**initialize module specific data**/
        $module = trim($this->router->directory, '/');
        $class = $this->router->class;

        $this->lang->db_load($module);

        $this->load->library('template');
        $this->load->model('modules_model');

        $medium = $this->agent->get_medium();

        $this->modules = $this->modules_model->get_modules($module, $class, $medium);
        $this->template->add_modules($this->modules);
        
        $this->template->set_breadcrumb(lang('home'), site_url());
        
        $this->template->set('is_logged_on', $this->customer->is_logged_on());
        $this->template->set('items_num', $this->shopping_cart->number_of_items());

        $this->template->set_layout('index.php');
    }



TOC_Loader
================

TOC_Loader继承至CI_Loader，在初始化加载php类路径上加入了local路径，加载的目录包括libraries，helper，models。
Loader的加载路径的顺序为，local/，system/tomatocart/，system/codeigniter/

TOC_Config
================

TOC_Config继承至CI_Config，在初始化加载php类路径上加入了local路径下的/config/config.php

TOC_Lang
================

TOC_Lang继承至CI_Lang，从models/languages_model.php中初始化、加载国际化语言包数据。
TOC_Lang的初始化操作在TOC_Controller的构造方法中完成。
详细功能参见 :doc:`Tomatocart的国际化 <i18n>`


