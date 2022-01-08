<?php
    include("../class/dump.php");
    include ("../class/these.php");
    $dump = new dump();

    $path = "../files/2021-09-15-theses_0.csv";
    $id = 0;
    $file = fopen($path,'r')or die("The files was not found");
    fgets($file);
    while (!feof($file)){
        $line = fgets($file);
        $sep = explode(";",$line);
        $these = new these($sep[0],$sep[1],$sep[2],$sep[3],$sep[5],$sep[6],$sep[7],$sep[8],$dump->changeDate($sep[10]),$dump->changeDate($sep[11]),$sep[12],$sep[13],$sep[14],$dump->changeDate($sep[15]),$dump->changeDate($sep[16]));
        $these->affichage();
        $these->save();
    }

    fclose($file);



