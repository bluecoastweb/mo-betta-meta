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
 * Mo Betta Meta Module Install/Update File
 *
 * @package    ExpressionEngine
 * @subpackage Addons
 * @category   Module
 * @author     Steve Pedersen
 * @link       http://www.bluecoastweb.com
 */
class Mo_betta_meta_upd {

    public $version = '1.1';
    private $ee;
    private $site_id;

    /**
     * Constructor
     */
    public function __construct() {
        $this->ee = function_exists('ee') ? ee() : get_instance();
        $this->site_id = $this->ee->config->item('site_id');
    }

    /**
     * Installation Method
     *
     * @return   boolean   true
     */
    public function install()   {
        $mod_data = array(
            'module_name'        => 'Mo_betta_meta',
            'module_version'     => $this->version,
            'has_cp_backend'     => 'y',
            'has_publish_fields' => 'n'
        );
        $this->ee->db->insert('modules', $mod_data);

        $sql = <<<SQL
CREATE TABLE `exp_mo_betta_meta_config` (
  `var` VARCHAR(65) NOT NULL,
  `value` VARCHAR(255) NOT NULL,
  `site_id` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`var`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
        $this->ee->db->query($sql);

        $sql = 'INSERT INTO exp_mo_betta_meta_config (var, value, site_id) values(?, ?, ?)';
        $default_config = array(
            'default_image'     => 'default.jpg',
            'default_page_type' => 'article',
            'field_name_prefix' => '_meta',
            'log_developer'     => 'n',
            'log_template'      => 'n',
            'page_type'         => '_page_type',
            'og_title'          => '_og_title',
            'og_description'    => '_og_description',
            'og_image'          => '_og_image',
            'so_name'           => '_so_name',
            'so_description'    => '_so_description',
            'so_image'          => '_so_image',
            'tw_title'          => '_tw_title',
            'tw_description'    => '_tw_description',
            'tw_image'          => '_tw_image',
        );
        foreach($default_config as $var => $value) {
            $this->ee->db->query($sql, array($var, $value, $this->site_id));
        }

        return true;
    }

    /**
     * Uninstall
     *
     * @return   boolean   true
     */
    public function uninstall() {
        $mod_id = $this->ee->db->select('module_id')->get_where('modules', array('module_name'  => 'Mo_betta_meta'))->row('module_id');
        $this->ee->db->where('module_id', $mod_id)->delete('module_member_groups');
        $this->ee->db->where('module_name', 'Mo_betta_meta')->delete('modules');
        $this->ee->db->query('DROP TABLE IF EXISTS `exp_mo_betta_meta_config`');

        return true;
    }

    /**
     * Module Updater
     *
     * @return   boolean   true
     */
    public function update($current = '') {
        return true;
    }

}
/* End of file upd.mo_betta_meta.php */
/* Location: /system/expressionengine/third_party/mo_betta_meta/upd.mo_betta_meta.php */
