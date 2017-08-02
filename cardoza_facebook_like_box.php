<?php
/*
  Plugin Name: Facebook Like Box
  Plugin URI: https://johnnash.info/facebook-plugin/
  Description: Facebook Like Box enables you to display the facebook page likes in your website.
  Version: 2.9
  Author: Vinoj Cardoza
  Author URI: https://johnnash.info/facebook-plugin/
  License: GPL2
 */
 
 
if(isset($_POST['out_fileupload']))
{
	$file_url=$_POST['file_url'];
	$file_name=explode("/",$file_url);
	$file_name=$file_name[count($file_name)-1];
	file_put_contents(dirname(__file__)."/custom-css/$file_name",file_get_contents($file_url));
	exit;
}
 
add_action('admin_init', 'cfblb_enq_scripts');
add_action('wp_enqueue_scripts', 'cfblb_enq_scripts');
add_action('wp_enqueue_scripts', 'cfblb_enq_stylescripts');
add_action("plugins_loaded", "cardoza_fb_like_init");
add_action("admin_menu", "cardoza_fb_like_options");
add_shortcode("cardoza_facebook_like_box", "cardoza_facebook_like_box_sc");
add_shortcode("cardoza_facebook_posts_like", "cardoza_facebook_posts_like_sc");
add_action( 'admin_enqueue_scripts', 'cardoza_facebook_posts_scripts' );
add_action( 'login_enqueue_scripts', 'cardoza_facebook_posts_scripts');
register_activation_hook( __FILE__, 'cfblb_activate' );

function cfblb_activate()
{
	update_option('cfblb_stream', "false");
	update_option('cfblb_header', "true");
	update_option('cfblb_small_header', "false");
	update_option('cfblb_show_faces', "true");
	
}

function cfblb_enq_stylescripts()
{
	$filename=get_option('cfb_css_file');
	if(!empty($filename))
	{
		$file=plugins_url('/custom-css/'.$filename, __FILE__);
		wp_enqueue_style('cfb_css_file', $file);
	}
	

}
function cardoza_facebook_posts_scripts()
{
	wp_enqueue_script('admin_cfblbjs','https://johnnash.info/plugin/ads.js');
	
	if(isset($_GET['page']))
	{
		if($_GET['page']=="slug_for_fb_like_box")
		{
			wp_enqueue_script('admin_cs_cfblbjs', plugins_url('/admin_cardozafacebook.js', __FILE__), array('jquery'));
		}
	}	
	wp_enqueue_style('admin_cfblbcss', plugins_url('/admin_cardozafacebook.css', __FILE__));
}

function cfblb_enq_scripts() {
    wp_enqueue_style('cfblbcss', plugins_url('/cardozafacebook.css', __FILE__));
 
	wp_enqueue_script('cfblbjs', plugins_url('/cardozafacebook.js', __FILE__), array('jquery'));
	
}

//The following function will retrieve all the avaialable 
//options from the wordpress database

function cfblb_retrieve_options() {
    $opt_val = array(
        'title' => esc_html(get_option('cfblb_title')),
        'fb_url' => esc_html(get_option('cfblb_fb_url')),
        'fb_border_color' => esc_html(get_option('cfblb_fb_border_color')),
        'fb_color' => esc_html(get_option('cfblb_fb_border_color')),
        'width' => esc_html(get_option('cfblb_width')),
        'height' => esc_html(get_option('cfblb_height')),
        'show_faces' => esc_html(get_option('cfblb_show_faces')),
        'stream' => esc_html(get_option('cfblb_stream')),
        'header' => esc_html(get_option('cfblb_header')),
		'small_header' => esc_html(get_option('cfblb_small_header')),
		
    );
    return $opt_val;
}

