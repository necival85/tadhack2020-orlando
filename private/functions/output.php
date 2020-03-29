<?php
/**
 ** VOIP Awesome Inc
 ** Jambu Atchison
 ** jambu@voipawesome.com
 **
 **/

function random_pic($dir = 'assets/backgrounds') {
  $files = glob($dir . '/*.*'); /* get files */
  $file = array_rand($files);
  return $files[$file];
} // END FUNCTION random_pic

function data_uri($file, $mime) {
  $contents = file_get_contents($file);
  $base64   = base64_encode($contents);
  return ('data:' . $mime . ';base64,' . $base64);
} // END FUNCTION data_uri

function xml_response($resp="") {
  header('Content-Type: text/xml');
  die($resp . "\n");
} // END FUNCTION xml_response

function json_response($resp=array()) {
  header('Content-Type: application/json');
  if (is_array($resp) || is_object($resp)) {
    die(json_encode($resp) . "\n");
  } else if (is_string($resp)) {
    die($resp . "\n");
  } // END IF array
} // END FUNCTION json_response

function json_encode_objs($item) {
  if (is_array($item)) {
    $pieces = array();
    foreach ($item as $k=>$v) {
      $pieces[] = "\"$k\":".json_encode_objs($v);
    }
    return '{'.implode(',',$pieces).'}';
  } else if (is_object($item)) {
    $pieces = array();
    foreach ($item as $k=>$v) {
      $pieces[] = "\"$k\":".json_encode_objs($v);
    }
    return '[{'.implode(',',$pieces).'}]';
  } else {
    return json_encode($item);
  } // END IF is_array is_object
} // END FUNCTION json_encode_objs

function format_phone($phone_number="", $method="dial", $digits=10, $prefix="1") {
  $phone_number = preg_replace("/[^0-9+]/", "", $phone_number);
  if ((substr($phone_number, 0, 1) == "+") && ($method == "dial")) {
    return $phone_number;
  } // END IF substr_count
  $prefix_len = strlen($prefix) + ((substr($phone_number, 0, 1) == "+") ? 1 : 0);
  if (strlen($phone_number) == ($digits + $prefix_len)) { $phone_number = mb_substr($phone_number, (-1 * $digits), $digits); } // remove prefix
  if ($method == "html") {
    if ((strlen($phone_number) == $digits) && ($digits == 10)) {
      $phone_npa = mb_substr($phone_number, 0, 3);
      $phone_nxx = mb_substr($phone_number, 3, 3);
      $phone_xxxx = mb_substr($phone_number, 6, 4);
      $phone_number = "+".$prefix." (".$phone_npa.") ".$phone_nxx."-".$phone_xxxx;
    } // END IF digits
  } else if (strlen($phone_number) == $digits) {
    $phone_number = $prefix . $phone_number;
  } // END IF method
  return $phone_number;
} // END FUNCTION format_phone
