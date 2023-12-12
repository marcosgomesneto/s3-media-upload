<?php
/**
 * @var AwsServicesKit\Admin\Admin $this
 * @var AwsServicesKit\Admin\Settings\Settings $current_tab
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="wrap">
	<h1>
		Configurations
	</h1>
	<nav class="nav-tab-wrapper">
		<?php
		foreach ( $this->get_tabs() as $tab ) {
			$current_class = $current_tab->getTab() == $tab->getTab() ? 'nav-tab nav-tab-active' : 'nav-tab';
			echo sprintf( '<a href="%s" class="%s" aria-current="%s">%s</a>', add_query_arg( 'tab', $tab->getTab(), $baseUrl ), $current_class, $tab->getTab(), $tab->getTitle() );
		}
		?>
	</nav>
</div>