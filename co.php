<?php
//error_reporting(E_ALL ^ E_DEPRECATED);
define('DB_NAME', 'hi_chat');
define('DB_USER', 'root');
define('DB_PASSWORD', 'dany');
define('DB_HOST', 'localhost');

function co_open() {
	mysql_select_db(DB_NAME,mysql_connect(DB_HOST,DB_USER,DB_PASSWORD));
}

function co_close() {
	mysql_close(mysql_connect(DB_HOST,DB_USER,DB_PASSWORD));
}

function closetags($html) {
	#put all opened tags into an array
	preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
	$openedtags = $result[1];   #put all closed tags into an array
	preg_match_all('#</([a-z]+)>#iU', $html, $result);
	$closedtags = $result[1];
	$len_opened = count($openedtags);
	# all tags are closed
	if (count($closedtags) == $len_opened) {
		return $html;
	}
	$openedtags = array_reverse($openedtags);
	# close tags
	for ($i=0; $i < $len_opened; $i++) {
	if (!in_array($openedtags[$i], $closedtags)){
		$html .= '</'.$openedtags[$i].'>';
	} else {
		unset($closedtags[array_search($openedtags[$i], $closedtags)]);    }
	}
	return $html;
}
?>
