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
 * @filesource modules/articles_categories/controllers/articles_categories.php
 */

class Articles_Categories extends TOC_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('articles_categories_model');
    }

    public function show()
    {
        $this->load->view('articles_categories_dialog');
        $this->load->view('articles_categories_grid');
        $this->load->view('articles_categories_general_panel');
        $this->load->view('articles_categories_meta_info_panel');
        $this->load->view('main');
    }

    public function list_articles_categories()
    {
        $totals = $this->articles_categories_model->get_total();
        
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : MAX_DISPLAY_SEARCH_RESULTS;

        $articles_categories = $this->articles_categories_model->get_articles_categories($start, $limit);

        $records = array();
        if (!empty($articles_categories))
        {
            foreach($articles_categories as $articles_category)
            {
                $records[] = array('articles_categories_id' => $articles_category['articles_categories_id'],
                                   'articles_categories_status' => $articles_category['articles_categories_status'],
                                   'articles_categories_name' => $articles_category['articles_categories_name'],
                                   'articles_categories_order' => $articles_category['articles_categories_order']);
            }
        }

        return array(EXT_JSON_READER_TOTAL => $totals,
        EXT_JSON_READER_ROOT => $records);
    }

    public function delete_article_category()
    {
        $error = FALSE;

        $count = $this->articles_categories_model->get_articles($this->input->post('articles_categories_id'));
        if ($count > 0)
        {
            $error = TRUE;
            $feedback = sprintf(lang('delete_warning_category_in_use_articles'), $count);
        }

        if ($error === FALSE)
        {
            if ($this->articles_categories_model->delete($this->input->post('articles_categories_id')) === FALSE)
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
            }
            else
            {
                $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . $feedback);
        }

        return $response;
    }

    public function delete_articles_categories()
    {
        $error = FALSE;
        $feedback = array();

        $articles_categories_ids = json_decode($this->input->post('batch'));

        $check_categories_array = array();
        if (!empty($articles_categories_ids))
        {
            foreach($articles_categories_ids as $id)
            {
                $count = $this->articles_categories_model->get_articles($id);

                if ($count > 0)
                {
                    $data = $this->articles_categories_model->get_data($id);
                    $check_categories_array[] = $data['articles_categories_name'];
                }
            }
        }

        if (!empty($check_categories_array))
        {
            $error = TRUE;
            $feedback[] = lang('batch_delete_error_articles_categories_in_use') . '<br />' . implode(', ', $check_categories_array);
        }

        if ($error === FALSE)
        {
            foreach($articles_categories_ids as $id)
            {
                if ($this->articles_categories_model->delete($id) === FALSE)
                {
                    $error = TRUE;
                    break;
                }
            }

            if ($error === FALSE)
            {
                $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
            }
            else
            {
                $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback));
        }

        return $response;
    }

    public function set_status()
    {
        if ($this->articles_categories_model->set_status($this->input->post('articles_categories_id'), $this->input->post('flag')))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }

        return $response;
    }

    public function save_articles_category()
    {
        $this->load->helper('html_output');

        $categories_name = $this->input->post('articles_categories_name');
        $urls = $this->input->post('articles_categories_url');
        $formatted_urls = array();
        
        //search engine friendly urls
        if ( is_array($urls) && !empty($urls) )
        {
            foreach($urls as $languages_id => $url)
            {
                $url = format_friendly_url($url);

                if (empty($url))
                {
                    $url = format_friendly_url($categories_name[$languages_id]);
                }

                $formatted_urls[$languages_id] = $url;
            }
        }

        $data = array('name' => $this->input->post('articles_categories_name'),
                      'url' => $formatted_urls,
                      'status' => $this->input->post('articles_categories_status'),
                      'articles_order' => $this->input->post('articles_categories_order'),
                      'page_title' => $this->input->post('page_title'),
                      'meta_keywords' => $this->input->post('meta_keywords'),
                      'meta_description' => $this->input->post('meta_description'));

        //save data
        if ($this->articles_categories_model->save($this->input->post('articles_categories_id'), $data))
        {
            $response = array('success' => TRUE, 'feedback' => lang('ms_success_action_performed'));
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => lang('ms_error_action_not_performed'));
        }

        return $response;
    }

    public function load_articles_categories()
    {
        $articles_categories_infos = $this->articles_categories_model->get_info($this->input->post('articles_categories_id'));

        $data = array();
        if (!empty($articles_categories_infos))
        {
            foreach($articles_categories_infos as $info)
            {
                if ($info['language_id'] == lang_id())
                {
                    $data['articles_categories_status'] = $info['articles_categories_status'];
                    $data['articles_categories_order'] = $info['articles_categories_order'];
                }

                $data['articles_categories_name[' . $info['language_id'] . ']'] = $info['articles_categories_name'];
                $data['articles_categories_url[' . $info['language_id'] . ']'] = $info['articles_categories_url'];
                $data['page_title[' . $info['language_id'] . ']'] = $info['articles_categories_page_title'];
                $data['meta_keywords[' . $info['language_id'] . ']'] = $info['articles_categories_meta_keywords'];
                $data['meta_description[' . $info['language_id'] . ']'] = $info['articles_categories_meta_description'];
            }
        }

        $response = array('success' => TRUE, 'data' => $data);

        return $response;
    }
}

/* End of file articles_categories.php */
/* Location: ./system/modules/articles_categories/controllers/articles_categories.php */