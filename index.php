<?php
  // Remember to copy files from the SDK's src/ directory to a
  // directory in your application on the server, such as php-sdk/
  require_once('lib/facebook.php');
  require_once 'lib/merge/WideImage.php';
  

// init new facebook class instance with app info (taken from the DB)
$facebook = new Facebook(array(
    'appId' => '571479746270457',
    'secret' => '4a9154054d8dfd74704b820ab6dc3e5c'
));
// get user UID
$fb_user_id = $facebook->getUser();

    // get the url where to redirect the user
$location = "". $facebook->getLoginUrl(array('scope' => 'publish_stream, email,user_birthday,user_photos'));

// check if we have valid user
if ($fb_user_id) {
    try {
        // Proceed knowing you have a logged in user who's authenticated.
        $fb_user_profile = $facebook->api('/me');   

    } catch (FacebookApiException $e) {
        $fb_user_id = NULL;
        // seems we don't have enough permissions
        // we use javascript to redirect user instead of header() due to Facebook bug
        print '<script language="javascript" type="text/javascript"> top.location.href="'. $location .'"; </script>';

        // kill the code so nothing else will happen before user gives us permissions
        die();
    }

} else {
    // seems our user hasn't logged in, redirect him to a FB login page

    print '<script language="javascript" type="text/javascript"> top.location.href="'. $location .'"; </script>';

    // kill the code so nothing else will happen before user gives us permissions
    die();
}

		$fb_user_profile = $facebook->api('/me','GET');
        $pic =  "https://graph.facebook.com/".$fb_user_profile['username'] ."/picture?width=108&height=127" ;
	   
        $access_token = $facebook->getAccessToken(); // Gives you current user's access_token

        $user = $facebook->api('/me'); // Gets User's information based on permissions the user has granted to your application.

        









       // function for retrive CDN link for DP
        function getFacebookImageFromURL($url)
            {
              $headers = get_headers($url, 1);
              if (isset($headers['Location']))
              {
                return $headers['Location'];
              }
            }

            
            $url = $pic ;
            $imageURL = getFacebookImageFromURL($url);
            
    



        // image merge works 

        $img = WideImage::load('ID--01.jpg');
        $watermark = WideImage::load($imageURL);

        // logo.jpg is 50×30 in size
         
        // place the logo directly in the center (x), 10px from bottom (y)
        
        
        // or use alignment labels, it's prettier
        $new = $img->merge($watermark, 'right-2', 'bottom-56%', 100);
        //echo '<img src="'.$new.'">'; 
        $new->saveToFile('image.png');

        // image posting on facebook

        $img = 'image.png';
        $args = array(
       'message' => 'This is what i actually need :3',
        'access_token'=>urlencode($access_token),
        );
        $args[basename($img)] = '@'.realpath($img);

        $ch = curl_init();
        $url = 'https://graph.facebook.com/me/photos';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        $data = curl_exec($ch);
        $response = json_decode($data,true);

          
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>AIUB ID</title>
</head>
<body>
	<ul>
	Conversion successfull ! Check your facebook profile

	</ul>
</body>
</html>