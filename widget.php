<?php

// Make sure class does not already exist (Playing safe).
if(!class_exists('kk_Ratings_Widget') && !function_exists('kk_ratings_widget_init')) :

class kk_Ratings_Widget extends WP_Widget
{
	// Runs when OBJECT DECLARED (Instanciated)
	public function kk_Ratings_Widget()
	{
		$widget_options = array(
		'classname' => 'kk-star-ratings-widget',
		'description' => 'Show top rated posts'
		);
		parent::WP_Widget('kk_Ratings_Widget', 'kk Star Ratings', $widget_options);
	}
	// Outputs USER INTERFACE
	public function widget($args, $instance)
	{
		extract( $args, EXTR_SKIP );
		$title = ( !empty($instance['title']) ) ? $instance['title'] : 'Top Posts';
		$total = ( !empty($instance['noofposts']) ) ? $instance['noofposts'] : '5';
		$sr = ($instance['showrating']) ? true : false;

		echo $before_widget;
		echo $before_title . $title . $after_title;
		
		// OUTPUT starts
	    global $wpdb;
		$table = $wpdb->prefix . 'postmeta';
		$posts = $wpdb->get_results("SELECT a.ID, a.post_title, b.meta_value AS 'ratings' FROM " . $wpdb->posts . " a, $table b WHERE a.post_status='publish' AND a.ID=b.post_id AND b.meta_key='_kk_ratings_avg' ORDER BY b.meta_value DESC LIMIT $total");
		echo '<ul>';
		foreach ($posts as $post)
		{
		   echo "<li><a href='".get_permalink($post->ID)."'>".$post->post_title."</a>";
		   if($sr)
		   {
			   echo " <span style='font-size:10px;'>(".$post->ratings."/5)</span>";
		   }
		   echo "</li>";
		}
		echo '</ul>';
		// OUTPUT ends
		
		echo $after_widget;
	}
	// Updates OPTIONS
	/*
	public function update()
	{
		
	}
	*/
	// The option FORM
	public function form( $instance )
	{
		?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:
            <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr(!empty($instance['title'])?$instance['title']: 'Top Posts'); ?>" /></label>
        </p> 
        <p>
            <label for="<?php echo $this->get_field_id('noofposts'); ?>">No of Posts:
            <input id="<?php echo $this->get_field_id('noofposts'); ?>" name="<?php echo $this->get_field_name('noofposts'); ?>" type="text" value="<?php echo esc_attr(!empty($instance['noofposts'])?$instance['noofposts']: '5'); ?>" size="3" /></label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('showrating'); ?>">Show Average?:
            <select id="<?php echo $this->get_field_id('showrating'); ?>" name="<?php echo $this->get_field_name('showrating'); ?>">
                <option value="0" <?php if(!esc_attr($instance['showrating'])){echo "selected='selected'";} ?>>No</option>
                <option value="1" <?php if(esc_attr($instance['showrating'])){echo "selected='selected'";} ?>>Yes</option>
            </select>
            </label>
        </p>
        <?php
	}
}

function kk_ratings_widget_init()
{
	register_widget('kk_Ratings_Widget');
}
add_action('widgets_init', 'kk_ratings_widget_init');


endif;
?>