<?php
/*
Plugin Name: Content instead of the excerpt
Plugin URI: http://takeai.silverpigeon.jp/
Description: 
Author: AI.Takeuchi
Version: 0.1.0
Author URI: http://takeai.silverpigeon.jp/
*/

// -*- Encoding: utf8n -*-

/*  Copyright 2012 AI Takeuchi (email: takeai@silverpigeon.jp)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// global
$wpContentInsteadOfTheExcerpt = new WpContentInsteadOfTheExcerpt();
require_once('module/common.php');


// global
$contentInsteadOfTheExcerpt_common = new contentInsteadOfTheExcerpt_common();

$plugin_folder = $contentInsteadOfTheExcerpt_common->get_plugin_folder();
$plugin_fullpath = $contentInsteadOfTheExcerpt_common->get_plugin_fullpath();
$plugin_path = $contentInsteadOfTheExcerpt_common->get_plugin_path();
$plugin_uri = $contentInsteadOfTheExcerpt_common->get_plugin_uri();

load_plugin_textdomain('contentinsteadoftheexcerpt',
                       $plugin_path . '/lang', $plugin_folder . '/lang');

if (is_admin()) {
    $model = $wpContentInsteadOfTheExcerpt->model;
    
    // Registration of management screen header output function.
    add_action('admin_head', array(&$wpContentInsteadOfTheExcerpt, 'addAdminHead'));
    // Registration of management screen function.
    add_action('admin_menu', array(&$wpContentInsteadOfTheExcerpt, 'addAdminMenu'));
} else {
    add_action('the_excerpt', 'contentInsteadOfTheExcerpt_use_the_content_instead_of_the_excerpt_hook');
}

/* Data model */
class WpContentInsteadOfTheExcerptItemModel {
    //
    var $content_instead_of_excerpt_on_home;
    var $content_instead_of_excerpt_on_page;
    var $content_instead_of_excerpt_on_archive;
    var $content_instead_of_excerpt_on_single;
    var $content_instead_of_excerpt_on_category_numbers;
    var $content_instead_of_excerpt_on_page_numbers;
}
class WpContentInsteadOfTheExcerptModel {
    // member variable
    var $version;
    var $debug;
    var $site;
    var $site_id;
    
    // constructor
    function WpContentInsteadOfTheExcerptModel() {
        // default value
        $this->version = '0.1.0';
        $this->debug = '';
        //
        $sid = $this->get_current_site_id();
        $this->site_id = $sid;
        //
        $this->site = array();
        $this->site[$sid] = new WpContentInsteadOfTheExcerptItemModel();
        //
        $this->site[$sid]->content_instead_of_excerpt_on_home = '';
        $this->site[$sid]->content_instead_of_excerpt_on_page = '';
        $this->site[$sid]->content_instead_of_excerpt_on_archive = '';
        $this->site[$sid]->content_instead_of_excerpt_on_single = '';
        $this->site[$sid]->content_instead_of_excerpt_on_category_numbers = '';
        $this->site[$sid]->content_instead_of_excerpt_on_page_numbers = '';
        //
    }

    /*
     * multi site function
     * site id
     */
    function get_current_site_id() {
        if (!function_exists('get_blog_list')) {
            return 999999;
        }
        $this_blog_name = get_bloginfo('name');
        $this_blog_id = 0;
        $my_child_blogs = get_blog_list();
        foreach ( $my_child_blogs as $key => $value ){
            if ( get_blog_option($value["blog_id"], 'blogname') == $this_blog_name ){
                $this_blog_id = $value["blog_id"];
                break 1;
            }
        }
        return $this_blog_id;
    }
    
    //
    function get_current_version() {
        return '0.1.0';
    }
    function get_version() {
        return $this->version;
    }
    function set_version($fields) {
        $this->version = $fields;
    }
    //
    function is_debug() {
        if ($this->debug) return true;
        else return false;
    }
    function setDebug($fields) {
        $this->debug = $fields;
    }
    function getDebug() {
        return $this->debug;
    }


