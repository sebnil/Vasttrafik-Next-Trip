<?php ?>
<div id="timeHeader">
    <?php
    if (!empty($serverTime))
	echo date('Y-m-d H:i', $serverTime);
    else
	echo 'Inget bra svar från servern.';
    ?>
</div>
<table class="departures">
    <?php
    if (isset($departures)) {
	echo $this->Html->tableHeaders(array(
	    'Linje',
	    'Tabelltid',
	    'Realtid'
	));
	$i = 0;

	foreach ($departures as $departure) {
	    //debug($departure);

	    $name = str_replace("Buss ", "", $departure['name']);
	    $name = str_replace("Spårvagn ", "", $name);

	    $time = strtotime($departure['date'] . ' ' . $departure['time']);
	    if (isset($departure['rtDate']) && isset($departure['rtTime']))
		$rtTime = strtotime($departure['rtDate'] . ' ' . $departure['rtTime']);

	    $style = 'color: ' . $departure['bgColor'] . '; background: ' . $departure['fgColor'];
	    echo $this->Html->tableCells(array(
		array(
		    array($name, array('class' => 'line', 'style' => $style)),
		    isset($time) ? time_diff($time - $serverTime) : '',
		    isset($rtTime) ? time_diff($rtTime - $serverTime) : '',
		    )));
	    if (++$i >= $maxResults)
		break;
	}
    }
    else
	echo 'Hittade inga avgångar.'
	?>
</table>
<?php
function time_diff($s) {
    $m = 0;
    $hr = 0;
    $d = 0;
    $td = "0";
    if ($s > 59) {
	$m = (int) ($s / 60);
	$s = $s - ($m * 60); // sec left over 
	$td = "$m";
    }
    if ($m > 59) {
	$hr = (int) ($m / 60);
	$m = $m - ($hr * 60); // min left over 
	$td = "$hr h";
	if ($m > 0)
	    $td .= ", $m min";
    }
    if ($hr > 23) {
	$d = (int) ($hr / 24);
	$hr = $hr - ($d * 24); // hr left over 
	$td = "$d dagar";
	if ($d > 1)
	    $td .= "s";
	if ($d < 3) {
	    if ($hr > 0)
		$td .= ", $hr hr"; if ($hr > 1)
		$td .= "s";
	}
    }
    return $td;
}