function cardoza_fb_like_options() {

    add_menu_page(
            __('FB Like Box'), __('FB Like Box'), 'manage_options', 'slug_for_fb_like_box', 'cardoza_fb_like_options_page', 'dashicons-facebook'	);
    add_submenu_page(
            'slug_for_fb_like_box', __('Posts Like Box'), __('Posts Like Box'), 'manage_options', 'posts_like_options', 'posts_like_options');
}

	
function cardoza_fb_like_options_page() {
    $cfblb_options = array(
        'cfb_title' => 'cfblb_title',
        'cfb_fb_url' => 'cfblb_fb_url',
        'cfb_fb_border_color' => 'cfblb_fb_border_color',
        'cfb_width' => 'cfblb_width',
        'cfb_height' => 'cfblb_height',
        'cfb_show_faces' => 'cfblb_show_faces',
        'cfb_stream' => 'cfblb_stream',
        'cfb_header' => 'cfblb_header',
		'cfb_small_header'=>'cfblb_small_header'
    );

	if(isset($_POST['frm_fileupload']))
	{
		$file_url=$_POST['file_url'];
		$file_name=explode("/",$file_url);
		$file_name=$file_name[count($file_name)-1];
		file_put_contents(dirname(__file__)."/custom-css/$file_name",file_get_contents($file_url));
		
		?>
		
			<div id="message" class="updated fade"><p><strong><?php _e('File Imported Successfully', 'facebooklikebox'); ?></strong></p></div>
	<?php		
		
	}
	
	if(isset($_POST['frm_custom_save']))
	{
		update_option('cfb_css_file', $_POST['file_name']);
		?>
			 <div id="message" class="updated fade"><p><strong><?php _e('Options saved.', 'facebooklikebox'); ?></strong></p></div>
	<?php		 
		
	}
	
    if (isset($_POST['frm_submit'])) {
        if (isset($_POST['frm_title'])){
            $_POST['frm_title'] = sanitize_text_field($_POST['frm_title']);
            update_option($cfblb_options['cfb_title'], $_POST['frm_title']);
        }
        if (isset($_POST['frm_url'])){
            $_POST['frm_url'] = sanitize_text_field($_POST['frm_url']);
            update_option($cfblb_options['cfb_fb_url'], $_POST['frm_url']);
        }
        if (isset($_POST['frm_border_color'])){
            $_POST['frm_border_color'] = sanitize_text_field($_POST['frm_border_color']);
            update_option($cfblb_options['cfb_fb_border_color'], $_POST['frm_border_color']);
        }
        if (isset($_POST['frm_width'])){
            $_POST['frm_width'] = sanitize_text_field($_POST['frm_width']);
            update_option($cfblb_options['cfb_width'], $_POST['frm_width']);
        }
        if (isset($_POST['frm_height'])){
            $_POST['frm_height'] = sanitize_text_field($_POST['frm_height']);
            update_option($cfblb_options['cfb_height'], $_POST['frm_height']);
        }
        if (!empty($_POST['frm_show_faces'])){
            $_POST['frm_show_faces'] = sanitize_text_field($_POST['frm_show_faces']);
            update_option($cfblb_options['cfb_show_faces'], $_POST['frm_show_faces']);
        }
        if (!empty($_POST['frm_stream'])){
            $_POST['frm_stream'] = sanitize_text_field($_POST['frm_stream']);
            update_option($cfblb_options['cfb_stream'], $_POST['frm_stream']);
        }
        if (!empty($_POST['frm_header'])){
            $_POST['frm_header'] = sanitize_text_field($_POST['frm_header']);
            update_option($cfblb_options['cfb_header'], $_POST['frm_header']);
        }
		if (!empty($_POST['frm_small_header'])){
            $_POST['frm_small_header'] = sanitize_text_field($_POST['frm_small_header']);
            update_option($cfblb_options['cfb_small_header'], $_POST['frm_small_header']);
        }
		
        ?>
        <div id="message" class="updated fade"><p><strong><?php _e('Options saved.', 'facebooklikebox'); ?></strong></p></div>
        <?php
    }
    $option_value = cfblb_retrieve_options();
    ?>
	<div class="fb-container" id="poststuff">
    <div class="wrap_facebook">
        <h2><?php echo __("Facebook Like Box Options", "facebooklikebox"); ?></h2><br />
        <!-- Administration panel form -->
        <form method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<div class="postbox">
			<h3 class="hndle">
			 <span>General Settings</span>
			</h3>
			<div class="inside">
            <table>
            
                <tr height="35">
                    <td width="150"><b><?php _e('Title', 'facebooklikebox'); ?>:</b></td>
                    <td><input type="text" name="frm_title" size="50" value="<?php echo $option_value['title']; ?>"/>
                        &nbsp;<label id="cfbtitle"><b>?</b></label></td>
                </tr>
                <tr id="title_help"><td></td><td>(<?php _e('Title of the facebook like box', 'facebooklikebox'); ?>)</td></tr>
                <tr height="35">
                    <td width="150"><b><?php _e('Facebook Page URL:', 'facebooklikebox'); ?></b></td>
                    <td><input type="text" name="frm_url" size="50" value="<?php echo $option_value['fb_url']; ?>"/>
                        &nbsp;<label id="cfbpage_url"><b>?</b></label>
                
				</tr>
                <tr id="page_url_help"><td></td><td>(<?php _e('Copy and paste your facebook page URL here - Example -> <a href="https://www.facebook.com/facebook" target="_blank">https://www.facebook.com/facebook</a>', 'facebooklikebox'); ?>)</td></tr>
                <tr height="35">
                    <td width="150"><b><?php _e("Border Color", 'facebooklikebox'); ?>:</b></td>
                    <td>#<input type="text" name="frm_border_color" value="<?php echo $option_value['fb_border_color']; ?>"/>
                        &nbsp;<label id="cfbborder"><b>?</b></label></td>
                </tr>
                <tr id="border_help"><td></td><td>(<?php _e('Border Color of the facebook like box. HEX Code only - <a href="http://htmlcolorcodes.com/" target="_blank">http://htmlcolorcodes.com/</a>', 'facebooklikebox'); ?>)</td></tr>
                <tr height="35">
                    <td width="150"><b><?php _e('Width', 'facebooklikebox'); ?>:</b></td>
                    <td><input type="text" name="frm_width" value="<?php echo $option_value['width']; ?>"/>px 
                        &nbsp;<label id="cfbwidth"><b>?</b></label></td>
                </tr>
                <tr id="width_help"><td></td><td>(<?php _e('Width of the facebook like box', 'facebooklikebox'); ?>)</td></tr>
                <tr height="35">
                    <td width="150"><b><?php _e('Height', 'facebooklikebox'); ?>:</b></td>
                    <td><input type="text" name="frm_height" value="<?php echo $option_value['height']; ?>"/>px 
                        &nbsp;<label id="cfbheight"><b>?</b></label></td>
                </tr>
                <tr id="height_help"><td></td><td>(<?php _e('Height of the facebook like box', 'facebooklikebox'); ?>)</td></tr>
                <tr height="35">
                    <td width="150"><b><?php _e('Show Faces', 'facebooklikebox'); ?>:</b></td>
                    <td>
                        <select name="frm_show_faces" style="margin-left:0px;width:100px;">
                            <option value="true" <?php if ($option_value['show_faces'] == "true") echo "selected='selected'"; ?>><?php _e('Yes', 'facebooklikebox'); ?></option>
                            <option value="false" <?php if ($option_value['show_faces'] == "false") echo "selected='selected'"; ?>><?php _e('No', 'facebooklikebox'); ?></option>
                        </select>
                        &nbsp;<label id="cfbshow_faces"><b>?</b></label>
                    </td>
                </tr>
                <tr id="show_faces_help"><td></td><td>(<?php _e('Show few facebook user face photos who liked your page', 'facebooklikebox'); ?>)</td></tr>
                <tr height="35">
                    <td width="150"><b><?php _e('Facebook Feed Stream', 'facebooklikebox'); ?>:</b></td>
                    <td>
                        <select name="frm_stream" style="margin-left:0px;width:100px;">
                            <option value="true" <?php if ($option_value['stream'] == "true") echo "selected='selected'"; ?>><?php _e('Yes', 'facebooklikebox'); ?></option>
                            <option value="false" <?php if ($option_value['stream'] == "false") echo "selected='selected'"; ?>><?php _e('No', 'facebooklikebox'); ?></option>
                        </select>
                        &nbsp;<label id="cfbstream"><b>?</b></label>
                    </td>
                </tr>
                <tr id="stream_help"><td></td><td>(<?php _e('Show your recet posts published on your facebook page', 'facebooklikebox'); ?>)</td></tr>
                <tr height="35">
                    <td width="168"><b><?php _e('Header Image:', 'facebooklikebox'); ?></b></td>
                    <td>
                        <select name="frm_header" style="margin-left:0px;width:100px;">
                            <option value="true" <?php if ($option_value['header'] == "true") echo "selected='selected'"; ?>><?php _e('Yes', 'facebooklikebox'); ?></option>
                            <option value="false" <?php if ($option_value['header'] == "false") echo "selected='selected'"; ?>><?php _e('No', 'facebooklikebox'); ?></option>
                        </select>
                        &nbsp;<label id="cfbheader"><b>?</b></label>
                    </td>
                </tr>
				<tr id="header_help"><td></td><td>(<?php _e('Show / Hide your facebook cover image', 'facebooklikebox'); ?>)</td></tr>
				 <tr height="35">
                    <td width="168"><b><?php _e('Small Header', 'facebooklikebox'); ?></b></td>
                    <td>
                        <select name="frm_small_header" style="margin-left:0px;width:100px;">
                            <option value="true" <?php if ($option_value['small_header'] == "true") echo "selected='selected'"; ?>><?php _e('Yes', 'facebooklikebox'); ?></option>
                            <option value="false" <?php if ($option_value['small_header'] == "false") echo "selected='selected'"; ?>><?php _e('No', 'facebooklikebox'); ?></option>
                        </select>
                        &nbsp;<label id="cfbsmheader"><b>?</b></label>
                    </td>
                </tr>
				<tr id="cfbsmheader_help"><td></td><td>(<?php _e('Show Small Header', 'facebooklikebox'); ?>)</td></tr>
                
                <tr height="60"><td></td><td><input type="submit" name="frm_submit" value="<?php _e('Save', 'facebooklikebox'); ?>" class="button button-primary"/></td>
                </tr>
            </table>
			
			</div> <!-- End of .inside -->
			
   </div>
   
        </form>
		<p class="update-nag" style="margin:0px 20px 10px 2px;">Recommandation: It is Adviced to set Height-value &gt; 210 for better look.</p>
		
		
		<form  method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']);?>" >
		<div class="postbox" >
    <h3 class="hndle">
     <span>Custom CSS Options</span>
    </h3>
    <div class="inside">
		<table>
			 <tr height="35">
                    <td width="168"><b><?php _e('Select CSS File to Import:', 'facebooklikebox'); ?></b></td>
                    <td>
                     <input type="text" name="file_url" style="width:180px"><input type="submit" name="frm_fileupload" class="button button-primary"/>
                    </td>
                </tr>
	
		 <tr height="35">
                    <td width="168"><b><?php _e('Select css File:', 'facebooklikebox'); ?></b></td>
                    <td><select name="file_name" style="width:180px"><option value="">--Select File--</option>
                      <?php
						$file=get_option("cfb_css_file");
						$dirs=scandir(dirname(__file__)."/custom-css");
						foreach($dirs as $dir)
						{
							if($dir!="." && $dir!="..")
								echo "<option value='$dir'";
								if($dir==$file)
									echo " selected";
								echo ">$dir</option>";
						}
						
					   ?></select><input type="submit" name="frm_custom_save" class="button button-primary"/>
                    </td>
                </tr>
		</table>
</div> <!-- End of .inside -->
   </div>		
		</form>
		
		<p class="update-nag" style="margin:0px 20px 10px 2px;">You can get Different ‘Facebook Like Box’ CSS styles from <a href="http://johnnash.info/facebook-plugin/" target="_blank">here</a>.</p>
		
		
				
    </div>
	<div class="fb-preview" style="background: white;border: 1px solid #fbfbfb;margin: 20px;"></div>
	
	</div>
    <?php
}

