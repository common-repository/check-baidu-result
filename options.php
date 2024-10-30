<?php
function check_baidu_result_register_settings() {
	add_option('check_baidu_result_show_to', 'admin');
	add_option('check_baidu_result_get_name', 'check_google');
	register_setting('check_baidu_result_options', 'check_baidu_result_show_to');
	register_setting('check_baidu_result_options', 'check_baidu_result_get_name');
}
add_action('admin_init', 'check_baidu_result_register_settings');

function check_baidu_result_register_options_page() {
	add_options_page(__('Check Baidu Result Options Page', CHECK_BAIDU_RESULT_TEXT_DOMAIN), __('Check Baidu Result', CHECK_BAIDU_RESULT_TEXT_DOMAIN), 'manage_options', CHECK_BAIDU_RESULT_TEXT_DOMAIN.'-options', 'check_baidu_result_options_page');
}
add_action('admin_menu', 'check_baidu_result_register_options_page');

function check_baidu_result_get_select_option($select_option_name, $select_option_value, $select_option_id){
	?>
	<select name="<?php echo $select_option_name; ?>" id="<?php echo $select_option_name; ?>">
		<?php
		for($num = 0; $num < count($select_option_id); $num++){
			$select_option_value_each = $select_option_value[$num];
			$select_option_id_each = $select_option_id[$num];
			?>
			<option value="<?php echo $select_option_id_each; ?>"<?php if (get_option($select_option_name) == $select_option_id_each){?> selected="selected"<?php } ?>>
				<?php echo $select_option_value_each; ?>
			</option>
		<?php } ?>
	</select>
	<?php
}

function check_baidu_result_options_page() {
?>
<div class="wrap">
	<h2><?php _e("Check Baidu Result Options Page", CHECK_BAIDU_RESULT_TEXT_DOMAIN); ?></h2>
	<form method="post" action="options.php">
		<?php settings_fields('check_baidu_result_options'); ?>
		<h3><?php _e("General Options", CHECK_BAIDU_RESULT_TEXT_DOMAIN); ?></h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="check_baidu_result_show_to"><?php _e("Who can check Google Result?", CHECK_BAIDU_RESULT_TEXT_DOMAIN); ?></label></th>
					<td>
						<?php check_baidu_result_get_select_option("check_baidu_result_show_to", array(__('Admin Only', CHECK_BAIDU_RESULT_TEXT_DOMAIN), __('All Visitors', CHECK_BAIDU_RESULT_TEXT_DOMAIN)), array('admin', 'visitor')); ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="check_baidu_result_get_name"><?php _e("Check Baidu Result in Post's URL: ", CHECK_BAIDU_RESULT_TEXT_DOMAIN); ?></label></th>
					<td>
						<?php echo get_option("siteurl"); ?>/my-post/?<input type="text" name="check_baidu_result_get_name" id="check_baidu_result_get_name" value="<?php echo get_option('check_baidu_result_get_name'); ?>" />
						<p><?php _e("(Leave empty for just simply visit the post.)", CHECK_BAIDU_RESULT_TEXT_DOMAIN); ?></p>
					</td>
				</tr>
			</table>
		<?php submit_button(); ?>
	</form>
</div>
<?php
}
?>