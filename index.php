<?php

use Faker\Factory;

require_once __DIR__ . '/vendor/autoload.php';

$faker = Factory::create();

echo $faker->name;
// doei