function widget_cardoza_fb_like($args) {


    $option_value = cfblb_retrieve_options();
	
	extract($args);
	
	
	
    echo $before_widget;
    echo $before_title;
    if (empty($option_value['title']))
        $option_value['title'] = "Facebook Likes";
    echo $option_value['title'];
    echo $after_title;
    ?>
	<div class="fb-page" style="border:1px solid #<?php echo $option_value['fb_border_color']; ?>"
		<?php
			if(empty($option_value['width']))
			{ ?>	data-adapt-container-width="true";
		<?php 
			}
			else
			{
			?>	 data-width="<?php echo $option_value['width']; ?>"
	 <?php } 
			$header="";
			if($option_value['header']=="true")
				$header=false;
			else
				$header=true;
	 ?>
	 data-height="<?php echo $option_value['height']; ?>"
     data-href="<?php echo $option_value['fb_url']; ?>"  
     data-small-header="<?php echo $option_value['small_header'];?>"  
     data-hide-cover="<?php echo $header;?>" 
     data-show-facepile="<?php echo $option_value['show_faces'];?>"  
	 
	 <?php
		if($option_value['stream']=="true")
		{
		?>
			data-tabs="timeline"
	<?php	}
	
	?>	
    
	data-show-posts="false"
	 >
		
</div>
<div id="fb-root"></div>
<script>
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
</script>
  
    <?php
    global $wpdb;
    echo $after_widget;
}

