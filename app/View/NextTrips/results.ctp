<?php if (empty($this->params->query['no_refresh'])) : ?>
    <meta http-equiv="refresh" content="5">
<?php endif; ?>
<div id="vasttrafik"></div>
<div id="weather"></div>
<script>
    var updateVasttrafikInterval;
    var updateWeatherInterval;
    var updateVasttrafikIntervalMillis = 10*1000;
    var updateWeatherIntervalMillis = 60*10*1000;
    
    $(document).ready(function() {
	updateVasttrafik();
	updateWeather();
    });
    
    
    function updateVasttrafik() {
	clearInterval(updateVasttrafikInterval);
	$.ajax({
	    url: "<?= Router::url('/next_trips/vasttrafik_xhr?from=' . $this->params->query['from'] . '&to=' . $this->params->query['to'] . '&max_results=' . (isset($this->params->query['max_results']) ? $this->params->query['max_results'] : '4'), true); ?>"
	}).done(function(data) { 
	    $('div#vasttrafik').html(data);
	}).always(function() {
	    updateVasttrafikInterval = window.setInterval(updateVasttrafik,updateVasttrafikIntervalMillis);
	}
    );
	
    }
    
    function updateWeather() {
	console.log('updateWeather');
	clearInterval(updateWeatherInterval);
	$.ajax({
	    url: "<?= Router::url('/next_trips/weather_xhr?weather_image=' . urlencode($this->params->query['weather_image']), true); ?>"
	}).done(function(data) { 
	    $('div#weather').html(data);
	}).always(function() {
	    updateWeatherInterval = window.setInterval(updateWeather,updateWeatherIntervalMillis);
	}
    );
    }
</script>
<style>
    html, body, #content {
	background: #000;
	color: #fff;
    }
    #content {
	padding: 10px 40px 40px 40px;
    }
    div#timeHeader {
	text-align: right;
	font-size: 60px;
	margin-bottom: 50px;
    }
    table th, table tr td, table tr {
	color: #fff;
	border: none;
    }
    table tr:nth-child(even) {
	background: #222;
    }
    table.departures td {
	vertical-align: middle;
	text-align: center;
	font-size: 80px;
    }
    table.departures th {
	font-weight: normal;
	vertical-align: middle;
	text-align: center;
	font-size: 60px;
    }
    td.line {
	width: 100px;
	height: 100px;
	font-weight: 900;
	vertical-align: middle;
	text-align: center;
	font-size: 80px;
    }

    @media (max-width: 960px), (max-height: 600px) {
	#content {
	    padding: 10px 10px 10px 10px;
	}
	div#timeHeader {
	    font-size: 30px;
	    margin-bottom: 10px;
	}
	table.departures th {
	    font-size: 30px;
	}
	table.departures td {
	    font-size: 45px;
	}
	td.line {
	    height: 50px;
	}
    }
</style>
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

