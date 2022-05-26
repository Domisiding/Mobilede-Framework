<?php
/*
@wordpress-plugin
Plugin Name: Framework Mobilede
Author: Domisiding
Author URI: https://domisiding.de
Description: Man kann nun, ganz einfach seinen Fahrzeugbestand von Mobile.de mit einem Framework auf seiner Webseite anzeigen lassen.
Version: 1.5
Text Domain: mobileframe
Domain Path: /languages
License: GNU General Public License v2
*/

//////////////////////////// Schutz //////////////////////////
if ( ! defined( 'WPINC' ) ) {
	die;
}
//////////////////////////// Add ////////////////////////////
add_action( 'wp_enqueue_scripts', 'mobileframe_register_plugin_styles' );
add_shortcode('mobileframe', 'mobileframe_start');
add_action('admin_menu', 'mobileframe_create_adminmenu');
add_action('mobileframe_translation', 'load_textdomain');
///////////////////////// Translation ///////////////////////
$plugin_header_translate = array( __('Mobilede Framework', 'mobileframe'), __('Man kann nun, ganz einfach seinen Fahrzeugbestand von Mobile.de mit einem Framework auf seiner Webseite anzeigen lassen.', 'mobileframe') );

function load_textdomain() {
	load_plugin_textdomain( 'mobileframe', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
///////////////////////// Menü //////////////////////////
function mobileframe_create_adminmenu() {

	add_menu_page('Mobilede Framework', 'Mobileframe', 'manage_options', __FILE__, 'mobileframe_page',plugins_url('/images/icon.png', __FILE__));

	$admin_stylesheet = plugins_url( 'style/mobileframe.css', __FILE__ );
	wp_register_style( 'mobileframe_admin_style', $admin_stylesheet );
	wp_enqueue_style( 'mobileframe_admin_style' );
	add_action( 'admin_init', 'mobileframe_settings' );
}
///////////////////////// Seite //////////////////////////
function mobileframe_page()
{
?>
<div class="wrap mobileframe_admin_wrap">
  <h2 class="box_50">
    Mobilede Framework
  </h2>
  <div class="box_100">
    <p>
      <?php _e('Um ihren Fahrzeugbestand auf einer Seite zu zeigen, verwenden Sie einfach folgenden Shortcode "[mobileframe].', 'mobileframe'); ?>  
    </p>
    <form id="mobileframe_optionsform" method="post" action="options.php">
        <?php settings_fields( 'mobileframe-group' ); ?>
        <?php do_settings_sections( 'mobileframe-group' ); ?>
        <table class="form-table">
            <tr valign="top">
            <th scope="row" colspan="2"><strong><?php _e('Im folgenden Bereich müssen Sie ihren Bestandslink hinterlegen', 'mobileframe'); ?></strong></th>
            </tr>
            <tr valign="top">
            <th scope="row"><?php _e('Bestandslink', 'mobileframe'); ?><br><span style="text_italic"><?php _e('(Die "customerID" ist in ihrem Administrationsbereich von Mobile.de)', 'mobileframe'); ?></span></th>
            <td>
              <input class="form_text" type="text" name="mobileframe_link" value="<?php echo get_option('mobileframe_link'); ?>" />
              <?php _e('Beispiel:', 'mobileframe'); ?><span class="text_italic">https://home.mobile.de/home/index.html?partnerHead=false&colorTheme=default&customerId=xxxxxx</span>
            </td>
            </tr>
            </tr>
         </table>
        
        <?php submit_button(); ?>
    </form>
  </div>
<?php }
///////////////////////// Einstellungen //////////////////////////
function mobileframe_settings()
{
	add_option('mobileframe_color', 'default');
	add_option('mobileframe_styles', 
	'
	
#mobileframe-frame
{
   margin: 0;
   width: 100%; 
   height: 600px;
}   

#mobileframe-wrapper
{
width: 100%; 
padding: 10px;
background: rgba(223, 223, 223, 0.9);
}     
	');
   register_setting( 'mobileframe-group', 'mobileframe_link' );
   register_setting( 'mobileframe-group', 'mobileframe_styles' );
}
///////////////////////// Style //////////////////////////
function mobileframe_register_plugin_styles()
{
   wp_register_style( 'mobileframe_style', plugins_url( 'style/mobileframe.css', __FILE__ ) ) ;
   wp_enqueue_style( 'mobileframe_style' );
}
///////////////////////// Start //////////////////////////
function mobileframe_start() 
{
   
   if(get_option('mobileframe_link')&&get_option('mobileframe_link')!="")
   {
      $mobile_link=get_option('mobileframe_link');
   }
   else unset($mobile_link);


   if(isset($mobile_link))
   {
      $url_array = parse_url ( $mobile_link );
      $query_para=array();
      parse_str($url_array['query'], $query_para);
      
      $new_query="";
      $para_count=0;
      while(list($key,$val)=each($query_para))
      {
         if($para_count!=0) $new_query.="&";
         $new_query.= $key."=".$val;
         $para_count++;
      }

      $url_array['query']=$new_query;
	  $url_array['key']=$new_query;
	
      
      $mobile_link="https://home.mobile.de/home/index.html?".$url_array['key'];
   }
?>

<?php 
   if(isset($mobile_styles))
   {
?>
      <style type="text/style">
<?php       
      echo $mobile_styles;
?>
      </style>
<?php       
   }
?>

	<div id="mobileframe-wrapper">
<?php 
   if(isset($mobile_link))
   {
?>               
      <iframe id="mobileframe-frame" src="<?php echo $mobile_link;?>"></iframe>
<?php 
   }
   else echo "<p>Unsere Fahrzeuge, stehen Ihn bald zur Verfügung.</p>"

?>    		
   </div>    
<?php  
}
?>