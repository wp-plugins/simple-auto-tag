<?php
/*
Plugin Name: Simple Auto Tag
Plugin URI: https://github.com/jzvikas/Auto-Tag
Description: Simple way to create auto tags from post/page title.
Version: 1.0
Author: djjmz
Author URI: https://github.com/jzvikas/Auto-Tag
*/
function SaveTitleAsTag($post_ID) {
 $gpt = get_post($post_ID);
 $posttitle = $gpt->post_title;
 $posttitle = strtolower($posttitle);
 if(get_the_tags($post_ID)){
    foreach(get_the_tags($post_ID) as $tag) {
      $tag_name = $tag->name;
      $tag_name  = strtolower($tag_name);
      $posttitle = str_replace($tag_name, "", $posttitle);
    }                           
 }
 if(get_post_status ( $post_ID ) == 'publish'){
 $splittotags = explode(' ', $posttitle);
 foreach ($splittotags as $atag){
     $atag = str_replace(' ', '', $atag);
	 $atag = strtolower(trim(preg_replace('#[^\p{L}\p{N}]+#u', '', $atag)));
     if($atag !=NULL){
           wp_set_object_terms($post_ID, $atag, 'post_tag', true );
     }
  }
  }
}

add_action('save_post', 'SaveTitleAsTag');
?>