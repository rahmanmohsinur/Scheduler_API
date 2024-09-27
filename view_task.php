<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Task</title>
    <style>
        * {
            font-family: Arial, sans-serif;
        }

        .complete {
            text-decoration: line-through;
        }

        label {
            display: inline-block;
            width: 100px;
        }
    </style>
</head>
<body>

    <h1>Task Details</h1>
    
    <div id="taskDetails">
        <form id="taskForm">
            <label for="taskDesc">Task Description:</label>
            <input type="text" id="taskDesc" name="taskDesc" required><br><br>

            <label for="taskCompleted">Completed:</label>
            <input type="checkbox" id="taskCompleted" name="taskCompleted"><br><br>

            <button type="button" id="editTaskButton">Save Changes</button>
        </form>
    </div>

    <button type="button" onclick="window.location.href = 'index.php';">Back to List</button>

    <script>
        const taskID = new URLSearchParams(window.location.search).get('taskID'); // Get taskID from URL

        // Fetch the task data from the API and populate the form
        async function loadTaskDetails() {
            try {
                const response = await fetch(`http://localhost/scheduler_API/data/?taskID=${taskID}`);
                const task = await response.json();
                
                document.getElementById('taskDesc').value = task.taskDesc;
                document.getElementById('taskCompleted').checked = task.completed == 1 ? true : false;
            } catch (error) {
                console.error('Error loading task details:', error);
            }
        }

        // Function to save the changes made to the task
        async function editTask() {
            const taskDesc = document.getElementById('taskDesc').value;
            const completedState = document.getElementById('taskCompleted').checked ? 1 : 0;

            // Send the updated task data to the API
            try {
                const params = new URLSearchParams({
                    "function": "editTask",
                    "taskID": taskID,
                    "taskDesc": taskDesc,
                    "completedState": completedState
                });

                const response = await fetch(`http://localhost/scheduler_API/data/`, {
                    method: 'POST',
                    body: params
                });

                const result = await response.text();
                alert(result);
            } catch (error) {
                console.error('Error updating task:', error);
            }
        }

        document.getElementById('editTaskButton').addEventListener('click', editTask);

        // Load task details on page load
        loadTaskDetails();
    </script>

</body>
</html>

