<?php // The template part for displaying the footer of the website ?>

            </div><!-- .site-content -->
			
			<?php // Ensure that the floats are cleared for the side-menu website layout ?>
			<div class="after-content">
			</div>
            
            <footer class="site-footer">
				
				<?php // Footer widget area ?>
				<?php if ( tl_lang_switch() != '' ) { ?>
				<div class="widget-area sidebar-footer clear">
					<div class="bnt-container">
						
						<div class="container" id="container-footer-languages">
							<div class="footer-languages">
								<?php echo tl_lang_switch(); ?>
							</div>
						</div>
						
					</div>
				</div>
				<?php } ?>
                    
				<?php // Footer menu and copyright area ?>
                <div class="bottom-footer clear">
                	<div class="bnt-container">
					
						<div class="footer-menu">
							<div id="nav-footer" class="nav">
								<nav>
									<ul class="footer-menu-container">
										<?php 
										$fm = array(
											'Contact' => 'contact',
											'Terms and Conditions' => 'terms',
											'Privacy Policy' => 'privacy'
										);
										foreach( $fm as $k => $v ) {
											echo '<li class="footer-menu-item"><a href="/'.$v.'">'.$k.'</a></li>';
										}
										?>
									</ul>
								</nav>
							</div>
						</div>
						
                        <div class="footer-copyright">
							<div class="footer-copyright-claim">&#169; <?php echo date_i18n( 'Y' ); ?> Turboluck</div>
							<div class="footer-responsible-gambling">
								<div class="eighteen">18+</div> <a href="https://www.begambleaware.org/" target="_blank">Gamble responsibly</a>
							</div>
						</div>
						
                    </div>
                </div>
                
            </footer><!-- .site-footer -->

		</div><!-- .site-wrapper -->
		
		<?php // Tag for including javascript in the footer; should always be the last element inside the <body> section ?>
		<?php wp_footer(); ?>
		
	</body>
	    
</html>