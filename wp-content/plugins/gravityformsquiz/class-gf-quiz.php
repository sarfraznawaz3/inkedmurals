<?php

/* example usage of the indicator filters

//easier to use if you just want to change the images
add_filter( 'gquiz_correct_indicator', 'gquiz_correct_indicator');
function gquiz_correct_indicator ($correct_answer_indicator_url){
    $correct_answer_indicator_url = 'http://myserver.com/correct.png';
    return $correct_answer_indicator_url;
}
add_filter( 'gquiz_incorrect_indicator', 'gquiz_incorrect_indicator');
function gquiz_incorrect_indicator ($incorrect_answer_indicator_url){
    $incorrect_answer_indicator_url = 'http://myserver.com/incorrect.png';
    return $incorrect_answer_indicator_url;
}


//advanced - more control
add_filter( 'gquiz_answer_indicator', 'gquiz_answer_indicator', 10, 7);
function gquiz_answer_indicator ($indicator_markup, $form, $field, $choice, $lead, $is_response_correct, $is_response_wrong){
    if ( $is_response_correct )
        $indicator_markup = ' (you got this one right!)';
    elseif ( $is_response_wrong ) {
	    if  ( $field['inputType'] == 'checkbox' && rgar( $choice, 'gquizIsCorrect' ) )
	        $indicator_markup = ' (you missed this one!)';
	    else
	        $indicator_markup = ' (you got this one wrong!)';
    } elseif ( rgar( $choice, 'gquizIsCorrect' ) ){
        $indicator_markup = ' (this was the correct answer!)';
    }
    return $indicator_markup;
}

// show values


add_filter('gform_quiz_show_choice_values', 'gquiz_show_values');
function gquiz_show_values(){
    return true;
}
*/

//------------------------------------------
GFForms::include_addon_framework();

class GFQuiz extends GFAddOn {

	protected $_version = GF_QUIZ_VERSION;
	protected $_min_gravityforms_version = '1.9.10';
	protected $_slug = 'gravityformsquiz';
	protected $_path = 'gravityformsquiz/quiz.php';
	protected $_full_path = __FILE__;
	protected $_url = 'http://www.gravityforms.com';
	protected $_title = 'Gravity Forms Quiz Add-On';
	protected $_short_title = 'Quiz';

	/**
	 * Members plugin integration
	 */
	protected $_capabilities = array(
		'gravityforms_quiz',
		'gravityforms_quiz_uninstall',
		'gravityforms_quiz_results',
		'gravityforms_quiz_settings',
		'gravityforms_quiz_form_settings'
	);

	/**
	 * Permissions
	 */
	protected $_capabilities_settings_page = 'gravityforms_quiz_settings';
	protected $_capabilities_form_settings = 'gravityforms_quiz_form_settings';
	protected $_capabilities_uninstall = 'gravityforms_quiz_uninstall';
	protected $_enable_rg_autoupgrade = true;

	private $_form_meta_by_id = array();
	private $_random_ids = array();
	private $_correct_indicator_url;
	private $_incorrect_indicator_url;

	private static $_instance = null;

