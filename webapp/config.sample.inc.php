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
