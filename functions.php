<?php
/* Load Parent Theme */
function theme_enqueue_styles() {

    $parent_style = 'parent-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style )
    );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

/* Skip Select Membership Type */
define("PMPRO_DEFAULT_LEVEL", "1");

/* Redirect to Clubhouse After Registration 
function my_pmpro_confirmation_url($url, $user_id, $level) {
    
    if($level->id == 1)
        $url = home_url('clubhouse');
    
    return $url;
}
add_filter('pmpro_confirmation_url', 'my_pmpro_confirmation_url', 10, 3);
*/
/* Allow shortcodes in widget areas */
add_filter('widget_text', 'do_shortcode');
/* Redirect to Clubhouse after login */
function my_login_redirect( $url, $request, $user ){
    if( $user && is_object( $user ) && is_a( $user, 'WP_User' ) ) {
        if( $user->has_cap( 'administrator' ) ) {
            $url = admin_url();
        } else {
            $url = home_url('/clubhouse/');
        }
    }
    return $url;
}
add_filter('login_redirect', 'my_login_redirect', 10, 3 );
/* User Navigation */

if ( ! function_exists( 'thrive_user_nav' ) ) {
	
	function thrive_user_nav() {

		if ( !function_exists('buddypress') ) 
		{
			return;
		}

		if ( !is_user_logged_in() ) { ?>

			<div id="user-nav-user-action" class="pull-right">

				<a title="<?php _e( 'Sign-in to your account', 'thrive' ); ?>" href="<?php echo esc_url( wp_login_url() ); ?>" class="button">
					<?php _e( 'Sign-in', 'thrive' ); ?>
				</a>

				<a title="<?php _e( 'Create account to get started', 'thrive' ); ?>" href="<?php echo esc_url( '/membership-account/membership-levels/' ); ?>" class="button">
					<?php _e( 'Register', 'thrive' ); ?>
				</a>
				
			</div>

			<div class="clearfix"></div>

			<?php

		} else {

			$message_notification = array();

			$profile_notification = array();

			?>
			<?php // Personal notifications ?>

			<?php $user_link = bp_loggedin_user_domain(); ?>

			<?php $user_notification_href = sprintf( "%s/profile", $user_link ); ?>

			<ul>
				
				<?php if ( function_exists( 'bp_notifications_get_notifications_for_user') ) { ?>
			
				<?php $notifications = bp_notifications_get_notifications_for_user( get_current_user_id() , 'string' ); ?>
				
				<?php $thrive_layout = get_theme_mod('thrive_layouts_customize', '2_columns'); ?>

				<?php if ( "1_column" === $thrive_layout ) { ?>
				<li class="item">
					<a class="hidden-sm hidden-xs" id="thrive-2-columns-search" href="<?php echo esc_url( get_search_link() );?>" alt="<?php _e("Search", "thrive"); ?>">
						<i class="material-icons md-24">search</i>
					</a>
				</li>
				<?php } ?>

				<li class="item">

					<a href="<?php echo esc_url( $user_notification_href ); ?>" title="<?php _e('My Profile', 'thrive'); ?>">
						
						<?php if ( !empty( $notifications ) ) { ?>

							<span class="thrive-user-nav-bubble">
								<?php echo count( $notifications ); ?>
							</span>

						<?php } ?>

						<i class="material-icons md-24">account_circle</i>
					</a>

					<?php if ( !empty( $notifications ) ) { ?>

						<div class="user-notifications">
							<?php if ( !empty( $notifications ) ) { ?>
							<ul class="user-notification-personal">
								<?php foreach ( $notifications as $notification ) { ?>
									<li><?php echo thrive_handle_empty_var( $notification ); ?></li>
								<?php } ?>
							</ul>
							<?php } ?>
						</div>

					<?php } ?>
				</li>
				<?php } ?>

				<?php // Unread notifications. ?>
				<?php $user_notification_list_href = sprintf( "%s/notifications", $user_link ); ?>

				<?php if ( function_exists( 'bp_notifications_get_unread_notification_count' ) ) { ?>

				<li class="item">

					<a href="<?php echo esc_url( $user_notification_list_href); ?>" title="<?php _e('See All Notifications', 'thrive'); ?>">

						<?php $unread_notifications = absint( bp_notifications_get_unread_notification_count( get_current_user_id() ) ); ?>

						<?php if ( 0 !== $unread_notifications ) { ?>
						<span class="thrive-user-nav-bubble">
							<?php echo intval( $unread_notifications ); ?>
						</span>
						<?php } ?>

						<i class="material-icons md-24">notifications</i>
					</a>


					<?php if ( 0 !== $unread_notifications ) { ?>

					<div class="user-notifications">
						<?php if ( bp_has_notifications() ) : ?>
							<ul id="notifications-ul">
								<?php while ( bp_the_notifications() ) : bp_the_notification(); ?>
									<li>
										<?php bp_the_notification_description();  ?>
									</li>
								<?php endwhile; ?>
							</ul>	
						<?php endif; ?>
					</div>

					<?php } ?>

				</li>
				<?php } ?>
				<?php if ( function_exists('messages_get_unread_count') ) { ?>

				<li class="item">

					<?php $user_messages_link = sprintf("%s%s", $user_link, bp_get_messages_slug() ); ?>

					<a href="<?php echo esc_url( $user_messages_link ); ?>" title="<?php _e('Show Unread Messages', 'thrive'); ?>">
						<i class="material-icons md-24">email</i>
					</a>

					<?php $unread_message_count = absint( messages_get_unread_count() ); ?>

					<?php if ( 0 !== $unread_message_count ) { ?>

					<span class="thrive-user-nav-bubble">

						<?php echo intval( $unread_message_count ); ?>

					</span>

					<div id="message-notification" class="user-notifications">

						<?php if ( bp_has_message_threads( 'type=unread' ) ) : ?>
							<div id="thrive-user-nav-messages-head">
								<?php _e('Messages', 'thrive'); ?>
							</div>
							<ul id="thrive-user-nav-messages">
								<?php while ( bp_message_threads() ) { ?>
								<?php bp_message_thread(); ?>
								<li>
									<a class="message-item-link" href="<?php echo esc_url( bp_message_thread_view_link() );?>" title="<?php echo sprintf( __( '%s &raquo; Read message ', 'thrive' ), bp_get_message_thread_subject() ); ?>">
										<div class="row">
											<div class="col-xs-2 messages-avatar">
												<?php bp_message_thread_avatar();  ?>
											</div>
											<div class="col-xs-10 messages-details">
												<h5><?php bp_message_thread_subject(); ?></h5>
												<p>
													<?php bp_message_thread_excerpt(); ?>
												</p>
											</div>
										</div>
									</a>
								</li>
								<?php } ?>
							</ul>
							<div class="clearfix"></div>
							
							<div id="thrive-user-nav-messages-footer">
								
								<?php $messages_link = trailingslashit( bp_loggedin_user_domain() . bp_get_messages_slug() . '/inbox' ); ?>

								<a href="<?php echo esc_url( $messages_link ); ?>" title="<?php _e('See All Messages', 'thrive'); ?>">
									<?php _e('See All Messages', 'thrive'); ?>
								</a>

							</div>

						<?php endif; ?>
					</div><!--#message-notification-->
					<?php } ?>

				</li>
				<?php } ?>
				<li class="item">
					<a href="#" title="" class="no-pd-right">
						<i class="material-icons md-24">menu</i>
					</a>
					<div class="user-notifications" id="navigation">
						<?php thrive_bp_nav_menu(); ?>
					</div>
				</li>
			</ul>
			<?php
			} // end else
		}

}  // End function exists thrive_user_nav.


