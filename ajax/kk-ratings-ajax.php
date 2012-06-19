<?php

header("Content-type: text/xml");

$path_to_root = '../../../../';
require_once($path_to_root."wp-config.php");

// check security
check_ajax_referer( 'kk-ratings' );

$kkratings_options = get_option('kk-ratings');

$userip = $_SERVER['REMOTE_ADDR'];
$Ratings['ratings'] = get_post_meta($_POST['id'], '_kk_ratings_ratings', true) ? get_post_meta($_POST['id'], '_kk_ratings_ratings', true) : false;

if(isset($_POST['id']) && ((!strcmp($_POST['op'], 'get') && $Ratings['ratings']) || (!strcmp($_POST['op'], 'put') && isset($_POST['stars']))))
{
	if(!strcmp($_POST['op'], 'put'))
	{
		if($Ratings['ratings'])
		{
		    $Ratings['casts'] = get_post_meta($_POST['id'], '_kk_ratings_casts', true);
			$Ratings['ips'] = get_post_meta($_POST['id'], '_kk_ratings_ips', true);
			update_post_meta($_POST['id'], '_kk_ratings_ratings', $Ratings['ratings'] + $_POST['stars']);
			update_post_meta($_POST['id'], '_kk_ratings_casts', $Ratings['casts'] + 1);
			update_post_meta($_POST['id'], '_kk_ratings_ips', $Ratings['ips'].'|'.$userip);
			update_post_meta($_POST['id'], '_kk_ratings_avg', round(($Ratings['ratings']/$Ratings['casts']),1));
		}
		else
		{
			update_post_meta($_POST['id'], '_kk_ratings_ratings', $_POST['stars']);
			update_post_meta($_POST['id'], '_kk_ratings_casts', 1);
			update_post_meta($_POST['id'], '_kk_ratings_ips', $userip);
			update_post_meta($_POST['id'], '_kk_ratings_avg', $_POST['stars']);
		}
	}
	
	$Ip = array();
	$Ratings['ratings'] = get_post_meta($_POST['id'], '_kk_ratings_ratings', true);
    $Ratings['casts'] = get_post_meta($_POST['id'], '_kk_ratings_casts', true);
	$Ratings['ips'] = get_post_meta($_POST['id'], '_kk_ratings_ips', true);
	$Ratings['avg'] = get_post_meta($_POST['id'], '_kk_ratings_avg', true).'/5';
	
    // Percentage
	$Ratings['per'] = round((($Ratings['ratings']/$Ratings['casts'])/5)*100);
	
	// Can user rate?
	$Ip = explode('|',$Ratings['ips']);	
	$Ratings['open'] = (in_array($userip, $Ip) && $kkratings_options['unique'])? 'no' : 'yes';
		
	// Legend
	
	$Ratings['legend'] = $kkratings_options['legend'];
	$Ratings['legend'] = str_replace('[total]',$Ratings['casts'], $Ratings['legend']);
	$Ratings['legend'] = str_replace('[avg]',$Ratings['avg'], $Ratings['legend']);
	$Ratings['legend'] = str_replace('[per]',$Ratings['per'].'%', $Ratings['legend']);
	
	echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
	echo "<response>";
	echo "<success>1</success>";
	echo "<percentage>".$Ratings['per']."</percentage>";
	echo "<legend>".$Ratings['legend']."</legend>";
	echo "<open>".$Ratings['open']."</open>";
	echo "</response>";
	exit;
}
else
{
	echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
	echo "<response>";
	echo "<error>Invalid error occurred</error>";
	echo "</response>";
	exit;
}

?>