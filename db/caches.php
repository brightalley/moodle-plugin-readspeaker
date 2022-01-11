<?php
	/**
	 * @package    block_readspeaker_embhl
	 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
	 */

	defined('MOODLE_INTERNAL') || die();

	$definitions = array(
		'readspeaker_tokens' => array(
	    	'mode' => cache_store::MODE_APPLICATION,
			'ttl' => 60,
			'maxsize' => 100
		)
	);
