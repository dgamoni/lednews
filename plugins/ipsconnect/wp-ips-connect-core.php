<?php

/*
 * @package (Pro) WordPress IPSConnect
 * @brief Big Bad John, The Core wpIpsConnect.
 * @version 1.1.3
 * @author Provisionists (based on Marcher Technologies WP IPS Connect)
 * @link https://provisionists.com
 * @copyright Copyright (c) 2014, Provisionists LLC All Rights Reserved
 */

class wpIpsConnect
{

	public $settings;
	protected $settingsClass;
	protected $DB;
	static private $instance;
	public $basePath;
	public static $member;
	public static $members = array();
	public static $connectIds = array();
	protected $postClass;
    protected $redirect_to = '';
	final protected function __construct()
	{
		
	}

	final protected function __clone()
	{
		
	}

	final static public function instance()
	{
		if (self::$instance === NULL)
		{
			self::$instance = new wpIpsConnectXmlRpc();
			self::$instance->_init();
		}
		return self::$instance;
	}

	protected function _init()
	{
		$this->_loadSettings();
		if (!defined('IN_IPB') && $this->settings['masterUrl'] && $this->settings['masterKey'] && $this->settings['returnUrl'])
		{
			/*
			 * So... Umm... we REALLY on?(Install Error Otherwise)
			 */
			if (is_plugin_active('ipsconnect/wp-ips-connect.php'))
			{
				$this->settings['ipsConnectOn'] = TRUE;
				//add our 'live' actions/filters
				add_action('init', array(&$this, 'init'), 0);
				add_action('widgets_init', array(&$this, 'registerWidgets'), 0);
				add_action('user_register', array(&$this, 'register'), 0);
				add_action('wp_authenticate', array(&$this, 'checkAuth'), 0, 2);
				add_action('wp_logout', array(&$this, 'logout'), 0);
				add_action('wp_login', array(&$this, 'loginRedirect'), 0, 2);
				add_filter('user_profile_update_errors', array(&$this, 'checkFields'), 0, 3);
				add_filter('registration_errors', array(&$this, 'checkRegister'), 0, 3);
				add_action('delete_user', array(&$this, 'delete'), 0);
				add_action('wp_head', array(&$this, 'killAdminLinks'), 0);
				if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'register')
				{
					$this->registrationRedirect();
				}
				add_action('lost_password', array(&$this, 'passwordRedirect'), 0);
				if ($this->settings['swapUCP'])
				{
					add_action('admin_print_scripts', array(&$this, 'killAdminLinks'), 0);
				}
			}
		}
		//add our 'admin' actions
		add_action('admin_menu', array(&$this->settingsClass, 'addSettings'), 0);
		add_action('admin_init', array(&$this->settingsClass, 'init'), 0);
	}

	protected function _loadSettings()
	{
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		$this->basePath = plugin_dir_path(__FILE__);
		require_once $this->basePath . 'sources/classes/settings.php';
		$this->settingsClass = ipsConnectSettings::instance();
		$this->settings = & $this->settingsClass->fetchSettings();
		$this->DB = $this->settingsClass->DB();
		$this->postClass = $this->getPostClass();
	}

	public function log($message = '', $file = 'debugLog', $data = NULL, $force = FALSE, $unlink = FALSE)
	{
		if (( defined('IPSCONNECT_LOG') AND IPSCONNECT_LOG === TRUE ) OR $force === TRUE)
		{
			if ($unlink === TRUE)
			{
				@unlink($this->basePath . '/' . $file . '.cgi');
			}

			/* something to dump? */
			if (!is_null($data))
			{
				$message .= "\n" . var_export($data, TRUE);
			}

			$message = "\n" . str_repeat('-', 80) . "\n> Time: " . time() . ' / ' . gmdate('r') . "\n> URL: " . $_SERVER['REQUEST_URI'] . "\n> " . $message;
			@file_put_contents($this->basePath . '/' . $file . '.cgi', $message, FILE_APPEND);
		}
	}

	public function isEnabled()
	{
		return (isset($this->settings['ipsConnectOn']) && (bool) $this->settings['ipsConnectOn']);
	}

	/*
	 * SSO Init
	 * modifies the current WP_User.
	 * @return void
	 * @access public
	 */

	public function init()
	{
		load_plugin_textdomain('ipsconnect/wp-ips-connect.php', false, dirname(plugin_basename(__FILE__)) . '/languages/');

		$masterUrl = $this->settings['masterUrl'];
		$ipsId = $localIdBackup = 0;

		// Only do this if the user has not expressly logged out of the IPS Connect Network...

		if (!isset($_COOKIE['ipsconnect_' . md5($masterUrl)]) or $_COOKIE['ipsconnect_' . md5($masterUrl)])
		{
			if (is_user_logged_in())
			{
				$ipsId = $localIdBackup = get_current_user_id();
			}
			elseif (isset($_COOKIE['ipsconnect_' . md5($masterUrl)]) and $_COOKIE['ipsconnect_' . md5($masterUrl)])
			{
				$_check = wp_remote_get($masterUrl . '?' . http_build_query(array('act' => 'cookies', 'data' => json_encode($_COOKIE))));
				if (!is_wp_error($_check) && $_check['response']['code'] == 200)
				{
					$login = @json_decode($_check['body'], TRUE);
					if (is_array($login))
					{
						switch ($login['connect_status'])
						{
							case 'SUCCESS':

								$credentials = array();

								// Load local member
								$member = $this->DB->get_row($this->DB->prepare('SELECT user_id FROM ' . $this->DB->usermeta . " WHERE meta_key='wpIpsConnectID' AND meta_value=%d", intval($login['connect_id'])));
								// If we don't have a member, create one

								if (is_wp_error($member) || !isset($member->user_id))
								{
									$data = array();

									$data['user_login'] = $login['connect_username'];
									$data['display_name'] = $login['connect_displayname'];
									$data['user_pass'] = wp_generate_password();
									$data['user_email'] = $login['connect_email'];
									$data['role'] = $this->settings['ipsConnectRole'];
									$credentials['ID'] = wp_insert_user($data);
									if (!is_wp_error($credentials['ID']))
									{
										$ipsId = $credentials['ID'];
										delete_user_option($ipsId, 'user_level');
										update_user_option($ipsId, 'capabilities', $data['role'] ? array($data['role'] => 1) : '' );
										update_user_meta($ipsId, 'wpIpsConnectID', $login['connect_id']);
									}
								}
								// Or update our existing one
								else
								{
									$updateDB = 0;
									$_member = get_userdata($member->user_id);
									$ipsId = $_member->ID;
									if ($_member->user_login != $login['connect_username'])
									{
										$updateDB = 1;
										$credentials['user_login'] = $login['connect_username'];
									}

									if ($_member->display_name != $login['connect_displayname'])
									{
										$updateDB = 1;
										update_user_meta($ipsId, 'nickname', $login['connect_displayname']);
										$this->DB->update($this->DB->comments, array('comment_author' => $login['connect_displayname']), array('user_id' => $ipsId), array('%s'), array('%d')
										);
										$credentials['display_name'] = $login['connect_displayname'];
									}

									if ($_member->user_email != $login['connect_email'])
									{
										$updateDB = 1;
										$credentials['user_email'] = $login['connect_email'];
									}
									if ($updateDB)
									{
										$credentials['ID'] = $ipsId;
										wp_update_user($credentials);
									}
									update_user_meta($ipsId, 'wpIpsConnectID', $login['connect_id']);
								}
								break;
							case 'VALIDATING':
								if ($login['connect_revalidate_url'] !== 'ADMIN_VALIDATION')
								{
									wp_redirect($login['connect_revalidate_url']);
									exit;
								}
								break;
						}
					}
				}
			}
		}
		else
		{
			//we logged out. abort. abort!
			wp_clear_auth_cookie();
			wp_set_current_user(0);
		}
		//sanity check. did the current user change??? if not, no need to update cookies etc.
		//@see http://community.invisionpower.com/topic/376502-wordpress-ipsconnect/page-45#entry2499886
		if ($ipsId !== $localIdBackup)
		{
			wp_set_auth_cookie($ipsId, 1);
			wp_set_current_user($ipsId);
		}
		if ($this->settings['masterApiKey'] && $this->isEnabled())
		{
			add_filter('widget_cache_period', array(&$this, 'resetWidgetsCache'), 0);
			add_action('load-post.php', array(&$this->postClass, 'addTopicMeta'), 0);
			add_action('load-post-new.php', array(&$this->postClass, 'addTopicMeta'), 0);
			add_action('wp_insert_comment', array(&$this->postClass, 'postReply'), 0, 2);
			add_action('wp_set_comment_status', array(&$this->postClass, 'postReply'), 0, 2);
			add_filter('comments_array', array(&$this->postClass, 'postReplies'), 0, 2);
			add_filter('get_avatar', array(&$this->postClass, 'getAvatar'), 0, 5);
			$this->handleGuestGroups();
		}
	}

	/*
	 * fun.... redirect to IPB Registration
	 */

	public function registrationRedirect()
	{
		if ($this->isEnabled())
		{
			$regUrl = $this->settingsClass->parseUrl(FALSE, 'ipsConnectRegister');
			header('location: ' . $regUrl);
			exit;
		}
	}

	public function passwordRedirect()
	{
		if ($this->isEnabled())
		{
			$passUrl = $this->settingsClass->parseUrl(FALSE, 'ipsConnectPass');
			header('location: ' . $passUrl);
			exit;
		}
	}

	/*
	 * for f..... sake WP...
	 * de/re-encrypt passwords so they match
	 * @param string $string password
	 * @return string $string encrypted password
	 */

	public function encodePassForIPB($string)
	{
		$string = stripslashes_deep($string);
		return $this->passForIpb($string);
	}

	/*
	 * en/de/re-crypt passwords so they match
	 * @param string $string password
	 * @param boolean $toLocal IPB to Local Application converion(default false)
	 * @return string $string encrypted password
	 */

	public function passForIpb($string, $toLocal = FALSE)
	{
		$bad = array('&', '!', '$', '"', '<', '>', "'", '\\');
		$good = array('&amp;', '&#33;', '&#036;', '&quot;', '&lt;', '&gt;', '&#39;', '&#092;');
		return $toLocal ? str_replace($good, $bad, $string) : str_replace($bad, $good, $string);
	}

	/*
	 * getConnect
	 * @return array
	 * @access public
	 */

	public function getConnect($key, $keyType)
	{
		$id = 0;
		$_member = NULL;
		switch ($keyType)
		{
			case 'username':
				$_member = get_user_by('login', $key);
				break;
			case 'email':
				$_member = get_user_by('email', $key);
				break;
		}
		if ($_member && !is_wp_error($_member))
		{
			$_member = $_member->data ? $_member->data : $_member;
			$connectID = get_user_meta($_member->ID, 'wpIpsConnectID', TRUE);
			if ($connectID)
			{
				$id = $connectID;
			}
		}
		$return = array('id' => $id, 'member' => $_member);
		return $return;
	}

	/*
	 * checkAuth. wp_authenticate hook.
	 * <=3.6.0 sends two strings, 3.6.1 sends an array with a size of 2
	 */

	public function checkAuth($username, $password = '')
	{
		$id = $nameOrEmail = is_array($username) ? $username[0] : $username;
		$oldpass = $password = empty($password) ? (is_array($username) ? $username[1] : $_REQUEST['pwd']) : $password;
		$errors = new WP_Error();
		$redirectTo = $this->getReturnUrl();
		$wpId = 0;
		$_member = NULL;
		$idType = (!isset($this->settings['masterType']) || $this->settings['masterType'] == 'username') ? 'username' : 'email';
		$typeString = ($idType == 'username') ? __('Username') : __(ucwords(str_replace('_', ' ', $this->settings['masterType'])));
		if ($nameOrEmail && $password)
		{
			$password = $this->encodePassForIPB($password);
			$credentials = array();
			$remember = 0;
			if (!empty($_POST['rememberme']))
			{
				$remember = intval($_POST['rememberme']);
			}
			$local = $this->getConnect($id, $this->settings['masterType']);
			if ($local['id'])
			{
				$idType = 'id';
				$id = $connectID = $local['id'];
			}
			if ($local['member'] && is_object($local['member']))
			{
				$_member = $local['member'];
			}
			$_login = wp_remote_get($this->settings['masterUrl'] . '?' . http_build_query(
							array(
								'act' => 'login',
								'idType' => $idType,
								'id' => ($idType == 'username') ? $this->encodePassForIPB($id) : $id,
								'password' => md5($password)
							)
			));
			if (!is_wp_error($_login) && $_login['response']['code'] == 200)
			{

				$login = @json_decode($_login['body'], TRUE);
				if (is_array($login))
				{
					switch ($login['connect_status'])
					{
						case 'SUCCESS':
							// Load local member
							$member = $this->DB->get_row($this->DB->prepare('SELECT user_id, meta_value FROM ' . $this->DB->usermeta . " WHERE meta_key='wpIpsConnectID' AND meta_value=%d", intval($login['connect_id'])));
							if (!is_wp_error($member) && $member->user_id)
							{
								$wpId = $member->user_id;
								$_connectID = $member->meta_value;
							}
							// If we can't load based of the connect ID, but we already loaded off the username, setup update for the connect ID

							if (!$_connectID && $_member && !is_wp_error($_member) && $_member->ID)
							{
								$wpId = $_member->ID;
							}

							// If we don't have a member, create one

							if (!$_connectID && !$connectID && !$wpId)
							{

								$data['user_login'] = $login['connect_username'];
								$data['display_name'] = $login['connect_displayname'];
								$data['user_pass'] = $password;
								$data['user_email'] = $login['connect_email'];
								$data['role'] = $this->settings['ipsConnectRole'];
								$credentials['ID'] = wp_insert_user($data);
								if (!is_wp_error($credentials['ID']))
								{
									$wpId = $credentials['ID'];
									delete_user_option($wpId, 'user_level');
									update_user_option($wpId, 'capabilities', $data['role'] ? array($data['role'] => 1) : '' );
								}
								else
								{
									$errors->add('MISSING_DATA', '<strong>' . __('ERROR') . '</strong>: ' . __('We could not log you in. Please try again later.'));
								}
							}
							//Update our now certainly existing member

							if ($wpId)
							{
								$credis = array('ID' => $wpId, 'user_pass' => $password);
								$creds = get_userdata($wpId);
								if (!is_wp_error($creds))
								{
									if ($creds->user_login != $login['connect_username'])
									{
										$credis['user_login'] = $login['connect_username'];
									}
									if ($creds->display_name != $login['connect_displayname'])
									{
										$credis['display_name'] = $login['connect_displayname'];
										update_user_meta($wpId, 'nickname', $login['connect_displayname']);
										$this->DB->update($this->DB->comments, array('comment_author' => $login['connect_displayname']), array('user_id' => $wpId), array('%s'), array('%d')
										);
									}

									if ($creds->user_email != $login['connect_email'])
									{
										$credis['user_email'] = $login['connect_email'];
									}
								}
								wp_update_user($credis);
								update_user_meta($wpId, 'wpIpsConnectID', $login['connect_id']);
							}
							break;
						case 'VALIDATING':
							switch ($login['connect_status'])
							{
								case 'ADMIN_VALIDATION':
									$errors->add('ADMIN_VALIDATION', '<strong>' . __('ERROR') . '</strong>: ' . __('Your account must be validated by an administrator to proceed. Please try again later.'));
									break;
								default:
									wp_redirect($login['connect_revalidate_url']);
									exit;
									break;
							}
							break;
						case 'WRONG_AUTH':
							$errors->add('WRONG_AUTH', '<strong>'
									. __('ERROR') . '</strong>:' .
									__('The password you entered for the') . ' ' . $typeString . ' <strong>'
									. $nameOrEmail . '</strong> ' . __('is incorrect. ')
									. '<a title="' . __('Password Lost and Found') .
									'" href="' . $this->settingsClass->parseUrl('wp-login.php?action=lostpassword')
									. '">' . __('Lost your password') . '</a>?');
							break;
						case 'NO_USER':
							$errors->add('NO_USER', '<strong>' . __('ERROR') . '</strong>: ' . __('Invalid') . ' ' . $typeString . '.  <a href="' . $this->settingsClass->parseUrl('wp-login.php?action=lostpassword') . '" title="' . __('Password Lost and Found') . '">' . __('Lost your password?') . '</a>');
							break;
						case 'ACCOUNT_LOCKED':
							$minutes = isset($login['connect_unlock_period']) ? ceil(( time() + ( $login['connect_unlock_period'] * 60 ) ) - $login['connect_unlock']) : ceil($login['connect_unlock'] / 60);
							$errors->add('ACCOUNT_LOCKED', '<strong>' . __('ERROR') . '</strong>: ' . __('Your account is locked. Please try again in ') . '<strong>' . $minutes . '</strong>' . __(' minutes.'));
							break;
						case 'MISSING_DATA':
						default:
							$this->missingData($errors, __LINE__);
							break;
					}
				}
			}
			if (!$wpId && $_member && !is_wp_error($_member) && $_member->ID && !$errors->get_error_message('ACCOUNT_LOCKED'))
			{

				/*
				 * #$$*@
				 * user is existing in this database without ipsconnect/existing in master
				 * but has logged out of ipsconnect on a diff account.
				 * so, we register with master if ok.
				 */
				if (user_pass_ok($_member->user_login, $oldpass))
				{
					$check = wp_remote_get($this->settings['masterUrl'] . '?' . http_build_query(
									array(
										'act' => 'check',
										'key' => $this->settings['masterKey'],
										'username' => $this->encodePassForIPB($_member->user_login),
										'email' => $_member->user_email,
										'displayname' => $this->encodePassForIPB($_member->display_name)
									)
					));
					if (!is_wp_error($check) && $check['response']['code'] == 200)
					{
						$login = @json_decode($check['body'], TRUE);
						if (is_array($login))
						{
							if ($login['status'] == 'SUCCESS')
							{
								$wpId = $_member->ID;
								$this->register($wpId, $oldpass);
								wp_set_auth_cookie($wpId, $remember);
								wp_set_current_user($wpId);
								$this->loginRedirect($nameOrEmail, $wpId, $oldpass);
							}
							$this->missingData($errors, __LINE__);
						}
						$this->missingData($errors, __LINE__);
					}
					$this->missingData($errors, __LINE__);
				}
				$this->missingData($errors, __LINE__);
			}
		}
		/* got an error?
		 * then halt.... login form it is
		 */
		$canReg = get_option('users_can_register');
		if ($errors->get_error_message())
		{
			$this->loginForm($errors, $nameOrEmail, $typeString, $redirectTo, $canReg);
		}
		if ($wpId)
		{
			wp_set_auth_cookie($wpId, $remember);
			wp_set_current_user($wpId);
			$this->loginRedirect($nameOrEmail, $wpId, $oldpass);
		}
		$this->loginForm($errors, $nameOrEmail, $typeString, $redirectTo, $canReg);
	}

	public function missingData($errors, $line)
	{
		if (!$errors->get_error_message())
		{
			$errors->add('MISSING_DATA', '<strong>' . __('ERROR') . ' [#' . $line . ']' . '</strong>: ' . __('We could not log you in. Please try again later.'));
		}
	}

	/*
	 * Print the login form
	 * @param object $errors WP_Error object.
	 * @param string $login User login name/email.
	 * @param string $typeString 'language' string for name/email.
	 * @param string $redirectTo url to redirect to on successful login.
	 * @param string $allowReg whether user registration is allowed..
	 * @return void(prints to screen)
	 * @access public
	 */

	public function loginForm($errors = NULL, $login = '', $typeString = '', $redirectTo = '', $allowReg = FALSE, $return = FALSE)
	{
		if (function_exists('login_header') && !$return)
		{
			login_header(__('Log In'), '', $errors);
		}
		$loginForm = wp_login_form(
				array(
					'echo' => false,
					'redirect' => $redirectTo,
					'value_username' => $login
				)
		);
		$loginForm .= '<p id="nav">';
		if ($allowReg)
		{
			$loginForm .= '<a href="' . $this->settingsClass->parseUrl('wp-login.php?action=register') . '">' . __('Register') . '</a> | ';
		}
		$loginForm .= '<a href="' . $this->settingsClass->parseUrl('wp-login.php?action=lostpassword') . '" title="' . __('Password Lost and Found') . '">' . __('Lost your password?') . '</a>';
		$loginForm .= '</p>';
		$loginForm = str_replace(__('Username'), $typeString, $loginForm);
		if ($return)
		{
			return $loginForm;
		}
		echo $loginForm;
		if (function_exists('login_footer'))
		{
			login_footer((is_object($errors) && $errors->get_error_message('NO_USER')) ? 'user_login' : 'user_pass');
		}
		exit;
	}

	/*
	 * Redirect the user to the master application on successful login
	 * @param string $user_login User login name.
	 * @param object $user WP_User object.
	 * @return void(may redirect)
	 * @access public
	 */

	public function loginRedirect($user_login, $user, $password = '')
	{
		$user = is_object($user) ? $user->data->ID : $user;
		$connectID = get_user_meta($user, 'wpIpsConnectID', TRUE);
		if ($connectID)
		{
			$password = empty($password) ? $_REQUEST['pwd'] : $password;
			$password = empty($password) ? wp_generate_password() : $password;
			$password = $this->encodePassForIPB($password);
			$wpUrlBase = $this->getReturnUrl();
			wp_redirect($this->settings['masterUrl'] . '?' . http_build_query(
							array('act' => 'login',
								'idType' => 'id',
								'id' => $connectID,
								'password' => md5($password),
								'key' => md5($this->settings['masterKey'] . $connectID),
								'redirect' => base64_encode($wpUrlBase),
								'redirectHash' => md5($this->settings['masterKey'] . base64_encode($wpUrlBase)),
								'noparams' => '1')));
			exit;
		}
	}

	public function getReturnUrl()
	{
        if(empty($this->redirect_to)){

            if (isset($_REQUEST['redirect_to'])){
                $this->redirect_to = urldecode($_REQUEST['redirect_to']);
            }
            elseif(isset($_SERVER['HTTP_REFERER'])){
                $this->redirect_to = $_SERVER['HTTP_REFERER'];
            }
            else{
                $this->redirect_to = $this->settings['returnUrl'];
            }
        }

		$wpUrlBase = $this->redirect_to;
		$wpUrlBase = ( strpos($wpUrlBase, 'wp-admin') !== FALSE && !is_super_admin() ) ? $this->settings['returnUrl'] : $wpUrlBase;
		$wpUrlBase = ( strpos($wpUrlBase, 'wp-login.php') !== FALSE ) ? $this->settings['returnUrl'] : $wpUrlBase;
		return $wpUrlBase;
	}

	/*
	 * Log the user out.
	 * @param int $id User ID.
	 * @return void (redirects)
	 * @access public
	 */

	public function logout($id)
	{
		$connectID = get_user_meta($id, 'wpIpsConnectID', TRUE);
		if ($connectID)
		{
			wp_redirect($this->settings['masterUrl'] . '?' . http_build_query(
							array('act' => 'logout',
								'id' => $connectID,
								'key' => md5($this->settings['masterKey'] . $connectID),
								'redirect' => base64_encode($this->settings['returnUrl']),
								'redirectHash' => md5($this->settings['masterKey'] . base64_encode($this->settings['returnUrl'])),
								'noparams' => '1')));
			exit;
		}
	}

	/*
	 * Check User Fields
	 * @param object $errors WP_Error object.
	 * @param boolean $update Whether this is a new user.
	 * @param object $user WP_User object.
	 * @return void
	 * @access public
	 */

	public function checkFields($errors, $update, $user)
	{
		switch ($update)
		{
			case 0:
				if (!$errors->get_error_message('username_exists') && !$errors->get_error_message('displayname_exists') && !$errors->get_error_message('email_exists'))
				{
					//Error checks
					$check = wp_remote_get($this->settings['masterUrl'] . '?' . http_build_query(
									array('act' => 'check',
										'key' => $this->settings['masterKey'],
										'username' => $user->user_login,
										'email' => $user->user_email,
										'displayname' => $user->user_login)
					));

					if (!is_wp_error($check) && $check['response']['code'] == 200)
					{
						$login = @json_decode($check['body'], TRUE);
						if (is_array($login))
						{
							if ($login['status'] == 'SUCCESS')
							{
								if ($login['username'] === FALSE && !$errors->get_error_message('username_exists'))
								{
									$errors->add('username_exists', '<strong>' . __('ERROR') . '</strong>: ' . __('This username is already registered. Please choose another one.'));
								}
								if ($login['status'] === FALSE && !$errors->get_error_message('displayname_exists'))
								{
									$errors->add('displayname_exists', '<strong>' . __('ERROR') . '</strong>: ' . __('This display name is already registered. Please choose another one.'));
								}
								if ($login['email'] === FALSE && !$errors->get_error_message('email_exists'))
								{
									$errors->add('email_exists', '<strong>' . __('ERROR') . '</strong>: ' . __('This email is already registered, please choose another one.'));
								}
							}
						}
					}
				}
				break;
			case 1:
				$connectID = get_user_meta($user->ID, 'wpIpsConnectID', TRUE);
				$params = array('act' => 'change',
					'id' => $connectID,
					'key' => md5($this->settings['masterKey'] . $connectID));
				if ($_POST['nickname'] && $user->user_login != $_POST['nickname'])
				{
					$params['displayname'] = $_POST['nickname'];
				}
				else if ($_POST['display_name'] && $user->user_login != $_POST['display_name'])
				{
					$params['displayname'] = $_POST['display_name'];
				}
				if ($_POST['email'] && $_POST['email'] != $user->user_email)
				{
					$params['email'] = $_POST['email'];
				}
				if ($_POST['pass1'] && $_POST['pass2'] && $_POST['pass1'] === $_POST['pass2'])
				{
					$params['password'] = md5($this->encodePassForIPB($_POST['pass1']));
				}
				$check = wp_remote_get($this->settings['masterUrl'] . '?' . http_build_query($params));
				if (!is_wp_error($check) && $check['response']['code'] == 200)
				{
					$login = @json_decode($check['body'], TRUE);
					if (is_array($login))
					{
						if ($login['status'] != 'SUCCESS')
						{
							if ($login['status'] == 'DISPLAYNAME_IN_USE' && !$errors->get_error_message('displayname_exists'))
							{
								$errors->add('nickname_exists', '');
								$errors->add('displayname_exists', '<strong>' . __('ERROR') . '</strong>: ' . __('This display name is already registered. Please choose another one.'));
							}
							if ($login['status'] == 'EMAIL_IN_USE' && !$errors->get_error_message('email_exists'))
							{
								$errors->add('email_exists', '<strong>' . __('ERROR') . '</strong>: ' . __('This email is already registered, please choose another one.'));
							}
						}
					}
				}
				break;
		}
		return $errors;
	}

	/*
	 * Register new User
	 * @param int $id User ID.
	 * @param string $password Password, if given.
	 * @return void
	 * @access public
	 */

	public function register($id, $password = '')
	{
		$user = get_userdata($id);
		$password = empty($password) ? $_REQUEST['pwd'] : $password;
		$password = empty($password) ? $_REQUEST['pass1'] : $password;
		$password = empty($password) ? wp_generate_password() : $password;
		$password = $this->encodePassForIPB($password);
		$_check = wp_remote_get($this->settings['masterUrl'] . '?' . http_build_query(
						array('act' => 'register',
							'key' => $this->settings['masterKey'],
							'username' => $this->encodePassForIPB($user->user_login),
							'email' => $user->user_email,
							'displayname' => $this->encodePassForIPB($user->user_login),
							'password' => md5($password)
						)
		));
		if (!is_wp_error($_check) && $_check['response']['code'] == 200)
		{
			$_login = @json_decode($_check['body'], TRUE);
			if (is_array($_login) && $_login['status'] == 'SUCCESS' && $_login['id'])
			{
				update_user_meta($id, 'wpIpsConnectID', $_login['id']);
			}
		}
	}

	public function checkRegister($errors, $login, $email)
	{
		if (!$errors->get_error_message('username_exists') && !$errors->get_error_message('displayname_exists') && !$errors->get_error_message('email_exists'))
		{
			$check = wp_remote_get($this->settings['masterUrl'] . '?' . http_build_query(
							array('act' => 'check',
								'key' => $this->settings['masterKey'],
								'username' => $this->encodePassForIPB($login),
								'email' => $email,
								'displayname' => $this->encodePassForIPB($login))
			));
			if (!is_wp_error($check) && $check['response']['code'] == 200)
			{
				$login = @json_decode($check['body'], TRUE);
				if (is_array($login))
				{
					if ($login['status'] == 'SUCCESS')
					{
						if ($login['username'] === FALSE && !$errors->get_error_message('username_exists'))
						{
							$errors->add('username_exists', '<strong>' . __('ERROR') . '</strong>: ' . __('This username is already registered. Please choose another one.'));
						}
						if ($login['status'] === FALSE && !$errors->get_error_message('displayname_exists'))
						{
							$errors->add('displayname_exists', '<strong>' . __('ERROR') . '</strong>: ' . __('This display name is already registered. Please choose another one.'));
						}
						if ($login['email'] === FALSE && !$errors->get_error_message('email_exists'))
						{
							$errors->add('email_exists', '<strong>' . __('ERROR') . '</strong>: ' . __('This email is already registered, please choose another one.'));
						}
					}
				}
			}
		}
		return $errors;
	}

	/*
	 * Register Widgets
	 * @return void
	 * @access public
	 * @todo finish the login box meta widget.
	 */

	public function registerWidgets()
	{
		$widgets = $this->settings['masterDisableWidgets'];
		$widgets = $widgets ? explode(',', $widgets) : array();
		foreach ($this->settings['ipsconnect_widgets'] as $widget)
		{
			if (in_array($widget, $widgets))
			{
				continue;
			}
			require_once $this->basePath . 'widgets/' . $widget . '.php';
			register_widget('Ips_Connect_' . ucwords(str_replace(array('-', '/'), '_', $widget)) . '_Widget');
		}
	}

	/*
	 * Default Widgets Cache
	 * @return void
	 * @access public
	 */

	public function resetWidgetsCache($cacheTime = 0)
	{
		$cacheTime = intval($cacheTime);
		$globalTime = intval($this->settings['widgetCachePeriod']);
		return($cacheTime < $globalTime) ? $globalTime : $cacheTime;
	}

	public function getPostClass()
	{
		if (!is_object($this->postClass))
		{
			require_once $this->basePath . 'sources/classes/post.php';
			$this->postClass = new ipsConnectPostClass();
		}
		return $this->postClass;
	}

	/*
	 * Delete the User
	 * @param int $user_id User ID.
	 * @return void(ignored)
	 * @access public
	 */

	public function delete($user_id)
	{
		$connectID = get_user_meta($user_id, 'wpIpsConnectID', TRUE);
		if ($connectID)
		{
			wp_remote_get($this->settings['masterUrl'] . '?' . http_build_query(
							array('act' => 'delete',
								'id' => array($connectID),
								'key' => md5($this->settings['masterKey'] . json_encode(array($connectID))),
							)
			));
		}
	}

	public function handleGuestGroups()
	{
		$_g = $this->settings['slaveDisableGroups'] ? explode(',', $this->settings['slaveDisableGroups']) : array();
		if (count($_g) && is_user_logged_in())
		{
			$user = wp_get_current_user();
			$wpId = $user->ID ? $user->ID : $user->data->ID;
			if (!is_array(self::$member))
			{
				if (!isset(self::$members[$wpId]))
				{
					if (!isset(self::$connectIds[$wpId]))
					{
						self::$connectIds[$wpId] = get_user_meta($wpId, 'wpIpsConnectID', TRUE);
					}
					$id = self::$connectIds[$wpId];
					if ($id)
					{
						self::$members[$wpId] = $this->getXmlRpc('fetchMember', array(
							'search_type' => 'id',
							'search_string' => $id,
						));
					}
				}
				self::$member = self::$members[$wpId];
			}
			if (is_array(self::$member) && self::$member['member_id'])
			{
				if (in_array(self::$member['member_group_id'], $_g))
				{
					if ($this->settings['slaveAlwaysSsoOwner'])
					{
						$blog_id = get_current_blog_id();
						$_member = $this->DB->get_row($this->DB->prepare('SELECT user_id FROM ' . $this->DB->usermeta . " WHERE meta_key='primary_blog' AND meta_value=%d", intval($blog_id)));
						if (!is_wp_error($_member) && $_member->user_id === $wpId)
						{
							return;
						}
					}
					wp_clear_auth_cookie();
					wp_set_current_user(0);
				}
			}
		}
	}

	public function getXmlRpc($method = '', array $params = array())
	{
		if (!class_exists('wpIpbApi'))
		{
			require_once $this->basePath . 'sources/classes/xmlrpc.php';
		}
		wpIpbApi::$settings = $this->settings;
		return ( $method === 'methods' ) ? wpIpbApi::methods() : wpIpbApi::getResponse($method, $params);
	}

	/*
	 * killAdminLinks and do user swap. woohoo!
	 * @return html
	 * @access public
	 */

	public function killAdminLinks()
	{
		$Css = '';
		$Js = '';
		$doHideAdmin = FALSE;
		$doSwapUcp = FALSE;
		$connectId = 0;
		if (is_user_logged_in())
		{
			if ($this->settings['hideAdmin'] || $this->settings['swapUCP'])
			{
				if ($this->settings['hideAdmin'] == 1 && !is_admin())
				{
					$doHideAdmin = TRUE;
				}
				if ($this->settings['swapUCP'])
				{
					$userObj = wp_get_current_user();
					$connectID = get_user_meta($userObj->data->ID, 'wpIpsConnectID', TRUE);
					if ($connectID)
					{
						$connectId = $connectID;
						$doSwapUcp = TRUE;
					}
				}
			}
		}
		if ($doHideAdmin || $doSwapUcp)
		{

			$logoutUrl = esc_url_raw(str_replace('&amp;', '&', $this->settingsClass->parseUrl(FALSE, 'logout')));
			$logout = __('Log Out');
			$ipsUrl = esc_url_raw($this->settingsClass->parseUrl(FALSE, 'ipsConnectProfile', 1));
			$wpProfile = $this->settingsClass->parseUrl('profile.php', 'profile');
			$Js .= <<<JAVASCRIPT
   window.addEventListener('load', function()
       {
JAVASCRIPT;
			if (!$doSwapUcp && $doHideAdmin && !is_admin())
			{
				$Js .= <<<JAVASCRIPT
document.getElementById("wp-admin-bar-my-account").getElementsByTagName('a')[0].href = "{$logoutUrl}";
document.getElementById("wp-admin-bar-my-account").getElementsByTagName('a')[0].title = '{$logout}';
JAVASCRIPT;
			}
			else if ($doSwapUcp)
			{
				$Js .= <<<JAVASCRIPT
   var getemAll = document.getElementsByTagName('a');
for(var i = 0; i < getemAll.length; i++)
{
if(getemAll[i].href === "{$wpProfile}")
    {
getemAll[i].href = "{$ipsUrl}";
    getemAll[i].addEventListener('click', function(e)
       {
       e.preventDefault();
       window.open("{$ipsUrl}");
       return false;
       });
    }
}
JAVASCRIPT;
			}
			$Js .= <<<JAVASCRIPT
	});
JAVASCRIPT;
			if (!$doSwapUcp && $doHideAdmin)
			{
				$Css .= '#wp-admin-bar-edit-profile, #wp-admin-bar-user-actions, #wp-admin-bar-user-info .display-name, #wp-admin-bar-user-info .username';
			}
			if ($doHideAdmin)
			{
				$Css .= ($Css ? ', ' : '') . '#wp-admin-bar-root-default, .widget_meta a[href^="' . esc_url($this->settingsClass->parseUrl(FALSE, 'admin')) . '"] { display:none!important; }';
			}
			echo '<style type="text/css" media="screen">' . $Css . '</style>';
			echo '<script type="text/javascript">' . $Js . '</script>';
		}
	}

}

