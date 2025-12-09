<?php
$bytesLibres = disk_free_space("C:");
$bytesTotal = disk_total_space("C:");
$gigasLibres = round($bytesLibres / pow(1024, 3), 2);
$gigasTotal = round($bytesTotal / pow(1024, 3), 2);
echo "Tienes $gigasLibres Gigas libres de $gigasTotal";
?>