<?php
/**
 * The core plugin class.
 * @since      1.0.0
 * @package    Add-on Add-on Contact Form 7 - Mailpoet 3 Integration
 * @subpackage add-on-contact-form-7-mailpoet/includes
 * @author     Tikweb <kasper@tikjob.dk>
 */

use MailPoet\Models\Segment;

if(!class_exists('MailPoet_CF7_Integration')){
	class MailPoet_CF7_Integration
	{
		/**
		 * Initialize the class
		 */
		public static function init()
		{
			$_this_class = new self;
			return $_this_class;
		}

		/**
		 * Constructor
		 */
		public function __construct()
		{

			// CF7 init
			add_action('wpcf7_init', array($this, 'cf7_init'));

			// Admin init
			add_action('admin_init', array($this, 'admin_init'), 20);

			// Form validation
			add_filter( 'wpcf7_validate_mailpoetsignup', array($this, 'mailpoetsignup_validation'), 10, 2 );
			add_filter( 'wpcf7_validate_mailpoetsignup*', array($this, 'mailpoetsignup_validation'), 10, 2 );

			// message
			add_filter( 'wpcf7_messages', array($this,'mailpoet_unsubscribed_msg') );

		}//End of __construct

		/**
		 * Contact Form 7 init
		 */
		public function cf7_init()
		{
			//Add Mailpoet Signup tag
			wpcf7_add_form_tag(
				array('mailpoetsignup', 'mailpoetsignup*'),
				array($this, 'mailpoet_signup_form_tag'),
				array('name-attr' => true)
			);

		}//End of cf7_init


		/**
		 * HTML Output of Subscribe checkbox
		 */
		public function mailpoet_signup_form_tag($tag)
		{

			// Non AJAX Validation error
			$validation_error = wpcf7_get_validation_error($tag->name);
			$class = '';

			//Form control span class
			$controls_class = wpcf7_form_controls_class($tag->type);

			// if there were errors, add class
			if( $validation_error ) $class .= ' wpcf7-not-valid';

			//Checkbox Label
			$label = empty($tag->values) ? $this->__('Sign up for the newsletter') : array_shift($tag->values);

			//id attribute
			$id_option = $tag->get_id_option();
			$id = empty($id_option) ? $tag->name : $id_option;

			//Array of list id
			$list_array = $tag->get_option('list', 'int');
			$mp_segments = $this->mailpoet_segments_data( $list_array );

			//Make ready all attributes
			$atts = array(
				'class' => $tag->get_class_option($class),
				'id' => $id,
				'name' => $tag->name.'[]'
			);

			if ( !$tag->has_option('subscriber-choice') ){
				$atts['value'] = ($list_array) ? implode($list_array, ',') : '0';
			}

			$attributes = wpcf7_format_atts($atts);

			ob_start(); //Start buffer to return
			?>

			<?php if ( count( $mp_segments ) > 1 && $tag->has_option('subscriber-choice') ): ?>

				<span class="wpcf7-form-control-wrap <?php echo $tag->name; ?>">
					<span class="<?php echo $controls_class; ?>">
						<label class="wpcf7-list-label"><?php echo $label; ?><br/></label>
							<?php foreach( $mp_segments as $key => $value ): ?>
								<label>
									<input type="checkbox" <?= $attributes; ?> value="<?= $key; ?>" <?php checked($tag->has_option('default:on'), true); ?>> <span class="wpcf7-list-value"><?= $value; ?></span><br/>
								</label>
							<?php endforeach; ?>

					</span>

					<?php echo $validation_error; //Show validation error ?>
				</span>
			<?php else: ?>
				<span class="wpcf7-form-control-wrap <?php echo $tag->name; ?>">
					<span class="<?php echo $controls_class; ?>">
						<label class="wpcf7-list-label">
							<input type="checkbox"
								<?php echo $attributes; ?>
								value="<?php echo key( $mp_segments ); ?>"
								<?php checked($tag->has_option('default:on'), true); ?>
							/>
						<?php echo $label; ?>
						</label>
						<br/>
					</span>

				</span>

			<?php
			endif; //End of $tag->has_option('label-inside-span')

			//Return all HTML output
			return ob_get_clean();

		}//End of mailpoet_signup_form_tag


		/**
		 * Translate text
		 */
		public function __($text)
		{
			return __($text, 'add-on-contact-form-7-mailpoet');
		}//End of __

		/**
		 * Convert mailpoet list ids to list name;
		 */
		public function mailpoet_segments_data( $list_ids ){

			if ( empty($list_ids) || !is_array($list_ids) ){
				return [];
			}

			$segments = Segment::where_not_equal('type', Segment::TYPE_WP_USERS)->findArray();

			$ret = array();

			foreach ($list_ids as $key => $value) {

				$seg_key = array_search( $value, array_column($segments, 'id') );
				$ret[$value] = $segments[$seg_key]['name'];

			}

			return $ret;
		} // End of mailpoet_segments_data

		/**
		 * Admin init
		 */
		public function admin_init()
		{
			//Add Tag generator button
			if(!class_exists('WPCF7_TagGenerator')) return;
			$tag_generator = WPCF7_TagGenerator::get_instance();
			$tag_generator->add(
				'mailpoetsignup',
				$this->__('Mailpoet Signup'),
				array($this, 'mailpoetsignup_tag_generator')
			);
		}//End of admin_init

		/**
		 * Tag Generator
		 */
		public function mailpoetsignup_tag_generator()
		{
			?>
			<div class="control-box">
				<fieldset>
					<legend><?php echo $this->__('Mailpoet Signup Form.'); ?></legend>
					<table class="form-table">
						<tbody>
						 	<tr>
								<th scope="row"><?php esc_html_e('Field type', 'contact-form-7'); ?></th>
								<td>
									<label>
										<input type="checkbox" name="required" />
										<?php esc_html_e('Required field', 'contact-form-7'); ?>
									</label>
								</td>
							</tr>

							<tr>
								<th scope="row"><?php echo $this->__('MailPoet Lists'); ?></th>
								<td>
									<?php
										$sagments = Segment::where_not_equal('type', Segment::TYPE_WP_USERS)->findArray();
										if(is_array($sagments)): foreach($sagments as $sagment):
									?>
									<label>
										<input type="checkbox" name="list:<?php echo $sagment['id']; ?>" class="option">
										<?php echo $sagment['name']; ?>
									</label>
									<br>
									<?php endforeach; endif; ?>
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="subscriber-choice">
										<?php echo $this->__('Let subscriber choose list'); ?>
									</label>
								</th>
								<td>
									<label>
										<input type="checkbox" name="subscriber-choice" class="option" id="subscriber-choice">
										<?php echo $this->__('Let your subscriber choose which list they will subscribe to!'); ?>
									</label>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="default:on"><?php echo $this->__('Checked by Default'); ?></label>
								</th>
								<td>
									<label>
										<input type="checkbox" name="default:on" class="option" id="default:on">
										<?php echo $this->__("Make this checkbox checked by default?"); ?>
										<div id="help"><i><?php echo $this->__("All choosen <code>MailPoet Lists</code> will be selected and subscriber will be tagged with all list."); ?></i></div>
									</label>
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="values"><?php echo $this->__('Checkbox Label'); ?></label>
								</th>
								<td>
									<input type="text" name="values" class="oneline" id="values" />
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="name"><?php esc_html_e( 'Name', 'contact-form-7' ); ?></label>
								</th>
								<td>
									<input type="text" name="name" class="tg-name oneline" id="name" />
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="id-attr"><?php esc_html_e( 'Id attribute', 'contact-form-7' ); ?></label>
								</th>
								<td>
									<input type="text" name="id" class="idvalue oneline option" id="id-attr" />
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="class-attr"><?php esc_html_e( 'Class attribute', 'contact-form-7' ); ?></label>
								</th>
								<td>
									<input type="text" name="class" class="classvalue oneline option" id="class-attr" />
								</td>
							</tr>

						</tbody>
					</table>
				</fieldset>
			</div><!-- /.control-box -->

			<!-- Show Insert shortcode in popup -->
			<div class="insert-box">
				<input type="text" name="mailpoetsignup" class="tag code" readonly="readonly" onfocus="this.select()" />
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
			<style>
				#help{
					display:none;
				}
				input[name="default:on"]:checked~#help{
					display:block;
				}
			</style>
			<?php
		}//End of mailpoetsignup_tag_generator

		/**
		 * Message to display after a subscriber request to unsubscribe.
		 */
		public function mailpoet_unsubscribed_msg( $msg ){

			$msg['mailpoet_unsubscribed_msg'] = array(
				'description'	=> 'Message to display after a subscriber being unsubscribed',
				'default'		=> 'You are unsubscribed!'
			);

			return $msg;

		}

		/**
		 * Form validation
		 * Validate for checkbox
		 */
		public function mailpoetsignup_validation( $result, $tag ) {
			$type = $tag->type;
			$name = $tag->name;

			$value = isset( $_POST[$name] ) ? (array) $_POST[$name] : array();

			if ( $tag->is_required() && empty( $value ) ) {
				$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
			}

			return $result;
		}//End of mailpoetsignup_validation



	}//End of class

	/**
	 * Instentiate core class
	 */
	MailPoet_CF7_Integration::init();

}//End if
