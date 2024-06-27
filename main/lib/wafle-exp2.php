<?php
// WAFLE Engine (v.exp-2b)
// Author: Bloodacote 
// Compiled at: 2024-01-12 22:40:16 
//
 class WafleCore { function __construct() { $name = "Wafle"; $author = "Bloodacote"; $version = "alpha"; $this -> options = array( "strict_exit" => true ); } public function get_option($key) { return $this -> options[$key]; } public function set_option($key, $val) { $this -> options[$key] = $val; } } $wafle = new WafleCore();  $output = null; $status = 200; $root_dir = $_SERVER["DOCUMENT_ROOT"]; $method = $_SERVER["REQUEST_METHOD"]; $error_list = array(); $input = file_get_contents("php://input"); $input = json_decode($input, true); if ($input == null) { $input = array(); }  function check_status() { global $status; if ($status >= 200 and $status < 300) { return true; } else { return false; } } function get_input($key) { global $input; if (array_key_exists($key, $input)) { return $input[$key]; } else { return null; } } function set_output($key, $value) { global $output; if ($key == null) { $output = $value; } else { $output[$key] = $value; } } function send_output_data() { global $output, $error_list, $status; if (check_status()) { $output = array( "status" => $status, "data" => $output ); } else { $output = array( "status" => $status, "errors" => $error_list ); } echo json_encode($output); } function add_error($code, $tag) { global $error_list, $status, $wafle; $status = $code; array_push($error_list, $tag); if ($wafle -> get_option("strict_exit") == true) { send_output_data(); exit(); } } function check_user_input($key, $type = null, $min_length = null, $max_length = null) { global $input; if (array_key_exists($key, $input)) { $check_value = $input[$key]; $check_value_type = gettype($check_value); if ($type != $check_value_type and $type != null) { add_error(422, "input-wrong-type--$key"); } elseif ($check_value_type == "string") { $check_value_len = mb_strlen($check_value); if ($check_value_len < $min_length and $min_length != null) { add_error(422, "input-too-short--$key"); } if ($check_value_len > $max_length and $max_length != null) { add_error(422, "input-too-long--$key"); } } } else { add_error(422, "input-no-value--$key"); } }  $default_db = null;  function db_connect($db_host = "127.0.0.1", $db_user = "root", $db_pass = "", $db_name = "test-db") { $db_dsn = "mysql:host=$db_host;dbname=$db_name"; $db_opts = [ PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ]; $pdo = new PDO($db_dsn, $db_user, $db_pass, $db_opts); return $pdo; } function db_query($pdo, $query, $placeholders = array()) { $result = $pdo -> prepare($query); $result -> execute($placeholders); return true; } function db_fetch_count($pdo, $query, $placeholders = array()) { $result = $pdo -> prepare($query); $result -> execute($placeholders); return $result -> fetch()["COUNT(*)"]; } function db_fetch_one($pdo, $query, $placeholders = array()) { $result = $pdo -> prepare($query); $result -> execute($placeholders); return $result -> fetch(); } function db_fetch_all($pdo, $query, $placeholders = array()) { $result = $pdo -> prepare($query); $result -> execute($placeholders); return $result -> fetchAll(); } function db_get_last_id($pdo) { return $pdo -> lastInsertId(); }  function hash64_encode($str) { return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($str)); } function hash64_decode($str) { return base64_decode(str_replace(['-', '_'], ['+', '/'], $str)); } function jwt_gen($payload, $secret_key) { $header = array( "typ" => "JWT", "alg" => "HS256" ); $base_header = hash64_encode(json_encode($header)); $base_payload = hash64_encode(json_encode($payload)); $signature = hash_hmac("sha256", $base_header .'.'. $base_payload, $secret_key, true); $base_signature = hash64_encode($signature); $token = $base_header .'.'. $base_payload .'.'. $base_signature; return $token; } function jwt_degen($token, $secret_key) { $token_parts = explode(".", $token); if ($token == "") { add_error(500, "no-token"); } if (count($token_parts) != 3) { add_error(500, "wrong-token-format"); } $base_header = $token_parts[0]; $base_payload = $token_parts[1]; $base_signature = $token_parts[2]; $header = hash64_decode($base_header); $payload = hash64_decode($base_payload); $signature = hash64_decode($base_signature); $new_signature = hash_hmac("sha256", $base_header .'.'. $base_payload, $secret_key, true); $is_valid = hash_equals($signature, $new_signature); if ($is_valid != 1) {$is_valid = 0;} $output = array( "valid" => $is_valid, "data" => json_decode($payload, true) ); return $output; }  function passhash_crypt($text) { $text = password_hash($text, PASSWORD_DEFAULT); return $text; } function passhash_check($text, $hashed_text) { $result = password_verify($text, $hashed_text); return $result; } 
?>