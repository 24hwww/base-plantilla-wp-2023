<!DOCTYPE html>
<html <?php language_attributes(); ?>><head><?php wp_head(); ?></head>
<body <?php body_class(); ?>>

<header class="header-syf">
	<div class="header-syf-one">
		<div class="container">
			<div class="header-syf-one-container">
				<div class="col-md-4 col-sm-2 col-xs-6 hidden-xs">
				</div>
				<div class="col-md-4 col-sm-8 col-xs-12">
					<a class="header-syf-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img src="<?php echo get_template_directory_uri(); ?>/inc/img/logo_micanton.png" class="img-responsive" width="200" height="100" alt=""/>
					<h1>Sabor y Familia</h1>
					</a>
				</div>
				<div class="col-md-4 col-sm-2 col-xs-6 hidden-xs">
				</div>
			</div>
		</div>
	</div>
	<div class="header-syf-two">
	<div class="container vertical-align flex-wrap">
		<div class="col-md-4 col-sm-6 col-xs-12 hidden-xs hidden-sm">
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<?php wp_nav_menu(['theme_location' => 'primary','depth' => 2,'container' => 'nav','container_class' => 'nav-menu','menu_class' => '']); ?>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<?php wp_nav_menu(['theme_location' => 'links-social','depth' => 2,'container' => 'nav','container_class' => 'nav-links-social','menu_class' => '']); ?>		
		</div>		
	</div>
	</div>	
</header>
