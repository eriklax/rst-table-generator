<?php

// -b (bold first column)
// -t (trim all values)
// -c (csv support)
// -p (pad with string)

$options = getopt("f:btcp:");

$file = @file($options['f'] ?? "php://stdin");
if (empty($file)) die("Could not open file\n");

$lines = [];
foreach($file as $f)
{
	if (isset($options['c']))
		$row = str_getcsv($f);
	else
		$row = explode("\t", str_replace("\n", "", $f));
	if (isset($options['t']))
		$row = array_map(trim, $row);
	if (isset($options['b']) && count($lines) != 0)
		$row[0] = '**' .$row[0] . '**';
	$lines[] = $row;
}

$max = [];
foreach($lines as $ln => $l)
	foreach($l as $c => $w)
		if (strlen($w) > $max[$c]) $max[$c] = strlen($w);

ksort($max);

if (isset($options['p'])) echo $options['p'];
echo "+"; foreach($max as $c) echo str_repeat("-", $c + 2)."+"; echo "\n";
foreach($lines as $ln => $l)
{
	if (isset($options['p'])) echo $options['p'];
	echo "|"; foreach($l as $c => $w) echo " ".str_pad($w, $max[$c])." |"; echo "\n";
	if (isset($options['p'])) echo $options['p'];
	echo "+"; foreach($max as $c) echo str_repeat($ln == 0 ? "=" : "-", $c + 2)."+"; echo "\n";
}
