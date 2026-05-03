<?php
// config.php - edit to your environment
return [
'uploads_dir' => __DIR__ . '/uploads',
'incidents_file' => __DIR__ . '/incidents.json',
'centers_file' => __DIR__ . '/centers_info.json',
'contacts_file' => __DIR__ . '/contacts.json',


// Twilio example (replace with your credentials)
'sms' => [
'provider' => 'twilio', // or 'none'
'twilio_sid' => 'YOUR_TWILIO_SID',
'twilio_token' => 'YOUR_TWILIO_AUTH_TOKEN',
'twilio_from' => '+1234567890'
],


// Basic admin access (very simple - replace with proper auth)
'admin_username' => 'admin',
'admin_password' => 'changeme'
];