    //
    function setContentInsteadOfExcerptOnHome($fields) {
        $fields = preg_replace('/[^a-zA-Z]/', '', $fields);
        $this->site[$this->site_id]->content_instead_of_excerpt_on_home = $fields;
    }
    function getContentInsteadOfExcerptOnHome() {
        return $this->site[$this->site_id]->content_instead_of_excerpt_on_home;
    }
    function setContentInsteadOfExcerptOnPage($fields) {
        $fields = preg_replace('/[^a-zA-Z]/', '', $fields);
        $this->site[$this->site_id]->content_instead_of_excerpt_on_page = $fields;
    }
    function getContentInsteadOfExcerptOnPage() {
        return $this->site[$this->site_id]->content_instead_of_excerpt_on_page;
    }
    function setContentInsteadOfExcerptOnArchive($fields) {
        $fields = preg_replace('/[^a-zA-Z]/', '', $fields);
        $this->site[$this->site_id]->content_instead_of_excerpt_on_archive = $fields;
    }
    function getContentInsteadOfExcerptOnArchive() {
        return $this->site[$this->site_id]->content_instead_of_excerpt_on_archive;
    }
    function setContentInsteadOfExcerptOnSingle($fields) {
        $fields = preg_replace('/[^a-zA-Z]/', '', $fields);
        $this->site[$this->site_id]->content_instead_of_excerpt_on_single = $fields;
    }
    function getContentInsteadOfExcerptOnSingle() {
        return $this->site[$this->site_id]->content_instead_of_excerpt_on_single;
    }
    function setContentInsteadOfExcerptOnCategoryNumbers($fields) {
        $f = trim(preg_replace('/[^0-9,]|^,+|,+$/', '', $fields));
        if ($f === "") {
            $this->site[$this->site_id]->content_instead_of_excerpt_on_category_numbers = NULL;
        } else {
            $f = explode(',', $f);
            $this->site[$this->site_id]->content_instead_of_excerpt_on_category_numbers = $f;
        }
    }
    function getContentInsteadOfExcerptOnCategoryNumbers() {
        $a = $this->site[$this->site_id]->content_instead_of_excerpt_on_category_numbers;
        if (!is_array($a)) return array();
        return $a;
    }
    function setContentInsteadOfExcerptOnPageNumbers($fields) {
        $f = trim(preg_replace('/[^0-9,]|^,+|,+$/', '', $fields));
        if ($f === "") {
            $this->site[$this->site_id]->content_instead_of_excerpt_on_page_numbers = NULL;
        } else {
            $f = explode(',', $f);
            $this->site[$this->site_id]->content_instead_of_excerpt_on_page_numbers = $f;
        }
    }
    function getContentInsteadOfExcerptOnPageNumbers() {
        $a = $this->site[$this->site_id]->content_instead_of_excerpt_on_page_numbers;
        if (!is_array($a)) return array();
        return $a;
    }
}

/* main class */
class WpContentInsteadOfTheExcerpt {
    var $view;
    var $model;
    var $common;
    var $request;
    var $plugin_name;
    var $plugin_fullpath, $plugin_path, $plugin_folder, $plugin_uri;
    
    // constructor
    function WpContentInsteadOfTheExcerpt() {
        $this->plugin_name = 'contentInsteadOfTheExcerpt';
        $this->model = $this->getModelObject();
    }
    
    // create model object
    function getModelObject() {
        $data_clear = 0; // Debug: 1: Be empty to data
        
        // get option from Wordpress
        $option = $this->getWpOption();
        
        //printf("<p>Debug[%s, %s]</p>", strtolower(get_class($option)), strtolower('WpContentInsteadOfTheExcerptModel'));
        
        // Restore the model object if it is registered
        if (strtolower(get_class($option)) === strtolower('WpContentInsteadOfTheExcerptModel') && $data_clear == 0) {
            $model = $option;
        } else {
            // create model instance if it is not registered,
            // register it to Wordpress
            $model = new WpContentInsteadOfTheExcerptModel();
            $this->addWpOption($model);
        }
        return $model;
    }
    
    function getWpOption() {
        $option = get_option($this->plugin_name);
        
        if(!$option == false) {
            $OptionValue = $option;
        } else {
            $OptionValue = false;
        }
        return $OptionValue;
    }

    /* be add plug-in data to Wordpresss */
    function addWpOption(&$model) {
        $option_description = $this->plugin_name . " Options";
        $OptionValue = $model;
        //print_r($OptionValue);
        add_option(
            $this->plugin_name,
            $OptionValue,
            $option_description);
    }

    /* update plug-in data */
    function updateWpOption(&$OptionValue) {
        $option_description = $this->plugin_name . " Options";
        $OptionValue = $OptionValue;
        //$OptionValue = $this->model;
        
        update_option(
            $this->plugin_name,
            $OptionValue,
            $option_description);
    }
    
    /*
     * management screen header output
     * reading javascript and css
     */
    function addAdminHead() {
        echo '<link type="text/css" rel="stylesheet" href="';
        echo $this->plugin_uri . '/content-instead-of-the-excerpt.css" />' . "\n";
    }

    function addAdminMenu() {
        $hook = add_options_page(
            __('Content instead of the excerpt Options','contentinsteadoftheexcerpt'),
            __('Content instead of the excerpt','contentinsteadoftheexcerpt'),
            8,
            'content-instead-of-the-excerpt.php',
            array(&$this, 'executeAdmin')
            );
        add_action('admin_print_scripts-'.$hook, array(&$this, 'admin_scripts'));
    }

    function admin_scripts() {
    }

    function executeAdmin() {
        require_once('module/execute_admin.php');
        contentInsteadOfTheExcerpt_execute_admin($this);
    }
}
?>
