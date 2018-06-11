<?php
/**
 * After submit form run actions
 * @since      1.0.0
 * @package    Add-on Add-on Contact Form 7 - Mailpoet 3 Integration
 * @subpackage add-on-contact-form-7-mailpoet/includes
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

				// unsubscribe first
				$unsubscribed = $this->unsubscribe_email( $posted_data );

				if ( $unsubscribed == false ){
					//Add new subscriber
					$this->add_email_list($posted_data, $scanned_tags);
				}

			}else{
				return;
			}
		}// End of wpcf7_before_send_mail


		/**
		 * Prepare form data
		 * Add data to the list
		 * your-email for email
		 * your-first-name for first name
		 * your-last-name for last name
		 */
		public function add_email_list($form_data, $form_tags)
		{
			// Get field name to get form value
			$field_names = $this->get_email_and_list_name($form_tags);

			//Check this form has mailpoet tag and email field is not empty
			$has_mailpoet_tag = false;
			if( isset($field_names['mailpoetsignup']) && !empty($field_names['mailpoetsignup']) && is_array($field_names['mailpoetsignup']) ){
				$has_mailpoet_tag = true;
			}
			$has_email_tag = false;
			if(isset($field_names['email']) && !empty($field_names['email'])){
				$has_email_tag = true;
			}

			// If has email and mailpoet tag
			if( $has_mailpoet_tag && $has_email_tag ){

				//If use `your-email` name for email field
				if(isset($form_data['your-email'])){
					$email = trim($form_data['your-email']);
				}else{
					$email = trim($form_data[$field_names['email']]);
				}

				//First name
				$firstname = '';
				if( isset($form_data['your-first-name']) ){
					$firstname = trim($form_data['your-first-name']);
				}elseif( isset($form_data['your-name']) ){
					$firstname = trim($form_data['your-name']);
				}else{
					$firstname = $email;
				}

				//Last name
				$lastname = isset($form_data['your-last-name']) ? trim($form_data['your-last-name']) : '';

				$list_ids = $this->get_list_ids($form_data, $field_names['mailpoetsignup']);

				if(!empty($list_ids) && is_array($list_ids)){
					$subscribe_data = array(
						'email' => $email,
						'segments' => $list_ids,
						'first_name' => $firstname,
					    'last_name' => $lastname,
					    'status' => 'subscribed'
					);

					//Save subcriber data
					$subscriber = Subscriber::subscribe($subscribe_data, $subscribe_data['segments']);
				}
				
			}else{
				//If the dont have mailpoet tag then return
				return;
			}
		}//End of prepare_add_to_list

		// Unsubscribe a email address
		public function unsubscribe_email($form_data)
		{
			if ( isset($form_data['unsubscribe-email']) ){

				if ( isset($form_data['your-email']) ){
					
					$subscriber_email = $form_data['your-email'];
					$subscriber = Subscriber::findOne( $subscriber_email );

					if ( $subscriber !== false ){

						$subscriber->status = 'unsubscribed';
						$subscriber->save();

						return true;
					}

				}
			}

			return false;
		} // End of unsubscribe_email 

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
							$form_names['mailpoetsignup'][] = $FormTag->name;
						break;
					}
				}//End foreach

				return $form_names;
			}//End if
		}//End of get_email_and_list_id

		/**
		 * Get list ids 
		 * @return  Array of list id
		 */
		public function get_list_ids($form_data, $mailpoetsignup)
		{
			$ids_string_array = array();
			$ids_string = '';
			
			foreach($mailpoetsignup as $mailpoet_name){
				if(isset($form_data[$mailpoet_name]) && !empty($form_data[$mailpoet_name])){
					
					if ( is_array($form_data[$mailpoet_name]) ){
						$ids_string_array = $form_data[$mailpoet_name];
					} else {
						$ids_string_array[] = $form_data[$mailpoet_name];
					}
				}
			}

			if(!empty($ids_string_array)){
				$ids_string = implode(",", $ids_string_array);
			}
			
			if ( !empty($ids_string) ){
				return explode(",", $ids_string);
			} else {
				return [];
			}
			
		}//get_list_ids

	}//End of class

	/**
	 * Instentiate submit form class
	 */
	MailPoet_CF7_Submit_Form::init();

}//End if