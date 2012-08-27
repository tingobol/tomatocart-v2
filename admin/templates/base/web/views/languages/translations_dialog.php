<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource system/modules/languages/views/translations_dialog.php
 */
?>

Ext.define('Toc.languages.TranslationsEditDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?= lang('heading_translations_title'); ?>';
    config.id = 'translations-win';
    config.layout = 'border';
    config.border = false;
    config.height = 400;
    config.width = 850;
    config.modal = true;
    config.iconCls = 'icon-languages-win';
    
    config.grdTranslations = Ext.create('Toc.languages.TranslationsEditGrid', {languagesId: config.languagesId});
    config.pnlModulesTree = Ext.create('Toc.languages.ModulesTreePanel', {languagesId: config.languagesId});
    
    config.pnlModulesTree.on('selectchange', this.onTreeSelect, this);

    config.items = [config.grdTranslations, config.pnlModulesTree];
    
    this.callParent([config]);
  },
  
  onTreeSelect: function(group) {
    this.grdTranslations.refreshGrid(group);
  }
});

/* End of file translations_dialog.php */
/* Location: system/modules/languages/views/translations_dialog.php */