/*
	Give Level 1 Members the BuddyPress "Member" Member Type
	
	1. First register the member type "student". If you are using 
	   BuddyPress v2.3+ and would like to use the Member Specific 
	   Directory option, update this function to use the 
	   bp_register_member_types hook. 
	   See: https://codex.buddypress.org/developer/member-types/
	
	2. Apply the member type on membership level change using the pmpro_after_change_membership_level hook.
	
*/
function my_pmpro_bbg_register_member_types() {
    bp_register_member_type( 'member', array(
        'labels' => array(
            'name'          => 'Members',
            'singular_name' => 'Member',
        ),
    ) );
}
add_action( 'bp_init', 'my_pmpro_bbg_register_member_types' );
function my_pmpro_bbg_member_type_after_change_membership_level($level_id, $user_id)
{
	//get user object
	$wp_user_object = new WP_User($user_id);
  
	if($level_id == 1)
	{
		//New member of level #1. Give them "Member" BuddyPress Member Type.
		bp_set_member_type( $user_id, 'member' );
	}
	elseif($level_id == 0)
	{
		//Cancelling. Remove their member type.
		bp_set_member_type( $user_id, '' );
	}
}
add_action("pmpro_after_change_membership_level", "my_pmpro_bbg_member_type_after_change_membership_level", 10, 2);

