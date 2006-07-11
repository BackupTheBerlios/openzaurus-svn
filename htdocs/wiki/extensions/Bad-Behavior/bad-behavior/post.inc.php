<?php if (!defined('BB2_CORE')) die('I said no cheating!');

// All tests which apply specifically to POST requests
function bb2_post($package)
{
	// MovableType needs specialized screening
	if (stripos($package['headers_mixed']['User-Agent'], "MovableType") !== FALSE) {
		if (strcmp($package['headers_mixed']['Range'], "bytes=0-99999")) {
			return "7d12528e";
		}
	}

	// Trackbacks need special screening
	$request_entity = $package['request_entity'];
	if (isset($request_entity['title']) && isset($request_entity['url']) && isset($request_entity['blog_name'])) {
		require_once(BB2_CORE . "/trackback.inc.php");
		return bb2_trackback($package);
	}

	// Catch a few broken spambots
	if (isset($request_entity['	document.write(Math.round ('])) {
		return "dfd9b1ad";
	}

	// Screen by cookie/JavaScript form add
	if (isset($_COOKIE[BB2_COOKIE])) {
		$screener1 = explode(" ", $_COOKIE[BB2_COOKIE]);
	}
	if (isset($_POST[BB2_COOKIE])) {
		$screener2 = explode(" ", $_POST[BB2_COOKIE]);
	}
	$screener = max($screener[0], $screener2[0]);

	if ($screener > 0) {
		// Posting too fast? 5 sec
		// FIXME: even 5 sec is too intrusive
		// if ($screener + 5 > time())
		//	return "408d7e72";
		// Posting too slow? 48 hr
		if ($screener + 172800 < time())
			return "b40c8ddc";
	}

	// Screen by IP address
	$ip = ip2long($package['ip']);
	$ip_screener = ip2long($screener[1]);
//	FIXME: This is b0rked, but why?
//	if ($ip && $ip_screener && abs($ip_screener - $ip) > 256)
//		return "c1fa729b";

return false;

	// Screen for user agent changes
	$q = bb2_db_query("SELECT COUNT(*) FROM " . $settings['log_table'] . " WHERE (`ip` = '" . $package['ip'] . "' OR `ip` = '" . $screener[1] . "') AND `http_headers` NOT LIKE '%User-Agent: %'");
	if ($q !== FALSE)
		return "799165c2";

	return false;
}

?>