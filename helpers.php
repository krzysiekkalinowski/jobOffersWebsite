<?php

/** Get a base path
 * 
 * @param string $path
 * @return string
 */
function basePath($path = '')
{
    return __DIR__ . '/' . $path;
}

/**
 * Load a view
 * 
 * @param string $name
 * @return void
 */
function loadView($name, $data = [])
{
    $viewPath = basePath("App/views/{$name}.view.php");
    if (file_exists($viewPath)) {
        extract($data);
        require $viewPath;
    } else {
        echo "View {$name} not found";
    }
}
/**
 * Load a partial
 * 
 * @param string $name
 * @return void
 */
function loadPartial($name, $data = [])
{
    $partialPath = basePath("App/views/partials/{$name}.php");
    if (file_exists($partialPath)) {
        extract($data);
        require $partialPath;
    } else {
        echo "Partial {$name} view not found";
    }
}

/**
 * Inspect a value
 * 
 * @param mixed $value
 * @return void
 */
function inspect($value)
{
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
}

/**
 * Inspect a value and die
 * 
 * @param mixed $value
 * @return void
 */
function inspectAndDie($value)
{
    echo '<pre>';
    die(var_dump($value));
    echo '</pre>';
}

/**
 * Format salary
 * 
 * @param string salart
 * @return string $formattedSalary
 */
function formatSalary($salary)
{
    return '$' . number_format(floatval($salary));
}


/**
 * Sanmitize data from form
 * 
 * @param string $dirty
 * @return string    
 */
function sanitize($dirty)
{
    return filter_var(trim($dirty), FILTER_SANITIZE_SPECIAL_CHARS);
}

/**
 * Redirect to a new page
 * 
 * @param string $url
 * @requires viod
 */
function redirect($url)
{
    header("Location: $url");
    exit;
};
