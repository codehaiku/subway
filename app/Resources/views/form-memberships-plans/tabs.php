<!--Plan Tabs-->
<ul class="subway-section-tabs" id="plan-tabs">
    <li>
        <a class="<?php echo $section == 'plan-information' ? 'active' : ''; ?>"
           data-section-target="plan-information" href="#">
            <span class="dashicons dashicons-info"></span>
			<?php esc_html_e( 'Information', 'subway' ); ?>
        </a>
    </li>
    <li><a class="<?php echo $section == 'plan-pricing' ? 'active' : ''; ?>"
           data-section-target="plan-pricing" href="#">
            <span class="dashicons dashicons-tag"></span>Pricing</a></li>
    <li><a class="<?php echo $section == 'plan-email' ? 'active' : ''; ?>"
           data-section-target="plan-email" href="#">
            <span class="dashicons dashicons-email"></span>Emails</a></li>
	<?php do_action( 'subway_plan_edit_list_tabs' ); ?>
</ul>
<!--/.Plan Tabs-->