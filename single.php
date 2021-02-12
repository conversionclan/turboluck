<?php // The default template for single posts ?>

<?php get_header(); ?>

<?php global $post; ?>

<div class="content content-post">
	<main class="site-main" role="main">

		<div class="review-header">
			<div class="review-title">
				<h1><?php echo do_shortcode( wp_kses( get_the_title(), array( 'br' => array() ) ) ); ?></h1>
			</div>
			<?php if ( get_post_meta( $post->ID, 'bento_afflink', true ) != '' ) { ?>
				<?php star_rating(); ?>
				<div class="review-visit">
					<a data-broker="<?php echo get_post_field( 'post_name', get_post() ); ?>" href="<?php echo get_post_meta( $post->ID, 'bento_afflink', true ); ?>" target="_blank">
						<?php _e( 'Visit Site', 'default' ); ?> <span class="visit-arrow">&rarr;</span>
					</a>
				</div>
			<?php } ?>
		</div>
			
		<div class="bnt-container">
			
			<div class="review-body entry-content">
				<?php the_content(); ?>
			</div>
			
			<?php echo do_shortcode('[mrp_rating_form]'); ?>
			
			<div class="review-comments">
				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</div>
			
		</div>

	</main>
</div>
    
<?php get_footer(); ?>

<?php if ( get_post_meta( $post->ID, 'bento_afflink', true ) != '' ) { ?>
	<div class="review-visit review-topbar">
		<?php 
		$afflink_topbar = get_post_meta( $post->ID, 'bento_afflink', true );
		if ( get_post_meta( $post->ID, 'bento_afflink_topbar', true ) != '' ) {
			$afflink_topbar = get_post_meta( $post->ID, 'bento_afflink_topbar', true );
		}
		?>
		<div class="review-topbar-title"><?php echo do_shortcode( wp_kses( get_the_title(), array( 'br' => array() ) ) ); ?></div>
		<a data-broker="<?php echo get_post_field( 'post_name', get_post() ); ?>" href="<?php echo $afflink_topbar; ?>" target="_blank">
			<?php _e( 'Visit Site', 'default' ); ?> <span class="visit-arrow">&rarr;</span>
		</a>
	</div>
<?php } ?>