	/**
	 * Get an instance of this class.
	 *
	 * @return GFQuiz
	 */
	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new GFQuiz();
		}

		return self::$_instance;
	}

	private function __clone() {
	} /* do nothing */

	/**
	 * Handles anything which requires early initialization.
	 */
	public function pre_init() {
		parent::pre_init();

		if ( $this->is_gravityforms_supported() && class_exists( 'GF_Field' ) ) {
			require_once( 'includes/class-gf-field-quiz.php' );

			add_filter( 'gform_export_field_value', array( $this, 'display_export_field_value' ), 10, 4 );
		}
	}

	/**
	 * Handles hooks and loading of language files.
	 */
	public function init() {

		/**
		 * A filter to allow the modification of the indicator when a user gets an answer correct on a quiz (Eg adding a link to your own icon)
		 */
		$this->_correct_indicator_url = apply_filters( 'gquiz_correct_indicator', $this->get_base_url() . '/images/tick.png' );

		/**
		 * A filter to allow the modification of the indicator when a user gets an answer wrong on a quiz (Eg adding a link to your own icon)
		 */
		$this->_incorrect_indicator_url = apply_filters( 'gquiz_incorrect_indicator', $this->get_base_url() . '/images/cross.png' );

		//------------------- both outside and inside admin context ------------------------

		// scripts
		add_action( 'gform_enqueue_scripts', array( $this, 'enqueue_front_end_scripts' ), 10, 2 );

		// maybe shuffle fields
		add_filter( 'gform_form_tag', array( $this, 'maybe_store_selected_field_ids' ), 10, 2 );
		add_filter( 'gform_pre_render', array( $this, 'pre_render' ) );
		add_action( 'gform_pre_validation', array( $this, 'pre_render' ) );

		// shuffle choices if configured
		add_filter( 'gform_field_content', array( $this, 'render_quiz_field_content' ), 10, 5 );

		// merge tags
		add_filter( 'gform_replace_merge_tags', array( $this, 'render_merge_tag' ), 10, 7 );

		// confirmation
		add_filter( 'gform_confirmation', array( $this, 'display_confirmation' ), 10, 4 );

		// Integration with the feed add-ons as of GF 1.9.15.12; for add-ons which don't override get_field_value().
		add_filter( 'gform_addon_field_value', array( $this, 'addon_field_value' ), 10, 4 );

		// AWeber 2.3 and newer use the gform_addon_field_value hook, only use the gform_aweber_field_value hook with older versions.
		if ( defined( 'GF_AWEBER_VERSION' ) && version_compare( GF_AWEBER_VERSION, '2.3', '<' ) ) {
			add_filter( 'gform_aweber_field_value', array( $this, 'legacy_addon_field_value' ), 10, 4 );
		}

		// Mailchimp Add-On integration
		add_filter( 'gform_mailchimp_field_value', array( $this, 'legacy_addon_field_value' ), 10, 4 );

		// Campaign Monitor Add-On integration
		add_filter( 'gform_campaignmonitor_field_value', array( $this, 'legacy_addon_field_value' ), 10, 4 );

		// Zapier Add-On integration
		add_filter( 'gform_zapier_field_value', array( $this, 'legacy_addon_field_value' ), 10, 4 );

		//------------------- admin but outside admin context ------------------------

		// display quiz results on entry footer
		add_action( 'gform_print_entry_footer', array( $this, 'print_entry_footer' ), 10, 2 );

		// add a special class to quiz fields so we can identify them later
		add_action( 'gform_field_css_class', array( $this, 'add_custom_class' ), 10, 3 );

		// display quiz results on entry detail & entry list
		add_filter( 'gform_entry_field_value', array( $this, 'display_quiz_on_entry_detail' ), 10, 4 );

		// conditional logic filters
		add_filter( 'gform_entry_meta_conditional_logic_confirmations', array( $this, 'conditional_logic_filters' ), 10, 3 );
		add_filter( 'gform_entry_meta_conditional_logic_notifications', array( $this, 'conditional_logic_filters' ), 10, 3 );

		parent::init();
	}

	/**
	 * Initialize the admin specific hooks.
	 */
	public function init_admin() {

		// form editor
		add_action( 'gform_field_standard_settings', array( $this, 'quiz_field_settings' ), 10, 2 );
		add_filter( 'gform_tooltips', array( $this, 'add_quiz_tooltips' ) );


		// display quiz results on entry detail & entry list
		add_filter( 'gform_entries_field_value', array( $this, 'display_entries_field_value' ), 10, 4 );

		if ( $this->is_gravityforms_supported( '2.0-beta-3' ) ) {
			add_filter( 'gform_entry_detail_meta_boxes', array( $this, 'register_meta_box' ), 10, 3 );
		} else {
			add_action( 'gform_entry_detail_sidebar_middle', array( $this, 'entry_detail_sidebar_middle' ), 10, 2 );
		}

		// merge tags
		add_filter( 'gform_admin_pre_render', array( $this, 'add_merge_tags' ) );

		// declare arrays on form import
		add_filter( 'gform_import_form_xml_options', array( $this, 'import_file_options' ) );


		//add the contacts tab
		add_filter( 'gform_contacts_tabs_contact_detail', array( $this, 'add_tab_to_contact_detail' ), 10, 2 );
		add_action( 'gform_contacts_tab_quiz', array( $this, 'contacts_tab' ) );

		parent::init_admin();

	}

	/**
	 * The Quiz add-on does not support logging.
	 *
	 * @param array $plugins The plugins which support logging.
	 *
	 * @return array
	 */
	public function set_logging_supported( $plugins ) {

		return $plugins;

	}


	// # SCRIPTS & STYLES -----------------------------------------------------------------------------------------------

	/**
	 * Return the scripts which should be enqueued.
	 *
	 * @return array
	 */
	public function scripts() {
		$scripts = array(
			array(
				'handle'   => 'gquiz_form_editor_js',
				'src'      => $this->get_base_url() . '/js/gquiz_form_editor.js',
				'version'  => $this->_version,
				'deps'     => array( 'jquery' ),
				'callback' => array( $this, 'localize_form_editor_scripts' ),
				'enqueue'  => array(
					array( 'admin_page' => array( 'form_editor' ) ),
				)
			),
			array(
				'handle'   => 'gquiz_form_settings_js',
				'src'      => $this->get_base_url() . '/js/gquiz_form_settings.js',
				'version'  => $this->_version,
				'deps'     => array( 'jquery', 'jquery-ui-sortable', 'gform_json' ),
				'callback' => array( $this, 'localize_form_settings_scripts' ),
				'enqueue'  => array(
					array(
						'admin_page' => array( 'form_settings' ),
						'tab'        => 'gravityformsquiz',
					),
				),
			),
		);

		return array_merge( parent::scripts(), $scripts );
	}

	/**
	 * Return the stylesheets which should be enqueued.
	 *
	 * @return array
	 */
	public function styles() {

		$styles = array(
			array(
				'handle'  => 'gquiz_form_editor_css',
				'src'     => $this->get_base_url() . '/css/gquiz_form_editor.css',
				'version' => $this->_version,
				'enqueue' => array(
					array( 'admin_page' => array( 'form_editor' ) ),
				)
			),
			array(
				'handle'  => 'gquiz_form_settings_css',
				'src'     => $this->get_base_url() . '/css/gquiz_form_settings.css',
				'version' => $this->_version,
				'enqueue' => array(
					array( 'admin_page' => array( 'form_settings' ), 'tab' => array( 'gravityformsquiz' ) ),
				)
			),
			array(
				'handle'  => 'gquiz_css',
				'src'     => $this->get_base_url() . '/css/gquiz.css',
				'version' => $this->_version,
				'enqueue' => array(
					array( 'field_types' => array( 'quiz' ) ),
					array( 'admin_page' => array( 'form_editor', 'results', 'entry_view', 'entry_detail' ) ),
				),
			),
		);

		return array_merge( parent::styles(), $styles );
	}

	/**
	 * Localize the strings used by the gquiz_form_editor_js script.
	 */
	public function localize_form_editor_scripts() {

		// Get current page protocol
		$protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
		// Output admin-ajax.php URL with same protocol as current page
		$params = array(
			'ajaxurl'   => admin_url( 'admin-ajax.php', $protocol ),
			'imagesUrl' => $this->get_base_url() . '/images'
		);
		wp_localize_script( 'gquiz_form_editor_js', 'gquizVars', $params );


		//localize strings
		$strings = array(
			'dragToReOrder'          => esc_html__( 'Drag to re-order', 'gravityformsquiz' ),
			'addAnotherGrade'        => esc_html__( 'add another grade', 'gravityformsquiz' ),
			'removeThisGrade'        => esc_html__( 'remove this grade', 'gravityformsquiz' ),
			'firstChoice'            => esc_html__( 'First Choice', 'gravityformsquiz' ),
			'secondChoice'           => esc_html__( 'Second Choice', 'gravityformsquiz' ),
			'thirdChoice'            => esc_html__( 'Third Choice', 'gravityformsquiz' ),
			'toggleCorrectIncorrect' => esc_html__( 'Click to toggle as correct/incorrect', 'gravityformsquiz' ),
			'defineAsCorrect'        => esc_html__( 'Click to define as correct', 'gravityformsquiz' ),
			'markAnAnswerAsCorrect'  => esc_html__( 'Mark an answer as correct by using the checkmark icon to the right of the answer.', 'gravityformsquiz' ),
			'defineAsIncorrect'      => esc_html__( 'Click to define as incorrect', 'gravityformsquiz' ),
		);
		wp_localize_script( 'gquiz_form_editor_js', 'gquiz_strings', $strings );

	}

	/**
	 * Localize the strings used by the gquiz_form_settings_js script.
	 */
	public function localize_form_settings_scripts() {

		// Get current page protocol
		$protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
		// Output admin-ajax.php URL with same protocol as current page
		$params = array(
			'ajaxurl'   => admin_url( 'admin-ajax.php', $protocol ),
			'imagesUrl' => $this->get_base_url() . '/images'
		);
		wp_localize_script( 'gquiz_form_settings_js', 'gquizVars', $params );


		//localize strings
		$strings = array(
			'dragToReOrder'   => esc_html__( 'Drag to re-order', 'gravityformsquiz' ),
			'addAnotherGrade' => esc_html__( 'add another grade', 'gravityformsquiz' ),
			'removeThisGrade' => esc_html__( 'remove this grade', 'gravityformsquiz' ),
		);
		wp_localize_script( 'gquiz_form_settings_js', 'gquiz_strings', $strings );

	}
	
	/**
	 * Localize the strings used by the gquiz_results_js script.
	 */
	public function localize_results_scripts() {

		$filter_fields    = rgget( 'f' );
		$filter_types     = rgget( 't' );
		$filter_operators = rgget( 'o' );
		$filter_values    = rgget( 'v' );

		// Get current page protocol
		$protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
		// Output admin-ajax.php URL with same protocol as current page

		$vars = array(
			'ajaxurl'         => admin_url( 'admin-ajax.php', $protocol ),
			'imagesUrl'       => $this->get_base_url() . '/images',
			'filterFields'    => $filter_fields,
			'filterTypes'     => $filter_types,
			'filterOperators' => $filter_operators,
			'filterValues'    => $filter_values,
		);


		wp_localize_script( 'gquiz_results_js', 'gresultsVars', $vars );

		$strings = array(
			'noFilters'         => esc_html__( 'No filters', 'gravityformsquiz' ),
			'addFieldFilter'    => esc_html__( 'Add a field filter', 'gravityformsquiz' ),
			'removeFieldFilter' => esc_html__( 'Remove a field filter', 'gravityformsquiz' ),
			'ajaxError'         => esc_html__( 'Error retrieving results. Please contact support.', 'gravityformsquiz' )
		);


		wp_localize_script( 'gquiz_results_js', 'gresultsStrings', $strings );

	}

	/**
	 * If necessary enqueue the gquiz_js script and localize the strings.
	 *
	 * @param array $form The current form.
	 * @param bool $is_ajax Is AJAX enabled.
	 */
	public function enqueue_front_end_scripts( $form, $is_ajax ) {
		$quiz_fields = GFAPI::get_fields_by_type( $form, array( 'quiz' ) );
		if ( empty ( $quiz_fields ) ) {
			return;
		}

		$instant_feedback_enabled = $this->get_form_setting( $form, 'instantFeedback' );
		if ( $instant_feedback_enabled ) {
			wp_enqueue_script( 'gquiz_js', $this->get_base_url() . '/js/gquiz.js', array( 'jquery' ), $this->_version );
			$params = array(
				'correctIndicator'   => $this->_correct_indicator_url,
				'incorrectIndicator' => $this->_incorrect_indicator_url,
			);
			wp_localize_script( 'gquiz_js', 'gquizVars', $params );

			$answers = array();
			foreach ( $quiz_fields as $quiz_field ) {
				$choices       = $quiz_field->choices;
				$correct_value = $this->get_correct_choice_value( $choices );

				$answer_explanation         = $quiz_field->gquizShowAnswerExplanation ? $quiz_field->gquizAnswerExplanation : '';
				$answers[ $quiz_field->id ] = array(
					'correctValue' => base64_encode( $correct_value ),
					'explanation'  => base64_encode( $answer_explanation )
				);
			}

			wp_localize_script( 'gquiz_js', 'gquizAnswers', $answers );
		}

	}

	
	// # RESULTS --------------------------------------------------------------------------------------------------------

	/**
	 * Configure the survey results page.
	 *
	 * @return array
	 */
	public function get_results_page_config() {
		return array(
			'title'        => esc_html__( 'Quiz Results', 'gravityformsquiz' ),
			'capabilities' => array( 'gravityforms_quiz_results' ),
			'callbacks'    => array(
				'fields'      => array( $this, 'results_fields' ),
				'calculation' => array( $this, 'results_calculation' ),
				'markup'      => array( $this, 'results_markup' ),
				'filters'     => array( $this, 'results_filters' )
			)
		);
	}

	/**
	 * Get all the quiz fields for the current form.
	 *
	 * @param array $form The current form object.
	 *
	 * @return GF_Field[]
	 */
	public function results_fields( $form ) {
		return GFAPI::get_fields_by_type( $form, array( 'quiz' ) );
	}

	/**
	 * Update the results page filters depending on how the grading for this form has been configured.
	 *
	 * @param array $filters The current filters.
	 * @param array $form The current form.
	 *
	 * @return array
	 */
	public function results_filters( $filters, $form ) {
		$unwanted_filters = array();
		$grading          = $this->get_form_setting( $form, 'grading' );
		switch ( $grading ) {
			case 'none' :
				$unwanted_filters = array( 'gquiz_score', 'gquiz_percent', 'gquiz_grade', 'gquiz_is_pass' );
				break;
			case 'passfail' :
				$unwanted_filters = array( 'gquiz_grade' );
				break;
			case 'letter' :
				$unwanted_filters = array( 'gquiz_is_pass' );
		}
		if ( empty( $unwanted_filters ) ) {
			return $filters;
		}

		foreach ( $filters as $key => $filter ) {
			if ( in_array( $filter['key'], $unwanted_filters ) ) {
				unset( $filters[ $key ] );
			}
		}

		return $filters;
	}


	/**
	 * Update the results data for this form.
	 * 
	 * @param array $data The current results data.
	 * @param array $form The current form.
	 * @param GF_Field[] $fields The Quiz fields for this form.
	 * @param array $leads The entries for this form.
	 *
	 * @return $data
	 */
	public function results_calculation( $data, $form, $fields, $leads ) {
		//$data is collected in loops of entries so check before initializing
		$sum          = (int) rgar( $data, 'sum' );
		$count_passed = (int) rgar( $data, 'count_passed' );
		if ( isset( $data['score_frequencies'] ) ) {
			$score_frequencies = rgar( $data, 'score_frequencies' );
		} else {
			//initialize counts
			$max_score = $this->get_max_score( $form );
			for ( $n = 0; $n <= $max_score; $n ++ ) {
				$score_frequencies[ intval( $n ) ] = 0;
			}
		}
		if ( isset( $data['grade_frequencies'] ) ) {
			$grade_frequencies = rgar( $data, 'grade_frequencies' );
		} else {
			//initialize counts
			$grades = $this->get_form_setting( $form, 'grades' );
			foreach ( $grades as $grade ) {
				$grade_frequencies[ $grade['text'] ] = 0;
			}
		}

		//$field_data already contains the counts for each choice so just add the totals
		$field_data = rgar( $data, 'field_data' );
		foreach ( $fields as $field ) {
			if ( false === isset( $field_data[ $field->id ]['totals'] ) ) {
				//initialize counts
				$field_data[ $field->id ]['totals']['correct'] = 0;
			}
		}

		foreach ( $leads as $lead ) {

			$results = $this->get_quiz_results( $form, $lead );
			$score   = $results['score'];
			$sum += $score;
			$score = max( floatval( $score ), 0 ); // negative quiz scores not supported
			if ( ! isset( $score_frequencies[ intval( $score ) ] ) ) {
				$score_frequencies[ intval( $score ) ] = 0;
			}
			$score_frequencies[ intval( $score ) ] = $score_frequencies[ intval( $score ) ] + 1;

			$is_pass = $results['is_pass'];
			if ( $is_pass ) {
				$count_passed ++;
			}

			$entry_grade = $results['grade'];
			if ( isset( $grade_frequencies[ $entry_grade ] ) ) {
				$grade_frequencies[ $entry_grade ] ++;
			}

			foreach ( $fields as $field ) {
				if ( $this->is_response_correct( $field, $lead ) ) {
					$field_data[ $field->id ]['totals']['correct'] += 1;
				}
			}
		}

		$entry_count               = (int) rgar( $data, 'entry_count' );
		$data['sum']               = $sum;
		$data['pass_rate']         = $entry_count > 0 ? round( $count_passed / $entry_count * 100 ) : 0;
		$data['score_frequencies'] = $score_frequencies;
		$data['grade_frequencies'] = $grade_frequencies;
		$data['field_data']        = $field_data;

		return $data;
	}

	/**
	 * Completely override the default results markup.
	 * 
	 * @param string $html The current results markup.
	 * @param array $data The results data for this form.
	 * @param array $form The current form.
	 * @param GF_Field[] $fields The quiz fields for this form.
	 *
	 * @return string
	 */
	public function results_markup( $html, $data, $form, $fields ) {

		$max_score       = $this->get_max_score( $form );
		$entry_count     = $data['entry_count'];
		$sum             = $data['sum'];
		$pass_rate       = $data['pass_rate'];
		$average_score   = $entry_count > 0 ? $sum / $entry_count : 0;
		$average_score   = round( $average_score, 2 );
		$average_percent = $entry_count > 0 ? ( $sum / ( $max_score * $entry_count ) ) * 100 : 0;
		$average_percent = round( $average_percent );
		$field_data      = $data['field_data'];

		$html .= "<table width='100%' id='gquiz-results-summary'>
						 <tr>
							<td class='gquiz-results-summary-label'>" . esc_html__( 'Total Entries', 'gravityformsquiz' ) . "</td>
							<td class='gquiz-results-summary-label'>" . esc_html__( 'Average Score', 'gravityformsquiz' ) . "</td>
							<td class='gquiz-results-summary-label'>" . esc_html__( 'Average Percentage', 'gravityformsquiz' ) . '</td>';
		$grading = $this->get_form_setting( $form, 'grading' );
		if ( $grading == 'passfail' ) {
			$html .= "  <td class='gquiz-results-summary-label'>" . esc_html__( 'Pass Rate', 'gravityformsquiz' ) . '</td>';
		}

		$html .= "  </tr>
						<tr>
							<td class='gquiz-results-summary-data'><div class='gquiz-results-summary-data-box postbox'>{$entry_count}</div></td>
							<td class='gquiz-results-summary-data'><div class='gquiz-results-summary-data-box postbox'>{$average_score}</div></td>
							<td class='gquiz-results-summary-data'><div class='gquiz-results-summary-data-box postbox'>{$average_percent}%</div></td>";
		if ( $grading == 'passfail' ) {
			$html .= "  <td class='gquiz-results-summary-data'><div class='gquiz-results-summary-data-box postbox'>{$pass_rate}%</div></td>";
		}

		$html .= '  </tr>
			  </table>';

		if ( $entry_count > 0 ) {
			$html .= "<div class='gresults-results-field-label'>" . esc_html__( 'Score Frequencies', 'gravityformsquiz' ) . "</div>";
			$html .= $this->get_score_frequencies_chart( $data['score_frequencies'] );

			if ( $grading == 'letter' ) {
				$html .= "<div class='gresults-results-field-label'>" . esc_html__( 'Grade Frequencies', 'gravityformsquiz' ) . "</div>";
				$html .= "<div class='gquiz-results-grades'>" . $this->get_grade_frequencies_chart( $data['grade_frequencies'] ) . '</div>';
			}

			foreach ( $fields as $field ) {
				$field_id = $field->id;
				$html .= "<div class='gresults-results-field' id='gresults-results-field-{$field_id}'>";
				$html .= "<div class='gresults-results-field-label'>" . esc_html( GFCommon::get_label( $field ) ) . '</div>';
				$html .= '<div>' . $this->get_field_score_results( $field, $data['field_data'][ $field_id ]['totals']['correct'], $entry_count ) . '</div>';
				$html .= '<div>' . $this->get_quiz_field_results( $field_data, $field ) . '</div>';
				$html .= '</div>';
			}
		}


		return $html;
	}

	/**
	 * Get the score markup for a single field.
	 * 
	 * @param GF_Field $field The current quiz field.
	 * @param int $total_correct The max total for this field. 
	 * @param int $entry_count The number of entries for this form.
	 *
	 * @return string
	 */
	public function get_field_score_results( $field, $total_correct, $entry_count ) {
		$field_results         = '';
		$total_correct_percent = round( $total_correct / $entry_count * 100 );
		$total_wrong           = $entry_count - $total_correct;
		$total_wrong_percent   = 100 - $total_correct_percent;

		$data_table    = array();
		$data_table [] = array( esc_html__( 'Response', 'gravityformsquiz' ), esc_html__( 'Count', 'gravityformsquiz' ) );
		$data_table [] = array( esc_html__( 'Correct', 'gravityformsquiz' ), $total_correct );
		$data_table [] = array( esc_html__( 'Incorrect', 'gravityformsquiz' ), $total_wrong );

		$chart_options = array(
			'legend'       => array(
				'position' => 'none',
			),
			'tooltip'      => array(
				'trigger' => 'none',
			),
			'pieSliceText' => 'none',
			'slices' => array(
				'0' => array(
					'color' => 'green',
				),
				'1' => array(
					'color' => 'red',
				)
			)
		);


		$data_table_json = json_encode( $data_table );
		$options_json    = json_encode( $chart_options );
		$div_id          = 'gquiz-results-chart-field-scores' . $field->id;

		$field_results .= "<div class='gquiz-field-precentages-correct'>
			<span class='gresults-label-group gresults-group-correct'>
				<span class='gresults-label'>" . esc_html__( 'Correct:', 'gravityformsquiz' ) . "</span>
				<span class='gresults-value'>{$total_correct} ({$total_correct_percent}%)</span>
			</span>
			<span class='gresults-label-group gresults-group-incorrect'>
				<span class='gresults-label'>" . esc_html__( 'Incorrect:', 'gravityformsquiz' ) . "</span>
				<span class='gresults-value'>$total_wrong ({$total_wrong_percent}%)</span>
			</div>";

		$field_results .= "<div class='gresults-chart-wrapper' style='width: 50px;height:50px;' id='{$div_id}'></div>";
		$field_results .= " <script>
							jQuery('#{$div_id}')
								.data('datatable',{$data_table_json})
								.data('options', {$options_json})
								.data('charttype', 'pie');
						</script>";

		return $field_results;

	}

	/**
	 * Get the results markup for a single field.
	 *
	 * @param array $field_data The results data for the current form.
	 * @param GF_Field $field The current quiz field.
	 *
	 * @return string
	 */
	public function get_quiz_field_results( $field_data, $field ) {
		$field_results = '';

		if ( empty( $field_data[ $field->id ] ) ) {
			$field_results .= esc_html__( 'No entries for this field', 'gravityformsquiz' );

			return $field_results;
		}
		$choices = $field->choices;

		$data_table    = array();
		$data_table[]  = array(
			esc_html__( 'Choice', 'gravityformsquiz' ),
			esc_html__( 'Frequency', 'gravityformsquiz' ),
			esc_html__( 'Frequency (Correct)', 'gravityformsquiz' )
		);

		foreach ( $choices as $choice ) {
			$text = htmlspecialchars( $choice['text'], ENT_QUOTES );
			$val  = $field_data[ $field->id ][ $choice['value'] ];
			if ( rgar( $choice, 'gquizIsCorrect' ) ) {
				$data_table[] = array( $text, 0, $val );
			} else {
				$data_table[] = array( $text, $val, 0 );
			}
		}

		$bar_height        = 40;
		$chart_area_height = ( count( $choices ) * $bar_height );
		$chart_height      = $chart_area_height + $bar_height;

		$chart_options = array(
			'isStacked' => true,
			'height'    => $chart_height,
			'chartArea' => array(
				'top'    => 0,
				'left'   => 200,
				'height' => $chart_area_height,
				'width'  => '100%',
			),
			'series'    => array(
				'0' => array(
					'color'           => 'silver',
					'visibleInLegend' => 'false',
				),
				'1' => array(
					'color'           => '#99FF99',
					'visibleInLegend' => 'false',
				)
			),
			'hAxis'     => array(
				'viewWindowMode' => 'explicit',
				'viewWindow'     => array( 'min' => 0 ),
				'title'          => esc_html__( 'Frequency', 'gravityformsquiz' )
			)
		);

		$data_table_json = json_encode( $data_table );
		$options_json    = json_encode( $chart_options );
		$div_id          = 'gquiz-results-chart-field-' . $field['id'];


		$field_results .= sprintf( '<div class="gresults-chart-wrapper" style="width: 100%%;" id=%s data-datatable=\'%s\' data-options=\'%s\' data-charttype="bar" ></div>', $div_id, $data_table_json, $options_json );

		return $field_results;

	}

	/**
	 * Determine if the entry value is correct for this field.
	 *
	 * @param GF_Field $field The current field.
	 * @param array $lead The current entry.
	 *
	 * @return bool
	 */
	public function is_response_correct( $field, $lead ) {
		$value = RGFormsModel::get_lead_field_value( $lead, $field );

		$completely_correct = true;

		$choices = $field->choices;
		foreach ( $choices as $choice ) {

			$is_choice_correct = isset( $choice['gquizIsCorrect'] ) && $choice['gquizIsCorrect'] == '1' ? true : false;

			$response_matches_choice = false;

			$user_responded = true;
			if ( is_array( $value ) ) {
				foreach ( $value as $item ) {
					if ( RGFormsModel::choice_value_match( $field, $choice, $item ) ) {
						$response_matches_choice = true;
						break;
					}
				}
			} elseif ( empty( $value ) ) {
				$response_matches_choice = false;
				$user_responded          = false;
			} else {
				$response_matches_choice = RGFormsModel::choice_value_match( $field, $choice, $value ) ? true : false;
			}

			if ( $field['inputType'] == 'checkbox' ) {
				$is_response_wrong = ( ( ! $is_choice_correct ) && $response_matches_choice ) || ( $is_choice_correct && ( ! $response_matches_choice ) ) || $is_choice_correct && ! $user_responded;
			} else {
				$is_response_wrong = ( ( ! $is_choice_correct ) && $response_matches_choice ) || $is_choice_correct && ! $user_responded;
			}

			if ( $is_response_wrong ) {
				$completely_correct = false;
			}
		}

		//end foreach choice
		return $completely_correct;
	}

	/**
	 * Return the markup for the score frequencies.
	 *
	 * @param array $score_frequencies The score frequencies.
	 *
	 * @return string
	 */
	public function get_score_frequencies_chart( $score_frequencies ) {
		$markup = '';

		$data_table    = array();
		$data_table[] = array( esc_html__( 'Score', 'gravityformsquiz' ), esc_html__( 'Frequency', 'gravityformsquiz' ) );

		foreach ( $score_frequencies as $key => $value ) {
			$data_table [] = array( (string) $key, $value );
		}

		$chart_options = array(
			'series' => array(
				'0' => array(
					'color'           => '#66CCFF',
					'visibleInLegend' => 'false',
				),
			),
			'hAxis'  => array(
				'title' => 'Score',
			),
			'vAxis'  => array(
				'title' => 'Frequency',
			)
		);

		$data_table_json = json_encode( $data_table );
		$options_json    = json_encode( $chart_options );
		$div_id          = 'gquiz-results-chart-field-score-frequencies';
		$markup .= "<div class='gresults-chart-wrapper' style='width:100%;height:250px;' id='{$div_id}'></div>";
		$markup .= "<script>
					jQuery('#{$div_id}')
						.data('datatable',{$data_table_json})
						.data('options', {$options_json})
						.data('charttype', 'column');
				</script>";

		return $markup;

	}

	/**
	 * Generate the markup for the grade frequencies.
	 *
	 * @param array $grade_frequencies The grade frequencies.
	 *
	 * @return string
	 */
	public function get_grade_frequencies_chart( $grade_frequencies ) {
		$markup = '';

		$data_table    = array();
		$data_table[] = array( esc_html__( 'Grade', 'gravityformsquiz' ), esc_html__( 'Frequency', 'gravityformsquiz' ) );

		foreach ( $grade_frequencies as $key => $value ) {
			$data_table[] = array( (string) $key, $value );
		}

		$chart_options = array(
			'series' => array(
				'0' => array(
					'color'           => '#66CCFF',
					'visibleInLegend' => 'false',
				),
			),
			'hAxis'  => array(
				'title' => esc_html__( 'Score', 'gravityformsquiz' ),
			),
			'vAxis'  => array(
				'title' => esc_html__( 'Frequency', 'gravityformsquiz' ),
			)
		);

		$data_table_json = json_encode( $data_table );
		$options_json    = json_encode( $chart_options );
		$div_id          = 'gquiz-results-chart-field-grade-frequencies';

		$markup .= "<div class='gresults-chart-wrapper' style='width:100%;height:250px;' id='{$div_id}'></div>";
		$markup .= "<script>
					jQuery('#{$div_id}')
						.data('datatable',{$data_table_json})
						.data('options', {$options_json})
						.data('charttype', 'column');
				</script>";

		return $markup;

	}


	// # MERGE TAGS -----------------------------------------------------------------------------------------------------

	/**
	 * Add the result merge tags to the merge tag drop downs in the admin.
	 *
	 * @param array $form The current form.
	 *
	 * @return array
	 */
	public function add_merge_tags( $form ) {
		$quiz_fields = GFAPI::get_fields_by_type( $form, array( 'quiz' ) );

		if ( empty( $quiz_fields ) ) {
			return $form;
		}

		foreach ( $quiz_fields as $field ) {
			$field_id     = $field->id;
			$field_label  = $field->label;
			$group        = $field->isRequired ? 'required' : 'optional';
			$merge_tags[] = array(
				'group' => $group,
				'label' => esc_html__( 'Quiz Results: ', 'gravityformsquiz' ) . $field_label,
				'tag'   => "{quiz:id={$field_id}}"
			);
			$merge_tags[] = array(
				'group' => $group,
				'label' => esc_html__( 'Quiz Score: ', 'gravityformsquiz' ) . $field_label,
				'tag'   => "{quiz_score:id={$field_id}}"
			);
		}

		$merge_tags[] = array( 'group' => 'other', 'label' => esc_html__( 'All Quiz Results', 'gravityformsquiz' ), 'tag' => '{all_quiz_results}' );
		$merge_tags[] = array( 'group' => 'other', 'label' => esc_html__( 'Quiz Score Total', 'gravityformsquiz' ), 'tag' => '{quiz_score}' );
		$merge_tags[] = array( 'group' => 'other', 'label' => esc_html__( 'Quiz Score Percentage', 'gravityformsquiz' ), 'tag' => '{quiz_percent}' );
		$merge_tags[] = array( 'group' => 'other', 'label' => esc_html__( 'Quiz Grade', 'gravityformsquiz' ), 'tag' => '{quiz_grade}' );
		$merge_tags[] = array( 'group' => 'other', 'label' => esc_html__( 'Quiz Pass/Fail', 'gravityformsquiz' ), 'tag' => '{quiz_passfail}' );

		?>
		<script type="text/javascript">
			if (window.gform)
				gform.addFilter('gform_merge_tags', 'gquiz_add_merge_tags');
			function gquiz_add_merge_tags(mergeTags, elementId, hideAllFields, excludeFieldTypes, isPrepop, option) {
				if (isPrepop)
					return mergeTags;
				var customMergeTags = <?php echo json_encode( $merge_tags ); ?>;
				jQuery.each(customMergeTags, function (i, customMergeTag) {
					mergeTags[customMergeTag.group].tags.push({tag: customMergeTag.tag, label: customMergeTag.label});
				});

				return mergeTags;
			}
		</script>
		<?php
		//return the form object from the php hook
		return $form;
	}

	/**
	 * Replace the result merge tags.
	 *
	 * @param string $text The current text in which merge tags are being replaced.
	 * @param array $form The current form object.
	 * @param array $entry The current entry object.
	 * @param bool $url_encode Whether or not to encode any URLs found in the replaced value.
	 * @param bool $esc_html Whether or not to encode HTML found in the replaced value.
	 * @param bool $nl2br Whether or not to convert newlines to break tags.
	 * @param string $format The format requested for the location the merge is being used. Possible values: html, text or url.
	 *
	 * @return string
	 */
	public function render_merge_tag( $text, $form, $entry, $url_encode, $esc_html, $nl2br, $format ) {

		if ( empty( $entry ) || empty( $form ) ) {
			return $text;
		}

		$quiz_fields = GFAPI::get_fields_by_type( $form, array( 'quiz' ) );
		if ( empty ( $quiz_fields ) ) {
			return $text;
		}

		$results = $this->get_quiz_results( $form, $entry );

		$text          = str_replace( '{all_quiz_results}', $results['summary'], $text );
		$text          = str_replace( '{quiz_score}', $results['score'], $text );
		$text          = str_replace( '{quiz_percent}', $results['percent'], $text );
		$text          = str_replace( '{quiz_grade}', $results['grade'], $text );
		$is_pass       = $results['is_pass'];
		$pass_fail_str = $is_pass ? esc_html__( 'Pass', 'gravityformsquiz' ) : esc_html__( 'Fail', 'gravityformsquiz' );
		$text          = str_replace( '{quiz_passfail}', $pass_fail_str, $text );

		preg_match_all( "/\{quiz:(.*?)\}/", $text, $matches, PREG_SET_ORDER );
		if ( ! empty( $matches ) ) {
			foreach ( $matches as $match ) {
				$full_tag = $match[0];

				$options_string = isset( $match[1] ) ? $match[1] : '';
				$options        = shortcode_parse_atts( $options_string );

				extract(
					shortcode_atts(
						array(
							'id' => 0,
						), $options
					)
				);

				$fields              = $results['fields'];
				$result_field_markup = '';
				foreach ( $fields as $results_field ) {
					if ( $results_field['id'] == $id ) {
						$result_field_markup = $results_field['markup'];
						break;
					}
				}
				$new_value = $result_field_markup;

				$text = str_replace( $full_tag, $new_value, $text );

			}
		}

		preg_match_all( "/\{quiz_score:(.*?)\}/", $text, $matches, PREG_SET_ORDER );
		if ( empty( $matches ) ) {
			return $text;
		}

		foreach ( $matches as $match ) {
			$full_tag = $match[0];

			$options_string = isset( $match[1] ) ? $match[1] : '';
			$options        = shortcode_parse_atts( $options_string );

			extract(
				shortcode_atts(
					array(
						'id' => 0,
					), $options
				)
			);

			$fields      = $results['fields'];
			$field_score = 0;
			foreach ( $fields as $results_field ) {
				if ( $results_field['id'] == $id ) {
					$field_score = $results_field['score'];
					break;
				}
			}
			$new_value = $field_score;

			$text = str_replace( $full_tag, $new_value, $text );

		}

		return $text;

	}


	// # FORM RENDER & SUBMISSION ---------------------------------------------------------------------------------------

	/**
	 * If necessary configure the select placeholder and shuffle the fields before the form is displayed.
	 *
	 * @param array $form The form currently being processed for display.
	 *
	 * @return array
	 */
	public function pre_render( $form ) {

		$quiz_fields = GFAPI::get_fields_by_type( $form, array( 'quiz' ) );
		if ( empty ( $quiz_fields ) ) {
			return $form;
		}

		//maybe shuffle fields
		if ( rgars( $form, $this->_slug . '/shuffleFields' ) ) {
			$random_ids    = $this->get_random_ids( $form );
			$c             = 0;
			$page_number   = 1;
			$random_fields = array();
			foreach ( $random_ids as $random_id ) {
				$random_fields[] = $this->get_field_by_id( $form, $random_id );
			}
			foreach ( $form['fields'] as $key => $field ) {
				if ( $field->type == 'quiz' ) {
					$form['fields'][ $key ]             = $random_fields[ $c ++ ];
					$form['fields'][ $key ]->pageNumber = $page_number;
				} elseif ( $field->type == 'page' ) {
					$page_number ++;
				}
			}
		}

		foreach ( $form['fields'] as &$field ) {
			if ( $field->type != 'quiz' ) {
				continue;
			}

			if ( $field->inputType == 'select' && ! $field->placeholder ) {
				$field->placeholder = esc_html__( 'Select one', 'gravityformsquiz' );
			}
		}

		return $form;
	}

	/**
	 * Retreive the specified quiz field.
	 *
	 * @param array $form The current form.
	 * @param int $field_id The ID of the field to be retrieved.
	 *
	 * @return GF_Field
	 */
	public function get_field_by_id( $form, $field_id ) {
		foreach ( $form['fields'] as $field ) {
			if ( $field->id == $field_id ) {
				return $field;
			}
		}
	}

	/**
	 * Return a randomized array containing the quiz field IDs.
	 *
	 * @param array $form The current form.
	 *
	 * @return array
	 */
	public function get_random_ids( $form ) {

		$random_ids = array();
		if ( false === empty( $this->_random_ids ) ) {
			$random_ids = $this->_random_ids;
		} elseif ( rgpost( 'gquiz_random_ids' ) ) {
			$random_ids = explode( ',', rgpost( 'gquiz_random_ids' ) );
		} else {
			$quiz_fields = GFAPI::get_fields_by_type( $form, array( 'quiz' ) );
			foreach ( $quiz_fields as $quiz_field ) {
				$random_ids[] = $quiz_field->id;
			}
			shuffle( $random_ids );
			$this->_random_ids = $random_ids;
		}

		return $random_ids;
	}

	/**
	 * If the fields were shuffled append a hidden field after the form tag containing the IDs.
	 *
	 * @param string $form_tag The form tag for the current form.
	 * @param array $form The current form.
	 *
	 * @return string
	 */
	public function maybe_store_selected_field_ids( $form_tag, $form ) {
		if ( $this->get_form_setting( $form, 'shuffleFields' ) ) {
			$value = implode( ',', $this->get_random_ids( $form ) );
			$input = "<input type='hidden' value='$value' name='gquiz_random_ids'>";
			$form_tag .= $input;
		}

		return $form_tag;
	}

	/**
	 * If necessary randomize the field choices.
	 *
	 * @param string $content The field content to be filtered.
	 * @param GF_Field $field The field currently being processed for display.
	 * @param string|array $value The field value. From default/dynamic population, $_POST, or a resumed incomplete submission.
	 * @param int $lead_id The ID of the entry if the field is being displayed on the entry detail page.
	 * @param int $form_id The ID of the current form.
	 *
	 * @return string
	 */
	public function render_quiz_field_content( $content, $field, $value, $lead_id, $form_id ) {

		if ( ! $this->is_form_editor() && $lead_id === 0 && $field->type == 'quiz' ) {

			//maybe shuffle choices
			if ( $field->gquizEnableRandomizeQuizChoices ) {

				//pass the HTML for the choices through DOMdocument to make sure we get the complete li node
				$dom     = new DOMDocument();
				$content = '<?xml version="1.0" encoding="UTF-8"?>' . $content;
				$loader  = libxml_disable_entity_loader( true );
				$errors  = libxml_use_internal_errors( true );
				$dom->loadHTML( $content );
				libxml_clear_errors();
				libxml_use_internal_errors( $errors );
				libxml_disable_entity_loader( $loader );

				$content = $dom->saveXML( $dom->documentElement );

				//pick out the elements: LI for radio & checkbox, OPTION for select
				$element_name = $field->inputType == 'select' ? 'select' : 'ul';
				$nodes        = $dom->getElementsByTagName( $element_name )->item( 0 )->childNodes;

				//cycle through the LI elements and swap them around randomly
				$temp_str1 = 'gquiz_shuffle_placeholder1';
				$temp_str2 = 'gquiz_shuffle_placeholder2';
				for ( $i = $nodes->length - 1; $i >= 0; $i -- ) {
					$n = rand( 0, $i );
					if ( $i <> $n ) {
						$i_str   = $dom->saveXML( $nodes->item( $i ) );
						$n_str   = $dom->saveXML( $nodes->item( $n ) );
						$content = str_replace( $i_str, $temp_str1, $content );
						$content = str_replace( $n_str, $temp_str2, $content );
						$content = str_replace( $temp_str2, $i_str, $content );
						$content = str_replace( $temp_str1, $n_str, $content );
					}
				}

				//snip off the tags that DOMdocument adds
				$content = str_replace( '<html><body>', '', $content );
				$content = str_replace( '</body></html>', '', $content );

			}
		}

		return $content;
	}

	/**
	 * If necessary update the confirmation to include the results.
	 *
	 * @param string|array $confirmation The forms current confirmation.
	 * @param array $form The form currently being processed.
	 * @param array $lead The entry currently being processed.
	 * @param bool $ajax Indicates if AJAX is enabled for this form.
	 *
	 * @return string|array
	 */
	public function display_confirmation( $confirmation, $form, $lead, $ajax ) {
		$grading = $this->get_form_setting( $form, 'grading' );
		if ( $grading != 'none' ) {

			// make sure there are quiz fields on the form
			$quiz_fields = GFAPI::get_fields_by_type( $form, array( 'quiz' ) );
			if ( empty ( $quiz_fields ) ) {
				return $confirmation;
			}

			switch ( $grading ) {
				case 'passfail' :
					$display_confirmation = $this->get_form_setting( $form, 'passfailDisplayConfirmation' );
					if ( false === $display_confirmation ) {
						return $confirmation;
					}
					break;
				case 'letter' :
					$display_confirmation = $this->get_form_setting( $form, 'letterDisplayConfirmation' );
					if ( false === $display_confirmation ) {
						return $confirmation;
					}
					break;
				default;
					return $confirmation;
			}

			$form_id = $form['id'];

			// override confirmation in the case of page redirect
			if ( is_array( $confirmation ) && array_key_exists( 'redirect', $confirmation ) ) {
				$confirmation = '';
			}

			// override confirmation in the case of a url redirect
			$str_pos = strpos( $confirmation, 'gformRedirect' );
			if ( false !== $str_pos ) {
				$confirmation = '';
			}

			$has_confirmation_wrapper = false !== strpos( $confirmation, 'gform_confirmation_wrapper' ) ? true : false;

			if ( $has_confirmation_wrapper ) {
				$confirmation = substr( $confirmation, 0, strlen( $confirmation ) - 6 );
			} //remove the closing div of the wrapper

			$has_confirmation_message = false !== strpos( $confirmation, 'gforms_confirmation_message' ) ? true : false;

			if ( $has_confirmation_message ) {
				$confirmation = substr( $confirmation, 0, strlen( $confirmation ) - 6 );
			} //remove the closing div of the message
			else {
				$confirmation .= "<div id='gforms_confirmation_message' class='gform_confirmation_message_{$form_id}'>";
			}

			$results           = $this->get_quiz_results( $form, $lead );
			$quiz_confirmation = '<div id="gquiz_confirmation_message">';
			$nl2br             = true;
			if ( $grading == 'letter' ) {
				$quiz_confirmation .= $this->get_form_setting( $form, 'letterConfirmationMessage' );
				if ( $this->get_form_setting( $form, 'letterConfirmationDisableAutoformat' ) === true ) {
					$nl2br = false;
				}
			} else {
				if ( $results['is_pass'] ) {
					$quiz_confirmation .= $this->get_form_setting( $form, 'passConfirmationMessage' );
					if ( $this->get_form_setting( $form, 'passConfirmationDisableAutoformat' ) === true ) {
						$nl2br = false;
					}
				} else {
					$quiz_confirmation .= $this->get_form_setting( $form, 'failConfirmationMessage' );
					if ( $this->get_form_setting( $form, 'failConfirmationDisableAutoformat' ) === true ) {
						$nl2br = false;
					}
				}
			}
			$quiz_confirmation .= '</div>';


			$confirmation .= GFCommon::replace_variables( $quiz_confirmation, $form, $lead, $url_encode = false, $esc_html = true, $nl2br, $format = 'html' ) . '</div>';
			if ( $has_confirmation_wrapper ) {
				$confirmation .= '</div>';
			}
		}

		return $confirmation;
	}


	// # ENTRY RELATED --------------------------------------------------------------------------------------------------

	/**
	 * Add the Quiz results entry meta properties.
	 *
	 * @param array $entry_meta An array of entry meta already registered with the gform_entry_meta filter.
	 * @param int $form_id The form id
	 *
	 * @return array The filtered entry meta array.
	 */
	public function get_entry_meta( $entry_meta, $form_id ) {
		if ( empty( $form_id ) ) {
			return $entry_meta;
		}
		$form        = RGFormsModel::get_form_meta( $form_id );
		$quiz_fields = GFAPI::get_fields_by_type( $form, array( 'quiz' ) );
		if ( false === empty ( $quiz_fields ) ) {
			$grading = $this->get_form_setting( $form, 'grading' );

			$entry_meta['gquiz_score']   = array(
				'label'                      => esc_html__( 'Quiz Score Total', 'gravityformsquiz' ),
				'is_numeric'                 => true,
				'is_default_column'          => true,
				'update_entry_meta_callback' => array( $this, 'update_entry_meta' ),
				'filter'                     => array(
					'operators' => array( 'is', 'isnot', '>', '<' )
				)
			);
			$entry_meta['gquiz_percent'] = array(
				'label'                      => esc_html__( 'Quiz Percentage', 'gravityformsquiz' ),
				'is_numeric'                 => true,
				'is_default_column'          => $grading == 'letter' || $grading == 'passfail' ? true : false,
				'update_entry_meta_callback' => array( $this, 'update_entry_meta' ),
				'filter'                     => array(
					'operators' => array( 'is', 'isnot', '>', '<' )
				)
			);
			$entry_meta['gquiz_grade']   = array(
				'label'                      => esc_html__( 'Quiz Grade', 'gravityformsquiz' ),
				'is_numeric'                 => false,
				'is_default_column'          => $grading == 'letter' ? true : false,
				'update_entry_meta_callback' => array( $this, 'update_entry_meta' ),
				'filter'                     => array(
					'operators' => array( 'is', 'isnot' )
				)
			);
			$entry_meta['gquiz_is_pass'] = array(
				'label'                      => esc_html__( 'Quiz Pass/Fail', 'gravityformsquiz' ),
				'is_numeric'                 => false,
				'is_default_column'          => $grading == 'passfail' ? true : false,
				'update_entry_meta_callback' => array( $this, 'update_entry_meta' ),
				'filter'                     => array(
					'operators'       => array( 'is', 'isnot' ),
					'choices'         => array(
						0 => array( 'value' => '1', 'text' => 'Pass' ),
						1 => array( 'value' => '0', 'text' => 'Fail' )
					),
					'preventMultiple' => true,
				)
			);

		}

		return $entry_meta;
	}

	/**
	 * Used to update the Quiz entry meta properties.
	 *
	 * @param string $key The key of the property to be updated.
	 * @param array $entry The current entry object.
	 * @param array $form The current form object.
	 *
	 * @return mixed
	 */
	public function update_entry_meta( $key, $entry, $form ) {
		$value   = '';
		$results = $this->get_quiz_results( $form, $entry, false );

		if ( $key == 'gquiz_score' ) {
			$value = $results['score'];
		} elseif ( $key == 'gquiz_percent' ) {
			$value = $results['percent'];
		} elseif ( $key == 'gquiz_grade' ) {
			$value = $results['grade'];
		} elseif ( $key == 'gquiz_is_pass' ) {
			$value = $results['is_pass'] ? '1' : '0';
		}

		return $value;
	}

	/**
	 * Remove the Quiz entry meta from the conditional logic filters on the notifications/confirmations pages.
	 *
	 * @param array $filters The array of filters.
	 * @param array $form The current form object.
	 * @param string $id The ID of the notification/confirmation being edited.
	 *
	 * @return mixed
	 */
	public function conditional_logic_filters( $filters, $form, $id ) {
		$quiz_fields = GFAPI::get_fields_by_type( $form, array( 'quiz' ) );
		if ( empty( $quiz_fields ) ) {
			return $filters;
		}

		switch ( $this->get_form_setting( $form, 'grading' ) ) {
			case 'letter' :
				if ( false === isset ( $form['gquizDisplayConfirmationLetter'] ) || $form['gquizDisplayConfirmationLetter'] ) {
					unset( $filters['gquiz_is_pass'] );
				}
				break;
			case 'passfail' :
				if ( false === isset ( $form['gquizDisplayConfirmationPassFail'] ) || $form['gquizDisplayConfirmationPassFail'] ) {
					unset( $filters['gquiz_grade'] );
				}
				break;
			default:
				unset( $filters['gquiz_grade'] );
				unset( $filters['gquiz_is_pass'] );
		}

		return $filters;

	}

	/**
	 * Format the value for the gquiz_is_pass and gquiz_percent entry meta.
	 *
	 * @param string $value The field value.
	 * @param string $field_id The entry meta key.
	 *
	 * @return string
	 */
	public function maybe_format_entry_meta_value( $value, $field_id ) {

		if ( $field_id == 'gquiz_is_pass' ) {
			$value = $value ? esc_html__( 'Pass', 'gravityformsquiz' ) : esc_html__( 'Fail', 'gravityformsquiz' );
		} elseif ( $field_id == 'gquiz_percent' ) {
			$value .= '%';
		}

		return $value;
	}

	/**
	 * If the field is a Poll type radio, select or checkbox then replace the choice value with the choice text.
	 *
	 * @param string $value The field value.
	 * @param GF_Field|null $field The field object being processed or null.
	 *
	 * @return string
	 */
	public function maybe_format_field_values( $value, $field ) {

		if ( is_object( $field ) && $field->type == 'quiz' ) {
			switch ( $field->inputType ) {
				case 'radio' :
				case 'select' :
					return RGFormsModel::get_choice_text( $field, $value );

				case 'checkbox' :
					if ( is_array( $value ) ) {
						foreach ( $value as &$choice ) {
							if ( ! empty( $choice ) ) {
								$choice = RGFormsModel::get_choice_text( $field, $choice );
							}
						}
					} else {
						foreach ( $field->choices as $choice ) {
							$val   = rgar( $choice, 'value' );
							$text  = rgar( $choice, 'text' );
							$value = str_replace( $val, $text, $value );
						}
					}
			}
		}

		return $value;
	}

	/**
	 * Format the Quiz field values so they use the choice text instead of values before being passed to the third-party.
	 *
	 * @param string $value The field value.
	 * @param array $form The form currently being processed.
	 * @param array $entry The entry object currently being processed.
	 * @param string $field_id The ID of the field currently being processed.
	 *
	 * @return string
	 */
	public function addon_field_value( $value, $form, $entry, $field_id ) {

		if ( ! is_numeric( $field_id ) ) {

			return $this->maybe_format_entry_meta_value( $value, $field_id );
		} elseif ( ! empty( $value ) ) {
			$field = RGFormsModel::get_field( $form, $field_id );

			return $this->maybe_format_field_values( $value, $field );
		}

		return $value;
	}

	/**
	 * Format the Quiz field values so they use the choice text instead of values before being passed to the third-party.
	 *
	 * @param string $value The field value.
	 * @param int $form_id The ID of the form currently being processed.
	 * @param string $field_id The ID of the field currently being processed.
	 * @param array $entry The entry object currently being processed.
	 *
	 * @return string
	 */
	public function legacy_addon_field_value( $value, $form_id, $field_id, $entry ) {

		if ( ! is_numeric( $field_id ) ) {

			return $this->maybe_format_entry_meta_value( $value, $field_id );
		} elseif ( ! empty( $value ) ) {
			$form  = RGFormsModel::get_form_meta( $form_id );
			$field = RGFormsModel::get_field( $form, $field_id );

			return $this->maybe_format_field_values( $value, $field );
		}

		return $value;
	}

	/**
	 * Format the Quiz field values for entry exports so they use the choice text instead of values.
	 *
	 * @param string|array $value The field value.
	 * @param int $form_id The ID of the form currently being processed.
	 * @param string $field_id The ID of the field currently being processed.
	 * @param array $entry The entry object currently being processed.
	 *
	 * @return string|array
	 */
	public function display_export_field_value( $value, $form_id, $field_id, $entry ) {

		return $this->legacy_addon_field_value( $value, $form_id, $field_id, $entry );
	}

	/**
	 * Format the Quiz field values so they use the choice text instead of values.
	 *
	 * Used for the entry list page, the AWeber, Campaign Monitor, and MailChimp add-ons.
	 *
	 * @param string|array $value The field value.
	 * @param int $form_id The ID of the form currently being processed.
	 * @param string $field_id The ID of the field currently being processed.
	 * @param array $entry The entry object currently being processed.
	 *
	 * @return string|array
	 */
	public function display_entries_field_value( $value, $form_id, $field_id, $entry ) {

		return $this->legacy_addon_field_value( $value, $form_id, $field_id, $entry );
	}

	/**
	 * Format the Quiz field values for display on the entry detail page and print entry.
	 *
	 * @param string|array $value The field value.
	 * @param GF_Field $field The field currently being processed.
	 * @param array $entry The entry object currently being processed.
	 * @param array $form The form object currently being processed.
	 *
	 * @return string|array
	 */
	public function display_quiz_on_entry_detail( $value, $field, $entry, $form ) {
		$new_value = '';

		if ( $field->type == 'quiz' ) {
			$new_value .= '<div class="gquiz_entry">';
			$results      = $this->get_quiz_results( $form, $entry, false );
			$field_markup = '';
			foreach ( $results['fields'] as $field_results ) {
				if ( $field_results['id'] == $field->id ) {
					$field_markup = $field_results['markup'];
					break;
				}
			}

			$new_value .= $field_markup;
			$new_value .= '</div>';

			// if original response is not in results display below
			// TODO - handle orphaned repsonses (orginal choice is deleted)

		} else {
			$new_value = $value;
		}

		return $new_value;
	}

	/**
	 * Include the results in the footer of the print entry screen.
	 *
	 * @param array $form The current form.
	 * @param array $entry The current entry.
	 */
	public function print_entry_footer( $form, $entry ) {
		$fields = GFAPI::get_fields_by_type( $form, array( 'quiz' ) );
		
		if ( ! empty ( $fields ) ) {
			echo '<h3>' . esc_html__( 'Quiz Results', 'gravityformsquiz' ) . '</h3>' . $this->get_results_panel_markup( $form, $entry );
		}
	}

	/**
	 * Include the results in the sidebar of the entry detail page.
	 *
	 * @param array $meta_boxes The properties for the meta boxes.
	 * @param array $entry The entry currently being viewed/edited.
	 * @param array $form The form object used to process the current entry.
	 *
	 * @return array
	 */
	public function register_meta_box( $meta_boxes, $entry, $form ) {
		$fields = GFAPI::get_fields_by_type( $form, array( 'quiz' ) );

		if ( ! empty( $fields ) ) {
			$meta_boxes['gf_quiz'] = array(
				'title'    => esc_html__( 'Quiz Results', 'gravityformsquiz' ),
				'callback' => array( $this, 'add_quiz_meta_box' ),
				'context'  => 'side',
			);
		}

		return $meta_boxes;
	}

	/**
	 * The callback used to echo the content to the gf_quiz meta box.
	 *
	 * @param array $args An array containing the form and entry objects.
	 */
	public function add_quiz_meta_box( $args ) {

		echo $this->get_results_panel_markup( $args['form'], $args['entry'] );
	}

	/**
	 * Generate the quiz results markup for use in the meta box and entry printout.
	 *
	 * @param array $form The current form.
	 * @param array $entry The current entry.
	 *
	 * @return string
	 */
	public function get_results_panel_markup( $form, $entry ) {

		$html = '<div id="gquiz-entry-detail-score-info">';

		$html .= sprintf( '%s: %s/%s<br/><br/>%s: %s%%<br/><br/>', esc_html__( 'Score', 'gravityformsquiz' ), rgar( $entry, 'gquiz_score' ), $this->get_max_score( $form ), esc_html__( 'Percentage', 'gravityformsquiz' ), rgar( $entry, 'gquiz_percent' ) );

		$grading = $this->get_form_setting( $form, 'grading' );
		if ( $grading == 'passfail' ) {
			$html .= sprintf( '%s: %s', esc_html__( 'Pass/Fail', 'gravityformsquiz' ), rgar( $entry, 'gquiz_is_pass' ) ? esc_html__( 'Pass', 'gravityformsquiz' ) : esc_html__( 'Fail', 'gravityformsquiz' ) );
		} elseif ( $grading == 'letter' ) {
			$html .= sprintf( '%s: %s', esc_html__( 'Grade', 'gravityformsquiz' ), rgar( $entry, 'gquiz_grade' ) );
		}

		return $html . '</div>';
	}


	// # FORM SETTINGS --------------------------------------------------------------------------------------------------

	/**
	 * Add the form settings tab.
	 *
	 * @param array $tabs The tabs to be displayed on the form settings page.
	 * @param int $form_id The ID of the current form.
	 *
	 * @return array
	 */
	public function add_form_settings_menu( $tabs, $form_id ) {
		$form        = $this->get_form_meta( $form_id );
		$quiz_fields = GFAPI::get_fields_by_type( $form, array( 'quiz' ) );
		if ( false === empty( $quiz_fields ) ) {
			$tabs[] = array( 'name' => 'gravityformsquiz', 'label' => esc_html__( 'Quiz', 'gravityformsquiz' ) );
		}

		return $tabs;
	}

	/**
	 * The settings fields to be rendered on the form settings page.
	 *
	 * @param array $form The current form object.
	 *
	 * @return array
	 */
	public function form_settings_fields( $form ) {

		// check for legacy form settings from a form exported from a previous version pre-framework
		$page = rgget( 'page' );
		if ( 'gf_edit_forms' == $page && false === empty( $form_id ) ) {
			$settings = $this->get_form_settings( $form );
			if ( empty( $settings ) && isset( $form['gquizGrading'] ) ) {
				$this->upgrade_form_settings( $form );
			}
		}

		return array(
			array(
				'title'  => esc_html__( 'Quiz Settings', 'gravityformsquiz' ),
				'fields' => array(
					array(
						'name'    => 'general',
						'label'   => esc_html__( 'General', 'gravityformquiz' ),
						'type'    => 'checkbox',
						'choices' => array(
							0 => array(
								'label'         => esc_html__( 'Shuffle quiz fields', 'gravityformsquiz' ),
								'name'          => 'shuffleFields',
								'default_value' => $this->get_form_setting( array(), 'shuffleFields' ),
								'tooltip'       => '<h6>' . esc_html__( 'Shuffle Fields', 'gravityformsquiz' ) . '</h6>' . esc_html__( "Display the quiz fields in a random order. This doesn't affect the position of the other fields on the form.", 'gravityformsquiz' )
							),
							1 => array(
								'label'         => esc_html__( 'Instant feedback', 'gravityformsquiz' ),
								'name'          => 'instantFeedback',
								'default_value' => $this->get_form_setting( array(), 'instantFeedback' ),
								'tooltip'       => '<h6>' . esc_html__( 'Instant Feedback', 'gravityformsquiz' ) . '</h6>' . esc_html__( "Display the correct answers plus explanations immediately after selecting an answer. Once an answer has been selected it can't be changed unless the form is reloaded. This setting only applies to radio button quiz fields and it is intended for training applications and trivial quizzes. It should not be considered a secure option for testing.", 'gravityformsquiz' )
							)
						)
					),
					array(
						'name'  => 'grading',
						'label' => esc_html__( 'Grading', 'gravityformsquiz' ),
						'type'  => 'grading',
					),
				)
			),
		);
	}

	/**
	 * Render the markup for the grading type field.
	 *
	 * @param array $field The setting properties.
	 */
	public function settings_grading( $field ) {

		$tooltip_form_confirmation_autoformat = '<h6>' . esc_html__( 'Disable Auto-Formatting', 'gravityformsquiz' ) . '</h6>' . esc_html__( 'When enabled, auto-formatting will insert paragraph breaks automatically. Disable auto-formatting when using HTML to create the confirmation content.', 'gravityformsquiz' );

		$this->settings_hidden(
			array(
				'name'          => 'grades',
				'default_value' => $this->get_form_setting( array(), 'grades' )
			)
		);
		?>

		<div id="gquiz-grading-options">

			<?php
			$grading_setting = array(
				'name'          => 'grading',
				'type'          => 'radio',
				'horizontal'    => true,
				'default_value' => $this->get_form_setting( array(), 'grading' ),
				'class'         => 'gquiz-grading',
				'choices'       => array(
					0 => array( 'value' => 'none', 'label' => esc_html__( 'None', 'gravityformsquiz' ), 'tooltip' => '<h6>' . esc_html__( 'No Grading', 'gravityformsquiz' ) . '</h6>' . esc_html__( 'Grading will not be used for this form.', 'gravityformsquiz' ) ),
					1 => array( 'value' => 'passfail', 'label' => esc_html__( 'Pass/Fail', 'gravityformsquiz' ), 'tooltip' => '<h6>' . esc_html__( 'Enable Pass/Fail Grading', 'gravityformsquiz' ) . '</h6>' . esc_html__( 'Select this option to enable the pass/fail grading system for this form.', 'gravityformsquiz' ) ),
					2 => array( 'value' => 'letter', 'label' => esc_html__( 'Letter', 'gravityformsquiz' ), 'tooltip' => '<h6>' . esc_html__( 'Enable Letter Grading', 'gravityformsquiz' ) . '</h6>' . esc_html__( 'Select this option to enable the letter grading system for this form.', 'gravityformsquiz' ) )
				)
			);
			$this->settings_radio( $grading_setting );
			?>
			<div id="gquiz-grading-pass-fail-container" style="margin-top:10px;display:none;">
				<div id="gquiz-form-setting-pass-grade">

					<div id="gquiz-form-setting-pass-grade-value">
						<label style="display:block;">
							<?php esc_html_e( 'Pass Percentage', 'gravityformsquiz' ) ?><?php gform_tooltip( '<h6>' . esc_html__( 'Pass Percentage', 'gravityformsquiz' ) . '</h6>' . esc_html__( 'Define the minimum percentage required to pass the quiz.', 'gravityformsquiz' ) ) ?>
						</label>
						<?php

						$this->settings_text(
							array(
								'name'          => 'passPercent',
								'class'         => 'gquiz-grade-value',
								'default_value' => $this->get_form_setting( array(), 'passPercent' )
							)
						);
						?>
						<span>%</span>
					</div>
				</div>

				<?php
				$this->settings_checkbox(
					array(
						'name'    => 'passfailDisplayConfirmation',
						'type'    => 'checkbox',
						'choices' => array(
							0 => array(
								'name'          => 'passfailDisplayConfirmation',
								'label'         => esc_html__( 'Display quiz confirmation', 'gravityformsquiz' ),
								'tooltip'       => '<h6>' . esc_html__( 'Display Confirmation', 'gravityformsquiz' ) . '</h6>' . esc_html__( 'Activate this setting to configure a confirmation message to be displayed after submitting the quiz. The message will appear below the confirmation configured on the Confirmations tab. When this setting is activated any page redirects configured on the Confirmations tab will be ignored.', 'gravityformsquiz' ),
								'default_value' => $this->get_form_setting( array(), 'passfailDisplayConfirmation' )
							)
						)
					)
				);
				?>
				<br />

				<div class="gquiz-quiz-confirmation">
					<div id="gquiz-form-setting-pass-confirmation-message">
						<label style="display:block;">
							<?php esc_html_e( 'Quiz Pass Confirmation', 'gravityformsquiz' ) ?>
						</label>
						<?php

						$this->settings_textarea(
							array(
								'name'          => 'passConfirmationMessage',
								'class'         => 'merge-tag-support mt-position-right fieldwidth-3 fieldheight-1',
								'default_value' => $this->get_form_setting( array(), 'passConfirmationMessage' )
							)
						);

						$this->settings_checkbox(
							array(
								'name'    => 'passConfirmationDisableAutoformat',
								'type'    => 'checkbox',
								'choices' => array(
									0 => array(
										'name'          => 'passConfirmationDisableAutoformat',
										'label'         => esc_html__( 'Disable Auto-formatting', 'gravityformsquiz' ),
										'tooltip'       => $tooltip_form_confirmation_autoformat,
										'default_value' => $this->get_form_setting( array(), 'passConfirmationDisableAutoformat' )
									)
								)
							)
						);

						?>
					</div>
					<br />

					<div id="gquiz-form-setting-fail-confirmation-message">
						<label style="display:block;">
							<?php esc_html_e( 'Quiz Fail Confirmation', 'gravityformsquiz' ) ?>
						</label>
						<?php

						$this->settings_textarea(
							array(
								'name'          => 'failConfirmationMessage',
								'class'         => 'merge-tag-support mt-position-right fieldwidth-3 fieldheight-1',
								'default_value' => $this->get_form_setting( array(), 'failConfirmationMessage' )
							)
						);


						$this->settings_checkbox(
							array(
								'name'    => 'failConfirmationDisableAutoformat',
								'type'    => 'checkbox',
								'choices' => array(
									0 => array(
										'name'          => 'failConfirmationDisableAutoformat',
										'label'         => esc_html__( 'Disable Auto-formatting', 'gravityformsquiz' ),
										'tooltip'       => $tooltip_form_confirmation_autoformat,
										'default_value' => $this->get_form_setting( array(), 'failConfirmationDisableAutoformat' )
									)
								)
							)
						);
						?>
					</div>
				</div>
			</div>

			<div id="gquiz-grading-letter-container" style="margin-top:10px;display:none;">
				<label for="gquiz-settings-grades-container" style="display:block;">
					<?php esc_html_e( 'Letter Grades', 'gravityformsquiz' ); ?>
					<?php gform_tooltip( 'gquiz_letter_grades' ) ?>
				</label>

				<div id="gquiz-settings-grades-container">
					<label class="gquiz-grades-header-label"><?php esc_html_e( 'Label', 'gravityformsquiz' ) ?></label><label
						class="gquiz-grades-header-value"><?php esc_html_e( 'Percentage', 'gravityformsquiz' ) ?></label>
					<ul id="gquiz-grades">
						<!-- placeholder for grades UI -->
					</ul>
				</div>
				<br />

				<?php

				$this->settings_checkbox(
					array(
						'name'    => 'letterDisplayConfirmation',
						'type'    => 'checkbox',
						'choices' => array(
							0 => array(
								'name'          => 'letterDisplayConfirmation',
								'label'         => esc_html__( 'Display quiz confirmation', 'gravityformsquiz' ),
								'tooltip'       => '<h6>' . esc_html__( 'Display Confirmation', 'gravityformsquiz' ) . '</h6>' . esc_html__( 'Activate this setting to configure a confirmation message to be displayed after submitting the quiz. The message will appear below the confirmation configured on the Confirmations tab. When this setting is activated any page redirects configured on the Confirmations tab will be ignored.', 'gravityformsquiz' ),
								'default_value' => $this->get_form_setting( array(), 'letterDisplayConfirmation' )
							)
						)
					)
				);
				?>
				<br />

				<div class="gquiz-quiz-confirmation">
					<div id="gquiz-form-setting-letter-confirmation-message">
						<label for="gquiz-settings-grades-container" style="display:block;">
							<?php esc_html_e( 'Quiz Confirmation', 'gravityformsquiz' ); ?>
						</label>
						<?php

						$this->settings_textarea(
							array(
								'name'          => 'letterConfirmationMessage',
								'class'         => 'merge-tag-support mt-position-right fieldwidth-3 fieldheight-1',
								'default_value' => $this->get_form_setting( array(), 'letterConfirmationMessage' )
							)
						);


						$this->settings_checkbox(
							array(
								'name'    => 'letterConfirmationDisableAutoformat',
								'type'    => 'checkbox',
								'choices' => array(
									0 => array(
										'name'          => 'letterConfirmationDisableAutoformat',
										'label'         => esc_html__( 'Disable Auto-formatting', 'gravityformsquiz' ),
										'tooltip'       => $tooltip_form_confirmation_autoformat,
										'default_value' => $this->get_form_setting( array(), 'letterConfirmationDisableAutoformat' )
									)
								)
							)
						);
						?>

					</div>
				</div>
			</div>
		</div>
	<?php
	}


	// # FIELD SETTINGS -------------------------------------------------------------------------------------------------

	/**
	 * Add the custom classes to the Quiz field.
	 *
	 * @param string $classes The CSS classes to be filtered, separated by empty spaces.
	 * @param GF_Field $field The field currently being processed.
	 * @param array $form The form currently being processed.
	 *
	 * @return string
	 */
	public function add_custom_class( $classes, $field, $form ) {
		if ( $field->type == 'quiz' ) {
			$classes .= ' gquiz-field ';
		}
		$instant_feedback_enabled = $this->get_form_setting( $form, 'instantFeedback' );
		if ( $instant_feedback_enabled ) {
			$classes .= ' gquiz-instant-feedback ';
		}

		return $classes;
	}

	/**
	 * Add the tooltips for the Quiz field.
	 *
	 * @param array $tooltips An associative array of tooltips where the key is the tooltip name and the value is the tooltip.
	 *
	 * @return array
	 */
	public function add_quiz_tooltips( $tooltips ) {
		//form settings
		$tooltips['gquiz_letter_grades'] = '<h6>' . esc_html__( 'Letter Grades', 'gravityformsquiz' ) . '</h6>' . esc_html__( 'Define the minimum percentage required for each grade.', 'gravityformsquiz' );


		//field settings
		$tooltips['gquiz_question']                  = '<h6>' . esc_html__( 'Quiz Question', 'gravityformsquiz' ) . '</h6>' . esc_html__( 'Enter the question you would like to ask the user. The user can then answer the question by selecting from the available choices.', 'gravityformsquiz' );
		$tooltips['gquiz_field_type']                = '<h6>' . esc_html__( 'Quiz Type', 'gravityformsquiz' ) . '</h6>' . esc_html__( "Select the field type you'd like to use for the quiz. Choose radio buttons or drop down if question only has one correct answer. Choose checkboxes if your question requires more than one correct choice.", 'gravityformsquiz' );
		$tooltips['gquiz_randomize_quiz_choices']    = '<h6>' . esc_html__( 'Randomize Quiz Answers', 'gravityformsquiz' ) . '</h6>' . esc_html__( 'Check the box to randomize the order in which the answers are displayed to the user. This setting affects only the quiz front-end. It will not affect the order of the results.', 'gravityformsquiz' );
		$tooltips['gquiz_enable_answer_explanation'] = '<h6>' . esc_html__( 'Enable Answer Explanation', 'gravityformsquiz' ) . '</h6>' . esc_html__( 'Activate this option to display an explanation of the answer along with the quiz results.', 'gravityformsquiz' );
		$tooltips['gquiz_answer_explanation']        = '<h6>' . esc_html__( 'Quiz Answer Explanation', 'gravityformsquiz' ) . '</h6>' . esc_html__( 'Enter the explanation for the correct answer and/or incorrect answers. This text will appear below the results for this field.', 'gravityformsquiz' );
		$tooltips['gquiz_field_choices']             = '<h6>' . esc_html__( 'Quiz Answers', 'gravityformsquiz' ) . '</h6>' . esc_html__( 'Enter the answers for the quiz question. You can mark each choice as correct by using the radio/checkbox fields on the right.', 'gravityformsquiz' );
		$tooltips['gquiz_weighted_score']            = '<h6>' . esc_html__( 'Weighted Score', 'gravityformsquiz' ) . '</h6>' . esc_html__( 'Weighted scores allow complex scoring systems in which each choice is awarded a different score. Weighted scores are awarded regardless of whether the response is correct or incorrect so be sure to allocate higher scores to correct answers. If this setting is disabled then the response will be awarded a score of 1 if correct and 0 if incorrect.', 'gravityformsquiz' );

		return $tooltips;
	}

	/**
	 * Add the custom settings for the Quiz fields to the fields general tab.
	 *
	 * @param int $position The position the settings should be located at.
	 * @param int $form_id The ID of the form currently being edited.
	 */
	public function quiz_field_settings( $position, $form_id ) {

		/**
		 * A filter to either allow or disallow choice values using true or false (Default is false)
		 */
		$show_values_style = apply_filters( 'gform_quiz_show_choice_values', false ) ? '' : 'display:none;';

		if ( $position == 10 ) {
			?>

			<li class="gquiz-setting-question field_setting">
				<label for="gquiz-question" class="section_label">
					<?php esc_html_e( 'Quiz Question', 'gravityformsquiz' ); ?>
					<?php gform_tooltip( 'gquiz_question' ); ?>
				</label>
				<textarea id="gquiz-question" class="fieldwidth-3 fieldheight-2" onkeyup="SetFieldLabel(this.value)"
				          size="35"></textarea>

			</li>

		<?php }
		if ( $position == 25 ) {
			?>

			<li class="gquiz-setting-field-type field_setting">
				<label for="gquiz-field-type" class="section_label">
					<?php esc_html_e( 'Quiz Field Type', 'gravityformsquiz' ); ?>
					<?php gform_tooltip( 'gquiz_field_type' ); ?>
				</label>
				<select id="gquiz-field-type"
				        onchange="if(jQuery(this).val() == '') return; jQuery('#field_settings').slideUp(function(){StartChangeQuizType(jQuery('#gquiz-field-type').val());});">
					<option value="select"><?php esc_html_e( 'Drop Down', 'gravityformsquiz' ); ?></option>
					<option value="radio"><?php esc_html_e( 'Radio Buttons', 'gravityformsquiz' ); ?></option>
					<option value="checkbox"><?php esc_html_e( 'Checkboxes', 'gravityformsquiz' ); ?></option>

				</select>

			</li>
			<li class="gquiz-setting-choices field_setting">

				<div style="float:right;">
					<input id="gquiz-weighted-score-enabled" type="checkbox"
					       onclick="SetFieldProperty('gquizWeightedScoreEnabled', this.checked); jQuery('#gquiz_gfield_settings_choices_container').toggleClass('gquiz-weighted-score');">
					<label class="inline gfield_value_label" for="gquiz-weighted-score-enabled"><?php esc_html_e( 'weighted
						score', 'gravityformsquiz' ) ?></label> <?php gform_tooltip( 'gquiz_weighted_score' ) ?>
				</div>
				<div style="float:right;<?php echo $show_values_style; ?>">
					<input type="checkbox" id="gquiz_field_choice_values_visible" onclick="gquizToggleValues();"/>
					<label for="gquiz_field_choice_values_visible"
					       class="inline gfield_value_label"><?php esc_html_e( 'show values', 'gravityformsquiz' ) ?></label>
				</div>
				<label for="gquiz-choice-text-0" class="section_label">
					<?php esc_html_e( 'Quiz Answers', 'gravityformsquiz' ); ?>
					<?php gform_tooltip( 'gquiz_field_choices' ) ?>
				</label>

				<div id="gquiz_gfield_settings_choices_container">
					<ul id="gquiz-field-choices"></ul>
				</div>

				<?php $window_title = esc_html__( 'Bulk Add / Predefined Choices', 'gravityformsquiz' ); ?>
				<input type='button' value='<?php echo esc_attr( $window_title ) ?>'
				       onclick="tb_show('<?php echo esc_js( $window_title ) ?>', '#TB_inline?height=500&amp;width=600&amp;inlineId=gfield_bulk_add', '');"
				       class="button"/>

			</li>

			<?php
		} elseif ( $position == 1368 ) {
			//right after the other_choice_setting
			?>
			<li class="gquiz-setting-randomize-quiz-choices field_setting">

				<input type="checkbox" id="gquiz-randomize-quiz-choices"
				       onclick="var value = jQuery(this).is(':checked'); SetFieldProperty('gquizEnableRandomizeQuizChoices', value);"/>
				<label for="gquiz-randomize-quiz-choices" class="inline">
					<?php esc_html_e( 'Randomize order of choices', 'gravityformsquiz' ); ?>
					<?php gform_tooltip( 'gquiz_randomize_quiz_choices' ) ?>
				</label>

			</li>
			<li class="gquiz-setting-show-answer-explanation field_setting">

				<input type="checkbox" id="gquiz-show-answer-explanation"
				       onclick="var value = jQuery(this).is(':checked'); SetFieldProperty('gquizShowAnswerExplanation', value); gquiz_toggle_answer_explanation(value);"/>
				<label for="gquiz-show-answer-explanation" class="inline">
					<?php esc_html_e( 'Enable answer explanation', 'gravityformsquiz' ); ?>
					<?php gform_tooltip( 'gquiz_enable_answer_explanation' ) ?>
				</label>

			</li>
			<li class="gquiz-setting-answer-explanation field_setting">
				<label for="gquiz-answer-explanation">
					<?php esc_html_e( 'Quiz answer explanation', 'gravityformsquiz' ); ?>
					<?php gform_tooltip( 'gquiz_answer_explanation' ); ?>
				</label>
				<textarea id="gquiz-answer-explanation" class="fieldwidth-3 fieldheight-2" size="35"
				          onkeyup="SetFieldProperty('gquizAnswerExplanation',this.value)"></textarea>

			</li>

			<?php
		}
	}


	// # HELPERS -------------------------------------------------------------------------------------------------------

	/**
	 * Retreive a specific form setting.
	 *
	 * @param array $form The current form object.
	 * @param string $setting_name The property to be retrieved.
	 *
	 * @return bool|string
	 */
	public function get_form_setting( $form, $setting_key ) {
		if ( false === empty( $form ) ) {
			$settings = $this->get_form_settings( $form );

			// check for legacy form settings from a form exported from a previous version pre-framework
			if ( empty( $settings ) && isset( $form['gquizGrading'] ) ) {
				$this->upgrade_form_settings( $form );
			}

			if ( isset( $settings[ $setting_key ] ) ) {
				$setting_value = $settings[ $setting_key ];
				if ( $setting_value == '1' ) {
					$setting_value = true;
				} elseif ( $setting_value == '0' ) {
					$setting_value = false;
				}
				if ( 'grades' == $setting_key && ! is_array( $setting_value ) ) {
					$setting_value = json_decode( $setting_value, true );
				}

				return $setting_value;
			}
		}

		// default values
		$value = '';
		switch ( $setting_key ) {
			case 'grading' :
				$value = 'none';
				break;
			case 'passPercent':
				$value = 50;
				break;
			case 'failConfirmationMessage' :
				$value = __( "<strong>Quiz Results:</strong> You Failed!\n<strong>Score:</strong> {quiz_score}\n<strong>Percentage:</strong> {quiz_percent}%", 'gravityformsquiz' );
				break;
			case 'passConfirmationMessage' :
				$value = __( "<strong>Quiz Results:</strong> You Passed!\n<strong>Score:</strong> {quiz_score}\n<strong>Percentage:</strong> {quiz_percent}%", 'gravityformsquiz' );
				break;
			case 'letterConfirmationMessage' :
				$value = __( "<strong>Quiz Grade:</strong> {quiz_grade}\n<strong>Score:</strong> {quiz_score}\n<strong>Percentage:</strong> {quiz_percent}%", 'gravityformsquiz' );
				break;
			case 'grades' :
				$value = array(
					array( 'text' => 'A', 'value' => 90 ),
					array( 'text' => 'B', 'value' => 80 ),
					array( 'text' => 'C', 'value' => 70 ),
					array( 'text' => 'D', 'value' => 60 ),
					array( 'text' => 'E', 'value' => 0 ),
				);
				break;
			case 'passConfirmationDisableAutoformat' :
			case 'failConfirmationDisableAutoformat' :
			case 'letterConfirmationDisableAutoformat' :
			case 'instantFeedback' :
			case 'shuffleFields':
				$value = false;
				break;
			case 'passfailDisplayConfirmation' :
			case 'letterDisplayConfirmation' :
				$value = true;
				break;
		}

		return $value;

	}

	/**
	 * Return an array of results including HTML formatted data.
	 *
	 * @param array $form The current form.
	 * @param array $lead The current entry.
	 * @param bool $show_question Indicates if the quiz question (label) should also be included in the markup.
	 *
	 * @return array
	 */
	public function get_quiz_results( $form, $lead = array(), $show_question = true ) {
		$total_score = 0;

		$output['fields']  = array();
		$output['summary'] = '<div class="gquiz-container">';
		$fields            = GFAPI::get_fields_by_type( $form, array( 'quiz' ) );
		$pass_percent      = $this->get_form_setting( $form, 'passPercent' );
		$grades            = $this->get_form_setting( $form, 'grades' );
		$max_score         = $this->get_max_score( $form );

		foreach ( $fields as $field ) {
			$weighted_score_enabled = $field->gquizWeightedScoreEnabled;
			$value                  = RGFormsModel::get_lead_field_value( $lead, $field );

			$field_score = 0;

			$field_markup = '<div class="gquiz-field">';
			if ( $show_question ) {
				$field_markup .= '    <div class="gquiz-field-label">';
				$field_markup .= GFCommon::get_label( $field );
				$field_markup .= '    </div>';
			}

			$field_markup .= '    <div class="gquiz-field-choice">';
			$field_markup .= '    <ul>';

			// for checkbox inputs with multiple correct choices
			$completely_correct = true;

			$choices = $field->choices;

			foreach ( $choices as $choice ) {
				$is_choice_correct = isset( $choice['gquizIsCorrect'] ) && $choice['gquizIsCorrect'] == '1' ? true : false;

				$choice_weight           = isset( $choice['gquizWeight'] ) ? (float) $choice['gquizWeight'] : 1;
				$choice_class            = $is_choice_correct ? 'gquiz-correct-choice ' : '';
				$response_matches_choice = false;
				$user_responded          = true;
				if ( is_array( $value ) ) {
					foreach ( $value as $item ) {
						if ( RGFormsModel::choice_value_match( $field, $choice, $item ) ) {
							$response_matches_choice = true;
							break;
						}
					}
				} elseif ( empty( $value ) ) {
					$response_matches_choice = false;
					$user_responded          = false;
				} else {
					$response_matches_choice = RGFormsModel::choice_value_match( $field, $choice, $value ) ? true : false;

				}
				$is_response_correct = $is_choice_correct && $response_matches_choice;
				if ( $response_matches_choice && $weighted_score_enabled ) {
					$field_score += $choice_weight;
				}


				if ( $field->inputType == 'checkbox' ) {
					$is_response_wrong = ( ( ! $is_choice_correct ) && $response_matches_choice ) || ( $is_choice_correct && ( ! $response_matches_choice ) ) || $is_choice_correct && ! $user_responded;
				} else {
					$is_response_wrong = ( ( ! $is_choice_correct ) && $response_matches_choice ) || $is_choice_correct && ! $user_responded;
				}

				$indicator_markup = '';
				if ( $is_response_correct ) {
					$indicator_markup = '<img src="' . $this->_correct_indicator_url . '" />';
					$choice_class .= 'gquiz-correct-response ';
				} elseif ( $is_response_wrong ) {
					$indicator_markup   = '<img src="' . $this->_incorrect_indicator_url . '" />';
					$completely_correct = false;
					$choice_class .= 'gquiz-incorrect-response ';
				}

				/**
				 * More control over the indication for any type of answer
				 *
				 * @param string $indicator_markup The indicator HTML/text for an answer
				 * @param array $form The Form object to filter through
				 * @param array $field The Field Object to filter through
				 * @param mixed $choice The Choice object for the quiz
				 * @param array $lead The Lead Object to filter through
				 * @param bool $is_response_correct True or false if the response is correct, can be used to change indicators
				 * @param bool $is_response_wrong True or false if the response is incorrect, can be used to change indicators
				 */
				$indicator_markup = apply_filters( 'gquiz_answer_indicator', $indicator_markup, $form, $field, $choice, $lead, $is_response_correct, $is_response_wrong );

				$choice_class_markup = empty( $choice_class ) ? '' : 'class="' . $choice_class . '"';
				$field_markup .= "<li {$choice_class_markup}>";

				$field_markup .= $choice['text'] . $indicator_markup;
				$field_markup .= '</li>';

			} // end foreach choice

			$field_markup .= '    </ul>';
			$field_markup .= '    </div>';

			if ( $field->gquizShowAnswerExplanation ) {
				$field_markup .= '<div class="gquiz-answer-explanation">';
				$field_markup .= $field->gquizAnswerExplanation;
				$field_markup .= '</div>';
			}

			$field_markup .= '</div>';
			if ( ! $weighted_score_enabled && $completely_correct ) {
				$field_score += 1;
			}
			$output['summary'] .= $field_markup;
			array_push(
				$output['fields'], array(
					'id'         => $field->id,
					'markup'     => $field_markup,
					'is_correct' => $completely_correct,
					'score'      => $field_score,
				)
			);
			$total_score += $field_score;

		} // end foreach field
		$total_score = max( $total_score, 0 );
		$output['summary'] .= '</div>';
		$output['score']   = $total_score;
		$total_percent     = $max_score > 0 ? $total_score / $max_score * 100 : 0;
		$output['percent'] = round( $total_percent );
		$total_grade       = $this->get_grade( $grades, $total_percent );

		$output['grade']   = $total_grade;
		$is_pass           = $total_percent >= $pass_percent ? true : false;
		$output['is_pass'] = $is_pass;

		return $output;
	}

	/**
	 * Return the maximum score for this form.
	 *
	 * @param array $form The current form.
	 *
	 * @return int
	 */
	public function get_max_score( $form ) {
		$max_score = 0;
		$fields    = GFAPI::get_fields_by_type( $form, array( 'quiz' ) );

		foreach ( $fields as $field ) {
			if ( $field->gquizWeightedScoreEnabled ) {
				if ( $field->get_input_type() == 'checkbox' ) {
					foreach ( $field->choices as $choice ) {
						$weight = (float) rgar( $choice, 'gquizWeight' );
						$max_score += max( $weight, 0 ); // don't allow negative scores to impact the max score
					}
				} else {
					$max_score_for_field = 0;
					foreach ( $field->choices as $choice ) {
						$max_score_for_choice = (float) rgar( $choice, 'gquizWeight' );
						$max_score_for_field  = max( $max_score_for_choice, $max_score_for_field );
					}
					$max_score += $max_score_for_field;
				}
			} else {
				$max_score += 1;
			}
		}

		return $max_score;
	}

	/**
	 * Return the grade for the percentage result.
	 *
	 * @param array $grades The grades for this entry.
	 * @param int $percent The entry result as a percentage.
	 *
	 * @return string
	 */
	public function get_grade( $grades, $percent ) {
		$the_grade = '';
		usort( $grades, array( $this, 'sort_grades' ) );
		foreach ( $grades as $grade ) {
			if ( $grade['value'] <= (double) $percent ) {
				$the_grade = $grade['text'];
				break;
			}
		}

		return $the_grade;
	}

	/**
	 * Helper for sorting the grades.
	 *
	 * @param array $a The properties for the first grade.
	 * @param array $b The properties for the second grade.
	 *
	 * @return integer The result of the coupons amount comparison.
	 */
	public function sort_grades( $a, $b ) {
		return $a['value'] < $b['value'];
	}

	/**
	 * Return the value for the correct choice.
	 *
	 * @param array $choices The field choices.
	 *
	 * @return string
	 */
	public function get_correct_choice_value( $choices ) {
		$correct_choice_value = '';
		foreach ( $choices as $choice ) {
			if ( rgar( $choice, 'gquizIsCorrect' ) ) {
				$correct_choice_value = rgar( $choice, 'value' );
			}
		}

		return $correct_choice_value;
	}

	/**
	 * Cache the form meta.
	 *
	 * @param int $form_id The form ID.
	 *
	 * @return mixed
	 */
	public function get_form_meta( $form_id ) {

		if ( ! isset( $this->_form_meta_by_id[ $form_id ] ) ) {
			$forms = GFFormsModel::get_form_meta_by_id( $form_id );
			$this->_form_meta_by_id[ $form_id ] = array_shift( $forms );
		}

		return $this->_form_meta_by_id[ $form_id ];
	}


	// # CONTACTS INTEGRATION -------------------------------------------------------------------------------------------

	public function add_tab_to_contact_detail( $tabs, $contact_id ) {
		if ( $contact_id > 0 ) {
			$tabs[] = array( 'name' => 'quiz', 'label' => __( 'Quiz Entries', 'gravityformsquiz' ) );
		}

		return $tabs;
	}

	public function contacts_tab( $contact_id ) {

		if ( false === empty( $contact_id ) ) :
			$search_criteria['status'] = 'active';
			$search_criteria['field_filters'][] = array( 'type'  => 'meta',
			                                             'key'   => 'gcontacts_contact_id',
			                                             'value' => $contact_id
			);
			$search_criteria['field_filters'][] = array( 'type'       => 'meta',
			                                             'key'        => 'gquiz_score',
			                                             'operator'   => '>=',
			                                             'value'      => 0,
			                                             'is_numeric' => true
			);

			$form_id = 0; //all forms
			$entries = GFAPI::get_entries( $form_id, $search_criteria );

			if ( empty( $entries ) ) :
				_e( 'This contact has not submitted any quiz entries yet.', 'gravityformsquiz' );
			else :
				?>
				<h3><span><?php _e( 'Quiz Entries', 'gravityformsquiz' ) ?></span></h3>
				<div>
					<table id="gcontacts-entry-list" class="widefat">
						<tr class="gcontacts-entries-header">
							<td>
								<?php _e( 'Entry Id', 'gravityformsquiz' ) ?>
							</td>
							<td>
								<?php _e( 'Date', 'gravityformsquiz' ) ?>
							</td>
							<td>
								<?php _e( 'Form', 'gravityformsquiz' ) ?>
							</td>
							<td>
								<?php _e( 'Score', 'gravityformsquiz' ) ?>
							</td>
							<td>
								<?php _e( 'Pass/Fail', 'gravityformsquiz' ) ?>
							</td>
							<td>
								<?php _e( 'Grade', 'gravityformsquiz' ) ?>
							</td>
						</tr>
						<?php


						foreach ( $entries as $entry ) {
							$form_id    = $entry['form_id'];
							$form       = GFFormsModel::get_form_meta( $form_id );
							$form_title = rgar( $form, 'title' );
							$entry_id   = $entry['id'];
							$entry_date = GFCommon::format_date( rgar( $entry, 'date_created' ), false );
							$entry_url  = admin_url( "admin.php?page=gf_entries&view=entry&id={$form_id}&lid={$entry_id}" );
							$passfail   = '';
							$grading    = $this->get_form_setting( $form, 'grading' );
							if ( 'passfail' == $grading ) {
								$is_pass  = rgar( $entry, 'gquiz_is_pass' ) ? true : false;
								$passfail = $is_pass ? __( 'Pass', 'gravityformsquiz' ) : __( 'Fail', 'gravityformsquiz' );
								$color    = $is_pass ? 'green' : 'red';
								$passfail = sprintf( '<span style="color:%s">%s</span>', $color, $passfail );
							}

							$grade = '';
							if ( 'letter' == $grading ) {
								$grade = rgar( $entry, 'gquiz_grade' );
							}
							?>
							<tr>
								<td>
									<a href="<?php echo $entry_url; ?>"><?php echo $entry_id; ?></a>
								</td>
								<td>
									<?php echo $entry_date; ?>
								</td>
								<td>
									<?php echo $form_title; ?>
								</td>
								<td>
									<?php echo rgar( $entry, 'gquiz_score' ); ?>
								</td>
								<td>
									<?php echo $passfail ?>
								</td>
								<td>
									<?php echo $grade; ?>
								</td>
							</tr>
							<?php
						}
						?>
					</table>
				</div>
				<?php
			endif;
		endif;

	}


	// # DEPRECATED -----------------------------------------------------------------------------------------------------

	/**
	 * Support importing forms using the old XML format.
	 *
	 * @param array $options Array of options for the XML import.
	 *
	 * @return array
	 */
	public function import_file_options( $options ) {
		$options['grade']      = array( 'unserialize_as_array' => true );
		$options['gquizGrade'] = array( 'unserialize_as_array' => true );

		return $options;
	}

	/**
	 * Include the results in the sidebar of the entry detail page.
	 *
	 * @param array $form The current form.
	 * @param array $entry The current entry.
	 */
	public function entry_detail_sidebar_middle( $form, $entry ) {
		$this->entry_results( $form, $entry );
	}

	/**
	 * Output the Quiz results in a postbox div.
	 *
	 * @param array $form The current form.
	 * @param array $entry The current entry.
	 */
	public function entry_results( $form, $entry ) {

		$fields            = GFAPI::get_fields_by_type( $form, array( 'quiz' ) );
		$count_quiz_fields = count( $fields );
		if ( $count_quiz_fields == 0 ) {
			return;
		}

		$grading = $this->get_form_setting( $form, 'grading' );
		$score   = rgar( $entry, 'gquiz_score' );
		$percent = rgar( $entry, 'gquiz_percent' );
		$is_pass = rgar( $entry, 'gquiz_is_pass' );
		$grade   = rgar( $entry, 'gquiz_grade' );

		$max_score = $this->get_max_score( $form );

		?>
		<div id="gquiz-entry-detail-score-info-container" class="postbox">
			<h3 style="cursor: default;"><?php esc_html_e( 'Quiz Results', 'gravityformsquiz' ); ?></h3>

			<div id="gquiz-entry-detail-score-info">
				<?php echo $this->get_results_panel_markup( $form, $entry ); ?>
			</div>

		</div>

		<?php
	}


	// # TO FRAMEWORK MIGRATION -----------------------------------------------------------------------------------------

	/**
	 * Checks if a previous version was installed and if the form settings need migrating to the framework structure.
	 *
	 * @param string $previous_version The version number of the previously installed version.
	 */
	public function upgrade( $previous_version ) {
		$previous_is_pre_addon_framework = version_compare( $previous_version, '1.1.7', '<' );

		if ( $previous_is_pre_addon_framework ) {
			$forms = GFFormsModel::get_forms();
			foreach ( $forms as $form ) {
				$form_meta = GFFormsModel::get_form_meta( $form->id );
				$this->upgrade_form_settings( $form_meta );
			}
		}
	}

	/**
	 * Migrates the quiz related form settings to the new structure.
	 *
	 * @param array $form The form object currently being processed.
	 */
	private function upgrade_form_settings( $form ) {
		if ( false === isset( $form['gquizGrading'] ) ) {
			return;
		}
		$legacy_form_settings = array(
			'gquizGrading'                              => 'grading',
			'gquizPassMark'                             => 'passPercent',
			'gquizConfirmationFail'                     => 'failConfirmationMessage',
			'gquizConfirmationPass'                     => 'passConfirmationMessage',
			'gquizConfirmationLetter'                   => 'letterConfirmationMessage',
			'gquizGrades'                               => 'grades',
			'gquizConfirmationPassAutoformatDisabled'   => 'passConfirmationDisableAutoformat',
			'gquizConfirmationFailAutoformatDisabled'   => 'failConfirmationDisableAutoformat',
			'gquizConfirmationLetterAutoformatDisabled' => 'letterConfirmationDisableAutoformat',
			'gquizInstantFeedback'                      => 'instantFeedback',
			'gquizShuffleFields'                        => 'shuffleFields',
			'gquizDisplayConfirmationPassFail'          => 'passfailDisplayConfirmation',
			'gquizDisplayConfirmationLetter'            => 'letterDisplayConfirmation',
		);
		$new_settings         = array();
		foreach ( $legacy_form_settings as $legacy_key => $new_key ) {
			if ( isset( $form[ $legacy_key ] ) ) {
				$new_settings[ $new_key ] = $legacy_key == 'gquizGrades' ? json_encode( $form[ $legacy_key ] ) : $form[ $legacy_key ];
				unset( $form[ $legacy_key ] );
			}
		}
		if ( false === empty( $new_settings ) ) {
			$form[ $this->_slug ] = $new_settings;
			GFFormsModel::update_form_meta( $form['id'], $form );
		}
	}

} // end class
