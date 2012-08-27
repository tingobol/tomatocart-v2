<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl.html
 * @link    http://tomatocart.com
 * @since   Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Search Model
 *
 * @package   TomatoCart
 * @subpackage  tomatocart
 * @category  template-search-model
 * @author    TomatoCart Dev Team
 * @link    http://tomatocart.com/wiki/
 */

class Search_Model extends CI_Model
{
    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_result($params = array())
    {
        $sql = 'select SQL_CALC_FOUND_ROWS distinct p.*, pd.*, m.*, i.image, if(s.status, s.specials_new_products_price, null) as specials_new_products_price, if(s.status, s.specials_new_products_price, p.products_price) as final_price';
        
        $has_price_set = (isset($params['price_from']) || isset($params['price_to']));
        $price_with_tax = isset($params['with_tax']) && ($params['with_tax'] == '1');
        
        if ($has_price_set && $price_with_tax)
        {
            $sql .= ', sum(tr.tax_rate) as tax_rate';
        }
        
        $sql .= ' from ' . $this->db->protect_identifiers('products', TRUE) . ' p left join ' . $this->db->protect_identifiers('manufacturers', TRUE) . ' m using(manufacturers_id) left join ' . $this->db->protect_identifiers('specials', TRUE) . ' s on (p.products_id = s.products_id) left join ' . $this->db->protect_identifiers('products_images', TRUE) . ' i on (p.products_id = i.products_id and i.default_flag = 1)';
        
        if ($has_price_set && $price_with_tax)
        {
            $sql .= ' left join ' . $this->db->protect_identifiers('tax_rates', TRUE) . ' tr on p.products_tax_class_id = tr.tax_class_id left join ' . $this->db->protect_identifiers('zones_to_geo_zones', TRUE) . ' gz on tr.tax_zone_id = gz.geo_zone_id and (gz.zone_country_id is null or gz.zone_country_id = 0 or gz.zone_country_id = ' . $params['country_id'] . ') and (gz.zone_id is null or gz.zone_id = 0 or gz.zone_id = ' . $params['zone_id'] . ')';
        }
        
        $sql .= ', ' . $this->db->protect_identifiers('products_description', TRUE) . ' pd, ' . $this->db->protect_identifiers('categories', TRUE) . ' c, ' . $this->db->protect_identifiers('products_to_categories', TRUE) . 'p2c';
        $sql .= ' where p.products_status = 1 and p.products_id = pd.products_id and pd.language_id = ' . $params['language_id'] . ' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id';
        
        //has the category
        if (isset($params['subcategories']) && !empty($params['subcategories']))
        {
            $sql .= ' and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and p2c.categories_id in (' . implode(',', $params['subcategories']) . ')';
        }
        else if (isset($params['category']) && !empty($params['category']))
        {
            $sql .= ' and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and pd.language_id = ' . $params['language_id'] . ' and p2c.categories_id = ' . $params['category'];
        }
        
        //has manufacturer
        if (isset($params['manufacturer']) && !empty($params['manufacturer']))
        {
            $sql .= ' and m.manufacturers_id = ' . $params['manufacturer'];
        }
        
        //has keywords
        if (isset($params['keywords']) && !empty($params['keywords']))
        {
            $sql .= ' and (pd.products_name like "%' . $this->db->escape_like_str($params['keywords']) . '%" or pd.products_description like "%' . $this->db->escape_like_str($params['keywords']) . '%")';
        }
        
        //has price from
        if (isset($params['price_from']) && !empty($params['price_from']))
        {
            $params['price_from'] = $params['price_from'] / $params['currency'];
        }
        
        //has price to
        if (isset($params['price_to']) && !empty($params['price_to']))
        {
            $params['price_to'] = $params['price_to'] / $params['currency'];
        }
        
        //display price with tax
        if ($price_with_tax)
        {
            if ($params['price_from'] > 0)
            {
                $sql .= ' and (if(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) >= ' . $params['price_from'] . ')';
            }
            
            if ($params['price_to'] > 0)
            {
                $sql .= ' and (if(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) <= ' . $params['price_to'] . ')';
            }
        }
        else
        {
            if ($params['price_from'] > 0)
            {
                $sql .= ' and (if(s.status, s.specials_new_products_price, p.products_price) >= ' . $params['price_from'] . ')';
            }
            
            if ($params['price_to'] > 0)
            {
                $sql .= ' and (if(s.status, s.specials_new_products_price, p.products_price) <= ' . $params['price_to'] . ')';
            }
        }
        
        if ($has_price_set && $price_with_tax)
        {
            $sql .= ' group by p.products_id, tr.tax_priority';
        }
        
        $result = $this->db->query($sql);
        
        return $result->result_array();
    }
}

/* End of file search_model.php */
/* Location: ./system/tomatocart/models/search_model.php */