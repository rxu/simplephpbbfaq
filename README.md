Simple phpBB FAQ
=============

Extension for phpBB 3.1 which allows using additional phpBB-like language help files (like help_faq.php).

Using:
=============

Drop the language file named `help_<custom_name>.php` either into `/language/<iso>/` or into this extension's `/language/<iso>/` folder.
Then you can access it via `/faq.php?mode=<custom_name>` board relative URL.

`help_<custom_name>.php` file can be created either manually or using the <a href="https://www.phpbb.com/customise/db/extension/faq_manager_2/">FAQ manager</a> extension by <a href="https://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=38086">david63</a>.

Drop the language file named `help_<custom_name>_lang.php` containing language entry with the key `'HELP_<CUSTOM_NAME>_TITLE'` into the same place the `help_<custom_name>.php` file is at.
The custom help page title will be changed in according to the `'HELP_<CUSTOM_NAME>_TITLE'` value. Example: `'HELP_<CUSTOM_NAME>_TITLE' => 'My custom FAQ page',`.
