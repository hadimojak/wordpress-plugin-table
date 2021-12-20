<?php

/**
 * @package apiTablePlugin
 */
/*
 * Plugin Name: cob table plugin
 * Description: a usefull plugin for create and fill the table with api json data.
 * Version: 1.0
 * Author: hadia arbabi
 * Author URI: https://www.linkedin.com/in/hadi-arbabi/
 * License: MIT
 */
/*
Copyright 2021 hadi arbabi

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the "Software"),
to deal in the Software without restriction, including without limitation
the rights to use, copy, modify, merge, publish, distribute, sublicense,
and/or sell copies of the Software, and to permit persons to whom the 
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, 
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR
ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION 
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE. 
 **/

if (!defined('ABSPATH')) {
    die;
}

//registering scripts
add_action('init', 'register_scripts');
function register_scripts()
{
    wp_register_script('plugin_script', plugins_url('/assets/main.js', __FILE__), array(), false, true);
    wp_register_script('plugin_script_bootstrap', plugins_url('/assets/bootstrap.min.js', __FILE__));
    wp_register_style('plugin_style', plugins_url('/assets/style.css', __FILE__));
    wp_register_style('plugin_style_bootstrap', plugins_url('/assets/bootstrap-rtl.min.css', __FILE__));
}



function enqueue_scripts()
{
    wp_enqueue_script('plugin_script');
    wp_enqueue_style('plugin_style');
    wp_enqueue_style('plugin_script_bootstrap');
    wp_enqueue_style('plugin_style_bootstrap');



    $script_params = array(
        'timeIntervel' => esc_attr(get_option('api_time_intervel')),
        'apiUrl' => esc_attr(get_option('api_url')),
        'tableClasses' => esc_attr(get_option('table_classes')),
        'tableHeadClasses' => esc_attr(get_option('table_head_classes')),
        'tableTdClasses' => esc_attr(get_option('table_td_classes')),
        'tableStripedClass' => esc_attr(get_option('table_striped_class')),
        'tableHoverClass' => esc_attr(get_option('table_hover_class')),
        'tableTheme' => esc_attr(get_option('mySelect')),
        'pick_options' => get_option('pick'),
        'buy_options' => get_option('buy'),
    );
    wp_localize_script('plugin_script', 'scriptParams', $script_params);
}

//add js to plugin settings page
function wp_ss_plugin_admin_init_cb()
{
    wp_register_style(
        'wp_ss_plugin_style',
        plugin_dir_url(__FILE__) . '/assets/bootstrap-rtl.min.css'
    );
    wp_register_style(
        'wp_ss_plugin_mystyle',
        plugin_dir_url(__FILE__) . '/assets/menu.css'
    );
    wp_register_script(
        'wp_ss_plugin_script_bootstrap',
        plugin_dir_url(__FILE__) . '/assets/bootstrap.min.js',
    );
    wp_register_script(
        'wp_ss_plugin_script',
        plugin_dir_url(__FILE__) . '/assets/menu.js',
    );
}
add_action('admin_init', 'wp_ss_plugin_admin_init_cb');
// admin_enqueue_scripts
// ************************************************************************************************
function wp_ss_plugin_admin_enqueue_scripts_cb()
{
    //Enqueue JS
    wp_enqueue_style('wp_ss_plugin_style');
    wp_enqueue_style('wp_ss_plugin_mystyle');
    wp_enqueue_script('wp_ss_plugin_script');
    wp_enqueue_script('wp_ss_plugin_script_bootstrap');
    $script_params = array(
        'apiUrl' => esc_attr(get_option('api_url')),
        'pick_options' => get_option('pick'),
        'buy_options' => get_option('buy'),
    );
    wp_localize_script('wp_ss_plugin_script', 'scriptParams', $script_params);
}
add_action('admin_enqueue_scripts', 'wp_ss_plugin_admin_enqueue_scripts_cb');

add_action('wp_enqueue_scripts', 'enqueue_scripts');
add_action('admin_menu', 'plugin_create_menu');

function plugin_create_menu()
{
    //create new top-level menu
    add_menu_page('Plugin Settings', 'Table Settings', 'administrator', __FILE__, 'plugin_settings_page', 'dashicons-editor-table');

    //call register settings function
    add_action('admin_init', 'register_plugin_settings');
}

