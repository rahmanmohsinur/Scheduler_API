<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To Do</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        button {
            border: none;
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

    <h1>To Do:</h1>

    <ul id="toDoList"></ul>

    <h2>Add new:</h2>
    <ul>
        <li>
            <input type="text" id="newTaskText" name="newTaskText">
            <button type="button" id="addTask">&#10133;</button>
        </li>
    </ul>

<script>

    /*
    * TO DO LIST FUNCTIONALITY 
    */
    
    const list = document.getElementById("toDoList");
    
    // Function to add tasks using a JSON data source
    function addTasks(taskList) {

        // Clear the current list
        list.innerText = "";

        taskList.forEach((task)=> {
            // Assign newly created page elements to vars
            let taskItem = document.createElement("li");
            let completeButton = document.createElement("button");
            let deleteButton = document.createElement("button");
            let viewButton = document.createElement("button"); // Add View Button
            let completeSpan = document.createElement("span");
            let taskTextSpan = document.createElement("span");
            let deleteSpan = document.createElement("span");
            let viewSpan = document.createElement("span"); // Add View Span
            
            taskItem.id = "taskID" + task.taskID;
            completeSpan.classList.add("completeSpan");
            taskTextSpan.classList.add("taskTextSpan");
            deleteSpan.classList.add("deleteSpan");
            viewSpan.classList.add("viewSpan"); // Add View Span Class

            taskItem.appendChild(completeSpan);
            taskItem.appendChild(taskTextSpan);
            taskItem.appendChild(deleteSpan);
            taskItem.appendChild(viewSpan); // Append viewSpan to the taskItem

            // Setting the values of the new elements
            if (task.completed == true) {
                taskTextSpan.classList.add("complete");
                completeButton.innerHTML = "&#9989;";
            } else {
                completeButton.innerHTML = "&#128998;";
            }
            taskTextSpan.innerText = task.taskDesc;
            deleteButton.innerHTML = "&#10060;";
            viewButton.innerText = "View"; // Set viewButton text

            // Appending the elements to the DOM
            completeSpan.appendChild(completeButton);
            deleteSpan.appendChild(deleteButton);
            viewSpan.appendChild(viewButton); // Append the viewButton to viewSpan
                       
            completeButton.addEventListener("click", ()=>{toggleComplete(task)});
            deleteButton.addEventListener("click", ()=>{removeTask(task)});
            viewButton.addEventListener("click", ()=>{viewTask(task)}); // Add event listener for viewButton
            
            list.appendChild(taskItem);
            
        });
    }

    const addButton = document.getElementById("addTask");
    const input = document.getElementById("newTaskText");
    addButton.addEventListener("click", ()=>{addTask()});
    input.addEventListener("keypress", (k)=>{if (k.key==="Enter") {addButton.click()}});

    async function getTasks() {
        try {
            const taskData = await fetch("http://localhost/scheduler_API/data/");
            const tasks = await taskData.json();
            addTasks(tasks);
        }
        catch (error) {
            console.log("Error retrieving data: " + error);
        }
    }

    async function toggleComplete(task) {
        try {
            const params = new URLSearchParams({"function" : "toggleComplete", "taskID" : task.taskID, "completedState" : task.completed});
            const taskComplete = await fetch("http://localhost/scheduler_API/data/", {method:"POST", body:params});
            getTasks();
        }
        catch (error) {
            console.log("Error setting completion state: " + error);
        }
    }

    async function removeTask(task) {
        try {
            const params = new URLSearchParams({"function" : "removeTask", "taskID" : task.taskID});
            const taskData = await fetch("http://localhost/scheduler_API/data/", {method:"POST", body:params});
            getTasks();
        }
        catch (error) {
            console.log("Error removing task: " + error);
        }
    }

    async function addTask() {
        try {
            const params = new URLSearchParams({"function" : "addTask", "newTaskText" : input.value});
            const taskData = await fetch("http://localhost/scheduler_API/data/", {method:"POST", body:params});
            getTasks();
        }
        catch (error) {
            console.log("Error adding task: " + error);
        }
    }

    // Function to redirect to a new page with taskID
    function viewTask(task) {
        window.location.href = `view_task.php?taskID=${task.taskID}`; // Redirect to new page with taskID in URL
    }

    getTasks();

</script>

</body>
</html>
