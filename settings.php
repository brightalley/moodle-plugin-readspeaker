<?php

	defined('MOODLE_INTERNAL') || die();

	if ($ADMIN->fulltree) {

		$settings->add(new admin_setting_heading(
			'headerconfig',
			get_string('headerconfig', 'block_readspeaker_embhl'),
			get_string('descconfig', 'block_readspeaker_embhl')
		));

		/*$settings->add(new admin_setting_configtext(
			'readspeaker_embhl/title',
			get_string('blocktitle', 'block_readspeaker_embhl'),
			get_string('descblocktitle', 'block_readspeaker_embhl'),
			get_string('defaultblocktitle', 'block_readspeaker_embhl'),
			PARAM_RAW_TRIMMED
		));*/

		$settings->add(new admin_setting_configtext(
			'readspeaker_embhl/cid',
			get_string('customerid', 'block_readspeaker_embhl'),
			get_string('desccustomerid', 'block_readspeaker_embhl'),
			0,
			PARAM_INT
		));

		$settings->add(new admin_setting_configtext(
			'readspeaker_embhl/readid',
			get_string('readid', 'block_readspeaker_embhl'),
			get_string('descreadid', 'block_readspeaker_embhl'),
			'region-main',
			PARAM_RAW
		));

		$settings->add(new admin_setting_configselect(
			'readspeaker_embhl/lang',
			get_string('lang', 'block_readspeaker_embhl'),
			get_string('desclang', 'block_readspeaker_embhl'),
			'en_us',
			array(
				'ar_ar' => 'Arabic',
				'eu_es' => 'Basque',
				'ca_es' => 'Catalan',
				'zh_cn' => 'Chinese (Mandarin)',
				'zh_tw' => 'Chinese Taiwanese Mandarin',
				'hr_hr' => 'Croatian',
				'cs_cz' => 'Czech',
				'da_dk' => 'Danish',
				'nl_nl' => 'Dutch',
				'fy_nl' => 'Dutch (Frisian)',
				'nl_be' => 'Dutch (Flemish)',
				'en_us' => 'English (American)',
				'en_au' => 'English (Australian)',
				'en_in' => 'English (Indian)',
				'en_sc' => 'English (Scottish)',
				'en_za' => 'English (South African)',
				'en_uk' => 'English (UK)',
				'fo_fo' => 'Faroese',
				'fa_ir' => 'Farsi',
				'fi_fi' => 'Finnish',
				'fr_fr' => 'French',
				'fr_be' => 'French (Belgian)',
				'fr_ca' => 'French (Canadian)',
				'gl_es' => 'Galician',
				'he_il' => 'Hebrew',
				'de_de' => 'German',
				'el_gr' => 'Greek',
				'hi_in' => 'Hindi',
				'zh_hk' => 'Hong Kong Cantonese',
				'hu_hu' => 'Hungarian',
				'is_is' => 'Icelandic',
				'it_it' => 'Italian',
				'ja_jp' => 'Japanese',
				'ko_kr' => 'Korean',
				'es_es' => 'Spanish (Castilian)',
				'es_us' => 'Spanish (American)',
				'es_co' => 'Spanish (Columbian)',
				'es_mx' => 'Spanish (Mexican)',
				'no_nb' => 'Norwegian (Bokm&aring;l)',
				'no_nn' => 'Norwegian (Nynorska)',
				'pl_pl' => 'Polish',
				'pt_pt' => 'Portuguese',
				'pt_br' => 'Portuguese (Brazilian)',
				'ro_ro' => 'Romanian',
				'ru_ru' => 'Russian',
				'sv_se' => 'Swedish',
				'sv_fi' => 'Swedish (Finnish)',
				'th_th' => 'Thai',
				'tr_tr' => 'Turkish',
				'cy_cy' => 'Welsh'
			)
		));

		$settings->add(new admin_setting_configselect(
			'readspeaker_embhl/region',
			get_string('region', 'block_readspeaker_embhl'),
			get_string('descregion', 'block_readspeaker_embhl'),
			'eu',
			array(
				'af' => 'Africa',
				'as' => 'Asia',
				'eas' => 'East Asia',
				'eu' => 'Europe',
				'me' => 'Middle East',
				'na' => 'North America',
				'sa' => 'South America',
				'oc' => 'Oceania'
			)
		));

		$settings->add(new admin_setting_configtext(
			'readspeaker_embhl/docreaderenabled',
			get_string('textdocreaderenabled', 'block_readspeaker_embhl'),
			get_string('desctextdocreaderenabled', 'block_readspeaker_embhl'), '')
		);

		$settings->add(new admin_setting_configselect(
	        'readspeaker_embhl/showincontent',
	        get_string('showincontent', 'block_readspeaker_embhl'),
	        get_string('descshowincontent', 'block_readspeaker_embhl'),
	        '0',
	        array(
	                '0' => 'Show in block (default)',
	                '1' => 'Show in content')
	    ));

		$settings->add(new admin_setting_configtext(
			'readspeaker_embhl/customparams',
			get_string('textcustomparams', 'block_readspeaker_embhl'),
			get_string('desctextcustomparams', 'block_readspeaker_embhl'),
			'',
			PARAM_TEXT
		));
	}