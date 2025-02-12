<?php
require_once __DIR__ . '/validation.php';
require_once __DIR__ . '/sanitization.php';
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


function filter(array $data, array $fields, array $messages=[]) : array
{
    $sanitization_rules = [];
    $validation_rules  = [];

    foreach ($fields as $field=>$rules) {
        if (strpos($rules, '|')) {
            [$sanitization_rules[$field], $validation_rules[$field] ] =  explode('|', $rules, 2);
        } else {
            $sanitization_rules[$field] = $rules;
        }
    }

    $inputs = sanitize($data, $sanitization_rules);
    $errors = validate($inputs, $validation_rules, $messages);

    return [$inputs, $errors];
}