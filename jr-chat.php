<?php
/*
Plugin Name: JR Chat
Plugin URI: http://www.jakeruston.co.uk/2010/05/wordpress-plugin-jr-chat/
Description: Displays your own Java Chat Room!
Version: 1.0.6
Author: Jake Ruston
Author URI: http://www.jakeruston.co.uk
*/

/*  Copyright 2010 Jake Ruston - the.escapist22@gmail.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$pluginname="chat";

// Hook for adding admin menus
add_action('admin_menu', 'jr_Chat_add_pages');

if (!function_exists("_iscurlinstalled")) {
function _iscurlinstalled() {
if (in_array ('curl', get_loaded_extensions())) {
return true;
} else {
return false;
}
}
}

if (!function_exists("jr_show_notices")) {
function jr_show_notices() {
echo "<div id='warning' class='updated fade'><b>Ouch! You currently do not have cURL enabled on your server. This will affect the operations of your plugins.</b></div>";
}
}

if (!_iscurlinstalled()) {
add_action("admin_notices", "jr_show_notices");

} else {
if (!defined("ch"))
{
function setupch()
{
$ch = curl_init();
$c = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
return($ch);
}
define("ch", setupch());
}

if (!function_exists("curl_get_contents")) {
function curl_get_contents($url)
{
$c = curl_setopt(ch, CURLOPT_URL, $url);
return(curl_exec(ch));
}
}
}

register_activation_hook(__FILE__,'Chat_choice');

if (!function_exists("jr_Chat_refresh")) {
function jr_Chat_refresh() {
update_option("jr_submitted_Chat", "0");
}
}

function Chat_choice () {
if (get_option("jr_Chat_links_choice")=="") {

if (_iscurlinstalled()) {
$pname="jr_Chat";
$url=get_bloginfo('url');
$content = curl_get_contents("http://www.jakeruston.co.uk/plugins/links.php?url=".$url."&pname=".$pname);
update_option("jr_submitted_Chat", "1");
wp_schedule_single_event(time()+172800, 'jr_Chat_refresh'); 
} else {
$content = "Powered by <a href='http://arcade.xeromi.com'>Free Online Games</a> and <a href='http://directory.xeromi.com'>General Web Directory</a>.";
}

if ($content!="") {
$content=utf8_encode($content);
update_option("jr_Chat_links_choice", $content);
}
}

if (get_option("jr_Chat_link_personal")=="") {
$content = curl_get_contents("http://www.jakeruston.co.uk/p_pluginslink4.php");

update_option("jr_Chat_link_personal", $content);
}
}

// action function for above hook
function jr_Chat_add_pages() {
    add_options_page('JR Chat', 'JR Chat', 'administrator', 'jr_Chat', 'jr_Chat_options_page');
}

// jr_Chat_options_page() displays the page content for the Test Options submenu
function jr_Chat_options_page() {

    // variables for the field and option names 
    $opt_name = 'mt_Chat_account';
    $opt_name_2 = 'mt_Chat_width';
	$opt_name_3 = 'mt_Chat_height';
	$opt_name_4 = 'mt_Chat_title2';
    $opt_name_5 = 'mt_Chat_plugin_support';
    $opt_name_6 = 'mt_Chat_title';
    $opt_name_9 = 'mt_Chat_cache';
    $hidden_field_name = 'mt_Chat_submit_hidden';
    $data_field_name = 'mt_Chat_account';
    $data_field_name_2 = 'mt_Chat_width';
	$data_field_name_3 = 'mt_Chat_height';
	$data_field_name_4 = 'mt_Chat_title2';
    $data_field_name_5 = 'mt_Chat_plugin_support';
    $data_field_name_6 = 'mt_Chat_title';
    $data_field_name_9 = 'mt_Chat_cache';

    // Read in existing option value from database
    $opt_val = get_option( $opt_name );
    $opt_val_2 = get_option($opt_name_2);
	$opt_val_3 = get_option($opt_name_3);
	$opt_val_4 = get_option($opt_name_4);
    $opt_val_5 = get_option($opt_name_5);
    $opt_val_6 = get_option($opt_name_6);
    $opt_val_9 = get_option($opt_name_9);
   

if (!$_POST['feedback']=='') {
$my_email1="the.escapist22@gmail.com";
$plugin_name="JR Chat";
$blog_url_feedback=get_bloginfo('url');
$user_email=$_POST['email'];
$user_email=stripslashes($user_email);
$subject=$_POST['subject'];
$subject=stripslashes($subject);
$name=$_POST['name'];
$name=stripslashes($name);
$response=$_POST['response'];
$response=stripslashes($response);
$category=$_POST['category'];
$category=stripslashes($category);
if ($response=="Yes") {
$response="REQUIRED: ";
}
$feedback_feedback=$_POST['feedback'];
$feedback_feedback=stripslashes($feedback_feedback);
if ($user_email=="") {
$headers1 = "From: feedback@jakeruston.co.uk";
} else {
$headers1 = "From: $user_email";
}
$emailsubject1=$response.$plugin_name." - ".$category." - ".$subject;
$emailmessage1="Blog: $blog_url_feedback\n\nUser Name: $name\n\nUser E-Mail: $user_email\n\nMessage: $feedback_feedback";
mail($my_email1,$emailsubject1,$emailmessage1,$headers1);

?>

<div class="updated"><p><strong><?php _e('Feedback Sent!', 'mt_trans_domain' ); ?></strong></p></div>

<?php
}
    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val = $_POST[ $data_field_name ];
        $opt_val_2 = $_POST[$data_field_name_2];
		$opt_val_3 = $_POST[$data_field_name_3];
		$opt_val_4 = $_POST[$data_field_name_4];
        $opt_val_5 = $_POST[$data_field_name_5];
        $opt_val_6 = $_POST[$data_field_name_6];
        $opt_val_9 = $_POST[$data_field_name_9];

        // Save the posted value in the database
        update_option( $opt_name, $opt_val );
        update_option( $opt_name_2, $opt_val_2 );
		update_option( $opt_name_3, $opt_val_3 );
		update_option( $opt_name_4, $opt_val_4 );
        update_option( $opt_name_5, $opt_val_5 );
        update_option( $opt_name_6, $opt_val_6 ); 
        update_option( $opt_name_9, $opt_val_9 );

        // Put an options updated message on the screen

?>
<div class="updated"><p><strong><?php _e('Options saved.', 'mt_trans_domain' ); ?></strong></p></div>
<?php

    }

    // Now display the options editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'JR Chat Plugin Options', 'mt_trans_domain' ) . "</h2>";
$blog_url_feedback=get_bloginfo('url');
	$donated=curl_get_contents("http://www.jakeruston.co.uk/p-donation/index.php?url=".$blog_url_feedback);
	if ($donated=="1") {
	?>
		<div class="updated"><p><strong><?php _e('Thank you for donating!', 'mt_trans_domain' ); ?></strong></p></div>
	<?php
	} else {
	?>
	<div class="updated"><p><strong><?php _e('Please consider donating to help support the development of my plugins!', 'mt_trans_domain' ); ?></strong><br /><br /><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="ULRRFEPGZ6PSJ">
<input type="image" src="https://www.paypal.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form></p></div>
<?php
}

    // options form
   
    $change3 = get_option("mt_Chat_plugin_support");
    $change6 = get_option("mt_Chat_cache");

if ($change3=="Yes" || $change3=="") {
$change3="checked";
$change31="";
} else {
$change3="";
$change31="checked";
}

if ($change5=="user" || $change5=="") {
$change5="checked";
$change51="";
} else {
$change5="";
$change51="checked";
}

    ?>
		<iframe src="http://www.jakeruston.co.uk/plugins/index.php" width="100%" height="20%">iframe support is required to see this.</iframe>
<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<p><?php _e("Channel Name:", 'mt_trans_domain' ); ?> 
<input type="text" name="<?php echo $data_field_name_6; ?>" value="<?php echo $opt_val_6; ?>" size="50">
</p><hr />

<p><?php _e("Width:", 'mt_trans_domain' ); ?> 
<input type="text" name="<?php echo $data_field_name_2; ?>" value="<?php echo $opt_val_2; ?>" size="6"> pixels
</p><hr />

<p><?php _e("Height:", 'mt_trans_domain' ); ?> 
<input type="text" name="<?php echo $data_field_name_3; ?>" value="<?php echo $opt_val_3; ?>" size="6"> pixels
</p><hr />

<p><?php _e("Show Plugin Support?", 'mt_trans_domain' ); ?> 
<input type="radio" name="<?php echo $data_field_name_5; ?>" value="Yes" <?php echo $change3; ?>>Yes
<input type="radio" name="<?php echo $data_field_name_5; ?>" value="No" <?php echo $change31; ?> id="Please do not disable plugin support - This is the only thing I get from creating this free plugin!" onClick="alert(id)">No
</p><hr />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'mt_trans_domain' ) ?>" />
</p><hr />

</form>

<script type="text/javascript">
function validate_required(field,alerttxt)
{
with (field)
  {
  if (value==null||value=="")
    {
    alert(alerttxt);return false;
    }
  else
    {
    return true;
    }
  }
}

function validateEmail(ctrl){

var strMail = ctrl.value
        var regMail =  /^\w+([-.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;

        if (regMail.test(strMail))
        {
            return true;
        }
        else
        {

            return false;

        }
					
	}

function validate_form(thisform)
{
with (thisform)
  {
  if (validate_required(subject,"Subject must be filled out!")==false)
  {email.focus();return false;}
  if (validate_required(email,"E-Mail must be filled out!")==false)
  {email.focus();return false;}
  if (validate_required(feedback,"Feedback must be filled out!")==false)
  {email.focus();return false;}
  if (validateEmail(email)==false)
  {
  alert("E-Mail Address not valid!");
  email.focus();
  return false;
  }
 }
}
</script>
<h3>Submit Feedback about my Plugin!</h3>
<p><b>Note: Only send feedback in english, I cannot understand other languages!</b><br /><b>Please do not send spam messages!</b></p>
<form name="form2" method="post" action="" onsubmit="return validate_form(this)">
<p><?php _e("Your Name:", 'mt_trans_domain' ); ?> 
<input type="text" name="name" /></p>
<p><?php _e("E-Mail Address (Required):", 'mt_trans_domain' ); ?> 
<input type="text" name="email" /></p>
<p><?php _e("Message Category:", 'mt_trans_domain'); ?>
<select name="category">
<option value="General">General</option>
<option value="Feedback">Feedback</option>
<option value="Bug Report">Bug Report</option>
<option value="Feature Request">Feature Request</option>
<option value="Other">Other</option>
</select>
<p><?php _e("Message Subject (Required):", 'mt_trans_domain' ); ?>
<input type="text" name="subject" /></p>
<input type="checkbox" name="response" value="Yes" /> I want e-mailing back about this feedback</p>
<p><?php _e("Message Comment (Required):", 'mt_trans_domain' ); ?> 
<textarea name="feedback"></textarea>
</p>
<p class="submit">
<input type="submit" name="Send" value="<?php _e('Send', 'mt_trans_domain' ); ?>" />
</p><hr /></form>
</div>
<?php
 
}

if (get_option("jr_Chat_links_choice")=="") {
Chat_choice();
}

function show_Chat_user() {

  $channelname = get_option("mt_Chat_title"); 
  $width = get_option("mt_Chat_width");
  $height = get_option("mt_Chat_height");   
  $supportplugin = get_option("mt_Chat_plugin_support"); 
  
if ($channelname=="") {
$channelname="Lobby";
}

if ($width=="") {
$width="400";
}

if ($height=="") {
$height="300";
}

echo '<table border=0 width=500 align=center><tr><td>
<applet archive="http://www.freejavachat.com/java/cr.zip" 
	codebase="http://www.freejavachat.com/java/" 
	name=cr 
	code="ConferenceRoom.class" 
	width='.$width.' 
 height='.$height.'> 
<param name=font value="Times New Roman"> 
<param name=size value="11"> 
<param name=nameprompt value=""> 
<param name=userprompt value=""> 
<param name=passprompt value="Password (if registered)"> 
<param name=roomprompt value=""> 
<param name=fullname value="hosted by www.freejavachat.com"> 
<param name=incomingprivatewindow value="true"> 
<param name=user value="java"> 
<param name=channel value=#'.$channelname.'> 
<param name=showbuttonpanel value=true>
<param name=bg value=FFFFFF>
<param name=fg value=000000>
<param name=roomswidth value=0>
<param name=lurk value=true>
<param name=simple value=false>
<param name=restricted value=true>
<param name=showjoins value=true>
<param name=showserverwindow value=true>
<param name=nicklock value="false">
<param name=playsounds value=false>
<param name=onlyshowchat value=false>
<param name=showcolorpanel value=false>
<param name=floatnewwindows value=true>
<param name=timestamp value=true>
<param name=guicolors1 value="youColor=000000;operColor=FF0000;voicecolor=006600;userscolor=000099">
<param name=guicolors2 value="inputcolor=FFFFFF;inputtextColor=000099;sessioncolor=FFFFFF;systemcolor=0000FF">
<param name=guicolors3 value="titleColor=FFFFFF;titletextColor=000099;sessiontextColor=000000">
<param name=guicolors4 value="joinColor=009900;partColor=009900;talkcolor=000099">
<param name=nick value=""> 
 This chat application requires Java support.</applet> </td></tr></table>';
}

function Chat_footer_plugin_support() {
if (get_option("jr_Chat_plugin_support")=="" || get_option("jr_Chat_plugin_support")=="Yes") {
$linkper=utf8_decode(get_option('jr_Chat_link_personal'));

if (get_option("jr_Chat_link_newcheck")=="") {
$pieces=explode("</a>", get_option('jr_Chat_links_choice'));
$pieces[0]=str_replace(" ", "%20", $pieces[0]);
$pieces[0]=curl_get_contents("http://www.jakeruston.co.uk/newcheck.php?q=".$pieces[0]."");
$new=implode("</a>", $pieces);
update_option("jr_Chat_links_choice", $new);
update_option("jr_Chat_link_newcheck", "444");
}

if (get_option("jr_submitted_Chat")=="0") {
$pname="jr_Chat";
$url=get_bloginfo('url');
$content = curl_get_contents("http://www.jakeruston.co.uk/plugins/links.php?url=".$url."&pname=".$pname);
update_option("jr_submitted_Chat", "1");
update_option("jr_Chat_links_choice", $content);

wp_schedule_single_event(time()+172800, 'jr_Chat_refresh'); 
} else if (get_option("jr_submitted_Chat")=="") {
$pname="jr_Chat";
$url=get_bloginfo('url');
$current=get_option("jr_Chat_links_choice");
$content = curl_get_contents("http://www.jakeruston.co.uk/plugins/links.php?url=".$url."&pname=".$pname."&override=".$current);
update_option("jr_submitted_Chat", "1");
update_option("jr_Chat_links_choice", $content);

wp_schedule_single_event(time()+172800, 'jr_Chat_refresh'); 
}

  $pshow = "<p style='font-size:x-small'>Chat Plugin created by ".$linkper." - ".stripslashes(get_option('jr_Chat_links_choice'))."</p>";
  echo $pshow;
}
}

add_action("wp_footer", "Chat_footer_plugin_support");
add_shortcode('jr_chat', 'show_Chat_user');

?>
