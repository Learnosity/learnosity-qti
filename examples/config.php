<?php

$consumer_key = 'yis0TYCu7U9V4o7M';

// Note - Consumer secret should never get displayed on the page - only used for creation of signature server side
$consumer_secret = '74c5fd430cf1242a527f6223aebd42d30464be22';

// Some products need the domain as part of the security signature. Demos has been tested on "localhost"
$domain = $_SERVER['SERVER_NAME'];

// Generate timestamp in format YYYYMMDD-HHMM for use in signature
$timestamp = gmdate('Ymd-Hi');

// Basic variables simulating any user details needed
$courseid = 'demo_' . $consumer_key;
$studentid = 'demo_student';
$teacherid = 'demo_teacher';
$schoolid = 'demo_school';

