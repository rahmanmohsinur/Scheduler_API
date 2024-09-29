<?php
// connect to the database
require 'db_connect.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (isset($_GET['taskID'])) {
        // Fetch a single task if taskID is provided
        getSingleTaskAsJSON($conn, intval($_GET['taskID']));
    } else {
        // Fetch all tasks if no taskID is provided
        taskListAsJSON($conn);
    }

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['function'])) {

        switch ($_POST['function']) {
            case 'addTask':
                // Insert new task record to db
                addTask($conn, $_POST['newTaskText']);
                break;
            
            case 'removeTask':
                // Remove task record from db
                removeTask($conn, intval($_POST['taskID']));
                break;

            case 'toggleComplete':
                // Set the completed column truthy or falsy based on current UI setting
                toggleCompleted($conn, intval($_POST['taskID']), intval($_POST['completedState']));
                break;

            case 'editTask':
                // Edit task description and completion state
                editTask($conn, intval($_POST['taskID']), $_POST['taskDesc'], intval($_POST['completedState']));
                break;
        }
    }
}

// Function to return a JSON array of all task objects
function taskListAsJSON($connection) {
    
    $sql = "SELECT taskID, taskDesc, completed FROM tasks";
    $result = $connection->query($sql);

    // Check to see if there are rows/tasks in the returned object
    if ($result->num_rows > 0) {
        // Create an empty array to hold the results of the query
        $results = array();

        // Fetch each row in the result object as an associative array, then push it to the results array
        while($row = $result->fetch_assoc()) {
            array_push($results, $row);
        }
        
        // JSON encode the array of objects and echo it
        $resultsJSON = json_encode($results);
        echo $resultsJSON;    
    } else {
        // Return a dummy object if no tasks are returned.
        echo '[{"taskID" : "0", "taskDesc" : "No tasks in the list.", "completed" : "0"}]';
    }
}

// Function to return a single task as a JSON object
function getSingleTaskAsJSON($connection, $taskID) {
    
    $sql = "SELECT taskID, taskDesc, completed FROM tasks WHERE taskID=?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $taskID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a task was found
    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();
        // JSON encode the task object and echo it
        echo json_encode($task);
    } else {
        // Return an empty object if no task is found
        echo '{"taskID" : "0", "taskDesc" : "Task not found.", "completed" : "0"}';
    }

    $stmt->close();
}

// Function to add a task
function addTask($connection, $newTaskText) {
    
    $sql = "INSERT INTO tasks (taskDesc, completed) VALUES (?, 0)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $newTaskText);

    if($stmt->execute()){
        echo "Task added successfully.";
    } else{
        echo "ERROR: Could not add task: $sql. " . $connection->error;
    }

    $stmt->close();
}

// Function to remove a task
function removeTask($connection, $taskID) {
    
    $sql = "DELETE FROM tasks WHERE taskID=?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $taskID);

    if($stmt->execute()){
        echo "Task removed successfully.";
    } else{
        echo "ERROR: Could not remove task: $sql. " . $connection->error;
    }

    $stmt->close();
}

// Function to toggle the completed state of a task
function toggleCompleted($connection, $taskID, $currentState) {
    
    $newState = abs($currentState - 1);
    $sql = "UPDATE tasks SET completed=? WHERE taskID=?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ii", $newState, $taskID);

    if ($stmt->execute()) {
        echo "Task completion status updated successfully.";
    } else {
        echo "ERROR: Could not execute query: $sql. " . $connection->error;
    }

    $stmt->close();
}

// New function to edit a task's description and completion status
function editTask($connection, $taskID, $taskDesc, $completedState) {
    $sql = "UPDATE tasks SET taskDesc = ?, completed = ? WHERE taskID = ?";
    $stmt = $connection->prepare($sql);
    if ($stmt === false) {
        echo "Error preparing query: " . $connection->error;
        return;
    }

    $stmt->bind_param("sii", $taskDesc, $completedState, $taskID);

    if ($stmt->execute()) {
        echo "Task updated successfully.";
    } else {
        echo "ERROR: Could not update task: " . $connection->error;
    }

    $stmt->close();
}

// close the database connection
$conn->close();
?>
