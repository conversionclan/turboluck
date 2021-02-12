<?php // The default template for displaying content ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php 
			
	bento_post_title();
	
	star_rating();
	
	bento_post_content();
	
	?>
		
</article>