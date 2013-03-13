<?php
/*
Plugin Name: Illustrated Recent Posts Widget
Plugin URI: http://github.com/elfsternberg/illustrated-recent-posts-wp/
Description: This plugin creates a fairly limited widget, but the magic in the CSS creates a lovely background for individual posts.
Version: 0.0.1
Author: Elf Sternberg
Author URI: http://www.elfsternberg.com/
Copyright: 2013 Omaha Sternberg (http://igameradio.com)
*/

/*
  This is a fairly ordinary extension of the Recent Posts widget.
  What's useful about it is that scans all each recent post for an
  image and, if one is found, adds it to a div at the end of the
  returned HTML object.  With a little CSS magic (see the included CSS
  file), the image is faded out and the text superimposed above it.
  This is really attractive for gaming and movie sites.  This effect,
  or something like it, can be clearly seen at theverge.com's home
  page, and I've implemented it for iGameRadio.com.
*/

class Illustrated_Recent_Posts_Widget extends WP_Widget {

  function __construct() {
    $widget_ops = array(
      /* classname embedded in parent wrapper */
      'classname'   => 'widget_recent_entries', 
      /* description as it appears in admin */
      'description' => 
        __('Display a list of recent post entries with a specific illustration.'));

    parent::__construct('illustrated-recent-posts', __('Illustrated Recent Posts'), $widget_ops);
  }

  /* PURE */
  function find_first_image($content) {
    $first_img = '';
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
    if (!($output === false)) {
      $first_img = $matches[1][0];
    }
    return $first_img;
  }
  
  function widget($args, $instance) {
    extract($args);
    $title = apply_filters('widget_title', empty($instance['title']) ? 'Recent Posts' : $instance['title'], $instance, $this->id_base);
    $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

    if (empty($instance['number']) || (!($number = absint($instance['number'])))) {
      $number = 5;
    }

    if(!($c = $instance["cats"])) {
      $c = '';
    }

    $irpw_args=array(
      'showposts' => $count,
      'category__in'=> $c,
      'no_found_rows' => true, 
      'post_status' => 'publish', 
      'ignore_sticky_posts' => true
    );
			
    $irp_widget = new WP_Query($irpw_args);

    echo $before_widget;
    while ($irp_widget->have_posts()) {
      $irp_widget->the_post();
      $first_image = find_first_image($post->post_content);
    ?>
      <article class="irpw-article">
         <a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent link to <?php the_title_attribute(); ?>" class="irpw-title">
           <h1>
             <span class="irpw-desc">
                <span class="irpw-title"><?php the_title(); ?></span>
                <span class="irpw-sep">/</span>
                <span class="irpw-date"><?php echo get_the_date(); ?></span>
           </h1>
           <div class="irpw-bg">
               <img src="<?php echo $first_image ?>">
           </div>
         </a>
      </article>
    <?php }
      wp_reset_query();
      echo $after_widget;
  }

  /* Details that need to be saved from the admin form for a specific
   * instance in a specific sidebar. */

  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['cats'] = $new_instance['cats'];
    $instance['number'] = absint($new_instance['number']);
    $instance['show_date'] = (bool) $new_instance['show_date'];
    return $instance;
  }

  function form( $instance ) {
    $title = isset($instance['title']) ? esc_attr($instance['title']) : 'Recent Posts';
    $number = isset($instance['number']) ? absint($instance['number']) : 5;
    $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
    $cats_instance = $instance['cats'];
    $option_base ='<input type="checkbox" id="'. $this->get_field_id('cats') .'[]" name="'. $this->get_field_name('cats') .'[]"';

    $isChecked = function($c) use ($cats_instance) {
      $is_a_cat = function($ic) { return ($ic == $c->term_id); };
      return (count(array_filter($cats_instance, $is_a_cat)) > 0);
    };

    $reduce_categories = function($result, $c) use ($isChecked, $option_base) {
      $checked = ($isChecked($c)) ? ' checked="checked"' : '';
      if ($result == NULL) {
        return $option_base . $checked . ' value="' . $c->term_id.'" />&nbsp;' . $c->cat_name . '<br />';
      }
      return $result . $option_base . $checked . ' value="' . $c->term_id.'" />&nbsp;' . $c->cat_name . '<br />';
    };
  ?>

    <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
                        
    <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
    <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

    <p>
      <label for="<?php echo $this->get_field_id('cats'); ?>"><?php _e('Select categories to include in the illustrated links list:');?> 
      <br/>                                                                                                             
         <?php echo array_reduce(get_categories('hide_empty=0'), $reduce_categories, NULL); ?>
      </label>
    </p>

<?php
	}
}

function irpw_register_widgets() {
	register_widget( 'Illustrated_Recent_Posts_Widget' );
}

add_action( 'widgets_init', 'irpw_register_widgets' );
?>
