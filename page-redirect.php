<?php // Template name: Redirect 1xBet RU ?>

<?php get_header(); ?>

<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/includes/1x_dom/api.js'></script>


<div class="bnt-container">
    
    <div class="content content-page">
        <main class="site-main">
        
            <?php 
            // Start the Loop
            if ( have_posts() ) { 
                while ( have_posts() ) { 
                    the_post(); 
                    // Include the page content
                    the_content();   	
					
					// If comments are open or the page has at least one comment, load the comments template.
                    if ( comments_open() || get_comments_number() ) {
                        comments_template();
                    }
					
                // End the Loop
                } 
            }
            ?>
    
        </main>
    </div>
        
</div>

<?php get_footer(); ?>