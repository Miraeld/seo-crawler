<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<p>Results for the SEO Crawler plugin.</p>

	<form method="post" action="">
		<?php wp_nonce_field( 'seo_crawler_nonce', 'seo_crawler_nonce' ); ?>
		<button type="submit" name="crawl_button" class="button button-primary">Trigger Crawl</button>
		<button type="submit" name="results_button" class="button button-secondary">View Latest Results</button>
	</form>


</div>