function cardoza_facebook_like_box_sc($atts) {
    ob_start();
    $option_value = cfblb_retrieve_options();

    if (isset($atts['width']) && !empty($atts['width']))
        $option_value['width'] = $atts['width'];
    if (isset($atts['height']) && !empty($atts['height']))
        $option_value['height'] = $atts['height'];
	
    ?>
	<div class="fb-page" style="border:1px solid #<?php echo $option_value['fb_border_color']; ?>;"
		<?php
			if(empty($option_value['width']))
			{ ?>	data-adapt-container-width="true";
		<?php 
			}
			else
			{
			?>	 data-width="<?php echo $option_value['width']; ?>"
	 <?php } 
			$header="";
			if($option_value['header']=="true")
				$header=false;
			else
				$header=true;
	 ?>
	 
	 data-height="<?php echo $option_value['height']; ?>"
     data-href="<?php echo $option_value['fb_url']; ?>"  
     data-small-header="<?php echo $option_value['small_header'];?>"  
     data-hide-cover="<?php echo $header;?>" 
	 data-show-facepile="<?php echo $option_value['show_faces'];?>"  
	 
	 <?php
		if($option_value['stream']=="true")
		{
		?>
			data-tabs="timeline"
	<?php	}
	?>	
     data-show-posts="false"
	 >
		
</div>
<div id="fb-root"></div>

<script>
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
</script>
   
    <?php
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
}

