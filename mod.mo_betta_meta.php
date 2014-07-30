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

/**
 * Mo Betta Meta Module Front End File
 *
 * @package    ExpressionEngine
 * @subpackage Addons
 * @category   Module
 * @author     Steve Pedersen
 * @link       http://www.bluecoastweb.com
 */
class Mo_betta_meta {

    public $return_data;
    private $ee;
    private $site_id;
    private $settings;
    private $base_url;
    private $current_url;
    private $nsm_ext;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ee = function_exists('ee') ? ee() : get_instance();

        $this->site_id = $this->ee->config->item('site_id');
        $this->settings = $this->_module_config();

        $this->ee->load->library('logger');

        $this->ee->load->helper('url');
        $this->base_url = base_url();
        $this->current_url = current_url();

        // load NSM settings
        if (! class_exists('Nsm_better_meta_ext')) {
            include(PATH_THIRD. 'nsm_better_meta/ext.nsm_better_meta.php');
        }
        $this->nsm_ext = new Nsm_better_meta_ext;
    }

    /**---------------------------------------------------------------------------
     * Public functions
     *--------------------------------------------------------------------------*/

    /**
     * Output meta tags for entry
     *
     *   Example:
     *
     *      {exp:nsm_better_meta:template entry_id='{structure:page:entry_id}'}
     *
     *      {exp:mo_betta_meta:tags entry_id='{structure:page:entry_id}'}
     *
     */
    public function tags() {
        $entry_id = $this->ee->TMPL->fetch_param('entry_id');
        $this->_log(__FUNCTION__." entry_id=$entry_id");

        $so_name = $this->_title($this->settings['so_name'], $entry_id);
        $so_description = $this->_description($this->settings['so_description'], $entry_id);
        $so_image = $this->_image($this->settings['so_image'], $entry_id);

        $tw_card = $this->_tw_card($entry_id);
        $tw_title = $this->_title($this->settings['tw_title'], $entry_id);
        $tw_description = $this->_description($this->settings['tw_description'], $entry_id);
        $tw_image = $this->_image($this->settings['tw_image'], $entry_id);

        $og_type = $this->_og_type($entry_id);
        $og_title = $this->_title($this->settings['og_title'], $entry_id);
        $og_description = $this->_description($this->settings['og_description'], $entry_id);
        $og_image = $this->_image($this->settings['og_image'], $entry_id);
        $og_url = $this->_canonical_url($entry_id);
        $og_site_name = $this->_site_name();

        $html = <<<HTML
<meta itemprop="name" content="$so_name" />
<meta itemprop="description" content="$so_description" />
<meta itemprop="image" content="$so_image" />

<meta name="twitter:card" value="$tw_card" />
<meta name="twitter:title" content="$tw_title" />
<meta name="twitter:description" content="$tw_description" />
<meta name="twitter:image" content="$tw_image" />

<meta property="og:type" content="$og_type" />
<meta property="og:title" content="$og_title" />
<meta property="og:description" content="$og_description" />
<meta property="og:image" content="$og_image" />
<meta property="og:url" content="$og_url" />
<meta property="og:site_name" content="$og_site_name" />
HTML;

        return $html;
    }

    /**---------------------------------------------------------------------------
     * Private functions
     *--------------------------------------------------------------------------*/

    /**
     * Return Title for entry
     */
    private function _title($type, $entry_id = null) {
        if ($entry_id) {
            if ($value = $this->_custom_field($entry_id, $type)) {
                return $value;
            }

            if ($value = $this->_nsm_entry_value($entry_id, 'title')) {
                return $value;
            }

            return $this->_channel_title($entry_id);
        }

        return $this->_nsm_default_title();
    }

    /**
     * Return Description for entry
     */
    private function _description($type, $entry_id = null) {
        if ($entry_id) {
            if ($value = $this->_custom_field($entry_id, $type)) {
                return $value;
            }

            if ($value = $this->_nsm_entry_value($entry_id, 'description')) {
                return $value;
            }
        }

        return $this->_nsm_default_description();
    }

    /**
     * Return Image URL for entry
     */
    private function _image($type, $entry_id = null) {
        if ($entry_id) {
            if ($value = $this->_custom_field($entry_id, $type)) {
                return $this->_url($value);
            }
        }

        return $this->_url($this->settings['default_image']);
    }

    /**
     * Return Twitter card type for entry
     */
    private function _tw_card($entry_id = null) {
        return $this->_page_type($entry_id) == 'video' ? 'player' : 'summary';
    }

    /**
     * Return OpenGraph page type for entry
     */
    private function _og_type($entry_id = null) {
        return $this->_page_type($entry_id);
    }

    /**
     * Return page type for entry
     */
    private function _page_type($entry_id = null) {
        if ($entry_id) {
            if ($value = $this->_custom_field($entry_id, $this->settings['page_type'])) {
                return $value;
            }
        }

        return $this->settings['default_page_type'];
    }

    /**
     * Return canonical URL for entry
     */
    private function _canonical_url($entry_id = null) {
        if ($entry_id) {
            if ($value = $this->_nsm_entry_value($entry_id, 'canonical_url')) {
                return $this->_ensure_terminal_slash($value);
            }
        }

        return $this->_ensure_terminal_slash(current_url());
    }

    /**
     * Return URL with a terminal slash
     */
    private function _ensure_terminal_slash($url) {
        return substr($url, strlen($url) - 1) == '/' ? $url : $url.'/';
    }

    /**
     * Return site name
     */
    private function _site_name() {
        if ($value = $this->_nsm_default_title()) {
            return $value;
        } else {
            return $this->ee->config->config['site_name'];
        }
    }

    /**
     * Return custom field value for entry
     */
    private function _custom_field($entry_id, $type) {
        if ($field_id = $this->_custom_field_id($entry_id, $type)) {
            return $this->_custom_field_value($entry_id, $field_id);
        }

        return false;
    }

    /**
     * Return custom field id for entry and field name pattern
     */
    private function _custom_field_id($entry_id, $field_name_suffix) {
        // $partial_name = '_meta_og_title';
        $partial_name = "{$this->settings['field_name_prefix']}$field_name_suffix";

        foreach($this->_custom_field_names($entry_id) as $field_name => $field_id) {
            if (substr_compare($field_name, $partial_name, -strlen($partial_name), strlen($partial_name)) === 0) {
                return $field_id;
            }
        }

        return false;
    }

    /**
     * Return array of custom field names for entry
     */
    private function _custom_field_names($entry_id) {
        $cache_key = "custom_field_names_$entry_id";

        if (! $this->ee->session->cache(__CLASS__, $cache_key)) {
            $value = array();

            $sql = <<<SQL
SELECT f.field_name, f.field_id
FROM exp_channel_fields f
  INNER JOIN exp_field_groups g
    USING (group_id)
  INNER JOIN exp_channels c
    ON c.field_group = g.group_id
  INNER JOIN exp_channel_titles t
    ON t.channel_id = c.channel_id
WHERE t.entry_id = ?
SQL;
            $query = $this->ee->db->query($sql, array($entry_id));

            if ($query->num_rows() > 0) {
                foreach($query->result() as $row) {
                    // $value['foobar_meta_og_title'] = 'field_id_23';
                    $value[$row->field_name] = "field_id_{$row->field_id}";
                }

                $this->_log(__FUNCTION__." entry_id=$entry_id, CACHE MISS value=".print_r($value, true));
            }

            $this->ee->session->set_cache(__CLASS__, $cache_key, $value);
        }

        return $this->ee->session->cache(__CLASS__, $cache_key);
    }

    private function _custom_field_value($entry_id, $field_id) {
        $custom_field_values = $this->_custom_field_values($entry_id);

        if (in_array($field_id, array_keys($custom_field_values))) {
            return $custom_field_values[$field_id];
        }

        return '';
    }

    /**
     * Return array of custom field values for entry
     */
    private function _custom_field_values($entry_id) {
        $cache_key = "custom_field_values_$entry_id";

        if (! $this->ee->session->cache(__CLASS__, $cache_key)) {
            $value = array();

            $field_names = array_values($this->_custom_field_names($entry_id));

            // 'SELECT field_id_7, field_id_8, field_id_9 ... FROM exp_channel_data WHERE entry_id = ?';
            $sql = 'SELECT '.implode(', ', $field_names)." FROM exp_channel_data WHERE entry_id = ?";

            $query = $this->ee->db->query($sql, array($entry_id));

            if ($query->num_rows() > 0) {
                foreach($query->result_array() as $row) {
                    foreach(array_values($this->_custom_field_names($entry_id)) as $field_name) {
                        // $value['field_id_7'] = 'Meta tag content...';
                        $value[$field_name] = $row[$field_name];
                    }
                }

                $this->_log(__FUNCTION__." entry_id=$entry_id, CACHE MISS value=".print_r($value, true));
            }

            $this->ee->session->set_cache(__CLASS__, $cache_key, $value);
        }

        return $this->ee->session->cache(__CLASS__, $cache_key);
    }

    /**
     * Return Title for entry
     */
    private function _channel_title($entry_id) {
        $cache_key = "channel_title_$entry_id";

        if (! $this->ee->session->cache(__CLASS__, $cache_key)) {
            $sql = 'SELECT title FROM exp_channel_titles WHERE entry_id = ? LIMIT 0,1';
            $query = $this->ee->db->query($sql, array($entry_id));
            $value = '';

            if ($query->num_rows() == 1) {
                $value = $query->row('title');
                $this->_log(__FUNCTION__." entry_id=$entry_id, CACHE MISS title=$value");
            }

            $this->ee->session->set_cache(__CLASS__, $cache_key, $value);
        }

        return $this->ee->session->cache(__CLASS__, $cache_key);
    }

    /**
     * Return entry value from NSM
     */
    private function _nsm_entry_value($entry_id, $nsm_column) {
        $cache_key = "nsm_{$nsm_column}_{$entry_id}";

        if (! $this->ee->session->cache(__CLASS__, $cache_key)) {
            $value = '';
            $sql = "SELECT $nsm_column FROM exp_nsm_better_meta WHERE entry_id = ? LIMIT 0,1";
            $query = $this->ee->db->query($sql, array($entry_id));

            if ($query->num_rows() == 1) {
                $value = $query->row($nsm_column);
                $this->_log(__FUNCTION__." entry_id=$entry_id, CACHE MISS $nsm_column=$value");
            }

            $this->ee->session->set_cache(__CLASS__, $cache_key, $value);
        }

        return $this->ee->session->cache(__CLASS__, $cache_key);
    }

    /**
     * Return default Title from NSM
     */
    private function _nsm_default_title() {
        return $this->nsm_ext->settings['default_site_meta']['site_title'];
    }

    /**
     * Return default Description from NSM
     */
    private function _nsm_default_description() {
        return $this->nsm_ext->settings['default_site_meta']['description'];
    }

    /**
     * Return full URL for relative path
     */
    private function _url($relative_path) {
        return $this->base_url.$relative_path;
    }

    /**
     * Conditionally log to Developer log and to Template
     */
    private function _log($string) {
        if ($this->settings['log_developer'] == 'y') {
            $this->ee->logger->developer(__CLASS__."::$string");
        }

        if ($this->settings['log_template'] == 'y') {
            $this->ee->TMPL->log_item(__CLASS__."::$string");
        }
    }

    /**
     * Get configuration
     */
    private function _module_config() {
        $cache_key = 'mo_betta_meta_config';

        if (! $this->ee->session->cache(__CLASS__, $cache_key)) {
            $value = array();
            $sql = <<<SQL
SELECT var, value
FROM exp_mo_betta_meta_config
WHERE site_id = ?
ORDER BY var ASC
SQL;

            $query = $this->ee->db->query($sql, array($this->site_id));

            if ($query->num_rows() > 0) {
                foreach($query->result() as $row) {
                    $value[$row->var] = $row->value;
                }
            }

            $this->ee->session->set_cache(__CLASS__, $cache_key, $value);
        }

        return $this->ee->session->cache(__CLASS__, $cache_key);
    }


    //  private function _fatal_error($string) {
    //      $this->ee->output->fatal_error(__CLASS__."::$string");
    //  }

}
/* End of file mod.mo_betta_meta.php */
/* Location: /system/expressionengine/third_party/mo_betta_meta/mod.mo_betta_meta.php */
