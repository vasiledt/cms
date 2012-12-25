<?php if (isset($message)) { ?>
	<h2>Error!</h2>
	<div><?php echo $message; ?></div>
<?php } else { ?>
<form action="<?php echo $formAction; ?>" method="post" name="regUser" id="regUser">
	<div class="row">	
		<h2>User Registration</h2>
	</div>
	<div class="row">	
		<label>Username</label>
		<div class="cell"><input name="username" id="username" value="" type="text" /></div>
	</div>
	<div class="row">	
		<label>Company</label>
		<div class="cell">
			<input name="companyname" id="companyname" value="<?php echo $company; ?>" type="text" disabled="disabled" />
			<input name="id_company" id="id_company" type="hidden" value="<?php echo $id_company; ?>" />
		</div>
	</div>
	<div class="row">	
		<label>Email Address</label>
		<div class="cell"><input name="email" id="email" value="" type="text" /></div>
	</div>
	<div class="row">	
		<label>Password</label>
		<div class="cell"><input name="userpassword" id="userpassword" value="" type="password" /></div>
	</div>
	<div class="row">
		<input name="id" id="id" type="hidden" value="<?php echo $id; ?>" />
		<div class="cell"><input name="submit" id="submit" value="submit" type="submit" /></div>
	</div>
</form>
<?php } ?>