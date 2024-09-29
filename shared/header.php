<?php

// Ensure these variables are set by the template
$page_title = isset($page_title) ? htmlspecialchars($page_title) . ' | ' . SITE_NAME : 'Default Title | ' . SITE_NAME;
//$page_title = isset($page_title) ? htmlspecialchars($page_title) : 'Default Title';
$page_description = isset($page_description) ? htmlspecialchars($page_description) : 'Default description.';
$page_keywords = isset($page_keywords) ? htmlspecialchars($page_keywords) : 'default, keywords';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <meta name="keywords" content="<?php echo $page_keywords; ?>">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        *, *:before, *:after {
            box-sizing: inherit;
        }

        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        input, textarea, button {
            width: auto;
            color: #333;
            border: 1px solid;
            background-color: rgba(255, 255, 255, 0);
        }

        ul {
            list-style:none;
        }

        .complete {
            text-decoration: line-through;
        }

        .viewSpan {
            margin-left: 10px;
        }
    </style>
</head>

<body>

<?php
// Include Navigation Bar
require_once 'navbar.php';
?>
