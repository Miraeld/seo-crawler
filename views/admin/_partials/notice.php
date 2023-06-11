<?php //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<div class="notice notice-<?php echo $notice->get_status(); ?> is-dismissible">
	<p><?php echo esc_html( $notice->get_message() ); ?></p>
</div>
