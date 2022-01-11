<?php
/**
 * Proxy component for DocReader
 *
 * @package		block_readspeaker_embhl
 * @category	proxy
 * @copyright	2016 ReadSpeaker
 * @author		Richard Risholm
 */

/**
* Generate a random string
*
* @param int $length      How many characters do we want?
* @return string
*/
function generateRandomString($length = 20) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
// Require the Moodle configuration
require_once('../../../config.php');
global $CFG;
require_once("$CFG->libdir/filelib.php");

// Get what stage we're going through
$currentStage = optional_param('stage', 'init', PARAM_ALPHA);
if ($currentStage == 'fetch') {
	// Get the document to fetch
	$documenturl = required_param('url', PARAM_URL);

	// Check that the request is made towards the Moodle server
	if (parse_url($documenturl, PHP_URL_HOST) != parse_url($CFG->wwwroot, PHP_URL_HOST)) {
		echo "Request towards \"" . $documenturl . "\" is not allowed.";
		http_response_code(403);
		die();
	}

	// Check if the url is empty
	if ($documenturl == '') {
		echo "Error: Invalid URL provided.";
		http_response_code(404);
		die();
	}

	// Get the token
	$useToken = true;
	if (isset($_COOKIE['DocReaderToken'])) {
		$token = $_COOKIE['DocReaderToken'];
	} else {
		// The URL is being accessed directly, redirect to the document instead
		header('Location: ' . $documenturl);
		http_response_code(302);
		die();
	}

 	if ($useToken) {
		// Make connection to cache and save the cookie and token
		$cache = cache::make('block_readspeaker_embhl', 'readspeaker_tokens');
		// Get the information from the cache
		$cookies = $cache->get($token);

		if ($cookies) {
			// Delete the token from the cache
			$cache->delete($token);
		}
	}

	// Check if user is authenticated
	if (!$cookies) {
		echo "Error: Unable to lookup session information.";
		http_response_code(403);
		die();
	}

	// Close the session to allow concurrent requests
	@session_start();
	@session_write_close();

	// This is where the request comes from the DocReader server
	$curl = new curl();
	//$curl = curl_init();

	// Set some options

	$options = array(
		"CURLOPT_RETURNTRANSFER" => true,
		"CURLOPT_URL" => $documenturl,
		"CURLOPT_COOKIE" => $cookies,
		//"CURLOPT_HEADER" => true,
		"CURLOPT_FOLLOWLOCATION" => true,
		// Do not cache results
		"CURLOPT_FRESH_CONNECT" => true,
		// Set timeout values
		"CURLOPT_CONNECTTIMEOUT" => 5,
		"CURLOPT_TIMEOUT" => 5,
		"CURLOPT_COOKIEFILE" => "-"
	);

	// Get the response from the server
	$result = $curl->get($documenturl, array(), $options);
	// Check for errors
	$error = $curl->get_errno();

	// Check if the request returned a result successfully
	$httpCode = $curl->info["http_code"];
	// Because the || has gone funky, I'm doing it like this for now. Ok? Ok!
	$problem = ($httpCode != 200) ? true : ($error != 0);
	if ($problem) {
		// Print the result as it will likely be an error message
		echo $result;
		http_response_code($httpCode);
		die();
	}

	// Remove all current headers to send
	header_remove();

	// Get the response headers for the request
	$headerArray = $curl->get_raw_response();
	// Process response headers and send to client
	foreach($headerArray as $header) {
		$colonPos = strpos($header, ':');
		if ($colonPos !== FALSE) {
			$headerName = substr($header, 0, $colonPos);

			// Ignore content headers, let the webserver decide how to deal with the content
			if (trim(strtolower($headerName)) == 'content-encoding') continue;
			if (trim(strtolower($headerName)) == 'content-length') continue;
			if (trim(strtolower($headerName)) == 'transfer-encoding') continue;
			// Since we are sending back the file, sending the location header is kinda unnecessary
			if (trim(strtolower($headerName)) == 'location') continue;
		}
		// Set header, overwrite duplicates
		header($header, TRUE);
	}

	// Echo the resulting file
	echo $result;
} else {
	// This is where the request is coming from the user
	// The user should be logged in when they make a request towards the proxy
	require_login();

	// Check that curl exists
	if (new curl() === false) {
		echo "Error: Missing component in library (curl).";
		http_response_code(503);
		die();
	}

	// Get all cookies
	$checkCookie = array(
		'MOODLEID_'.$CFG->sessioncookie,
		'MoodleSession'.$CFG->sessioncookie,
		'MoodleSessionTest'.$CFG->sessioncookie
	);
	$cookies = array();
	foreach ($checkCookie as $check) {
		if (isset($_COOKIE[$check])) {
			$cookies[] = $check . '=' . $_COOKIE[$check];
		}
	}

	$sessioncookie = implode(';', $cookies);

	// Generate a random token
	$token = generateRandomString();

	// Save cookie and token in cache
	$cache = cache::make('block_readspeaker_embhl', 'readspeaker_tokens');
	if (!$cache->set($token, $sessioncookie)) {
		echo "Error: Failed to connect to cache, has it been set up?";
		http_response_code(503);
		die();
	}

	// Create a cookie to send from the token
	$cookie = 'DocReaderToken='.$token;

	$protocol = "http://";
	if (function_exists("is_https")) {
		$protocol = is_https() ? "https://" : "http://";
	} else {
		$protocol = isset($_SERVER['HTTPS']) ? "https://" : "http://";
	}

	// Close the session to allow concurrent requests
	session_write_close();

	// Redirect the user to DocReader with the token
	$redirect = str_replace('&amp;', '&', $protocol.'docreader.readspeaker.com/docreader/?'.$_SERVER['QUERY_STRING'].'&sessioncookie='.urlencode($cookie));
	header('Location: ' . $redirect);
	http_response_code(302);
	die();
}
