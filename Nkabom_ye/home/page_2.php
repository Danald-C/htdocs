<?php
	
	require_once("_layouts/layout_1.php");
	require_once("_layouts/layout_2.php");
	require_once("page_funcs.php");
	
	page_header();
		
		
	
?>
	<div id="page-2" class="page-wrapper">
		<?php
			page_layout(2);
		?>
	</div>
<?php
	page_footer();
?>