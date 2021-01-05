<?php

return [
	'mode'                 => '',
	'format'               => 'A4',
	'default_font_size'    => '12',
	'default_font'         => 'aefurat,sans-serif',
	'margin_left'          => 10,
	'margin_right'         => 10,
	'margin_top'           => 10,
	'margin_bottom'        => 10,
	'margin_header'        => 0,
	'margin_footer'        => 0,
	'orientation'          => 'P',
	'title'                => 'Laravel mPDF',
	'author'               => '',
	'watermark'            => public_path('certificate.jpg'),
	'show_watermark'       => false,
	'watermark_font'       => 'sans-serif',
	'display_mode'         => 'fullpage',
	'watermark_text_alpha' => 0.1,
	'custom_font_dir'      => '',
	'custom_font_data' 	   => [],
	'auto_language_detection'  => false,
	'temp_dir'               => '',
	'custom_font_dir' => public_path('backend/assets/fonts/din-next/regular/'), // don't forget the trailing slash!
	'custom_font_data' => [
		'examplefont' => [
			'R'  => 'DinNextRegular.ttf',    // regular font
			'B'  => 'DinNextRegular.ttf',       // optional: bold font
			'I'  => 'DinNextRegular.ttf',     // optional: italic font
			'BI' => 'DinNextRegular.ttf' // optional: bold-italic font
		]
		// ...add as many as you want.
	]
];
