<?php

if (!empty($weatherImage))
    echo $this->Html->image($weatherImage . '?' . time());
?>