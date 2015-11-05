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

// Twitter tokens for writing notification tweets
if (isset($_SERVER['SERVER_NAME'])) {
    if ( $_SERVER['SERVER_NAME'] == 'makerbase.dev') {
        //@makerbaes is a private account only week can see. Use it on dev.
        $ISOSCELES_CFG['twitter_oauth_notifier_consumer_key']    = 'eqlMkDl7w6pQeRmNqX7FFtlP5';
        $ISOSCELES_CFG['twitter_oauth_notifier_consumer_secret'] = '9jqTtaRppyqlcYH7ZTLAobte9KC24cehxKRpiSFSWMxbregrvY';
        $ISOSCELES_CFG['twitter_oauth_notifier_access_token']    = '3906229646-IS4NCPNlwdIyu6anDgLg4oOB6j7Oa72QuJdgmfw';
        $ISOSCELES_CFG['twitter_oauth_notifier_access_token_secret'] = 'r2hS8TSXUQnJ4fwxmJ0mM6nibXNQTV2NhsrFj3WlqLUc3';
    } else {
        //@makerbase is our public company account! Use on production.
        $ISOSCELES_CFG['twitter_oauth_notifier_consumer_key']    = 'A92kmEzkkAWowxbJxh06Yqkdv';
        $ISOSCELES_CFG['twitter_oauth_notifier_consumer_secret'] = 'nHD9wldLzsCszt0rFyMdTYLNiR7XXuxr4a8zLlU6ANFv8KquiN';
        $ISOSCELES_CFG['twitter_oauth_notifier_access_token']    = '3093491501-SLBROHZzp7nBqpXl1lsD3FBfX2fMI7MEemHBKXT';
        $ISOSCELES_CFG['twitter_oauth_notifier_access_token_secret'] = 'hssinbUGGw538fIf99D6vohbgkDgBLwBXBWl7QtAC8cFd';
    }
}

// GitHub
$ISOSCELES_CFG['github_username'] = 'ginatrapani';
$ISOSCELES_CFG['github_personal_access_token'] = '7495a63997ae14b8b5cffd88b40ea81fd7112a41';

// Rando
$ISOSCELES_CFG['thinkup_uid']    = '38523i';
$ISOSCELES_CFG['admins']         = array('makerbase', 'anildash', 'ginatrapani');

//Sponsors
$ISOSCELES_CFG['sponsors'] = array(
    'Slack' => array(
        'name' => 'Slack',
        'avatar_url' => 'https://makerba.se/assets/img/sponsors/logo-square-slack.png',
        'uid' => 'm348b6',
        'slug'=> 'slackhq'
    ),
    'MailChimp' => array(
        'name' => 'MailChimp',
        'avatar_url' => 'https://makerba.se/assets/img/sponsors/logo-small-mailchimp.png',
        'uid' =>'9u0s6y',
        'slug'=> 'mailchimp'
    ),
    'Hover' => array(
        'name' => 'Hover',
        'avatar_url' =>'https://makerba.se/assets/img/sponsors/logo-small-hover.png',
        'uid' => '7p97ga',
        'slug'=> 'hover'
    )
);

// Mandrill
if (isset($_SERVER['SERVER_NAME'])) {
    if ( $_SERVER['SERVER_NAME'] == 'makerbase.dev' /*|| $_SERVER['SERVER_NAME'] == 'stage.makerba.se'*/ ) {
        //Use test key that doesn't actually send email on dev
        $ISOSCELES_CFG['mandrill_api_key']    = 'hw_9lwqlFJ753LjJUcI4FA';
    } else {
        //Production key
        $ISOSCELES_CFG['mandrill_api_key']    = 'g5zBXny4VBAp0s4TCVB27Q';
    }
}

//Featured makers/projects/users
if (file_exists('featured.inc.php')) {
    require('featured.inc.php');
}
