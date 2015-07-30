<?php

/*********************************/
/***  MAKERBASE CUSTOM CONFIG  ***/
/*********************************/

// Image proxy/cacher
$ISOSCELES_CFG['image_proxy_passphrase']           = 'nice2bmakerbase';
$ISOSCELES_CFG['image_proxy_sig']           = md5($ISOSCELES_CFG['image_proxy_passphrase']);

// Twitter
$ISOSCELES_CFG['twitter_oauth_consumer_key']    = 'A0He9gJZUrxVNOT1WD1t8vUse';
$ISOSCELES_CFG['twitter_oauth_consumer_secret'] = '8u1501QjvKUNVCxpyp6pYnbyCNVBnCABlIkyL8RvEAWt4w8CII';

// Twitter Makerbase tokens
$ISOSCELES_CFG['twitter_makerbase_oauth_access_token']    = '3093491501-Q0cTtgFrRgbn3Slvn1aM2wX0DvnFlzgiYrH5M4G';
$ISOSCELES_CFG['twitter_makerbase_oauth_access_token_secret'] = 'A4CYpcG79wcdmJpLzoeNTZ9tzjwfYHDCSzwbVLTm1lkON';

// Rando
$ISOSCELES_CFG['thinkup_uid']    = '38523i';
$ISOSCELES_CFG['admins']         = array('makerbase', 'anildash');

//Sponsors
$ISOSCELES_CFG['sponsors'] = array(
    'Slack' => array(
        'name' => 'Slack',
        'avatar_url' => 'http://pbs.twimg.com/profile_images/378800000271328329/349dc6f270e53cbe09cd05f6c032fc67.png',
        'uid' => 'm348b6',
        'slug'=> 'slackhq'
    ),
    'MailChimp' => array(
        'name' => 'MailChimp',
        'avatar_url' => 'https://pbs.twimg.com/profile_images/615875108385656832/D_nZMonl.jpg',
        'uid' =>'9u0s6y',
        'slug'=> 'mailchimp'
    ),
    'Hover' => array(
        'name' => 'Hover',
        'avatar_url' =>'http://pbs.twimg.com/profile_images/505340149660520449/3-sIzuBq.png',
        'uid' => '7p97ga',
        'slug'=> 'hover'
    )
);

// Mandrill
$ISOSCELES_CFG['mandrill_api_key']    = 'g5zBXny4VBAp0s4TCVB27Q';

//Featured makers/projects/users
if (file_exists('featured.inc.php')) {
    require('featured.inc.php');
}
