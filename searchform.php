<?php
/**
 * The Search Form
 */
?>

    <form class="email-signup" name="searchform" method="get" action="<?php echo home_url(); ?>">
		<div>
        
        <div class="enteryouremail">
			<input type="text" id="s" name="s" value="Search..." onfocus="if(this.value == 'Search...'){this.value = '';}" />
        </div>
        <div class="submitemail">
			<input type="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'shaken'); ?>" />
        </div>
		</div>
    </form>