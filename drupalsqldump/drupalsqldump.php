#!/usr/bin/php
<?php

$root = '/var/www';
$output = '/tmp';

$gzip = "/bin/gzip";
$zipext = "gz";

$mysqldump = "/usr/bin/mysqldump";

if (! file_exists($gzip)) {
  print "You need gzip installed.\n";
  exit (1);
}

if (! file_exists($mysqldump)) {
  print "You need mysqldump installed.\n";
  exit (1);
}

if (! isset($argv[1])) {
  print "You must specify a drupal site.\n";
  exit(1);
}

$target = $argv[1];
$settings = "$root/$target/sites/default/settings.php";

if (! file_exists($settings)) {
  print "You must specify a valid drupal site.\n$settings does not exists.\n";
  exit(1);
}

require $settings;

preg_match('!mysqli://(.+?):(.+?)@(.+?)/(.+)!', $db_url, $matches);

$user = $matches[1];
$pass = $matches[2];
$host = $matches[3];
$db = $matches[4];

print "Running dump...\n";

$command = "$mysqldump -u $user -p$pass -h $host $db > $output/$target.sql";
shell_exec($command);

$zip = "$gzip $output/$target.sql";
shell_exec($zip);

print "Dump saved to:\n";
print "/tmp/$target.sql.$zipext\n";

?>