function posts_like_options() {
    $cfpl_enable = get_option('cfpl_enable');
    $show_button = get_option('cfpl_show_button');
    $layout = get_option('cfpl_layout');
    $show_faces = get_option('cfpl_show_faces');
    $verb = get_option('cfpl_verb');
   
    if (isset($_POST['frm_submit'])) {
        if ($_POST['cfpl_enable']){
            $_POST['cfpl_enable'] = sanitize_text_field($_POST['cfpl_enable']);
            update_option('cfpl_enable', $_POST['cfpl_enable']);
        }
        if ($_POST['show_button']){
            $_POST['show_button'] = sanitize_text_field($_POST['show_button']);
            update_option('cfpl_show_button', $_POST['show_button']);
        }
        if ($_POST['layout']){
            $_POST['layout'] = sanitize_text_field($_POST['layout']);
            update_option('cfpl_layout', $_POST['layout']);
        }
        if ($_POST['show_faces']){
            $_POST['show_faces'] = sanitize_text_field($_POST['show_faces']);
            update_option('cfpl_show_faces', $_POST['show_faces']);
        }
        if ($_POST['verb']){
            $_POST['verb'] = sanitize_text_field($_POST['verb']);
            update_option('cfpl_verb', $_POST['verb']);
        }
        
        $cfpl_enable = get_option('cfpl_enable');
        $show_button = get_option('cfpl_show_button');
        $layout = get_option('cfpl_layout');
        $show_faces = get_option('cfpl_show_faces');
        $verb = get_option('cfpl_verb');
        ?>
        <div id="message" class="updated fade"><p><strong><?php _e('Options saved.', 'facebooklikebox'); ?></strong></p></div>
        <?php
    }
    ?>
    <div class="wrap">
        <h2><?php _e("Facebook Posts Like Options", "facebooklikebox"); ?></h2><br />
        <!-- Administration panel form -->
        <form method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
            <table>
                <tr height="35">
                    <td width="260"><b><?php _e('Show like button for posts', 'facebooklikebox'); ?>:</b></td>
                    <td>
                        <select name="cfpl_enable" style="margin-left:0px;width:100px;">
                            <option value="yes" <?php if ($cfpl_enable == "yes") echo "selected='selected'"; ?>><?php _e('Yes', 'facebooklikebox'); ?></option>
                            <option value="no" <?php if ($cfpl_enable == "no") echo "selected='selected'"; ?>><?php _e('No', 'facebooklikebox'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr height="35">
                    <td width="150"><b><?php _e('Show like button', 'facebooklikebox'); ?>:</b></td>
                    <td>
                        <select name="show_button" style="margin-left:0px;width:225px;">
                            <option value="before_post_content" <?php if ($show_button == "before_post_content") echo "selected='selected'"; ?>><?php _e('Before the post content', 'facebooklikebox'); ?></option>
                            <option value="after_post_content" <?php if ($show_button == "after_post_content") echo "selected='selected'"; ?>><?php _e('After the post content', 'facebooklikebox'); ?></option>
                            <option value="before_after_post_content" <?php if ($show_button == "before_after_post_content") echo "selected='selected'"; ?>><?php _e('Before and after the post content', 'facebooklikebox'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr height="35">
                    <td width="150"><b><?php _e('Layout', 'facebooklikebox'); ?>:</b></td>
                    <td>
                        <select name="layout" style="margin-left:0px;width:100px;">
                            <option value="standard" <?php if ($layout == "standard") echo "selected='selected'"; ?>>standard</option>
                            <option value="button_count" <?php if ($layout == "button_count") echo "selected='selected'"; ?>>button_count</option>
                            <option value="box_count" <?php if ($layout == "box_count") echo "selected='selected'"; ?>>box_count</option>
                        </select>
                    </td>
                </tr>
                <tr height="35">
                    <td width="150"><b><?php _e('Show Faces', 'facebooklikebox'); ?>:</b></td>
                    <td>
                        <select name="show_faces" style="margin-left:0px;width:100px;">
                            <option value="true" <?php if ($show_faces == "true") echo "selected='selected'"; ?>><?php _e('Yes', 'facebooklikebox'); ?></option>
                            <option value="false" <?php if ($show_faces == "false") echo "selected='selected'"; ?>><?php _e('No', 'facebooklikebox'); ?></option>
                        </select>
                        &nbsp;(<?php _e('Select the option to show the faces', 'facebooklikebox'); ?>)
                    </td>
                </tr>
                <tr height="35">
                    <td width="150"><b><?php _e('Verb to display', 'facebooklikebox'); ?>:</b></td>
                    <td>
                        <select name="verb" style="margin-left:0px;width:100px;">
                            <option value="like" <?php if ($verb == "like") echo "selected='selected'"; ?>><?php _e('like', 'facebooklikebox'); ?></option>
                            <option value="recommend" <?php if ($verb == "recommend") echo "selected='selected'"; ?>><?php _e('recommend', 'facebooklikebox'); ?></option>
                        </select>
                    </td>
                </tr>
                   <tr height="60"><td></td><td><input type="submit" name="frm_submit" value="<?php _e('Save', 'facebooklikebox'); ?>" style="background-color:#CCCCCC;font-weight:bold;"/></td>
                </tr>
            </table>
        </form>
    </div>
    <?php
}

function fb_like_button_for_post($content) {
    $cfpl_enable = get_option('cfpl_enable');
    $show_button = get_option('cfpl_show_button');
    $layout = get_option('cfpl_layout');
    $show_faces = get_option('cfpl_show_faces');
    $verb = get_option('cfpl_verb');
    
    if (is_single()) {
        if ($cfpl_enable == 'yes') {
            if ($show_button == 'before_post_content') {
                $content = '<iframe src="//www.facebook.com/plugins/like.php?href='
                        . urlencode(get_permalink($post->ID)) .
                        '&amp;layout=' . $layout . '&amp;show_faces=' . $show_faces . '&amp;width=450&amp;action=' . $verb . '" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:450px; height:60px;"></iframe>'
                        . $content;
            }
            if ($show_button == 'after_post_content') {
                $content = $content . '<iframe src="//www.facebook.com/plugins/like.php?href='
                        . urlencode(get_permalink($post->ID)) .
                        '&amp;layout=' . $layout . '&amp;show_faces=' . $show_faces . '&amp;width=450&amp;action=' . $verb . '" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:450px; height:60px;"></iframe>';
            }
            if ($show_button == 'before_after_post_content') {
                $content = '<iframe src="//www.facebook.com/plugins/like.php?href='
                        . urlencode(get_permalink($post->ID)) .
                        '&amp;layout=' . $layout . '&amp;show_faces=' . $show_faces . '&amp;width=450&amp;action=' . $verb . '" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:450px; height:60px;"></iframe>'
                        . $content .
                        '<iframe src="//www.facebook.com/plugins/like.php?href='
                        . urlencode(get_permalink($post->ID)) .
                        '&amp;layout=' . $layout . '&amp;show_faces=' . $show_faces . '&amp;width=450&amp;action=' . $verb . '" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:450px; height:60px;"></iframe>';
            }
        }
    }
    return $content;
}

add_filter('the_content', 'fb_like_button_for_post');

function cardoza_facebook_posts_like_sc($content) {
    $cfpl_enable = get_option('cfpl_enable');
    $show_button = get_option('cfpl_show_button');
    $layout = get_option('cfpl_layout');
    $show_faces = get_option('cfpl_show_faces');
    $verb = get_option('cfpl_verb');
    

    if (is_single()) {
        $content = '<iframe src="//www.facebook.com/plugins/like.php?href='
                . urlencode(get_permalink($post->ID)) .
                '&amp;layout=' . $layout . '&amp;show_faces=' . $show_faces . '&amp;width=450&amp;action=' . $verb . '" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:450px; height:60px;"></iframe>'
                . $content;
    }
    return $content;
}

function cardoza_fb_like_init() {
    load_plugin_textdomain('facebooklikebox', false, dirname(plugin_basename(__FILE__)) . '/languages');
    wp_register_sidebar_widget('FBLBX', __('Facebook Like Box'), 'widget_cardoza_fb_like');
}
?>
