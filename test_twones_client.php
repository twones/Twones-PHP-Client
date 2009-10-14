<?php

require_once('twones_client.php');



$user_credentials      =  array('username' => 'breyten', 
                          'password' => 'oQbdbIp7VU');
$service_credentials   =  array('name' => 'breyten23111977', 
                          'apikey' => '585166706326aa6bfac16faa83220c26517d470f');

$t = new TwonesClient($service_credentials, $user_credentials);
$t->setApiBaseURL('http://api.localhost.twones.com:8888/v3');

echo "joining service...\n";
$t->join();
echo "joined!\n";

$play_data = array('title' => 'Ready to Embrace', 
                    'creator' => 'Yun Ernsting', 
                    'location' => 'http://yunmusic.com/wp-content/uploads/2009/07/Ready-to-Embrace-Yun-Ernsting.mp3',
                    'link' => array(array('http://twones.com/ns/jspf#pageLink' => 'http://yunmusic.com/')));

echo "going to play...\n";
$t->play($play_data);
echo "played!\n";


echo "going to shout...\n";
$t->shout($play_data, 
        'Amazing track! Makes me shiver all the time. UUUuuuuhhhh');
echo "shouted!\n";


echo "going to favorite...\n";
$t->favorite($play_data, 
           array('new age', 'godspell', 'gabber', 'trance'));
echo "favorited!\n";


?>
