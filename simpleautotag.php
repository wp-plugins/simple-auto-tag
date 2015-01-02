<?php
/*
Plugin Name: Simple Auto Tag
Plugin URI: https://github.com/jzvikas/Auto-Tag
Description: Simple way to create auto tags from post/page title.
Version: 1.0.2
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
      $posttitle = str_replace($tag_name, '', $posttitle);
    }                           
 }
 if(get_post_status ( $post_ID ) == 'publish'){
$filename=plugin_dir_path( __FILE__ ).'/words.txt';
$lines = array();
$file = fopen($filename, 'r');
while(!feof($file)) {
$lines[] = trim(fgets($file, 4096));
}
fclose ($file);
 $splittotags = explode(' ', $posttitle);
 foreach ($splittotags as $atag){
     $atag = str_replace(' ', '', $atag);
	 $atag = strtolower(trim(preg_replace('#[^\p{L}\p{N}]+#u', '', $atag)));
     if($atag != NULL && !in_array($atag,$lines)){
           wp_set_object_terms($post_ID, $atag, 'post_tag', true );
     }
  }
  }
}
add_action('save_post', 'SaveTitleAsTag');
add_action('admin_menu', 'sat_add_menu');
function sat_add_menu() {
    add_menu_page('S-A-T Settings', 'S-A-T Settings', 'manage_options', 'sat_settings', 'sat_settings',plugins_url('simple-auto-tag/img/logo.jpg'), 6);
}
function sat_settings() {
$message = '';
if(isset($_POST['words']) && !empty($_POST['words'])){
if (is_writable(plugin_dir_path( __FILE__ ).'/words.txt')) {	
file_put_contents(plugin_dir_path( __FILE__ ).'/words.txt',$_POST['words']);
$message = 'Words list updated<br>';	
}
else{
chmod(plugin_dir_path( __FILE__ ).'/words.txt', 0777);	
$message = 'Can\'t update words list!<br>';	
}
}
$file_read = file_get_contents(plugin_dir_path( __FILE__ ).'/words.txt');
echo '<div class="wrap">
<div class="metabox-holder has-center-sidebar"> 
<div id="post-body">
<div id="post-body-content">
<div class="postbox">
<div class="inside">
<div align="center">
<h2>'.$message.'</h2><br>
<form method = "post" action = "">
Words (one word per line):<br>
<textarea rows="4" cols="50" name="words">'.$file_read.'</textarea><br>
<input type="submit" value="Submit">
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</div>';	
}
?>