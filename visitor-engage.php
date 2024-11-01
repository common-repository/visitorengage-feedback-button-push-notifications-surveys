<?php
/*
Plugin Name: VisitorEngage
Plugin URI: http://www.visitorengage.com
Description: Add a free <strong>feedback button</strong>, run surveys and behavioral based proactive web <strong>push notifications</strong> on your wordpress website with ease. This plugin comes with a free forever plan.
Version: 0.1a
Author: VisitorEngage
Author URI: http://www.visitorengage.com
License: GPL2
*/

//Master Class to manage Visitor Engage
if(!class_exists(Visitorengage))
{
	class Visitorengage
	{
		protected $domainId;

		public function __construct()
		{
			add_action('admin_init', array(&$this, 'admin_init'));
			add_action('admin_menu', array(&$this, 'add_menu'));
			$this->domainId = get_option('ve_domain_id');
		}

		public static function activate()
		{
			add_option('ve_domain_id', '255', '', 'yes' );
		}

		public static function deactivate()
		{
			delete_option('ve_domain_id');
		}

		public function admin_init()
		{
			$this->init_settings();
		}

		public function init_settings()
		{
			register_setting('visitor_engage-group', 'domain_id');
		}

		public function add_menu()
		{
			add_options_page('Visitor Engage', 'Visitor Engage', 'manage_options', 'visitor_engage', array(&$this, 'plugin_settings_page'));
		}

		public function plugin_settings_page()
		{
			if(!current_user_can('manage_options'))
			{
				wp_die(__('You do not have sufficient permissions to access this page.'));
			}
			include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
		} 


	}
}

//Main Plugin Routine
if(class_exists(Visitorengage))
{
	register_activation_hook(__FILE__, array('Visitorengage', 'activate'));
	register_deactivation_hook(__FILE__, array('Visitorengage', 'deactivate'));
	$ve = new Visitorengage();

	if(isset($ve))
	{
		function plugin_settings_link($links)
		{ 
			$settings_link = '<a href="options-general.php?page=visitor_engage">Settings</a>'; 
			array_unshift($links, $settings_link); 
			return $links; 
		}

		$plugin = plugin_basename(__FILE__); 
		add_filter("plugin_action_links_$plugin", 'plugin_settings_link');
	}
}

function ve_code()
{ 
	if(get_option('domain_id', false))
	{
		?>
		<script type='text/javascript'>
			var _ave =_ave|| []; _ave._setAccount = '<?php echo get_option("domain_id") ?>';
			var ave = document.createElement('script'); ave.type = 'text/javascript'; ave.async = true;
			ave.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'app.visitorengage.com/feedback-visitor/visitor-engage.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ave, s);
		</script>
		<?php
	}
}

add_action('wp_footer', 've_code', 20);

function admin_js() { ?>
<script type="text/javascript">

	jQuery(document).ready( function () { 
		jQuery("#getDomainIdBtn").click(function(e){
			e.preventDefault();
			jQuery("#getDomainIdForm").show();
		});
		jQuery("#submitDomainIdForm").click(function(e){
			e.preventDefault();
			jQuery("#va-status").text("Loading....");
			data = {
				email: jQuery("#va_email").val(),
				password: jQuery("#va_password").val(),
				plugin_type: "wp_plugin",
			};
			jQuery.getJSON('http://app.visitorengage.com/authorization?callback=?', data, function(response){
				if(response.status == "success")
				{
					var response = jQuery.parseJSON(response.response);
					var domain_id = response[0].domain_id;
					jQuery("#va-status").text("Saving......");
					jQuery("#domain_id").val(domain_id);
					jQuery("#submit").trigger('click');
				}
				else
				{
					jQuery("#va-status").text("Please make sure that you clicked on the verification email sent. If you forgot your password, you can reset at http://app.visitorengage.com");
				}
			});
		});
	});
</script>
<?php }
add_action('admin_print_footer_scripts', 'admin_js');