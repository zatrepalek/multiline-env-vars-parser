<?php
declare(strict_types=1);

$last_variable = null;
$variables = [];

$settings_prefix = 'APPSETTING_';
$settings_prefix_len = strlen($settings_prefix);

// parse input from stdin
while(!feof(STDIN)){
    $line = fgets(STDIN);
    if (is_string($line)) {
        $line = trim($line); // remove whitespaces

        $pos = strpos($line, '=');
        $variable_content = '';
        $variable_name = '';

        if ($pos > 0) {
            $variable_content = substr($line, $pos + 1);
        }

        if (strlen($variable_content) > 0 && !preg_match('|^\\s+$|', $variable_content)) { // set content of variable (if content is not empty)
            $variable_name = substr($line, 0, $pos);
            if (strpos($variable_name, $settings_prefix) === 0) {
                $variables[$variable_name] = $variable_content;
                $variables[substr($variable_name, $settings_prefix_len)] = $variable_content;
                $last_variable = $variable_name;
            } else {
                $last_variable = null;
            }
        } elseif ($last_variable !== null) { // continue for multiline variable
            $variables[$last_variable] .= "\n" . $line;
            $variables[substr($last_variable, $settings_prefix_len)] .= "\n" . $line;
        }
    }
}

// output sanitized variables
foreach ($variables as $var => $value) {
    echo 'export ' . $var . '="' . $value . '"' . "\n";
}
