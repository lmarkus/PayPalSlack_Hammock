# The PayPal+Slack extension


What I've done so far:

Configure Apache on my mac:  https://discussions.apple.com/docs/DOC-3083
Install hammock: https://github.com/tinyspeck/hammock
Register a new slack application: https://api.slack.com/applications
Change hammock configuration `lib/config.php` as follows:

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

Probably need to turn off warnings in PHP due to some minor version incompatibilities....
