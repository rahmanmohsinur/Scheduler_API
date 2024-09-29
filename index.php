<?php
require_once 'include/config.php'; // Load configuration

// Get the requested route
$route = isset($_GET['route']) ? $_GET['route'] : '';

// Get taskID if provided
$taskID = isset($_GET['taskID']) ? intval($_GET['taskID']) : null;

// Basic Routing Logic
switch ($route) {
    case '':
        require 'shared/header.php';
        require 'templates/home.php';
        require 'shared/footer.php';
        break;
    

        case 'view_task':
            // If taskID is provided, load view_task.php
            if ($taskID !== null) {
                require 'shared/header.php';
                require 'templates/view-task.php'; // This file will handle task display based on taskID
                require 'shared/footer.php';
            } else {
                echo "<h1>Error: No Task ID provided.</h1>";
            }
            break;


    case 'view-':
        require 'shared/header.php';
        require 'templates/view-task.php'; // This file will handle task display based on taskID
        require 'shared/footer.php';
        break;

    case 'contact':
        require 'templates/contact.php';
        break;

    case 'booking':
        require 'templates/booking.php';
        break; 

    case 'todo':
        require 'templates/todo.php';
        break;

    default:
        // Load a 404 page if the route doesn't match any case
        require 'templates/404.php';
        break;
}
?>