function register_plugin_settings()
{
    //register our settings
    register_setting('plugin-settings-group', 'api_url');
    register_setting('plugin-settings-group', 'api_time_intervel');
    register_setting('plugin-settings-group', 'table_classes');
    register_setting('plugin-settings-group', 'table_head_classes');
    register_setting('plugin-settings-group', 'table_td_classes');
    register_setting('plugin-settings-group', 'table_striped_class');
    register_setting('plugin-settings-group', 'table_hover_class');
    register_setting('plugin-settings-group', 'mySelect');
    register_setting('plugin-settings-group', 'pick');
    register_setting('plugin-settings-group', 'buy');
}
function plugin_settings_page()
{
?>
    <div class="wrap">
        <h1>Cob Table Api</h1>

        <form method="post" action="options.php">
            <?php settings_fields('plugin-settings-group'); ?>
            <?php do_settings_sections('plugin-settings-group'); ?>
            <div class="form-table">
                <div class="row my-2">
                    <p class="col-3 p-0 ml-2 mb-0 text-right align-self-center">Api URL</p>
                    <input class="col-5 mr-3" type="url" name="api_url" value="<?php echo esc_attr(get_option('api_url')); ?>" />
                    <button type="button" class="btn btn-outline-success" onclick="fetchFunction()">recieve</button>
                </div>

           

                <p class="col-3 p-0 ml-2 mb-0 text-right align-self-center">لسیت رمز ارزهای دریافت شده از سرویس</p>

                <div id='tableLoc' class="row my-2">

                    <table id='apiTable' class="table col-12"></table>
                </div>
                <div class="row my-2">
                    <p class="col-3 p-0 ml-2 mb-0 text-right align-self-center">فرم نمایش جدول</p>
                    <select name='mySelect' class="form-select" aria-label="Default select example">
                        <option selected>انتخاب کنید</option>
                        <option value="all" <?php if (esc_attr(get_option('mySelect')) === 'all') { ?> selected <?php }; ?>>نمایش همه ردیف ها</option>
                        <option value="paggini" <?php if (esc_attr(get_option('mySelect')) === 'paggini') { ?> selected <?php }; ?>>صفحه بندی</option>
                        <option value="scroll" <?php if (esc_attr(get_option('mySelect')) === 'scroll') { ?> selected <?php }; ?>>نمایش با نوار پیمایش</option>
                    </select>
                    <?php

                    ?>
                </div>
                <div class="row my-2">
                    <p class="col-3 p-0 ml-2 mb-0 text-right align-self-center">Time Intervel</p>
                    <input class='col-3 mr-3' type="number" name="api_time_intervel" value="<?php echo esc_attr(get_option('api_time_intervel')); ?>" />
                    <label for="api_time_intervel" class="m-0 align-self-center">ثانیه </label>
                </div>
                <div class="row my-2">
                    <p class="col-3 p-0 ml-2 mb-0 text-right align-self-center">کلاس های bootstrap جدول</p>
                    <input class='col-5 ' type="text" name="table_classes" style="width: 60%;" value="<?php echo esc_attr(get_option('table_classes')); ?>" />
                    <label for="table_classes" class="ml-2 align-self-center"> با فاصله از هم جدا کنید </label>
                </div>
                <div class="row my-2">
                    <p class="col-3 p-0 ml-2 mb-0 text-right align-self-center">نماش striped جدول</p>
                    <input type="checkbox" name="table_striped_class" value='table-striped' <?php if (esc_attr(get_option('table_striped_class')) === 'table-striped') { ?> checked <?php }; ?> />
                </div>
                <div class="row my-2">
                    <p class="col-3 p-0 ml-2 mb-0 text-right align-self-center">نماش hover جدول</p>
                    <input type="checkbox" name="table_hover_class" value='table-hover' <?php if (esc_attr(get_option('table_hover_class')) === 'table-hover') { ?> checked <?php }; ?> />
                </div>
                <div class="row my-2">
                    <p class="col-3 p-0 ml-2 mb-0 text-right align-self-center">کلاس های bootstrap سربرگ جدول</p>
                    <input class="col-5" type="text" name="table_head_classes" style="width: 60%;" value="<?php echo esc_attr(get_option('table_head_classes')); ?>" />
                    <label for="table_head_classes" class="ml-2"> با فاصله از هم جدا کنید </label>
                </div>
                <div class="row my-2">
                    <p class="col-3 p-0 ml-2 mb-0 text-right align-self-center">کلاس های bootstrap خانه های جدول</p>
                    <input class="col-5" type="text" name="table_td_classes" style="width: 60%;" value="<?php echo esc_attr(get_option('table_td_classes')); ?>" />
                    <label for="table_td_classes" class="ml-2"> با فاصله از هم جدا کنید </label>
                </div>
                <div class="row my-2">
                    <p class="col-3 p-0 ml-2 mb-0 text-right align-self-center">shortcode</p>
                    <input type="text" name="table_shortcode" value="[cob_table_shortcode]" readonly />
                </div>
            </div>
            <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e('ذخیره'); ?>" />
        </form>
    </div>
<?php }



//shortkey
function tbare_wordpress_plugin_demo($atts)
{
    $Content =   "<div id='cob_table_plugin' class='mx-auto' ></div>";
    return $Content;
}
add_shortcode('cob_table_shortcode', 'tbare_wordpress_plugin_demo');
