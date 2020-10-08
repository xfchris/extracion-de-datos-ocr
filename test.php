<?php
include_once ('extraerDatos/testDev/GstPruebas.php');

//Extrae datos de una escritura, a tener en cuenta que desde aquí, los actos estan en un archivo actos.json
//Al usar el entorno de pruebas de gevir,
GstPruebas::extraerDatos('txt');
