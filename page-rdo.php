<?php // Template name: Roll Dice ?>

<?php get_header(); ?>

<div class="bnt-container">
    
    <div class="content content-page">
        <main class="site-main">
		
			<div class="rdo">
			
				<div class="rdoleft">
			
					<h1 style="font-size: 2em; margin: 0 0 40px 0;"><?php the_title(); ?></h1>
					
					<!-- <a style="border-bottom: 2px solid; text-decoration: none !important; margin: 40px 0; display: inline-block; font-size: 21px; color: #00b285; font-weight: bold; font-style: italic;" href="https://record.commissionkings.ag/_KVfqxF5PV0CO--BLE9QE82Nd7ZgqdRLk/1/?payload=roll-dice-text-link" target="_blank" rel="noopener noreferrer">Roll Dice for real money - try Craps 🎲🎲</a> <span style="font-size: 30px; color: #00b285;">→</span> -->
				
					<div class="rdotool">
						<div class="rdotool-name">
							Number of sides:
						</div>
						<div class="rdotool-select">
							<select name="rdoselect" id="rdoselect">
								<option value="2">2 (two)</option>
								<option value="3">3 (three)</option>
								<option value="4">4 (four), tetrahedron, d4)</option>
								<option value="5">5 (five)</option>
								<option selected="selected" value="6">6 (six, cubical die, d6)</option>
								<option value="7">7 (seven)</option>
								<option value="8">8 (eight, octahedron, d8)</option>
								<option value="9">9 (nine)  </option>
								<option value="10">10 (ten, pentagonal trapezohedron, d10)</option>
								<option value="11">11 (eleven)</option>
								<option value="12">12 (twelve, dodecahedron, d12)</option>
								<option value="13">13 (thirteen)</option>
								<option value="18">18 (eighteen)</option>
								<option value="19">19 (nineteen)</option>
								<option value="20">20 (twenty, icosahedron, d20)</option>
								<option value="30">30 (thirty, triantakohedron, d30)</option>
								<option value="100">100 (one hundered)</option>
							</select>
						</div>
						<!--
						<div class="rdotool-name">
							Number of dice to roll:
						</div>
						<div class="rdotool-select rdotool-dice">
							<input type="text" value="1">
						</div>
						-->
						<div class="rdotool-name">
							Number of rolls:
						</div>
						<div class="rdotool-select rdotool-rolls">
							<input type="text" value="1">
						</div>
					</div>
					
					<button class="rdo-roll">
						Roll dice!
					</button>
				
				</div>
				
				<div class="rdoright">
					
					<div class="rdoresult">
						your results will appear here
					</div>
					
					<div class="rdobanner">
						<a href="https://turboluck.com/go/freebitcoin/rolldiceonline" target="_blank">
						<!--
						<a href="https://record.commissionkings.ag/_KVfqxF5PV0CO--BLE9QE82Nd7ZgqdRLk/1/?payload=roll-dice-banner-custom" target="_blank">
						-->
							<img src="<?php echo get_template_directory_uri().'/images/banner-rdo.png'; ?>">
						</a>
					</div>
					
				</div>
				
			</div>
			
			<div class="rdodesc">
				<?php the_content(); ?>
			</div>
    
        </main>
    </div>
        
</div>

<?php get_footer(); ?>