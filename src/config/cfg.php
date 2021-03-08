<?php

define('CLIENT_ID', '');
define('CLIENT_SECRET', '');
define('REDIRECT_URL', '');

define('MAIN_URL', 'https://login.microsoftonline.com');
define('API_URL', MAIN_URL . '/consumers');
define('GRAPH_SCOPE', 'User.ReadWrite offline_access Calendars.ReadWrite');
define('GRAPH_API_ENDPOINT', 'https://graph.microsoft.com/v1.0');

define('TOKENS_FILEPATH', __DIR__ . '/tokens.txt');