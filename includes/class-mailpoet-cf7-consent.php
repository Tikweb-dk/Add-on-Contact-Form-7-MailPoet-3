<?php

class MailpoetSubscriptionConsent
{

	public function __construct()
	{
		// CF7 init
		add_action('wpcf7_init', array($this, 'cf7_init'));

		// Admin init
		add_action('admin_init', array($this, 'admin_init'), 20);

	} // end of __construct

	public static function init()
	{

		$instance = false;

		if ( empty($instance) ){
			$instance = new self();
		}
	}

	/**
	 * Translate text
	 */
	public function __($text)
	{
		return __($text, 'add-on-contact-form-7-mailpoet');
	}//End of __


	/**
	 * Contact Form 7 init
	 */
	public function cf7_init()
	{

		wpcf7_add_form_tag(
			array('mpconsent','mpconsent*'),
			array($this,'mpconsent_form_tag'),
			array('name-attr' => true)
		);

	} //End of cf7_init

	/**
	 * Admin init
	 */
	public function admin_init()
	{
		//Add Tag generator button
		if(!class_exists('WPCF7_TagGenerator')) return;
		$tag_generator = WPCF7_TagGenerator::get_instance();

		$tag_generator->add(
			'mpconsent',
			$this->__('MailPoet Consent'),
			array($this,'mailpoetsignup_tag_generator')
		);

	} //End of admin_init


	/**
	 * Display message
	 */
	public function mpconsent_form_tag( $tag )
	{
		$controls_class = wpcf7_form_controls_class($tag->type); // conrol class
		$id_option = $tag->get_id_option(); // id option if avilable
		$id = empty($id_option) ? $tag->name : $id_option; // fetch id

		// build html attribute
		$atts = array(
			'class' => $tag->get_class_option(),
			'id' => $id,
		);

		$attributes = wpcf7_format_atts($atts);


		// build consent ***
		$consent = '';

		foreach ($tag->values as $key => $value) {

			if ( empty($value) ){

				$consent .= "<br/>";
				continue;
			}

			$consent .= $value;
			$consent .= '&nbsp;';

		}

		$privacypage = $tag->get_option('privacypage');
		$privacypage = !empty($privacypage[0]) ? "<a href='$privacypage[0]'>Privacy Policy</a>" : '';

		$consent = str_replace('{privacypage}', $privacypage, $consent);

		if ( !empty($consent) ){
			$consent = html_entity_decode( $consent );
		}

		ob_start();

		?>

		<span class="wpcf7-form-control-wrap <?php echo $tag->name; ?>">
			<span class="<?php echo $controls_class; ?>">
				<?php echo $consent; ?>
			</span>
		</span>

		<?php

		return ob_get_clean();
	}

	/**
	 * Tag Generator
	 */
	public function mailpoetsignup_tag_generator()
	{
		?>

		<div class="control-box">
			<fieldset>
				<legend><?php echo $this->__('Mailpoet Subscription Consent'); ?></legend>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<?php echo $this->__('Consent Message'); ?>
							</th>
							<td>
								<label>
									<textarea name="values" id="values" cols="40" rows="10" class="oneline">By subscribing you agree to receive our promotional marketing materials and agree with out {privacypage}. You may unsubscribe at any time.</textarea>
								</label>
								<br/>
								We have add some default text to help. Please feel free to input any kinds of text in it. Don't forget to include this -> <code>{privacypage}</code> shortcode to include privacy policy page link automatically which you can select from dropdown below.
							</td>
						</tr>
						<tr>
							<th scope="row">
								<?php echo $this->__('Privacy Page'); ?>
							</th>
							<td>
								<label>
									<?php echo $this->privacy_page_select(); ?> or <a href="<?php echo admin_url('/privacy.php'); ?>"> Create new </a>
									<input type="hidden" name="privacypage" id="privacypage" class="option">
								</label>
								<script>
									var pr_sel = document.getElementById('page_id');
									pr_sel.addEventListener('change',function(){
										console.log('fired');
										document.getElementById('privacypage').value = pr_sel.value;
									},false);
								</script>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<?php echo $this->__('Name'); ?>
							</th>
							<td>
								<label>
									<input type="text" name="name" class="tg-name oneline" id="name">
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<?php echo $this->__('Id attribute'); ?>
							</th>
							<td>
								<label>
									<input type="text" name="id" class="idvalue oneline option" id="id-attr">
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<?php echo $this->__('Class attribute'); ?>
							</th>
							<td>
								<label>
									<input type="text" name="class" class="classvalue oneline option" id="class-attr">
								</label>
							</td>
						</tr>
					</tbody>
				</table>
			</fieldset>
		</div>

		<!-- Show Insert shortcode in popup -->
		<div class="insert-box">
			<input type="text" name="mpconsent" class="tag code" readonly="readonly" onfocus="this.select()" />
			<div class="submitbox">
				<input type="button" class="button button-primary insert-tag" value="<?php esc_attr_e('Insert Tag', 'contact-form-7'); ?>" />
			</div>
			<br class="clear" />
			<p class="description mail-tag">
				<label>
					<?php
						printf(
							esc_html__( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'contact-form-7' ),
							'<strong><span class="mail-tag"></span></strong>'
						);
					?>
					<input type="text" class="mail-tag code hidden" readonly="readonly" />
				</label>
			</p>
		</div><!-- /.insert-box -->

		<?php
	} //End of mailpoetsignup_tag_generator


	public function privacy_page_select()
	{

		$pages = get_pages(array(
			'post_status'	=> 'publish',
			'post_per_page'	=>	-1
		));

		$ret = "<select name='page_id' id='page_id'>";

		foreach ($pages as $key => $page) {

			$link = get_permalink( $page, false );
			$ret .= "<option value='$link'> $page->post_title </option>";

		}

		$ret .= "</select>";


		return $ret;
	}

}

MailpoetSubscriptionConsent::init();