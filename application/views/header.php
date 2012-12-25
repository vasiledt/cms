<?php
if (empty($hideMenu)) {
	$rClass = $this->router->fetch_class();
	$rMethod = $this->router->fetch_method();
?>

<div class="header">
	<ul class="main_menu">
		<?php if (empty($this->loggedUser)) { ?>
			<li>
				<?php $more = ' class="active"'; ?>
				<a href="<?php echo site_url('admin/index'); ?>"<?php echo $more; ?>>Login</a>
			</li>
		<?php } else { ?>
			<li>
				<?php $more = (($rClass == 'admin') && (stripos('#index##viewFactor##results##plot#', '#'.$rMethod.'#') !== FALSE)) ? ' class="active"' : ''; ?>
				<a href="<?php echo site_url('admin/index'); ?>"<?php echo $more; ?>>Home</a>
			</li>
			<?php
			$menuItems = array(
				array('method' => 'questions'),
				array('method' => 'groups'),
				array('method' => 'companies'),
				array('method' => 'factors'),
				array('method' => 'formulas')
			);
			foreach ($menuItems as $item) {
				$controller = (isset($item['controller'])) ? $item['controller'] : 'admin';
				$method = $item['method'];
				$title = (isset($item['title'])) ? $item['title'] : ucfirst($method);
				$more = (($rClass == $controller) && ($rMethod == $method)) ? ' class="active"' : '';
				if (checkAccessRights($this->loggedUser['role'], $controller, $method)) {
				?>
					<li>
						<a href="<?php echo site_url($controller.'/'.$method); ?>"<?php echo $more; ?>>
							<?php echo $title; ?>
						</a>
					</li>
				<?php
				}
			}
			?>
			<li>
				<?php $more = (($rClass == 'admin') && ($rMethod == 'logout')) ? ' class="active"' : ''; ?>
				<a href="<?php echo site_url('admin/logout'); ?>"<?php echo $more; ?>>Logout</a>
			</li>
		<?php } ?>
		<li class="clear"></li>
	</ul>
</div>

<?php 
}
?>