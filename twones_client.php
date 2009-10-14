<?php 

/*
Le fourre-tout:

{
 "playlist" : {
   "meta"          : [
     {"http://twones.com/ns/jspf#authName"    : "marcelcorso"},
     {"http://twones.com/ns/jspf#authPassword"   : "lalala"}
     {"http://twones.com/ns/jspf#metaService" : "fake_hypem"},
     {"http://twones.com/ns/jspf#metaApiKey"  : "1234" }
   ],
   "track"         : [
     {
       "location"      : "http://example.com/2.mp3",
       "identifier"    : "http://example.com/1/",
       "title"         : "Track title",
       "creator"       : "Artist name",
       "annotation"    : "Some text",
       "info"          : "http://example.com/",
       "image"         : "http://example.com/",
       "album"         : "Album name",
       "trackNum"      : 1,
       "duration"      : 0,
       "link"          : [
         {"http://twones.com/ns/jspf#pageLink" : "http://www.last.fm/listen/globaltags/late%20night%20music"}
       ],
       "meta"          : [
        {"http://twones.com/ns/jspf#playlists" : ["metal", "rock"]},
       ]
     }
   ]
 }
}
*/

class TwonesClient {
  
  private $api_base_url = 'http://api.twones.com/v3';
  
  function __construct($service_credentials, $user_credentials) {
    $this->service_credentials = $service_credentials;
    $this->user_credentials = $user_credentials;
  }
  
  function setApiBaseURL($url){
    $this->api_base_url = $url;
  }
  
  function play($track) {
    $this->post('plays', array('playlist' => array('track' => array($track), 'meta' => $this->build_meta())));
  }
  
  function favorite($track, $playlists = array()) {
    $track['meta'] = array(array('http://twones.com/ns/jspf#playlists' => $playlists));
    $this->post('favorites', array('playlist' => array('track' => array($track), 
                                                 'meta' => $this->build_meta())));
  }
  
  function shout($track, $shout) {
    $track['annotation'] = $shout;
    $this->post('shouts', array('playlist' => array('track' => array($track), 'meta' => $this->build_meta())));
  }
 
  function join() {
    $this->post('members', $this->build_meta_flat(), 'member');
  }
 
  private 
  function post($collection, $data, $post_field = 'playlist') {
    
    $post_string = $post_field .'='.json_encode($data);

    $url = $this->api_base_url.'/'.$collection;

    $useragent = 'Twones API PHP5 Client (curl) ' . phpversion();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1 );
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    $result = curl_exec($ch);
    
    if(curl_errno($ch)) {
      $error = curl_error($ch);
      curl_close($ch);
      throw new Exception('Curl error: ' . $error);
    }
  }
  
  function build_meta() {
    return array(array("http://twones.com/ns/jspf#authName" => $this->user_credentials['username']),
            array("http://twones.com/ns/jspf#authPassword" => $this->user_credentials['password']),
            array("http://twones.com/ns/jspf#metaService" => $this->service_credentials['name']),
            array("http://twones.com/ns/jspf#metaApiKey" => $this->service_credentials['apikey']));
  }
  
  function build_meta_flat() {
    return array("http://twones.com/ns/jspf#authName" => $this->user_credentials['username'],
            "http://twones.com/ns/jspf#authPassword" => $this->user_credentials['password'],
            "http://twones.com/ns/jspf#metaService" => $this->service_credentials['name'],
            "http://twones.com/ns/jspf#metaApiKey" => $this->service_credentials['apikey']);
  } 
}

?>
