<?php
/*
Plugin Name: Smart Currency Converter
Plugin URI: https://www.currency-rates.com
Description: A customizable currency converter widget with support for over 70 currencies!
Author: Nokta
Version: 1.0
Author URI: http://www.noktamedya.com/
*/

// Creating the widget
class CRConverterWidget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'CRConverterWidget',
			__('Smart Currency Converter', 'cr_converter_widget_option1'),
			array( 'description' => __( 'Smart Currency Converter Widget by currency-rates.com', 'cr_converter_widget_option1' ), )
		);
	}

	public function widget( $args, $instance ) {

		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		$converterLeftSymbol = empty($instance['firstCurrency']) ? 'usd' : $instance['firstCurrency'];
		$converterRightSymbol = empty($instance['secondCurrency']) ? 'eur' : $instance['secondCurrency'];

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		$converterCurrencies = $this->get_currencies();
		$converterLeft = $this->get_currency($converterLeftSymbol);
		$converterRight = $this->get_currency($converterRightSymbol);

		echo '<div id="' . $args['widget_id'] . '" class="cr-currency-converter-widget">';
		echo '<input class="currency-converter-input" data-left type="text" placeholder="'.$converterLeft['symbol'].'">';
		echo '<button onclick="crDropdown(this)" class="btn-currencies" id="converterTop" symbol="'. $converterLeft['symbol'] .'">
						<span class="flags flags-' . strtolower($converterLeft['symbol']) . '"></span>
            <span class="currency-symbol"> ' . $converterLeft['symbol'] . '</span>
            <span class="caret"></span>
          </button>';
		echo '<ul id="cr-curriencies-dropdown" class="dropdown-content left" aria-labelledby="converterTop">';
		foreach ( $converterCurrencies as $currency ) {
			echo '<li><a href="javascript:;" onclick="crSetCurrency(this)" datasymbol="';
			echo $currency['symbol'];
			echo '" data-usd-value="';
			echo $currency['usdValue'];
			echo '" data-left><span class="flags flags-';
			echo strtolower($currency['symbol']);
			echo '"></span>&nbsp';
			echo '(' . $currency['symbol'] . ')&nbsp' . $currency['name'];
			echo '</a></li>';
		}
		echo '</ul>';

		echo '<input class="currency-converter-input" data-right type="text" placeholder="'.$converterRight['symbol'].'">';
		echo '<button onclick="crDropdown(this)" class="btn-currencies" id="converterBottom" symbol="'. $converterRight['symbol'] .'">
						<span class="flags flags-' . strtolower($converterRight['symbol']) . '"></span>
            <span class="currency-symbol"> ' . $converterRight['symbol'] . '</span>
            <span class="caret"></span>
          </button>';
		echo '<ul id="cr-curriencies-dropdown2" class="dropdown-content right" aria-labelledby="converterBottom">';
		foreach ( $converterCurrencies as $currency ) {
			echo '<li><a href="javascript:;" onclick="crSetCurrency(this)" datasymbol="';
			echo $currency['symbol'];
			echo '" data-usd-value="';
			echo $currency['usdValue'];
			echo '" data-right><span class="flags flags-';
			echo strtolower($currency['symbol']);
			echo '"></span>&nbsp';
			echo '(' . $currency['symbol'] . ')&nbsp' . $currency['name'];
			echo '</a></li>';
		}
		echo '</ul>';

		echo '<div class="clear"></div><p class="text-center">Powered by <a href="https://www.currency-rates.com" title="Currency-Rates.com" target="_blank">Currency-Rates.com</a></p>';
		echo $args['after_widget'];
	}

	public function form( $instance ) {

		$defaults = array(
			'title' => __('Currency Converter', 'cr_currency_converter'),
			'firstCurrency' => 'usd',
			'secondCurrency' => 'eur'
		);

		$instance = wp_parse_args( (array)$instance, $defaults );

		$title = $instance['title'];
		$firstCurrency = $instance['firstCurrency'];
		$secondCurrency = $instance['secondCurrency'];
		$converterCurrencies = $this->get_currencies();

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('text'); ?>">First Currency:
				<select class='widefat' id="<?php echo $this->get_field_id('firstCurrency'); ?>" name="<?php echo $this->get_field_name('firstCurrency'); ?>" type="text">
					<?php foreach ( $converterCurrencies as $currency ) { ?>
						<option value='<?php echo $currency['symbol'] ?>'<?php echo ($firstCurrency == $currency['symbol'])?'selected':''; ?>>
							<?php echo '(' . $currency['symbol'] . ') ' . $currency['name'] ?>
						</option>
					<?php } ?>
				</select>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('text'); ?>">Second Currency:
				<select class='widefat' id="<?php echo $this->get_field_id('secondCurrency'); ?>" name="<?php echo $this->get_field_name('secondCurrency'); ?>" type="text">
					<?php foreach ( $converterCurrencies as $currency ) { ?>
						<option value='<?php echo $currency['symbol'] ?>'<?php echo ($secondCurrency == $currency['symbol'])?'selected':''; ?>>
							<?php echo '(' . $currency['symbol'] . ') ' . $currency['name'] ?>
						</option>
					<?php } ?>
				</select>
			</label>
		</p>
		<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['firstCurrency'] = $new_instance['firstCurrency'];
		$instance['secondCurrency'] = $new_instance['secondCurrency'];

		return $instance;
	}

	private function get_currencies() {
		$data = file_get_contents('http://api.currency-rates.com/currencies');
		$data = json_decode($data, true);
		return $data;
	}

	private function get_currency($symbol) {
		$data = file_get_contents('http://api.currency-rates.com/currencies/' . $symbol);
		$data = json_decode($data, true);
		return $data;
	}
}

// Register and load the widget
function crConverter_load_widget() {
	register_widget( 'CRConverterWidget' );
}
add_action( 'widgets_init', 'crConverter_load_widget' );

function crConverterInit() {
	wp_enqueue_style('cr-converter-widgets', plugins_url('/assets/css/cr-currency-widgets.css', __FILE__));
	wp_enqueue_style('cr-converter-flags', plugins_url('/assets/css/cr-currency-flags.css', __FILE__));

	wp_register_script( 'cr-converter-script', plugins_url('/assets/js/cr-currency-converter-script.js', __FILE__), array('jquery'));
	wp_enqueue_script( 'cr-converter-script' );

	wp_register_script( 'cr-converter-core', plugins_url('/assets/js/cr-currency-converter-core.js', __FILE__), array());
	wp_enqueue_script( 'cr-converter-core' );
}
add_action('init', 'crConverterInit');
