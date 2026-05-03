<?php
$photo_path = 'uploads/' . $safe; // web path
}
}


// prepare incident record
$incident = [
'id' => uniqid('inc_', true),
'title' => $title,
'description' => $description,
'center' => $center,
'reporter_name' => $reporter_name,
'reporter_phone' => $reporter_phone,
'lat' => $lat,
'lng' => $lng,
'photo' => $photo_path,
'created_at' => date('c')
];


// append to incidents.json safely
$incidents = [];
if(file_exists($incidents_file)){
$raw = file_get_contents($incidents_file);
$incidents = json_decode($raw, true) ?: [];
}
array_unshift($incidents, $incident); // newest first
file_put_contents($incidents_file, json_encode($incidents, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));


// send SMS to contacts (best-effort)
$contacts = [];
if(file_exists($contacts_file)){
$contacts = json_decode(file_get_contents($contacts_file), true) ?: [];
}


if(!empty($contacts) && isset($config['sms']['provider']) && $config['sms']['provider']==='twilio'){
// Twilio REST call (using curl). Replace credentials in config.php
$sid = $config['sms']['twilio_sid'];
$token = $config['sms']['twilio_token'];
$from = $config['sms']['twilio_from'];
$message = "New incident: {$title} — Reporter: {$reporter_name} ({$reporter_phone})";
if($lat && $lng) $message .= " Location: https://maps.google.com/?q={$lat},{$lng}";


foreach($contacts as $c){
$to = $c['phone'];
// Twilio API
$url = "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json";
$data = http_build_query(['From'=>$from, 'To'=>$to, 'Body'=>$message]);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_USERPWD, $sid . ':' . $token);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$resp = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);
// you may want to log $resp / $err
}
}


// redirect to a thank-you or back to form
header('Location: index.php?sent=1');
exit;