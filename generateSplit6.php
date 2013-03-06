<?php
file_put_contents("generateSplit6.sh", '#!/bin/bash'. "\n");
for($i=0; $i<200; $i++)
{
    $nb = 1 + ($i*200); 
    $content = "nohup php app/console bench:split:generateData --split6=true --startNb=".$nb . " > /tmp/bordel-".$nb." &";
    file_put_contents("generateSplit6.sh", $content . "\n", FILE_APPEND);
}
