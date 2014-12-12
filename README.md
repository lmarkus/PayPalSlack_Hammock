# The PayPal+Slack extension
A [PayPal](https://developer.paypal.com) integration for [Slack](http://slack.com)

## Supports the following:
* Display Sales Notifications. Can be split between two channels (eg: sales / fulfillment). Now you can know right away when you make a sale!
* Creating / Sending invoices via slash command, using a natural language: `/paypal invoice lenny@msn.com 10.99 for Bananas`
* Providing support options via slash command: `/paypal support`

## Disclaimer:
This is hackathon code, built under 20 hours. Expect brittleness.




### Some notes for my own benefit
What I've done so far:

* Configure Apache on my mac:  https://discussions.apple.com/docs/DOC-3083
* Install hammock: https://github.com/tinyspeck/hammock
* Register a new slack application: https://api.slack.com/applications
* Change hammock configuration `lib/config.php` as follows:

```

	$cfg['root_url'] = 'http://127.0.0.1/~lmarkus/hammock/';


	# The details of your OAuth application.
	# You can create it here: https://api.slack.com/applications
	# Make sure to set the OAuth URL to the same as 'root_url' above

        $cfg['client_id'] = '3199114983.3202489006';
        $cfg['client_secret'] = '769b8d4ee84473808ac04c18ac44cfda';


	# Where we should store cookies
	# NOTE: the domain must contain a dot. if you want to use localhost,
	# try `127.0.0.1`, or set up an alias in your /etc/hosts file.
	# If running from a subdirectory, make sure to change 'cookie_path'

        $cfg['cookie_domain'] = '127.0.0.1';

```
* Create boilerplate plugin, and start modifying it for PayPal.
* 
* Dropped in PayPal Logos in various sizes for displays.

* Installed PayPal php REST sdk

* Added basic support for IPN

* Added Support for slash commands:
	- invoice
	- support
	- help

* Need to find out from Slack support how to deploy commands upon installation.
	
* Added mutichannel support
* 
