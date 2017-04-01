<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

$theme = wp_get_theme();

$plugins = get_plugins();
$active_plugins = get_option('active_plugins', array());
$memory_limit = ini_get('memory_limit');
$memory_usage = round(memory_get_usage() / 1024 / 1024, 2);
$all_options = wp_load_alloptions();
$all_options_serialized = serialize($all_options);
$all_options_bytes = round(mb_strlen($all_options_serialized, '8bit') / 1024, 2);
$all_options_transients = get_transients_in_options($all_options);


global $wpdb;
//$wphpc_phpinfo=phpinfo();
//$wphpc_phpinfo=phpinfo();
$mysqlversion = $wpdb->get_var("select version();");

?>

<div id="sysinfo">
    <div class="wrap">


        <h2 class="title"><?php _e('SysInfo', 'sysinfo') ?></h2>

        <div class="clear"></div>

        <div class="section">
            <div class="header">
                <?php _e('System Information', 'sysinfo') ?>
            </div>
            <h4>Technology Level</h4>
<table>
    <tr>
        <td valign="top">

            <?php
            include ("technology.php");
            ?>

</td>
        <td valign="top">
            <?php
            include ("benchmark.php");
            ?>


        </td>
    </tr>
</table>
            <div class="inside">

                <textarea style="width: 900px; height: 990px;">
                    WordPress Version:      <?php echo get_bloginfo('version') . "\n"; ?>

                    PHP version 5.6 is GOOD, below 5.6 is TERRIBLE. PHP 7.0 or HHVM is GREAT.
                    PHP Version:            <?php echo PHP_VERSION . "\n"; ?>

                    You want to have an FPM or fcgi handler, everything else is rubbish. I think nginx says srv here, that's fine also (as I think that's pph-fpm). litespeed is another good choice.
                    PHP Handler:            <?php echo get_defined_constants()['PHP_SAPI']. "\n"; ?>

                    You need version 5.6 or above here
                    MySQL Version:          <?php  echo $mysqlversion . "\n"; ?>

                    Web Server:             <?php echo $_SERVER['SERVER_SOFTWARE'] . "\n"; ?>

                    If you regularly use URLs other than these, you might have some redirects slowing things down.
                    WordPress URL:          <?php echo get_bloginfo('wpurl') . "\n"; ?>
                    Home URL:               <?php echo get_bloginfo('url') . "\n"; ?>


                    Multi-Site Active:      <?php echo is_multisite() ? _e('Yes', 'sysinfo') . "\n" : _e('No', 'sysinfo') . "\n" ?>

                    PHP cURL Support:       <?php echo (function_exists('curl_init')) ? _e('Yes', 'sysinfo') . "\n" : _e('No', 'sysinfo') . "\n"; ?>
                    PHP GD Support:         <?php echo (function_exists('gd_info')) ? _e('Yes', 'sysinfo') . "\n" : _e('No', 'sysinfo') . "\n"; ?>
                    PHP Memory Limit:       <?php echo $memory_limit . "\n"; ?>
                    PHP Memory Usage:       <?php echo $memory_usage . "M (" . round($memory_usage / $memory_limit * 100, 0) . "%)\n"; ?>
                    PHP Post Max Size:      <?php echo ini_get('post_max_size') . "\n"; ?>
                    PHP Upload Max Size:    <?php echo ini_get('upload_max_filesize') . "\n"; ?>

                    WP Options Count:       <?php echo count($all_options) . "\n"; ?>
                    WP Options Size:        <?php echo $all_options_bytes . "kb\n" ?>
                    WP Options Transients:  <?php echo count($all_options_transients) . "\n"; ?>

                    WP_DEBUG:               <?php echo defined('WP_DEBUG') ? WP_DEBUG ? _e('Enabled', 'sysinfo') . "\n" : _e('Disabled', 'sysinfo') . "\n" : _e('Not set', 'sysinfo') . "\n" ?>
                    SCRIPT_DEBUG:           <?php echo defined('SCRIPT_DEBUG') ? SCRIPT_DEBUG ? _e('Enabled', 'sysinfo') . "\n" : _e('Disabled', 'sysinfo') . "\n" : _e('Not set', 'sysinfo') . "\n" ?>
                    SAVEQUERIES:            <?php echo defined('SAVEQUERIES') ? SAVEQUERIES ? _e('Enabled', 'sysinfo') . "\n" : _e('Disabled', 'sysinfo') . "\n" : _e('Not set', 'sysinfo') . "\n" ?>
                    AUTOSAVE_INTERVAL:      <?php echo defined('AUTOSAVE_INTERVAL') ? AUTOSAVE_INTERVAL ? AUTOSAVE_INTERVAL . "\n" : _e('Disabled', 'sysinfo') . "\n" : _e('Not set', 'sysinfo') . "\n" ?>
                    WP_POST_REVISIONS:      <?php echo defined('WP_POST_REVISIONS') ? WP_POST_REVISIONS ? WP_POST_REVISIONS . "\n" : _e('Disabled', 'sysinfo') . "\n" : _e('Not set', 'sysinfo') . "\n" ?>

                    Active Theme:
- <?php echo $theme->get('Name') ?> <?php echo $theme->get('Version') . "\n"; ?>
                    <?php echo $theme->get('ThemeURI') . "\n"; ?>
<?php


//print_r(get_defined_constants());
?>
                    Active Plugins:
                    <?php
                    foreach ($plugins as $plugin_path => $plugin) {
                        // Only show active plugins
                        if (in_array($plugin_path, $active_plugins)) {
                            echo '- ' . $plugin['Name'] . ' ' . $plugin['Version'] . "\n";

                            if (isset($plugin['PluginURI'])) {
                                echo '  ' . $plugin['PluginURI'] . "\n";
                            }

                            echo "\n";
                        }
                    }
                    ?>
				</textarea>
            </div>

        </div>
    </div>
</div>
with thanks to the original sysinfo plugin https://wordpress.org/plugins/sysinfo/

<?php
function get_transients_in_options($options)
{
    $transients = array();

    foreach ($options as $name => $value) {
        if (stristr($name, 'transient')) {
            $transients[$name] = $value;
        }
    }
}
?>

