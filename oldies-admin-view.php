<div class="wrap">
<h2>Old and not used post types:</h2>

<h4>Warning: clicking "Delete" would remove all post entries for that post type permanently from the database!</h4>

<?php if( ! empty( $other_post_types ) && is_array( $other_post_types ) ) { 
	$wp_nonce = wp_nonce_field('delete-oldies', 'oldie-nonce');
	?>
	<?php foreach( $other_post_types as $other_post_type ) { ?>
	<div class="cpt-entry">
		<form action="" method="POST">
			<div class="cpt-left"><?php echo $other_post_type->post_type; ?></div>
			<div><input type="submit" name="delete" value="Delete" /></div>
			<input type="hidden" name="cpt_post_type" value="<?php echo esc_attr($other_post_type->post_type); ?>" />
			<?php echo $wp_nonce; ?>
		</form>
	</div>					
<?php 	  } 
	  } ?>
</div>
	  