// Exclude Golf Match Requests & Handicap Corner from Blog //
function exclude_category( $query ) {
    if ( $query->is_home() && $query->is_main_query() ) {
        $query->set( 'cat', '-205,-201' );
    }
}
add_action( 'pre_get_posts', 'exclude_category' );
/*
	Sync PMPro fields to BuddyPress profile fields.
*/
function pmprobuddy_update_user_meta($meta_id, $object_id, $meta_key, $meta_value)
{		
	

	//array of user meta to mirror
	$um = array(
		"region" => "Region",			//usermeta field => buddypress profile field
		"ghin" => "GHIN",			//usermeta field => buddypress profile field
		"phone" => "Phone",			//usermeta field => buddypress profile field
		"gender" => "Gender",			//usermeta field => buddypress profile field
		
	);		
		
	//check if this user meta is to be mirrored
	foreach($um as $left => $right)
	{
		if($meta_key == $left)
		{			
			//find the buddypress field
			$field = xprofile_get_field_id_from_name($right);
			
			//update it
			if(!empty($field))
				xprofile_set_field_data($field, $object_id, $meta_value);
		}
	}
}
add_action('update_user_meta', 'pmprobuddy_update_user_meta', 10, 4);
//need to add the meta_id for add filter
function pmprobuddy_add_user_meta($object_id, $meta_key, $meta_value)
{
	pmprobuddy_update_user_meta(NULL, $object_id, $meta_key, $meta_value);
}
add_action('add_user_meta', 'pmprobuddy_add_user_meta', 10, 3);
/* Add Nav Item to BuddyPress Profile Menu 
bp_core_new_nav_item( 
    array( 
        'name' => __('Post A Score', 'buddypress'), 
        'slug' => 'http://www.scga.org/handicap/post-a-score', 
        'position' => 50, 
        'show_for_displayed_user' => false,  
        'item_css_id' => 'post-score' 
    ));
*/
// Redirect to Home Page on Log Out //
add_action('wp_logout','go_home');
function go_home(){
  wp_redirect( home_url() );
  exit();
}
add_action('pmpro_after_checkout', 'update_ghin');
function update_ghin($user_id){

if($_POST['level']=='1'){

if($_POST['ghin']==''){

$fname=$_POST['first-name'];
$lname=$_POST['last-name'];
if($_POST['gender']=='Male'){
$gender='M';
}else{
$gender='F';
}
if($_POST['saddress1']!=""){
$address1=$_POST['saddress1'];
} else {
$address1=$_POST['baddress1'];
}
if($_POST['saddress2']!=""){
$address2=$_POST['saddress2'];
} else {
$address2=$_POST['baddress2'];
}
if($_POST['scity']!=""){
$city=$_POST['scity'];
} else {
$city=$_POST['bcity'];
}
if($_POST['sstate']!=""){
$state=$_POST['sstate'];
} else {
$state=$_POST['bstate'];
}
if($_POST['szipcode']!=""){
$zip=$_POST['szipcode'];
} else {
$zip=$_POST['bzipcode'];
}

$email=$_POST['bemail'];

if($_POST['region']=='SOCAL Golfer Ventura/Coast' || $_POST['region']=='North Coastal/Central Valley'){
$club='2900';
} else if($_POST['region']=='SOCAL Golfer Greater LA Area' || $_POST['region']=='Metro LA'){
$club='2898';
} else if($_POST['region']=='SOCAL Golfer Orange/Inland' || $_POST['region']=='Orange/Inland Empire'){
$club='2899';
} else if($_POST['region']=='SOCAL Golfer Greater SD Area' || $_POST['region']=='San Diego/Coachella Valley'){
$club='2897';
}

$url='http://ghp.ghin.com/ghponline/dataservices/golfermethods.asmx/GolferAdd';
$response = wp_remote_retrieve_body(wp_remote_post($url, array('body' => array(
'userName'   => 'GolfPulp',
'password'   => 'G7p3M',
'association'   => '73',
'club'   => $club,
'service'   => '1',
'prefix'   => '',
'firstName'   => $fname,
'middleName'   => '',
'lastName'   => $lname,
'suffix'   => '',
'gender'   => $gender,
'memberType'   => 'R',
'address1'   => $address1,
'address2'   => $address2,
'city'   => $city,
'state'   => $state,
'zip'   => $zip,
'email'   => $email
))));


$xml = simplexml_load_string($response);
$json = json_encode($xml);
$array2 = json_decode($json,TRUE);
$ghin=$array2['GHINNumber'];
$resdata=print_r($array2,true);


update_user_meta($user_id, 'ghin', $ghin);

} else {

$url='http://ghp.ghin.com/ghponline/dataservices/golfermethods.asmx/FindGolfer';
$response = wp_remote_retrieve_body(wp_remote_post($url, array('body' => array(
'userName'   => 'GolfPulp',
'password'   => 'G7p3M',
'ghinNumber'   => $_POST['ghin'],
'association'   => '0',
'club'   => '0',
'service'   => '0',
'lastName'   => '',
'firstName'   => '',
'gender'   => '',
'activeOnly'   => 'false',
'includeLowHandicapIndex'   => 'false',
'includeAffiliateClubs'   => 'false'
))));


/*echo "<pre>";
echo $response;
echo "</pre>";*/

$xml = simplexml_load_string($response);
$json = json_encode($xml);
$array2 = json_decode($json,TRUE);
/*echo "<pre>";
print_r($array2);
echo "</pre>";
*/
if($array2['Golfer'][0]){
$servicefrom=$array2['Golfer'][0]['Service'];
 $assofrom=$array2['Golfer'][0]['Assoc'];
 $clubfrom=$array2['Golfer'][0]['Club'];
 } else {
$servicefrom=$array2['Golfer']['Service'];
 $assofrom=$array2['Golfer']['Assoc'];
 $clubfrom=$array2['Golfer']['Club']; 
 }


if($_POST['region']=='SOCAL Golfer Ventura/Coast' || $_POST['region']=='North Coastal/Central Valley'){
$club='2900';
} else if($_POST['region']=='SOCAL Golfer Greater LA Area' || $_POST['region']=='Metro LA'){
$club='2898';
} else if($_POST['region']=='SOCAL Golfer Orange/Inland' || $_POST['region']=='Orange/Inland Empire'){
$club='2899';
} else if($_POST['region']=='SOCAL Golfer Greater SD Area' || $_POST['region']=='San Diego/Coachella Valley'){
$club='2897';
}

$url2='http://ghp.ghin.com/ghponline/dataservices/golfermethods.asmx/GolferTransfer';
$response2 = wp_remote_retrieve_body(wp_remote_post($url2, array('body' => array(
'userName'   => 'GolfPulp',
'password'   => 'G7p3M',
'ghinNumber'   => $_POST['ghin'],
'toAssociation'   => '73',
'toClub'   => $club,
'toService'   => '1',
'fromAssociation'   => $assofrom,
'fromClub'   => $clubfrom,
'fromService'   => $servicefrom
))));


$xml = simplexml_load_string($response2);
$json = json_encode($xml);
$array3 = json_decode($json,TRUE);
/*echo "<pre>";
print_r($array3);
echo "</pre>";
exit;*/

}

global $wpdb;
$resulttt = $wpdb->query( "UPDATE `wp_pmpro_memberships_users` SET `enddate`='2016-12-31 00:00:00' WHERE user_id=".$user_id );

}

}

