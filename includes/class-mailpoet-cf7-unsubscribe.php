<?php

class MailpoetSubscriptionUnsubscribe
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
			array('mpunsub','mpunsub*'),
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
			'mpunsub',
			$this->__('MailPoet Unsubscribe'),
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


		// build data_label ***
		$data_label = '';

		foreach ($tag->values as $key => $value) {

			$data_label .= $value;
			$data_label .= '&nbsp;';

		}

		$data_label = html_entity_decode($data_label);


		ob_start();
		
		?>

		<span class="wpcf7-form-control-wrap <?php echo $tag->name; ?>">
			<span class="<?php echo $controls_class; ?>">
				<label>
					<input type="checkbox" name="unsubscribe-email" <?= $attributes; ?> /> <span class="wpcf7-list-value"><?= $data_label; ?></span><br/>
				</label>
			</span>
		</span>
		<script>
			document.addEventListener( 'wpcf7mailsent', function( event ) {
			    var unsub_req = event.detail.formData.get('unsubscribe-email');
			    
			    if ( unsub_req != null ){
			    	event.detail.apiResponse.message += ' <strong><?php echo wpcf7_get_message( 'mailpoet_unsubscribed_msg' ); ?></strong>';
			    }

			}, false );
		</script>

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
				<legend><?php echo $this->__('Unsubscribe Option'); ?></legend>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<?php echo $this->__('Unsubscribe Checkbox Label'); ?>
							</th>
							<td>
								<label>
									<textarea name="values" id="values" cols="30" rows="10" class="oneline">Unsubscribe from newsletter.</textarea><br>
									<i>Use any html tag for styling</i>
								</label>
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
			<input type="text" name="mpunsub" class="tag code" readonly="readonly" onfocus="this.select()" />
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

}

MailpoetSubscriptionUnsubscribe::init();