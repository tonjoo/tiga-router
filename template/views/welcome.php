<?php get_header(); ?>

<h1>Welcome to Tiga Router</h1>

<h4>Sample: Get 10 latest posts using query builder:</h4>

<ul>
	<?php foreach ( $posts as $post ) : ?>
		<li><a href="<?php echo get_permalink( $post->ID ); ?>"><?php echo $post->post_title; ?></a></li>
	<?php endforeach; ?>
</ul>

<h4>You can edit this page or create your own routes by editing files on this path:</h4>

<pre>
<?php echo str_replace( WP_CONTENT_DIR, '/wp-content', get_stylesheet_directory() ); ?>/app
</pre>

<?php get_footer(); ?>