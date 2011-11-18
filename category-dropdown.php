<?php
/*
Plugin Name: Category Dropdowns
Plugin URI: http://48Web.com
Description: Category dropdown widget
Author: 48Web
Version: 1
Author URI: http://48web.com
*/
 
class CategoryDropdownWidget extends WP_Widget {
  function CategoryDropdownWidget() {
    parent::WP_Widget( false, $name = 'Category Dropdown Widget', array('description' => 'Creates dropdown lists of all your categories') );
  }

  function widget( $args, $instance ) {
    extract( $args );
    $title = apply_filters( 'widget_title', $instance['title'] );
    ?>

    <?php
		echo $before_widget;
	
		if ($title) {
			echo $before_title . $title . $after_title;
		}
    	
		$args = array(
			'depth'	=>	'1'
		);
		$categories = get_categories($args);
		foreach ($categories as $cat) {
			// check if category has child
			if (get_category_children($cat->term_id) != "") {
				echo '<span class="parent-category-title">' .$cat->name .'</span><br />';
				wp_dropdown_categories(array('id' => $cat->term_id, 'class' => 'child-cat-select', 'child_of' => $cat->term_id, 'show_option_none' => 'Select ' .$cat->name, 'hide_if_empty' => 1));
				echo '<br />';
			}
		}
		
      	echo $after_widget;
		wp_enqueue_script('jquery');
		?>
		<script type="text/javascript"><!--
			jQuery(".child-cat-select").change(function() { 
				if (jQuery(this).val() > 0) {
					location.href = "<?php echo get_option('home');
		?>/?cat=" + jQuery(this).val();
				}
			});
		--></script>
     <?php
  }

  function update( $new_instance, $old_instance ) {
    return $new_instance;
  }

  function form( $instance ) {
    $title = esc_attr( $instance['title'] );
    ?>

    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
      </label>
    </p>
    <?php
  }
}

add_action( 'widgets_init', 'CategoryDropdownWidgetInit' );
function CategoryDropdownWidgetInit() {
  register_widget( 'CategoryDropdownWidget' );
}