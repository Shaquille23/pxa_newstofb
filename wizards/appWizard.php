<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

define('TYPO3_MOD_PATH', '../typo3conf/ext/pxa_newstofb/wizards/');

require ('../src/facebook.php');
require ('../../../../typo3/init.php');
//require ('../../../../typo3/template.php');


$uid = $_GET["P"]['uid'];
$pid = $_GET["P"]['pid'];
$row = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'tx_pxanewstofb_config_app', 'uid = ' . intval($uid));

$appId = $row['0']['appid'];
$appSecret = $row['0']['secret'];

$facebook = new Facebook(array(
 'appId'  => $appId,
 'secret' => $appSecret,
));

$user = $facebook->getUser();

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
    $accounts = $facebook->api( '/me/accounts', 'GET', array( 'ref' => 'fbwpp' ) );
    $permissions = $facebook->api( '/me/permissions', 'GET');
    $acs = $facebook->api( '/me/accounts');
    $tocken = $facebook->getAccessToken();
 
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
} else {
  $loginUrl = $facebook->getLoginUrl();
}

$giveAccessUrl= 'https://graph.facebook.com/oauth/authorize?client_id='.$appId.'&redirect_uri=http://www.facebook.com/connect/login_success.html&scope=publish_stream,create_event,manage_pages'
?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <link rel="stylesheet" type="text/css" href="wizard.css">
    <title>php-sdk</title>
    <style>
      body {
        font-family: 'Lucida Grande', Verdana, Arial, sans-serif;
      }
      h1 a {
        text-decoration: none;
        color: #3b5998;
      }
      h1 a:hover {
        text-decoration: underline;
      }
    </style>
  </head>
  <body>
    <h1>Connection to Faceboook</h1>
    <form action="" method="post">
    <?php if ($user): ?>
       <a href="<?php echo $giveAccessUrl; ?>" target="_blank" class='button'>Grand publish access to Facebook Page</a>
    <?php else: ?>
       <a href="<?php echo $giveAccessUrl; ?>" target="_blank" class='button'>Grand publish access to Facebook Page</a><br/><br/><br/><a href="<?php echo $loginUrl; ?>" class='button'>Connect to Facebook and get Page Tocken</a>
    <?php endif ?>
    </form>
    <pre>

    <?php if ($user): ?>
   
        <?php 

        function pagePresent($pageId,$pid)
        {
          $row = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'tx_pxanewstofb_app_token', 'pid = ' . intval($pid));
          foreach ($row as $value) { 
            if(0== strcmp($value['id'], $pageId))

              return true;
          }
          return false;
        }
        //print_r($accounts);
        #print_r($tocken);
        #print_r($acs);
        #print_r($permissions);
        $data = $accounts["data"];
        $rowKey = array();
        #print_r($data);
          foreach ($data as $value ) {
            foreach ($value as $key => $value) {
              if($key!= 'perms' && $key != 'id' && $key != 'name' && $key != 'category_list')
                $rowKey[$key] = $value;
              if($key == 'id')
                $rowKey['appid'] = $value;
              if($key == 'name')
                $rowKey['title'] = $value;
            }
          $rowKey['parent'] = $uid;
          $rowKey['pid'] = $pid;
          #var_dump($rowKey);
          if(!pagePresent($rowKey['appid'],$pid))
             
            $res= $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_pxanewstofb_app_token',$rowKey);
          }
        ?>
      <strong><em>Tocken is saved</em></strong>
  </pre>
    <?php else: ?>
      <strong><em>You are not Connected.</em></strong>
    <?php endif ?>
  </body>
</html>
