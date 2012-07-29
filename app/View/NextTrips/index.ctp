<?php ?>
<h2>Västtrafik - Nästa tur</h2>
<p>Tänkt att till viss del likna den bussmonitor som nu finns på de flesta hållplatser</p>
<?php
echo $this->Form->create('NextTrip', array('url' => array('action'=>'results'), 'type'=>'GET'));
echo $this->Form->input('from', array('label' => 'Från'));
echo $this->Form->input('to', array('label' => 'Till'));
echo $this->Form->input('max_results', array('label' => 'Maxresultat. Visar bara så här många turer. Standard är 4.'));
echo $this->Form->input('weather_image', array('label' => 'Väder-bild (eller vilken bild som helst som du vill visa)'));
echo $this->Form->input('no_refresh', array('type' => 'checkbox', 'checked' => true, 'label' => 'Stäng av autorefresh (använder ajax istället)'));
echo $this->Form->end('OK');

?>
<h3>Snabblänkar</h3>
<ul>
    <li><?= $this->Html->link('Lindholmen till Chalmers med väder. Maxresultat 4 rader.', '/next_trips/results?from=.lind&to=.ch&weather_image=http%3A%2F%2Fwww.yr.no%2Fsted%2FSverige%2FV%25C3%25A4stra_G%25C3%25B6taland%2FLindholmen~2694868%2Favansert_meteogram.png&no_refresh=0&no_refresh=1'); ?></li>
    <li><?= $this->Html->link('Lindholmen till Chalmers med väder. Maxresultat 2 rader.', '/next_trips/results?from=.lind&to=.ch&max_results=2&weather_image=http%3A%2F%2Fwww.yr.no%2Fsted%2FSverige%2FV%25C3%25A4stra_G%25C3%25B6taland%2FLindholmen~2694868%2Favansert_meteogram.png&no_refresh=0&no_refresh=1'); ?></li>
    <li><?= $this->Html->link('Chalmers till Lindholmen utan väder', '/next_trips/results?from=.ch&to=.lind&weather_image=&no_refresh=0&no_refresh=1'); ?></li>
</ul>

<br/>
<br/>
<h3>Instruktioner</h3>
<p>
    Skriv hållplatserna så att de alltid skulle bli första resultatet för en sökning på Västtrafik.se.<br/>
    Det är ok att använda Västtrafiks kortkommandon exempelvis .lind för Lindholmen eller .ch för Chalmers.
</p>
<p>
    Väderbild är den bild som visas under hållplatstiderna. Inget angivet tar bort bilden.
</p>
<p>
    Autorefresh är bra om webbläsaren inte klarar av javascript. 
</p>
<h4>Exempel</h4>
<p>Från: .lind</p>
<p>Till: Chalmers</p>
<p>Väderbild: http://www.yr.no/sted/Sverige/V%C3%A4stra_G%C3%B6taland/Lindholmen~2694868/avansert_meteogram.png</p>
<p>Stäng av autorefresh</p>