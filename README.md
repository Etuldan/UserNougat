UserNougat
==========

What is it ?
------------
UserNougat is PHP user management system with a plugin system to link with other platforms.  
User will have to register once on UserNougat, and their credentials will works for the linked plaforms.  
Currently, there are plugins for PhpBB boards and Owncloud. If you want to develop your own plugin and distribute it, fell free to commit it on this repository !  

How does it work ?
------------------
UserNougat is a fork of the [UserCake](http://usercake.com) PHP user management. The main modification is the plugin system.  
Every plugin perform a database insert/modification in the targeted platform tables to add/modify users credentials.  
They also forbid any modification of the credentials directly in the targeted platform.

Requirements
------------
UserNougat requires PHP.
UserNougat supports MySQLi and requires MySQL server version 4.1.3 or newer.

Installation steps
------------------
1. Download the package
2. Edit the `db-settings.php` file in the `models` directory and fill out the connection details.
3. Remove (or rename with a non-php extension) the plugins you will NOT use in the `plugins` directory.
4. Edit all the plugins php file and fill out the configuration details.
5. Upload the UserNougat to your WebHosting.
6. Use the installer : visit http://yourdomain.com/install/ in your browser.

In order that plugins works, you need to have a fresh installation of the platforms before installing UserCake.

License
-------
UserNougat is under the GPLv3 license. See the `LICENSE` file.  
For UserCake, see the `LICENSE-UserCake.txt` file or [the original one](http://usercake.com/licence.txt).  
