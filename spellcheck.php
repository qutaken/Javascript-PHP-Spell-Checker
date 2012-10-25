<?php

/**
 * Javascript/PHP Spell Checker
 * https://github.com/sdturner02/Javascript-PHP-Spell-Checker
 *
 * Copyright 2012 LPology, LLC  
 * Released under the MIT license
 *
 * Requires the Pspell extension
 * http://www.php.net/manual/en/book.pspell.php 
 */ 

if (!empty($_SERVER['HTTP_REFERER'])) {
	if (false === strpos($_SERVER['HTTP_REFERER'],'lpology.com')) {
		exit(json_encode(array('success' => false)));
	}
}
else {
	exit(json_encode(array('success' => false)));
} 

if (isset($_REQUEST['text'])) {
	$text = $_REQUEST['text'];
} else {
	exit(json_encode(array('success' => false)));
}

if (!$pspell = pspell_new('en')) {
	exit(json_encode(array('success' => false)));
}

$words = preg_split('/[\W]+?/',$text);
$misspelled = array();
$return = array();

foreach ($words as $w) {
	if (preg_match('/^[A-Z]*$/',$w)) {
		continue;
	}
	if (!pspell_check($pspell, $w)) {
		$misspelled[] = $w;
	}
}

if (sizeof($misspelled) < 1) {
	exit(json_encode(array('success' => true, 'errors' => false)));
}

foreach ($misspelled as $m) {
	$return[$m] = pspell_suggest($pspell, $m);
}

echo json_encode(array('success' => true, 'errors' => true, 'words' => $return));