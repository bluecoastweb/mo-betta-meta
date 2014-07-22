<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package    ExpressionEngine
 * @author    ExpressionEngine Dev Team
 * @copyright  Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license    http://expressionengine.com/user_guide/license.html
 * @link    http://expressionengine.com
 * @since    Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Mo Betta Meta Module Control Panel File
 *
 * @package    ExpressionEngine
 * @subpackage Addons
 * @category   Module
 * @author     Steve Pedersen
 * @link       http://www.bluecoastweb.com
 */
class Mo_betta_meta_mcp {

    private $ee;
    private $base_path;
    private $base_url;
    private $site_id;

    public $return_data;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ee = function_exists('ee') ? ee() : get_instance();
        $this->base_path = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=mo_betta_meta';
        $this->base_url = BASE.AMP.$this->base_path;
        $this->ee->cp->set_right_nav(array(
            'mcp_nav_home'   => $this->base_url,
            'mcp_nav_usage'  => $this->base_url.AMP.'method=usage',
            'mcp_nav_tags'   => $this->base_url.AMP.'method=tags',
            'mcp_nav_config' => $this->base_url.AMP.'method=config'
        ));
        $this->site_id = $this->ee->config->item('site_id');
    }

    /**
     * CP index page
     *
     * @return   void
     */
    public function index() {
        $this->ee->cp->set_variable('cp_page_title', lang('mcp_title_home'));
        $vars = array();
        $vars['usage_url'] = $this->base_url.AMP.'method=usage';
        $vars['tags_url'] = $this->base_url.AMP.'method=tags';
        $vars['config_url'] = $this->base_url.AMP.'method=config';
        return $this->ee->load->view('mcp_index', $vars, true);
    }

    /**
     * CP tags page
     *
     * @return   void
     */
    public function tags() {
        $this->ee->cp->set_variable('cp_page_title', lang('mcp_title_tags'));
        $vars = array();
        $vars['platforms'] = array(
            'Schema.org' => array(
                'name',
                'description',
                'image',
            ),
            'Twitter' => array(
                'card',
                'title',
                'description',
                'image',
            ),
            'OpenGraph' => array(
                'type',
                'title',
                'description',
                'image',
                'url'
            ),
        );
        $vars['home_url'] = $this->base_url;
        $vars['usage_url'] = $this->base_url.AMP.'method=usage';
        $vars['config_url'] = $this->base_url.AMP.'method=config';

        return $this->ee->load->view('mcp_tags', $vars, true);
    }

    /**
     * CP usage page
     *
     * @return   void
     */
    public function usage() {
        $this->ee->cp->set_variable('cp_page_title', lang('mcp_title_usage'));
        $vars['home_url'] = $this->base_url;
        $vars['tags_url'] = $this->base_url.AMP.'method=tags';
        $vars['config_url'] = $this->base_url.AMP.'method=config';
        $vars['config'] = $this->_config();
        return $this->ee->load->view('mcp_usage', $vars, true);
    }

    /**
     * CP config page
     *
     * @return   void
     */
    public function config() {
        $this->ee->cp->set_variable('cp_page_title', lang('mcp_title_config'));
        $this->ee->load->library('table');
        $this->ee->load->helper('form');
        $vars['action_url'] = $this->base_path.AMP.'method=update';
        $vars['form_hidden'] = null;
        $vars['config'] = $this->_config();
        return $this->ee->load->view('mcp_config', $vars, true);
    }

    /**
     * CP update config
     */
    public function update() {
        $sql = 'UPDATE exp_mo_betta_meta_config SET value = ? WHERE var = ? AND site_id = ?';
        $config_vars = array(
            'field_name_prefix', 
            'so_name',
            'so_description',
            'so_image',
            'tw_title',
            'tw_description',
            'tw_image',
            'og_title',
            'og_description',
            'og_image',
            'page_type',
            'default_image',
            'default_page_type',
            'log_developer',
            'log_template',
        );
        foreach($config_vars as $var) {
            $value = $this->ee->input->post($var, true);
            $this->ee->db->query($sql, array($value, $var, $this->site_id));
        }

        $this->ee->session->set_flashdata('message_success', 'Config updated');
        $this->ee->functions->redirect($this->base_url);
    }

    private function _config() {
        $config = array();

        $sql = <<<SQL
SELECT var, value
FROM exp_mo_betta_meta_config
WHERE site_id = ?
ORDER BY var ASC
SQL;

        $query = $this->ee->db->query($sql, array($this->site_id));

        if ($query->num_rows() > 0) {
            foreach($query->result() as $row) {
                $config[$row->var] = $row->value;
            }
        }

        return $config;
    }
}
/* End of file mcp.mo_betta_meta.php */
/* Location: /system/expressionengine/third_party/mo_betta_meta/mcp.mo_betta_meta.php */
