<?php
/**
 * After submit form run actions
 * @since      1.0.0
 * @package    MailPoet 3 – Contact Form 7 Integration
 * @subpackage mailpoet-3-contact-form-7/includes
 * @author     Tikweb <kasper@tikjob.dk>
 */

// use MailPoet\Models\Segment;
use MailPoet\Models\Subscriber;

if(!class_exists('MailPoet_CF7_Submit_Form')){
	class MailPoet_CF7_Submit_Form 
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
			add_action('wpcf7_before_send_mail', array($this, 'wpcf7_before_send_mail'));

		}//End of __construct

		/**
		 * Run action just before the mail send
		 * Add user to list
		 */
		public function wpcf7_before_send_mail($contact_form)
		{
			if(!empty($contactform->skip_mail)) return; //If need to skip

			if(class_exists('WPCF7_Submission') && class_exists('WPCF7_ShortcodeManager')){
				//Get submited form data
				$submission = WPCF7_Submission::get_instance();
				$posted_data = ( $submission ) ? $submission->get_posted_data() : null;

				//Get the tags that are in the form
				$manager = WPCF7_ShortcodeManager::get_instance();
				$scanned_tags = $manager->get_scanned_tags();

				//Add new subscriber
				$this->add_email_list($posted_data, $scanned_tags);
			}else{
				return;
			}
		}// End of wpcf7_before_send_mail


		/**
		 * Prepare form data
		 * Add data to the list
		 */
		public function add_email_list($form_data, $form_tags)
		{
			$field_names = $this->get_email_and_list_name($form_tags);

			//Check this form has mailpoet tag and email field is not empty
			//Also check for subscribed field checked or not
			if( 
				( isset($field_names['mailpoetsignup']) && !empty($field_names['mailpoetsignup']) )
				&& ( isset($field_names['email']) && !empty($field_names['email']) )
				&& ( isset($form_data[$field_names['email']]) && !empty($form_data[$field_names['email']]) )
				&& ( isset($form_data[$field_names['mailpoetsignup']]) && !empty($form_data[$field_names['mailpoetsignup']]) )
			){
				//If use `your-email` name for email field
				if(isset($form_data['your-email'])){
					$email = trim($form_data['your-email']);
				}else{
					$email = trim($form_data[$field_names['email']]);
				}

				//If use `mailpoetsignup` name for checkbox field
				if(isset($form_data['mailpoetsignup'])){
					$list_id = trim($form_data['mailpoetsignup']);
				}else{
					$list_id = trim($form_data[$field_names['mailpoetsignup']]);
				}
				
				//First name
				$firstname = '';
				if( isset($form_data['your-first-name']) ){
					$firstname = trim($form_data['your-first-name']);
				}elseif( isset($form_data['your-name']) ){
					$firstname = trim($form_data['your-name']);
				}

				//Last name
				$lastname = isset($form_data['your-last-name']) ? trim($form_data['your-last-name']) : '';

				$subscribe_data = array(
					'email' => $email,
					'segments' => explode(",", $list_id),
					'first_name' => $firstname,
				    'last_name' => $lastname,
				    'status' => 'subscribed'
				);

				//Save subcriber data
				$subscriber = Subscriber::createOrUpdate($subscribe_data);

			}else{
				//If the dont have mailpoet tag then return
				return;
			}
		}//End of prepare_add_to_list


		/**
		 * Find email and subscribe list id
		 */
		public function get_email_and_list_name($form_tags)
		{
			if(is_array($form_tags)){
				$form_names = array();
				foreach($form_tags as $FormTag){

					//Find type for email and mailpoetsignup
					switch($FormTag->basetype){
						case 'email': //get email tag name
							$form_names['email'] = $FormTag->name;
						break;

						case 'mailpoetsignup': //get subscribe checkbox name
							$form_names['mailpoetsignup'] = $FormTag->name;
						break;
					}

				}//End foreach

				return $form_names;
			}//End if
		}//End of get_email_and_list_id


	}//End of class

	/**
	 * Instentiate submit form class
	 */
	MailPoet_CF7_Submit_Form::init();

}//End if