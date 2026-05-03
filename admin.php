<?php
if(!isset($_SESSION['admin_logged_in'])){
// login form
?>
<form method="POST">
<h3>Admin login</h3>
<?php if(!empty($error)) echo '<p style="color:red">'.htmlspecialchars($error).'</p>'; ?>
<label>User</label>
<input name="user">
<label>Pass</label>
<input name="pass" type="password">
<button type="submit">Login</button>
</form>
<?php
exit;
}


$incidents = [];
if(file_exists($incidents_file)){
$incidents = json_decode(file_get_contents($incidents_file), true) ?: [];
}
?>
<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin - Incidents</title>
<style>
body{font-family:Arial;padding:12px;background:#f5f7fb}
.card{background:#fff;padding:12px;border-radius:10px;margin-bottom:12px}
img{max-width:150px;border-radius:8px}
</style>
</head>
<body>
<h2>Incidents (<?=count($incidents)?>)</h2>
<?php foreach($incidents as $inc): ?>
<div class="card">
<strong><?=htmlspecialchars($inc['title'])?></strong>
<div><small><?=htmlspecialchars($inc['created_at'])?> — Center: <?=htmlspecialchars($centers_map[$inc['center']]['name'] ?? $inc['center'])?></small></div>
<p><?=nl2br(htmlspecialchars($inc['description']))?></p>
<div>Reporter: <?=htmlspecialchars($inc['reporter_name'])?> (<?=htmlspecialchars($inc['reporter_phone'])?>)</div>
<?php if(!empty($inc['lat']) && !empty($inc['lng'])): ?>
<div><a target="_blank" href="https://maps.google.com/?q=<?=urlencode($inc['lat'].','.$inc['lng'])?>">Open location</a></div>
<?php endif; ?>
<?php if(!empty($inc['photo'])): ?>
<div style="margin-top:8px"><img src="<?=htmlspecialchars($inc['photo'])?>"></div>
<?php endif; ?>
</div>
<?php endforeach; ?>
</body>
</html>