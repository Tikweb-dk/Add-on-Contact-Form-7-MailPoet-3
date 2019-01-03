=== Add-on Contact Form 7 - MailPoet 3 ===
Contributors: kasperta
Tags: mailpoet, contact form 7, cf7, form, forms, contact form, wysija, mail, email, e-mail, extension, add-on, newsletter, newsletters, subscription, checkout, list, lists, double opt-in
Donate link: http://www.tikweb.dk/donate/
Requires at least: 4.6
Tested up to: 4.9
Requires PHP: 5.2
Stable tag: 1.3.1

Add a MailPoet 3 signup field to your Contact Form 7 forms.

== Description ==

> #### Requirements
> [MailPoet 3](https://wordpress.org/plugins/mailpoet/)<br />
> [Contact Form 7](https://wordpress.org/plugins/contact-form-7/)

MailPoet is a free newsletter and post notification plugin for WordPress that makes it really simple to send out email newsletters to your subscription lists. This plugin integrates Contact Form 7 with MailPoet by providing an option for your customers to signup for your newsletter lists while submitting a form.

Please see the extensive installation / setup instructions to set up your form correctly.

= Features =

* Allow your users to sign up for a Mailpoet newsletter list using a Contact Form 7 form
* You can capture first name, last name, and (of course) email
* You can signup users to as many lists as you like
* You can set up the form to opt in or opt out

= Form Setup =

After installing & activating the plugin it's time to set up your form.

1. Click on Contact in the WordPress admin
2. Edit an existing form or create a new one by clicking on Add New in the WordPress admin menu
3. Add your fields
4. Add a text field named `your-name`
5. Add an email field named `your-email`
6. Add a MailPoet Signup field named `mailpoetsignup`
7. When you're adding the MailPoet Signup field you can select any number of lists you want the user to be assigned to
8. You can also choose to make the user opt in or opt out

== <a name="how-to-translate"></a>How to translate? ==

We use the official WordPress Polyglots translation team and online translation system - which is not very common among plugin authors, and therefore we would like to explain why this is both easier and better for all than the common .pot/.po/.mo files.

= Online web translation =
To make it short, you simply use the online system at <https://translate.wordpress.org/projects/wp-plugins/add-on-contact-form-7-mailpoet> to translate both "Development" and "Development Readme" into your language. And when a translation editor have approved the translations, a language pack will automatically be generated for all websites using our plugin and the language you translated. No need to work with any files at all, WordPress will automatically load the translation, when it is approved by an editor.

= .pot/.po/.mo files =
If you need to have your own texts and translations, you can off course still use Poedit or a plugin like Loco Translate with your own .po files. You can export and download a translation to a .po file from
<https://translate.wordpress.org/projects/wp-plugins/add-on-contact-form-7-mailpoet> -> Choose language -> Choose Development -> Export (at the bottom)
If you add new translations, please consider using the import button at the same place, to import your .po file translations into the online system so everyone may benefit from your translations :-)

= Online web translation: Editors and approval =
Everyone can edit and add translations for our plugin using the online system at <https://translate.wordpress.org/projects/wp-plugins/add-on-contact-form-7-mailpoet> - this only require that you are logged in with your wordpress.org user name. But only editors may approve translations. So after adding translations for a new language, you should apply to become the Project Translation Editor (PTE) for your language for our plugin, then you may approve your own translations.

Only members of the WordPress Polyglots team can approve new PTEs, which they usually do pretty fast when you have added a full language of translations. To approve a new PTE, the polyglots team member must be a General Translation Editor (GTE) for the language, meaning one that have access to all plugins for the specific language, since the one approving you off course needs to be fluent in your language to be able to read your first translations and check that they are of good quality.

To become PTE, you simply request it at the Polyglots forum.
We suggest you use the example below - exchange xx_XX with your locale (ex. da_DK for danish in Denmark) and XXXXX with your wordpress.org username (ex. mine is kasperta). If your language have several different locales, add an extra line with that locale.
So copy and paste the text below to a new post at <https://make.wordpress.org/polyglots/> - and edit locale + user name, and soon you may approve your own translations :-)
---beginning of forum post---
	Hello Polyglots, I have added translations for "Add-on WooCommerce - MailPoet 3" (&lt;a href="https://wordpress.org/plugins/add-on-contact-form-7-mailpoet/">Add-on WooCommerce - MailPoet 3/a>) and would like to become the Project Translation Editor (PTE) for my language.
	Please add my WordPress.org user as Project Translation Editor (PTE) for the respective locales:
	o #xx_XX - @XXXXX
	If you have any questions, just comment here. Thank you!
	#editor-requests
---end of forum post---

= Translations and editors =

See the current translation contributors and editors for our plugin for the different languages at:
<https://translate.wordpress.org/projects/wp-plugins/add-on-contact-form-7-mailpoet/contributors>

See the generated language packs at:
<https://translate.wordpress.org/projects/wp-plugins/add-on-contact-form-7-mailpoet/language-packs>

If the online system have not generated a language pack for your language, it is because:

1. Your texts are not approved, check if they are still in the "waiting" column. If they are, then check if there is an [editor](https://translate.wordpress.org/projects/wp-plugins/add-on-contact-form-7-mailpoet/contributors) for your language, if not, then request to become an editor.
2. There are not enough texts translated, you need about 90% translated before a translation pack is generated.
3. You have only translated the plugin strings and not the readme. You need above 90% for "Development" and "Development Readme" together, check the percentage of both columns for your language at <https://translate.wordpress.org/projects/wp-plugins/add-on-contact-form-7-mailpoet>

== Installation ==

There are 3 ways to install this plugin:

= 1. The super easy way =
1. In your WordPress dashboard, navigate to Plugins > Add New
2. Search for `Contact Form 7 - MailPoet 3`
3. Click on "install now" under "Contact Form 7 - MailPoet 3"
4. Activate the plugin

= 2. The easy way =
1. Download the plugin (.zip file) by using the blue "download" button underneath the plugin banner at the top
2. In your WordPress dashboard, navigate to Plugins > Add New
3. Click on "Upload Plugin"
4. Upload the .zip file
5. Activate the plugin

= 3. The old-fashioned and reliable way (FTP) =
1. Download the plugin (.zip file) by using the blue "download" button underneath the plugin banner at the top
2. Extract the archive and then upload, via FTP, the `contact-form-7-mailpoet-3` folder to the `<WP install folder>/wp-content/plugins/` folder on your host
3. Activate the plugin

= Form Setup =

After installing & activating the plugin it's time to set up your form.

1. Click on Contact in the WordPress admin
2. Edit an existing form or create a new one by clicking on Add New in the WordPress admin menu
3. Add your fields
4. Add a text field named `your-name`
5. Add an email field named `your-email`
6. Add a MailPoet Signup field named `mailpoetsignup`
7. When you're adding the MailPoet Signup field you can select any number of lists you want the user to be assigned to
8. You can also choose to make the user opt in or opt out

== Screenshots ==

1. A sample Contact Form 7 form all ready to go.
2. A view of the MailPoet Signup Tag Generator
3. A view of the MailPoet Consent Tag Generator

== Changelog ==

= 1.3.1 – 2018-06-12 =
* Designed a new shortcode for the opt out or Unsubscribe option.

[Changelog](https://plugins.svn.wordpress.org/add-on-contact-form-7-mailpoet/trunk/changelog.txt)

== Upgrade Notice ==

= x.0.0 =
* There are nothing else needed, than upgrading from the WordPress pluings screen.

