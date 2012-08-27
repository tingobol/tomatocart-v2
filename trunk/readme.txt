 -- TomatoCart v2.0

   Website: http://www.tomatocart.com
   License: GNU General Public License v3.0 
            http://www.gnu.org/licenses/gpl.html
   Forums:  http://www.tomatocart.com/community/75-discussion-for-tomatocart-v20.html
   Version: v2.0 Alpha1
   
 -- Description
    
    The primary goal of TomatoCart v2.0 is to create a simple open source ecommerce framework with strong extensibility 
    and flexibility; it enables developers and designers to create full-featured ecommerce solution. Developers can 
    override any components without touch any core code. This keeps the framework very clean and is very easy for upgrade.
    
    TomatoCart is free to use, redistribute and/or modify for any purpose whether personal or commercial 
    however the copyright in the source code and in the administration must be retained.
    
    Features included in this release:
      1. Core Complete Rewrite with CodeIgniter
      2. New Template Enginee
         -- Customized Settings for Templates
         -- Template Modules Can Be Instaniated More Than Once and Each Instance Has Its Own Parameters
         -- Template Module Layout Management through Drag & Drop 
         -- Response Web Design for Store Front
            Added Mobile View Support With jQuery Mobile
            Added Mobile View Support For Template Module
      3. Upgrade Extjs 2.0 to Extjs 4.0
      4. Developed as a flexible and solid ecommerce framework utilizing mvc pattern.
      5. Add and override core features without touching the core source code.      
      6. Integrate the sencha touch into the admin panel to support the pad and mobile device.      
      7. Improve the template engine as the engine in the wordpress.      
      8. Perform modifications and override base views in a separate template for the specific device.      
      9. Migrate the the sytem config file into the local directory so as to let the developers setup the system without touching the core config file.      
      10. Share the system config file between Backend and Frontend      
      11. Add the payment, shipping, order totals and service module into the admin panel.
      
    
 -- Installation
    1. Upload file to web server
    2. Create an database and import the install/install.sql
    3. Configure the local/config/config.php file
       -- set the $config['base_url'] to the url of your root
       -- set the $config['cookie_domain'] to .your-domain.com for site-wide cookies
    4. Configure the local/config/database.php
       -- set the $db['default']['hostname'] to your database host
       -- set the $db['default']['username'] to your database username
       -- set the $db['default']['password'] to your database password
       -- set the $db['default']['database'] to your database name
    5. Configure the admin/local/config/config.php file
       -- set the $config['base_url'] to the url of your admin root
       -- set the $config['cookie_domain'] to .your-domain.com for site-wide cookies