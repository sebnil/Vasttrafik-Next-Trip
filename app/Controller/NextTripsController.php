<?php

App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');

class NextTripsController extends AppController {

    public $helpers = array('Cache');
    public $cacheAction = array(
	'vasttrafik_xhr' => 10,
	'weather_xhr' => 10,
	'index' => '+1 day',
	'results' => '+1 day',
    );
	
	// Byt denna mot din egen nyckel. Skicka ett mail till webmaster@vasttrafik.se så får du din nyckel
    var $authKey = '6511154616';
	
    var $httpSocket = null;

    function index() {
	$this->set('title_for_layout', 'Sök nästa tur');
    }

    function results() {
	$this->set('title_for_layout', 'Nästa tur');
	$this->httpSocket = new HttpSocket();

	/* Alternativa urler:
	  $results = $HttpSocket->post('https://www.vasttrafik.se/CustomerServices/EPiServerWs/Service.svc/GetNextTrips', array(
	  'request' => array(
	  'RDC_Language' => 'sv-SE',
	  'StopAreaExternalId' => '9021014006580000'
	  )
	  ));
	  $results = $HttpSocket->post('http://reseplanerare.vasttrafik.se/bin/stboard.exe/sox?ld=fe1&', array(
	  'input' => '.lind',
	  'disableEquivs' => 1,
	  'viewMode' => 'COMPACT',
	  'preview' => '60',
	  'sortType' => 'LINE',
	  'format' => 'json',
	  'boardType=dep&start:' => 'Avgång »'
	  )); */
    }

    function vasttrafik_xhr() {
	// tar bort all layout
	$this->layout = 'ajax';

	// httpSocket är ett objekt som vi gör alla http-anrop med
	$this->httpSocket = new HttpSocket();

	// fortsätt endast om from och to är satta
	if (!empty($this->params->query['from']) && !empty($this->params->query['to'])) {
	    // hämta id till hållplatserna. ta första resultatet
	    $fromLocationId = $this->getStopId($this->params->query['from']);
	    $toLocationId = $this->getStopId($this->params->query['to']);

	    if (!empty($fromLocationId) && !empty($toLocationId)) {
		$results = $this->httpSocket->get('http://api.vasttrafik.se/bin/rest.exe/v1/departureBoard', array(
		    'authKey' => $this->authKey,
		    'format' => 'json',
		    'id' => $fromLocationId,
		    'direction' => $toLocationId
			));
		$departureBoard = json_decode($results->body, true);

		if (!empty($departureBoard['DepartureBoard']['servertime'])) {
		    $serverTime = strtotime($departureBoard['DepartureBoard']['serverdate'] . ' ' . $departureBoard['DepartureBoard']['servertime']);
		    $this->set(compact('serverTime'));
		}
		if (!empty($departureBoard['DepartureBoard']['Departure']))
		    $this->set('departures', $departureBoard['DepartureBoard']['Departure']);
	    }
	}
	
	if (!empty($this->params->query['max_results']))
	    $maxResults = $this->params->query['max_results'];
	else
	    $maxResults = 4;
	$this->set(compact('maxResults'));
    }

    function weather_xhr() {
	$this->layout = 'ajax';
	
	// använd standardbild om weather_image inte är satt
	if (empty($this->params->query['weather_image']))
	    $weatherImage = false;
	else
	    $weatherImage = $this->params->query['weather_image'];
	
	// lägg till http:// om det saknas
	if (strpos($weatherImage, 'http://') !== 0 && strpos($weatherImage, 'http://') !== 0)
	    $weatherImage = 'http://' . $weatherImage;
	
	$this->set(compact('weatherImage'));
    }

    // hämta id till hållplatserna. ta första resultatet
    private function getStopId($query) {
	// ta från cache om den finns. hållplatserna ändras inte så ofta.
	$locationId = Cache::read('getStopId.' . md5($query), 'long');
	if (!$locationId) {
	    $locations = $this->httpSocket->get('http://api.vasttrafik.se/bin/rest.exe/v1/location.name', array(
		'authKey' => $this->authKey,
		'format' => 'json',
		'input' => $query
		    ));
	    $locations = json_decode($locations->body, true);
	    if (isset($locations['LocationList']['StopLocation']['id']))
		$locationId = $locations['LocationList']['StopLocation']['id'];
	    else if (isset($locations['LocationList']['StopLocation'][0]['id']))
		$locationId = $locations['LocationList']['StopLocation'][0]['id'];
	    else
		$locationId = false;
	    Cache::write('getStopId.' . md5($query), $locationId, 'long');
	}
	return $locationId;
    }

}