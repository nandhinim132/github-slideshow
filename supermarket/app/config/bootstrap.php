<?php

error_reporting(E_ALL);
ini_set('display_errors', APP_ENV === 'development' ? '1' : '0');

require_once __DIR__ . '/config.php';

// Timezone
date_default_timezone_set(APP_TIMEZONE);

// Session
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

// Helpers
require_once APP_BASE_PATH . '/app/helpers/util.php';
require_once APP_BASE_PATH . '/app/helpers/db.php';
require_once APP_BASE_PATH . '/app/helpers/auth.php';