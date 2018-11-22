<?php
require_once(WPSHOP_CLASSES_DIR.'/sofort_lib/core/sofortLibMultipay.inc.php');

/**
 * @copyright 2010-2015 SOFORT GmbH
 *
 * @license Released under the GNU LESSER GENERAL PUBLIC LICENSE (Version 3)
 * @license http://www.gnu.org/licenses/lgpl.html
 */
class Sofortueberweisung extends SofortLibMultipay {
	
	/**
	 * Constructor for Sofortueberweisung
	 *
	 * @param string $configKey
	 * @return \Sofortueberweisung
	 */
	public function __construct($configKey) {
		parent::__construct($configKey);
		$this->_parameters['su'] = array();
	}
	
	
	/**
	 * Setter for Customer Protection if possible for customers
	 * 
	 * @param bool $customerProtection (default true)
	 * @return Sofortueberweisung $this
	 */
	public function setCustomerprotection($customerProtection = true) {
		if (!array_key_exists('su', $this->_parameters) || !is_array($this->_parameters['su'])) {
			$this->_parameters['su'] = array();
		}
		
		$this->_parameters['su']['customer_protection'] = $customerProtection ? 1 : 0;
		
		return $this;
	}
  
  public function safeRedirect($url, $exit = TRUE) {
		try {
			// Only use the header redirection if headers are not already sent
			if (!headers_sent()) {
				header('HTTP/1.1 301 Moved Permanently');
				header('Location: ' . $url);
				// Optional workaround for an IE bug (thanks Olav)
				header("Connection: close");
			}
			// HTML/JS Fallback:
			// If the header redirection did not work, try to use various methods other methods
			print '<html>';
			print '<head><title>Redirecting you...</title>';
			print '<meta http-equiv="Refresh" content="0;url=' . $url . '" />';
			print '</head>';
			print '<body onload="location.replace(\'' . $url . '\')">';
			// If the javascript and meta redirect did not work, 
			// the user can still click this link
			print 'You should be redirected to this URL:<br />';
			print "<a href='$url'>$url</a><br /><br />";
			print 'If you are not, please click on the link above.<br />';
			print '</body>';
			print '</html>';
			// Stop the script here (optional)
			if ($exit) {
				exit;
			}
		} catch (Exception $err) {
			return $err->getMessage();
		}
	}
	
	
	/**
	 * Handle Errors occurred
	 * 
	 * @return void
	 */
	protected function _handleErrors() {
		parent::_handleErrors();
		
		//handle errors
		if (isset($this->_response['errors']['su'])) {
			if (!isset($this->_response['errors']['su']['errors']['error'][0])) {
				$tmp = $this->_response['errors']['su']['errors']['error'];
				unset($this->_response['errors']['su']['errors']['error']);
				$this->_response['errors']['su']['errors']['error'][0] = $tmp;
			}
			
			foreach ($this->_response['errors']['su']['errors']['error'] as $error) {
				$this->errors['su'][] = $this->_getErrorBlock($error);
			}
		}
		
		//handle warnings
		if (isset($this->_response['new_transaction']['warnings']['su'])) {
			if (!isset($this->_response['new_transaction']['warnings']['su']['warnings']['warning'][0])) {
				$tmp = $this->_response['new_transaction']['warnings']['su']['warnings']['warning'];
				unset($this->_response['new_transaction']['warnings']['su']['warnings']['warning']);
				$this->_response['new_transaction']['warnings']['su']['warnings']['warning'][0] = $tmp;
			}
			
			foreach ($this->_response['new_transaction']['warnings']['su']['warnings']['warning'] as $warning) {
				$this->warnings['su'][] = $this->_getErrorBlock($warning);
			}
		}
	}
}