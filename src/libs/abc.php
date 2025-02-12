<?php

require __DIR__ . '/filter.php';

$data = [
    'name' => 'cscc',
    'email' => 'john$email.com',
];

$fields = [
    'name' => 'string | required | max: 255 | min: 4',
    'email' => 'email | required | email'
];

[$inputs, $errors] = filter($data, $fields);

echo "<pre>";
print_r($inputs);
print_r($errors);
echo "<pre>";