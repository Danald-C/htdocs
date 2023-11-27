<?php
	
	require_once("_layouts/layout_1.php");
	require_once("_layouts/layout_2.php");
	require_once("page_funcs.php");
	
	page_header();
	
?>
	<div id="page-1" class="page-wrapper">
		<?php
			page_layout();
		?>
	</div>
<?php
	page_footer();
?>