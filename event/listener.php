<?php
/**
 *
 * @package simplephpbbfaq
 * @copyright (c) 2014 Ruslan Uzdenov (rxu)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace rxu\simplephpbbfaq\event;

/**
* Event listener
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
    public function __construct(\phpbb\config\config $config, \phpbb\user $user, \phpbb\extension\manager $phpbb_extension_manager, $phpbb_root_path, $php_ext)
    {
		$this->config = $config;
        $this->user = $user;
        $this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
    }

	static public function getSubscribedEvents()
	{
		return array(
			'core.faq_mode_validation'		=> 'add_new_faq',
		);
	}

	public function add_new_faq($event)
	{
		$mode = $event['mode'];
		$page_title = $event['page_title'];
		$ext_name = $event['ext_name'];
		$lang_file = $mode;

		// If custom help file is not in the main language folder, try extension one
		if (!$this->lang_file_exists($lang_file))
		{
			$ext_name = 'rxu/simplephpbbfaq';
			if (!$this->lang_file_exists($lang_file, $ext_name))
			{
				// Custom help file doesn't exist neither in the main nor in the extension's language folder
				// Reset vars below to load phpBB's default faq.php page
				$lang_file = $ext_name = '';
			}
		}

		// If custom help page title language entry doesn't exist, load phpBB's default one
		// You can add custom title language entry in additional language file with the key 'HELP_<MODE>_TITLE' =>
		$page_title = (isset($this->user->lang['HELP_' . strtoupper($mode) . '_TITLE'])) ? $this->user->lang['HELP_' . strtoupper($mode) . '_TITLE'] : $page_title;

		$event['page_title'] = $page_title;
		$event['ext_name'] = $ext_name;
		$event['lang_file'] = $lang_file;
	}

	private function lang_file_exists($lang_file, $ext_name = '', $use_help = true)
	{
		// Make sure the language name is set (if the user setup did not happen it is not set)
		if (!$this->user->lang_name)
		{
			$this->user->lang_name = basename($this->config['default_lang']);
		}

		if ($use_help && strpos($lang_file, '/') !== false)
		{
			$filename = dirname($lang_file) . '/help_' . basename($lang_file);
		}
		else
		{
			$filename = (($use_help) ? 'help_' : '') . $lang_file;
		}

		if ($ext_name)
		{
			$ext_path = $this->phpbb_extension_manager->get_extension_path($ext_name, true);

			$lang_path = $ext_path . 'language/';
		}
		else
		{
			$lang_path = $this->user->lang_path;
		}

		if (strpos($this->phpbb_root_path . $filename, $lang_path . $this->user->lang_name . '/') === 0)
		{
			$language_filename = $this->phpbb_root_path . $filename;
		}
		else
		{
			$language_filename = $lang_path . $this->user->lang_name . '/' . $filename . '.' . $this->php_ext;
		}

		return file_exists($language_filename);
	}
}
