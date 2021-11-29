<?php

/** 
 * @package TablePlugin
 */

/*
    Plugin Name: Cob Table Plugin
    Description: This is table plugin that create and update table from json API
    Version: 1.0.0
    Author: hadi arbabi
    Author URI: https://github.com/hadimojak
    License: MIT
    Text Domain: hadi-plugin    
*/

/*
Copyright 2021 MIT

Permission is hereby granted, free of charge, to any person obtaining a copy of this software
and associated documentation files (the "Software"), to deal in the Software without restriction,
including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all 
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL 
THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR 
OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, 
ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR 
THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

//plugin security
defined('ABSPATH') or die('Hey, you can/t access this file, you silly human!');

class CobexTable
{
    //public 
    //can be accessed everywhere
    //protected
    //can be accessed only within the class itself or extensions of that class
    //private
    //can be accessed only within the class itself
    public $plugin;
    function __construct()
    {
        $this->plugin = plugin_basename(__FILE__);
    }
    //take care of registerig scripts and css 
    function register()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue'));
        add_action('admin_menu', array($this, 'add_admin_pages'));
        add_filter("plugin_action_links_$this->plugin", array($this, 'settings_link'));
    }
    function settings_link($links)
    {
        $settings_link = '<a href="admin.php?page=table_plugin">Settings</a>';
        array_push($links, $settings_link);
        return $links;
    }
    //callback for above function
    function add_admin_pages()
    {
        add_menu_page('Cob Table Plugin', 'Cob Title', 'manage_options', 'table_plugin', array($this, 'admin_index'), 'dashicons-editor-table', 110);
    }
    function admin_index()
    {
        require_once plugin_dir_path(__FILE__) . 'templates/admin.php';
    }
    function adcivate()
    {
        flush_rewrite_rules();
    }
    function deactivate()
    {
        flush_rewrite_rules();
    }
    function enqueue()
    {
        //enqueue all scripts
        wp_enqueue_style('pluginStyle', plugins_url('/assets/styles.css', __FILE__));
        wp_enqueue_script('pluginScript', plugins_url('/assets/main.js ', __FILE__));
    }
}


if (class_exists('CobexTable')) {
    $cobexTable = new CobexTable();
    $cobexTable->register();
}

//activateion
register_activation_hook(__FILE__, array($cobexTable, 'adcivate'));

//deactivation
register_deactivation_hook(__FILE__, array($cobexTable, 'deactivate'));

//unistall
