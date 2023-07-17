<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/* This function is used for send mail */
function wpspaddon_send_mail( $to, $subject, $body, $attachment='' ) {
	global $wpsp_settings_data;	
	$email			=	$wpsp_settings_data['sch_email'];
	$from			=	$wpsp_settings_data['sch_name'];
	$admin_email	=	get_option( 'admin_email' );
	$email		=	!empty( $email ) ? $email : $admin_email;
	$from		=	!empty( $from ) ? $from : get_option( 'blogname'  );
	$headers	=	 array();
	if( !empty( $email ) && !empty( $from ) ) {
		$headers[]	=	"From: $from <$email>";
		$headers[] 	=	'Content-Type: text/html; charset=UTF-8';
	}	
	if( wp_mail( $to, $subject, $body, $headers, $attachment )) return true;	
	else return false;
}
?>