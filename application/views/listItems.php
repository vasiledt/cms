<div class="content">
	<h2 class="content_title"><?php echo $title; ?></h2>
	<div class="content_body<?php if(!empty($cssClass)) echo ' '.$cssClass; ?>">
		<?php
		if (isset($addButton)) {
			echo '<div class="content_submenu">'.$addButton.'</div>';
		}
		?>
		<table id="item_list"></table>
		<div id="item_pager"></div>
		<div id="grid_id"></div>
	</div>
</div>

<div class="dialog-confirm dhidden" id="delete_item" title="Confirm" >
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0px 5px 0px 0px;"></span>Are you sure you want to delete this item?</p>
</div>

<div class="dialog-add-item dhidden" id="add_item" title="Add <?php echo $item; ?>"></div>