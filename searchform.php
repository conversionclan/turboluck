<?php // Display the custom search form ?>

<form role="search" method="get" id="searchform" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="search-form-wrap">
		<input type="text" value="<?php echo get_search_query(); ?>" name="s" id="s" class="search-form-input" placeholder="<?php esc_attr_e( 'Search', 'default' ); ?>..">
		<button type="submit" id="searchsubmit" class="search-submit"><i class="fa fa-search"></i></button>
    </div>
</form>