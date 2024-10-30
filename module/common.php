<?php

/*
 * common.php
 * -*- Encoding: utf8n -*-
 */

class contentInsteadOfTheExcerpt_common {
    
    /*
     * use to content instead of excerpt
     */
    function use_the_content_instead_of_the_excerpt() {
        //return true;
        //echo 'use_the_content_instead_of_the_excerpt';
        global $post;
        global $wpContentInsteadOfTheExcerpt;
        //global $contentInsteadOfTheExcerpt_common;
        $model = $wpContentInsteadOfTheExcerpt->model;
        
        $instead = false;
        if ($model->getContentInsteadOfExcerptOnHome() && is_home()) {
            $instead = true;
        } else if ($model->getContentInsteadOfExcerptOnPage() && is_page()) {
            $instead = true;
        } else if ($model->getContentInsteadOfExcerptOnArchive() && is_archive()) {
            //echo 'is_archive';exit;
            $instead = true;
        } else if ($model->getContentInsteadOfExcerptOnSingle() && is_single()) {
            $instead = true;
        } else {
            if (is_archive()) {
                $cat = $model->getContentInsteadOfExcerptOnCategoryNumbers();
                $cat_id = get_query_var('cat');
                if (in_array($cat_id, $cat)) {
                    $instead = true;
                }
            } else if (is_page()) {
                $page = $model->getContentInsteadOfExcerptOnPageNumbers();
                $page_id = get_query_var('page_id');
                if (in_array($page_id, $page)) {
                    $instead = true;
                }
            }
        }
        return $instead;
    }
    
    
    /* directory *************************/
    
    /* ex: http://wordpress/wp-content/plugins/this_plugin */

    function get_plugin_uri() {
        $p = $this->get_plugin_path();
        $first = substr($p, 0, 1);
        if ($first !== '/')
            $p = '/' . $p;
        return get_settings('siteurl') . $p;
    }

    /* ex: http://wordpress/wp-content/plugins/this_plugin/module */

    function get_plugin_module_uri() {
        return $this->get_plugin_uri() . '/module';
    }

    /* ex: /home/user/public_html/wordpress/wp-content/plugins/this_plugin */

    function get_plugin_fullpath() {
        $path = '';
        $cpath = $this->get_current_path();
        $first = substr($cpath, 0, 1);
        if ($first === '/')
            $path = $first;
        $f = explode('/', $cpath);
        $max = count($f);
        foreach ($f as $i => $p) {
            if ($p === 'module') {
                return $path;
            }
            if ($i == 0 && $p === '') {
                $path = '/';
                continue;
            }
            if ($path !== '' && $path !== '/')
                $path .= '/';
            $path .= $p;
        }
        return $path;
    }

    /* ex: /home/user/public_html/wordpress */

    function get_wp_fullpath() {
        $path = '';
        $cpath = $this->get_current_path();
        $first = substr($cpath, 0, 1);
        if ($first === '/')
            $path = $first;
        $f = explode('/', $cpath);
        $max = count($f);
        foreach ($f as $i => $p) {
            if ($p === 'wp-content') {
                return $path;
            }
            if ($i == 0 && $p === '') {
                $path = '/';
                continue;
            }
            if ($path !== '' && $path !== '/')
                $path .= '/';
            $path .= $p;
        }
        return $path;
    }

    /* ex: /home/user/public_html/wordpress/wp-content */

    function get_wp_content_fullpath() {
        return $this->get_wp_fullpath() . '/wp-content';
    }

    /* ex: /wp-content/plugins/this_plugin */

    function get_plugin_path() {
        $path = '';
        $flag = 1;
        $cpath = $this->get_current_path();
        $first = substr($cpath, 0, 1);
        if ($first === '/')
            $path = $first;
        $f = explode('/', $cpath);
        $max = count($f);
        foreach ($f as $i => $p) {
            if ($p !== 'wp-content' && $flag)
                continue;
            $flag = 0;
            if ($p === 'module') {
                return $path;
            }
            if ($i == 0 && $p === '') {
                $path = '/';
                continue;
            }
            if ($path !== '' && $path !== '/')
                $path .= '/';
            $path .= $p;
        }
        return $path;
    }

    /* ex: this_plugin */

    function get_plugin_folder() {
        $cpath = $this->get_current_path();
        $f = explode('/', $cpath);
        $max = count($f);
        for ($i = $max - 1; $i >= 0; $i--) {
            if ($f[$i] === 'module' && $i > 0) {
                return $f[$i - 1];
            }
        }
        return false;
    }
    
    /*
     * php.ini
     * -- before --
     * error_reporting = E_ALL | E_STRICT
     * -- after --
     * ;error_reporting = E_ALL | E_STRICT
     * error_reporting  =  E_ALL & ~E_NOTICE & ~E_DEPRECATED
     */
    /* ex: /home/user/public_html/wordpress/wp-content/plugins/this_plugin... */
    function get_current_path() {
        //echo "WP_PLUGIN_URL = " . WP_PLUGIN_URL;
        $current_path = (dirname(__FILE__));
        
        // sanitize for Win32 installs
        $current_path = str_replace('\\' ,'/', $current_path);
        $current_path = preg_replace('|/+|', '/', $current_path);
        return $current_path;
        //echo "<p>current_path = $current_path</p>";
    }
    
}//class


function contentInsteadOfTheExcerpt_use_the_content_instead_of_the_excerpt_hook($content) {
    global $post;
    global $contentInsteadOfTheExcerpt_common;
    
    //$content = get_the_excerpt($post->ID);
    if (!$contentInsteadOfTheExcerpt_common->use_the_content_instead_of_the_excerpt()) {
        return $content;
    }
    $content = get_the_content($post->ID);
    $content = apply_filters('the_content', $content);
    return $content;
}

?>
