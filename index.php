<?php

use Faker\Factory;

require_once __DIR__ . '/vendor/autoload.php';

$faker = Factory::create('nl_NL');

echo $faker->name . '<br>' . $faker->address;
