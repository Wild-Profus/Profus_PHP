# Profus

Images.zip à extraire dans /images/ :
http://149.202.50.223/downloads/images.zip

Base mysql à charger dans la votre serveur sql :
http://149.202.50.223/downloads/profus.sql

Fichier à créer pour paramétrer le serveur dans php (avec vos paramètres entre les ""):

\modules\shared\dbID.php

<?php
define("HOST", "");
define("DB_NAME", "");
define("USER", "");
define("PWD", ""); 
