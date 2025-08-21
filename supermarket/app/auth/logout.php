<?php
require_once __DIR__ . '/../config/bootstrap.php';

logoutUser();
redirect(APP_URL_BASE . '/login.php');