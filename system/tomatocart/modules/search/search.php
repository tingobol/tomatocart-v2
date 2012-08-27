<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends TOC_Module {

  protected $code = 'search';

  public function __construct() {
    parent::__construct();

    $this->title = lang('box_search_heading');
  }

  public function index() {
    $data['title'] = 'Search';
    $data['action'] = 'index.php/tag/';
    $data['btn_search_text'] = 'Search';
    $data['advanced_search_link'] = 'index.php/search/';
    $data['link_advanced_search_text'] = 'Advanced Search';

    return $this->load_view('index.php', $data);
  }
}