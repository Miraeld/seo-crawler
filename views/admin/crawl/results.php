<div class="wrap">
	<h2>Latest Crawl Results</h2>

<?php if ( ! empty( $results ) ) : // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- this is not a global variable. ?>
	<ul>
	<?php foreach ( $results as $result ) : // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- this is not a global variable. ?>
			<li><p><?php echo esc_html( $result->get_url() ); ?> <span class="muted">- <?php echo esc_html( $result->get_creation_date()->format( 'Y-m-d H:i:s' ) ); ?></span></p></li>
	<?php endforeach; ?>
	</ul>
<?php else : ?>
	<p>There isn't any results yet. Try to trigger a crawl first.</p>
<?php endif; ?>
</div>