function get_custom_cat_template($single_template) {
     global $post;
 
       if ( in_category( 'match-requests' )) {
          $single_template = dirname( __FILE__ ) . '/single-match.php';
     }
     return $single_template;
}
 
add_filter( "single_template", "get_custom_cat_template" ) ;

/*** Customize Forgot PW Page ***/
function my_login_logo() { ?>
    <style type="text/css">
	    
	    .login, html {
		    background: #B61F38 !important;
	    }
        .login #login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/site-login-logo.png);
            background-size: cover;
            padding-bottom: 30px;
            width: 200px;
            height: 156px;
        }
        .login #nav {
	        color: #fff;
        }
        .login #nav a, #backtoblog a {
	        color: #fff !important;
        }
        .login #nav a:hover, #backtoblog a:hover {
	        color: #fff !important;
	        text-decoration: underline !important;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' ); 
function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return 'Welcome to SOCAL Golfer';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );

/*** Add Language ***/
add_action( 'after_setup_theme', 'my_child_theme_setup' );
function my_child_theme_setup() {
    load_child_theme_textdomain( 'my_child_theme', get_stylesheet_directory() . '/languages' );
}
/*** Add PW Function ***/
function pippin_change_password_form() {
	global $post;	
 
   	if (is_singular()) :
   		$current_url = get_permalink($post->ID);
   	else :
   		$pageURL = 'http';
   		if ($_SERVER["HTTPS"] == "on") $pageURL .= "s";
   		$pageURL .= "://";
   		if ($_SERVER["SERVER_PORT"] != "80") $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
   		else $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
   		$current_url = $pageURL;
   	endif;		
	$redirect = $current_url;
 
	ob_start();
 
		// show any error messages after form submission
		pippin_show_error_messages(); ?>
 
		<?php if(isset($_GET['password-reset']) && $_GET['password-reset'] == 'true') { ?>
			<div class="pippin_message success">
				<span><?php _e('Password changed successfully', 'rcp'); ?></span>
			</div>
		<?php } ?>
		<form id="pippin_password_form" method="POST" action="<?php echo $current_url; ?>">
			<fieldset>
				<p>
					<label for="pippin_user_pass"><?php _e('New Password', 'rcp'); ?></label>
					<input name="pippin_user_pass" id="pippin_user_pass" class="required" type="password"/>
				</p>
				<p>
					<label for="pippin_user_pass_confirm"><?php _e('Password Confirm', 'rcp'); ?></label>
					<input name="pippin_user_pass_confirm" id="pippin_user_pass_confirm" class="required" type="password"/>
				</p>
				<p>
					<input type="hidden" name="pippin_action" value="reset-password"/>
					<input type="hidden" name="pippin_redirect" value="<?php echo $redirect; ?>"/>
					<input type="hidden" name="pippin_password_nonce" value="<?php echo wp_create_nonce('rcp-password-nonce'); ?>"/>
					<input id="pippin_password_submit" type="submit" value="<?php _e('Change Password', 'pippin'); ?>"/>
				</p>
			</fieldset>
		</form>
	<?php
	return ob_get_clean();	
}
/*** Add Profile Fields to Members Page ***/
add_action('bp_directory_members_item', 'bphelp_dpioml');
function bphelp_dpioml(){
$bphelp_my_profile_field_1='Region';
$bphelp_my_profile_field_2='Handicap';
       if( is_user_logged_in() && bp_is_members_component() ) { ?>
        <div class="bph_xprofile_fields">
                          <p><span style="font-weight: bold;"><?php echo $bphelp_my_profile_field_1  ?>:</span>&nbsp;<?php echo bp_member_profile_data( 'field='.$bphelp_my_profile_field_1 );  ?></p>
                          <p><span style="font-weight: bold;"><?php echo $bphelp_my_profile_field_2 ?>:</span>&nbsp;<?php echo bp_member_profile_data( 'field='.$bphelp_my_profile_field_2 ); ?></p>
                </div><?php
       }
}
/*** Add Sub Nav Link ***/

function benkurbis_add_tab() {
    global $bp;
   
    bp_core_new_subnav_item( array(
        'name'              => 'My Membership',
        'slug'              => 'my-membership',
        'parent_url'        => trailingslashit( bp_displayed_user_domain() . $bp->profile->slug ),
        'parent_slug'       => $bp->profile->slug,
        'screen_function'   => 'benkurbis_add_tab_screen',
        'position'          => 50,
        'user_has_access'   => bp_is_my_profile()
    ) );       
}
add_action( 'bp_setup_nav', 'benkurbis_add_tab', 100 );
 
 
function benkurbis_add_tab_screen() {
    add_action( 'bp_template_content', 'benkurbis_add_tab_screen_content' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}
 
function benkurbis_add_tab_screen_content() {
    bp_core_redirect( 'http://socalgolfer.org/membership-account/' );
}
/*** Disallow Comment Replies ***/
add_filter('bp_activity_can_comment_reply','__return_false');
function buddydev_override_legacy_theme_hide_comments_js() {
	?>

<?php
	
}
add_action( 'bp_after_activity_loop', 'buddydev_override_legacy_theme_hide_comments_js' );

set_post_thumbnail_size( 800, 533, array( 'center', 'center')  );
?>
