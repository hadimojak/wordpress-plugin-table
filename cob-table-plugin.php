<?php

/**
 * Plugin Name: cob table plugin
 * Description: This is the very first plugin I ever created.
 * Version: 1.0
 * Author: hadia arbabi
 **/


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
        'tableClasses' => esc_attr(get_option('table_classes'))
    );
    wp_localize_script('plugin_script', 'scriptParams', $script_params);
}
add_action('wp_enqueue_scripts', 'enqueue_scripts');

add_action('admin_menu', 'plugin_create_menu');
function plugin_create_menu()
{
    //create new top-level menu
    add_menu_page('Plugin Settings', 'Cool Settings', 'administrator', __FILE__, 'plugin_settings_page', 'dashicons-editor-table');

    //call register settings function
    add_action('admin_init', 'register_plugin_settings');
}
function register_plugin_settings()
{
    //register our settings
    register_setting('plugin-settings-group', 'api_url');
    register_setting('plugin-settings-group', 'api_time_intervel');
    register_setting('plugin-settings-group', 'table_classes');
}
function plugin_settings_page()
{
?>
    <div class="wrap">
        <h1>Cob Table Api</h1>

        <form method="post" action="options.php">
            <?php settings_fields('plugin-settings-group'); ?>
            <?php do_settings_sections('plugin-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Api URL</th>
                    <td><input type="url" name="api_url" value="<?php echo esc_attr(get_option('api_url')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Time Intervel</th>
                    <td><input type="number" name="api_time_intervel" value="<?php echo esc_attr(get_option('api_time_intervel')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Classes</th>
                    <td><input type="text" name="table_classes" value="<?php echo esc_attr(get_option('table_classes')); ?>" /> <label for="table_classes">با فاصله از هم جدا کنید</label>
                    </td>
                </tr>
            </table>
            <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e('ذخیره'); ?>" />
        </form>
    </div>
<?php }
//shortkey
function tbare_wordpress_plugin_demo($atts)
{
    $Content =   "<div id='cob_table_plugin' ></div>";
    return $Content;
}
add_shortcode('cob_table_shortcode', 'tbare_wordpress_plugin_demo');
