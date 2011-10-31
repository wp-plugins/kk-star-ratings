<?php
// LAYOUT FOR THE SETTINGS/OPTIONS PAGE
?>
<div class="wrap">
	<?php 
	    /* ************************
		      SETTINGS - starts
		************************** */
	?>
	
    <?php screen_icon(); ?>
	<form action="options.php" method="post" id=<?php echo $this->plugin_id; ?>"_options_form" name=<?php echo $this->plugin_id; ?>"_options_form">
	<?php settings_fields($this->plugin_id.'_options'); ?>
    <h2>kk Star Ratings &raquo; Settings</h2>
    <table width="697" class="widefat" style="width:600px;">
		<thead>
		   <tr>
		     <th width="8">#</th>
		     <th width="69">Label</th>
			 <th width="604">Value</th>
		   </tr>
		</thead>
		<tfoot>
		   <tr>
		     <th>#</th>
		     <th>Label</th>
			 <th>Value</th>
		   </tr>
		</tfoot>
		<tbody>
		   <tr>
			 <td>1</td>
			 <td><label for="<?php echo $this->plugin_id; ?>[enable]">Enable</label></td>
			 <td><input type="checkbox" name="<?php echo $this->plugin_id; ?>[enable]" value="1" <?php echo $this->options['enable'] ? "checked='checked'" : ""; ?> /></td>
		   </tr>
		   <tr>
			 <td>2</td>
			 <td>Where do you need the ratings</td>
			 <td>
				 <input type="checkbox" name="<?php echo $this->plugin_id; ?>[show_in_home]" value="1" <?php echo $this->options['show_in_home'] ? "checked='checked'" : ""; ?> /> Home
				  (Some themes may not be appropriate for this option)
				 <br />
				 <input type="checkbox" name="<?php echo $this->plugin_id; ?>[show_in_archives]" value="1" <?php echo $this->options['show_in_archives'] ? "checked='checked'" : ""; ?> /> Archives
				  (Some themes may not be appropriate for this option)
				 <br />
				 <input type="checkbox" name="<?php echo $this->plugin_id; ?>[show_in_posts]" value="1" <?php echo $this->options['show_in_posts'] ? "checked='checked'" : ""; ?> /> Posts
				 <br />
				 <input type="checkbox" name="<?php echo $this->plugin_id; ?>[show_in_pages]" value="1" <?php echo $this->options['show_in_pages'] ? "checked='checked'" : ""; ?> /> Pages
				 <br />
				 <p>
				    <strong>NOTE</strong> : For manual, please use shortcode <span style="color:#06F;">[kkratings]</span>
					<br />in any post or page you need the ratings.
                    <br /><br />
                    <strong>For use in theme files:</strong>
                    <br /> <span style="color:#F60;">&lt;?php if(function_exists('kk_star_ratings')) : echo kk_star_ratings($pid); endif; ?&gt;</span>
                    <br />Where $pid is the post of the id
					<br /><br />
                    <strong>Get top rated posts as array of objects:</strong>
                    <br /> <span style="color:#F60;">&lt;?php if(function_exists('kk_star_ratings_get')) : $top_rated_posts  = kk_star_ratings_get($total); endif; ?&gt;</span>
                    <br />Where $total is the limit (int)
					<br />$top_rated_posts will contain an array of objects, each containing an ID and ratings.
                    <br />
                    <strong>Example Usage:</strong>
                    <pre>
                     foreach($top_rated_posts as $post)
                     {
                         // you get $post->ID and $post->ratings
                         // Do anything with it like get_post($post->ID)
                         // ...
                     }
                    </pre>
                    <br />
				 </p>
			 </td>
		   </tr>
		   <tr>
			 <td>3</td>
			 <td><label for="<?php echo $this->plugin_id; ?>[unique]">Unique Voting (Based on IP)</label></td>
			 <td><input type="checkbox" name="<?php echo $this->plugin_id; ?>[unique]" value="1" <?php echo $this->options['unique'] ? "checked='checked'" : ""; ?> /></td>
		   </tr>
		   <tr>
			 <td>4</td>
			 <td><label for="<?php echo $this->plugin_id; ?>[position]">Placement</label></td>
			 <td>
			     <select name="<?php echo $this->plugin_id; ?>[position]">
				    <option value="top-left" <?php echo !strcmp($this->options['position'], 'top-left') ? "selected='selected'" : ""; ?>>Top Left</option>
					<option value="top-right" <?php echo !strcmp($this->options['position'], 'top-right') ? "selected='selected'" : ""; ?>>Top Right</option>
					<option value="bottom-left" <?php echo !strcmp($this->options['position'], 'bottom-left') ? "selected='selected'" : ""; ?>>Bottom Left</option>
					<option value="bottom-right" <?php echo !strcmp($this->options['position'], 'bottom-right') ? "selected='selected'" : ""; ?>>Bottom Right</option>
				 </select>
			 </td>
		   </tr>
		   <tr>
			 <td>5</td>
			 <td><label for="<?php echo $this->plugin_id; ?>[legend]">Description</label></td>
			 <td>
			     <input type="text" name="<?php echo $this->plugin_id; ?>[legend]" value="<?php echo $this->options['legend']; ?>" size="50" />
				 <p>
				 <strong>[total]</strong> : Total amount of casted votes
				 <br />
				 <strong>[per]</strong> &nbsp;&nbsp;: Rating Percentage
				 <br />
				 <strong>[avg]</strong> &nbsp;: Rating Average
				 </p>
		     </td>
		   </tr>
		   <tr>
			 <td>6</td>
			 <td><label for="<?php echo $this->plugin_id; ?>[init_msg]">Initial Message</label></td>
			 <td>
			     <input type="text" name="<?php echo $this->plugin_id; ?>[init_msg]" value="<?php echo $this->options['init_msg']; ?>" size="50" />
		     </td>
		   </tr>
		   <tr>
			 <td>7</td>
			 <td><label for="<?php echo $this->plugin_id; ?>[clear]">Clear Line</label></td>
			 <td>
			     <input type="checkbox" name="<?php echo $this->plugin_id; ?>[clear]" value="1" <?php echo $this->options['clear'] ? "checked='checked'" : ""; ?> />
		     </td>
		   </tr>
		</tbody>
	</table>
	<p><input type="submit" name="submit" value="Save Settings" class="button" /></p>
	</form>
	<form action="" method="POST">
        <?php
		    if(strcmp($_POST['kkratings-flush'],'Flush Ratings'))
			{
				wp_nonce_field('kkratings_flush','kkratings-flush-nonce');
		?>
            <p>
                <strong>RESET RATINGS:</strong> Enter id(s) of posts/pages (comma seperated) and click the 'Flush Ratings' button to reset its ratings
            </p>
            <p>
            <input name="kkratings_input" type="text" value="" size="10" />
			<input type="submit" value="Flush Ratings" name="kkratings-flush" class="button" /> 
			<strong>NOTE:</strong> To reset all votings occured throughout the site, leave the input field empty. 
            </p>
		<?php
			}
		?>
    </form>
	<p>Donations to this plugin will always be appreciated. It is a way of saying thanks!</p>
    <p>
	    
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="EHPTKTC2TT4QC">
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
	</p>
    <?php 
	    /* ************************
		      SETTINGS - ends
		************************** */
	?>
</div>