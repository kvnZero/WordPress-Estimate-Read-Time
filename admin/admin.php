<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add setting page
 */
add_action( 'admin_menu', function() {
	$pagename = __( '[AB] Setting');
	add_options_page(
		$pagename,
		$pagename,
		'manage_options',
		AB_Read_Time_Menu::MENU_SLUG,
		['AB_Read_Time_Menu', 'setting_page']
	);
});

add_action( 'admin_init', function() {
	register_setting( AB_Read_Time_Menu::MENU_SLUG, AB_Read_Time_Menu::MENU_SLUG );

	add_settings_section(
		'ab_setting_section',
		__( 'Basic settings' ),
		'',
		AB_Read_Time_Menu::MENU_SLUG
	);

    foreach ( AB_Read_Time_Menu::setting_fileds() as $id => $data ) {
		$args = $data['args'];
		$args['id'] = $id;

		add_settings_field(
			$id,
			$data['title'],
			['AB_Read_Time_Menu', 'settings_field_cb'],
			AB_Read_Time_Menu::MENU_SLUG,
			'ab_setting_section',
			$args
		);
	}
});


class AB_Read_Time_Menu {

    const MENU_SLUG = 'ab_read_time_setting';

    protected static $settings = null;

    /**
     * Setting
     */
    public static function setting_page()
    {
        echo '<div>'.
		'<h1>'. __('Setting') .'</h1>'.
		'<form action="options.php" method="post">';
		do_settings_sections( AB_Read_Time_Menu::MENU_SLUG );
		settings_fields( AB_Read_Time_Menu::MENU_SLUG );
		submit_button();
		echo '</form></div>';
    }

    public static function setting_fileds()
    {
        return [
            'sup_chinese' => [
                'title' => __( 'Support Chinese'),
                'args' => [
                    'type' => 'checkbox',
                    'label' => '',
                    'desc' => '', 
                ]
            ],
			'rate' => [
                'title' => __( 'Read rate' ),
                'args' => [
                    'desc' => '',
                ]
            ],
            'short_code' => [
                'title' => __( 'Short code' ),
                'args' => [
                    'desc' => '',
                ]
            ],
            'show_text_template' => [
                'title' => __( 'if you use "Astra" theme, This text will be show post header' ),
                'args' => [
                    'type' => 'textarea',
                    'rows' => 2,
                    'desc' => '',
                ]
            ],
        ];
    }

    public static function settings_field_cb( $args = [] )
    {
		$default = [
			'id'    => '',
			'type'   => 'input',
			'input_type'   => 'text',
			'choices' => [],
			'label' => '',
			'rows' => '',
			'before' => '',
			'after' => '',
			'desc' => '',
		];
		$args = array_merge( $default, $args );

		$type = $args['type'];
		if ( 'input' === $type ) {
			self::field_input( $args );
		} elseif ( 'checkbox' === $type ) {
			self::field_checkbox( $args );
		} elseif ( 'textarea' === $type ) {
			self::field_textarea( $args );
		}
    }

    public static function get_setting_value($key = '', $default = null)
    {
        if (self::$settings === null) {
            self::$settings = get_option( AB_Read_Time_Menu::MENU_SLUG ) ?: [];
        }
		$defaultValue = [
			'sup_chinese' => 'on',
			'rate' => 400,
			'short_code' => "ab_post_read_time",
			'show_text_template' => __("Read {{time}} about")
		];
        if ($key == '') {
            return array_merge($defaultValue, self::$settings);
        }
        return array_merge($defaultValue, self::$settings)[$key] ?? $default;
    }

	private static function field_input( $args ) {

		$name = 'ab_read_time_setting['. $args['id'] . ']';

		echo $args['before']. '<input id="' . $args['id'] . '" name="' . $name . '" type="'. $args['input_type']. '" value="'. self::get_setting_value($args['id']) .'" />'. $args['after'];
	}

	private static function field_textarea( $args ) {

		$name = 'ab_read_time_setting['. $args['id'] . ']';

		echo '<div class="hcb_field_textarea '. $args['id'] .'">' .
			'<textarea id="'. $args['id'] .'" name="'. $name . '" type="text" class="regular-text" rows="'. $args['rows'] . '" >'.
			self::get_setting_value($args['id']) .'</textarea>'. $args['after'].
		'</div>';
	}

	private static function field_checkbox( $args ) {

		$name = 'ab_read_time_setting['. $args['id'] . ']';

		$checked = checked( self::get_setting_value($args['id']), 'on', false );
		echo '<input type="hidden" name="'. $name.  '" value="off">'.
		'<input type="checkbox" id="'. $args['id'] . '" name="'. $name . '" value="on" '. $checked. ' />'.
		'<label for="'. $args['id'] . '">'. $args['label'] . '</label>';
	}
}