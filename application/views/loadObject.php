<form method="post" name="loadObject" id="loadObject" action="<?php echo $formAction; ?>">
	<div class="row dnone">	
		<h3 class="title">Update Object</h3>
	</div>
	<div class="row aleft">
		<label>Title</label>
		<div class="cell"><input name="title" id="title" value="<?php echo $title; ?>" type="text" readonly="readonly" /></div>
	</div>
	<div class="row aleft">
		<label>Description</label>
		<div class="cell"><textarea name="description" id="description" class="big" rows="10"><?php echo $description; ?></textarea></div>
	</div>
	<div class="row">
		<label>Status</label>
		<select id="status" name="status">
			<option value="0"<?php if (!$status) echo ' selected="selected"'; ?>>inactive</option>
			<option value="1"<?php if ($status) echo ' selected="selected"'; ?>>active</option>
		</select>
	</div>
	<div class="row aright">	
		<input name="submit" id="submit" value="submit" type="submit" />
		<input name="cancel" id="cancel" value="cancel" type="button" />
	</div>
</form>