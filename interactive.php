<?php
include("./Api140Proof.php");

$p = array();
$err = false;
$api_url = '';
$api_result = '';

if($_POST){
  $p = $_POST;

  $api = new Api140Proof($p['app_id']);

  try{
    $api->return_url = true;
    $api_url = $api->getAd($p['user_id'], $p['format'], $p['width'], $p['lat'], $p['long'], $p['lang'], $p['jsonp']);
    $api->return_url = false;
    $api_result     = $api->getAd($p['user_id'], $p['format'], $p['width'], $p['lat'], $p['long'], $p['lang'], $p['jsonp']);
  }
  catch(Exception $e){
    $err = $e->getMessage();
  }
  
}
else{
  $p['format'] = Api140Proof::HTML;
  $p['user_id'] = 'jm3';
  $p['app_id'] = 'test';
}

?>

<html>
  <head>
    <style type="text/css">
      h2,h3,pre,form {
        display: block;
        clear: both;
        margin: 10px 0;
        padding: 0;
      }
      form { width: 450px;}
      pre {
        font-family: courier;
        padding: 10px;
        background-color: #eee;
	font-size: 12px;
      }
      h1, h2 {
        font-family: Helvetica;
        text-transform: uppercase;
        color: #fff;
        background-color: #F20C76;
        padding: 5px 10px;
      }
      h2 { font-style: italic; }
      label,input,select { float: left; }
      label {
        width: 238px;
        clear: left;
        margin-right: 10px;
        text-align: right;
        padding: 5px;
        border: 1px solid #fff;
      }
      label,input {
        font-family: helvetica;
        font-size: 15px;
        line-height: 15px;
      }
      input, select {
        clear: right;
        width: 188px;
      }
      input[type="text"]{
        border: 1px solid #333;
        color: #F20C76;
        padding: 5px;
      }
      input[type="submit"]{
        clear: both;
	float: right !important;
	font-weight: bold;
	font-family: helvetica;
	text-transform: uppercase;
	font-style: italic;
      }
      .clearfix:after { 
        content: ".";  
        line-height: 1px; 
        display: block;  
        height: 0;  
        clear: both;  
        visibility: hidden; 
      }  
      .clearfix {display: inline-block;}
      form .row{
        width: 100%;
        padding: 0;
        margin: 0 0 5px 0;
      }
      form .row.submit {
	margin-top: 15px;
      }
    </style>
  </head>
  <body>
    <h1>140Proof PHP API Binding</h1>

    <h2>Request:</h2>
    
<?php if($api_url){ ?>
    <pre><a href="<?= $api_url ?>" target="_blank"><?= $api_url ?></a></pre>
<?php } else { ?>
    <pre>&nbsp;</pre>
<?php } ?>

    <form method="post" action="index.php" class="clearfix">
<div class="row clearfix">	 
      <label for="format">Format:</label>
      <select name="format">
        <option value="<?= Api140Proof::HTML ?>" <?php if($p['format'] === Api140Proof::HTML){ ?>selected<?php } ?>>HTML</option>
        <option value="<?= Api140Proof::XML  ?>" <?php if($p['format'] === Api140Proof::XML ){ ?>selected<?php } ?>>XML</option>
        <option value="<?= Api140Proof::JS   ?>" <?php if($p['format'] === Api140Proof::JS  ){ ?>selected<?php } ?>>JS</option>
        <option value="<?= Api140Proof::JSON ?>" <?php if($p['format'] === Api140Proof::JSON){ ?>selected<?php } ?>>JSON</option>
      </select>
</div>															   
<div class="row clearfix">	 
      <label for="user_id">Twitter User ID:</label>
      <input type="text" name="user_id" value="<?= $p['user_id'] ?>" />
</div>															   
<div class="row clearfix">	 
      <label for="app_id">Your 140Proof App ID:</label>
      <input type="text" name="app_id" value="<?= $p['app_id'] ?>" />
</div>															   
<div class="row clearfix">	 
      <label for="width">Width (HTML format only):</label>
      <select name="width">
        <option value="<?= Api140Proof::TINY ?>" <?php if($p['width'] === Api140Proof::TINY){ ?>selected<?php } ?>><?= Api140Proof::TINY ?>px</option>
        <option value="<?= Api140Proof::SMALL ?>" <?php if($p['width'] === Api140Proof::SMALL){ ?>selected<?php } ?>><?= Api140Proof::SMALL ?>px</option>
        <option value="<?= Api140Proof::MEDIUM ?>" <?php if($p['width'] === Api140Proof::MEDIUM){ ?>selected<?php } ?>><?= Api140Proof::MEDIUM ?>px</option>
        <option value="<?= Api140Proof::LARGE ?>" <?php if($p['width'] === Api140Proof::LARGE){ ?>selected<?php } ?>><?= Api140Proof::LARGE ?>px</option>
        <option value="<?= Api140Proof::EXTRA_LARGE ?>" <?php if($p['width'] === Api140Proof::EXTRA_LARGE){ ?>selected<?php } ?>><?= Api140Proof::EXTRA_LARGE ?>px</option>
      </select>
</div>															   
<div class="row clearfix">	 
      <label for="lat">Latitude:</label>
      <input type="text" name="lat" value="<?= $p['lat'] ?>" />
</div>															   
<div class="row clearfix">	 
      <label for="lat">Longitude:</label>
      <input type="text" name="long" value="<?= $p['long'] ?>" />
</div>															   
<div class="row clearfix">	 
      <label for="lang">Language:</label>
      <input type="text" name="lang" value="<?= $p['lang'] ?>" />
</div>															   
<div class="row clearfix">	 
      <label for="jsonp">JSONP Callback (JS format only):</label>
      <input type="text" name="jsonp" value="<?= $p['jsonp'] ?>" />
</div>															   
<div class="row clearfix submit">	 
      <input type="submit" value="submit"></input>
</div>															   
    </form>
	
<?php if($api_result){ // echo results from previous call
  ?><h2>Response:</h2><?php
  if($err){
    ?><h3>Error: <?= $err ?></h3><?php
  } else {
    $api_result = htmlentities($api_result);
    ?><pre><?= $api_result ?></pre><?php
  }
} ?>

  </body>
</html>
