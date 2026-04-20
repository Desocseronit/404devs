<?php namespace core;

require_once('Collection.php');
require_once('CollectionElement.php');
require_once('Database.php');
require_once('Request.php');
require_once('Response.php');
require_once('Record.php');
require_once('User.php');
require_once('Image.php');

echo '<pre>';
Database::instance('host=26.152.118.24 port=5432 dbname=404devs password=1 user=postgres');
$a = new Request();
$b = new Image($a->getinfo()->files->getValue()->image->getValue());
print_r($b->getSize());

class Application{

}

