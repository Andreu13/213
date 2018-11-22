<script type="text/javascript">
	var __cart = 0;
	var __w = 0;

	CURR = "<?php  echo get_option("wpshop.currency");?>";	

	jQuery(document).ready(function()
	{
		if (window.Cart !== undefined)
		{
			window.__cart = new window.Cart("wpshop_minicart", "wpshop_cart");
		}
		if (window.wshop !== undefined)
		{
			window.__w = new window.wshop('',window.__cart,'');
		}
	});
</script>
