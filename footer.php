<footer class="footer-syf">
<div class="container">
	<div class="col-md-4 col-sm-12 col-xs-12">
		<a class="header-syf-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<img src="<?php echo get_template_directory_uri(); ?>/inc/img/logo_micanton.png" class="img-responsive" width="200" height="100" alt=""/>
		</a>
	</div>
	<div class="col-md-4 col-sm-12 col-xs-12">
		<?php wp_nav_menu(['theme_location' => 'primary','depth' => 2,'container' => 'nav','container_class' => 'nav-menu','menu_class' => '']); ?>
	</div>
	<div class="col-md-4 col-sm-12 col-xs-12">
		<?php wp_nav_menu(['theme_location' => 'links-social','depth' => 2,'container' => 'nav','container_class' => 'nav-links-social','menu_class' => '']); ?>	
	</div>	
</div>
</footer>

<?php wp_footer(); ?>
</body>
</html> 