/*
 * @package (MT) WordPress IPSConnect
 * @brief API Extensions
 * @version 1.1.1
 * @author Robert Marcher
 * @link http://www.marchertech.com/ Marcher Technologies
 * @copyright Copyright (c) 2013, Marcher Technologies All Rights Reserved
 */

class wpIpsConnectXmlRpc extends wpIpsConnect
{
	/*
	 * getConnect
	 * @return array
	 * @access public
	 */

	public function getConnect($key, $keyType)
	{
		if ($this->settings['masterApiKey'] && $keyType == 'display_name')
		{
			$id = 0;
			$_member = $this->DB->get_row($this->DB->prepare('SELECT ID FROM ' . $this->DB->users . " WHERE display_name=%s", $key));
			if (!isset(self::$connectIds[$key]))
			{
				if (!is_array(self::$member))
				{
					if (!isset(self::$members[$key]))
					{
						self::$members[$key] = $this->getXmlRpc('fetchMember', array(
							'search_type' => 'displayname',
							'search_string' => $this->encodePassForIPB($key),
						));
					}
					self::$member = self::$members[$key];
				}
				if (is_array(self::$member) && self::$member['member_id'])
				{
					self::$connectIds[$key] = self::$member['member_id'];
				}
			}
			if (isset(self::$connectIds[$key]))
			{
				$id = self::$connectIds[$key];
			}
			return array('id' => $id, 'member' => $_member);
		}
		return parent::getConnect($key, $keyType);
	}

}
