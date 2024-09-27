<?php
require_once 'Database.php';

$database = new Database();
$conn = $database->getConnection();

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        // Fetch a single chapter if id is provided
        getSingleChapterAsJSON($conn, intval($_GET['id']));
    } else {
        // Fetch all chapters if no id is provided
        getChaptersAsJSON($conn);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['function'])) {
        switch ($_POST['function']) {
            case 'addChapter':
                // Add new chapter record to db
                addChapter($conn, $_POST['title'], $_POST['meta_description'], $_POST['meta_keywords'], $_POST['content'], $_POST['image_url']);
                break;
            
            case 'removeChapter':
                // Remove chapter record from db
                removeChapter($conn, intval($_POST['id']));
                break;

            case 'editChapter':
                // Edit chapter details
                editChapter($conn, intval($_POST['id']), $_POST['title'], $_POST['meta_description'], $_POST['meta_keywords'], $_POST['content'], $_POST['image_url']);
                break;
        }
    }
}

function getChaptersAsJSON($connection) {
    $sql = "SELECT id, chapter_number, title, meta_description, meta_keywords, content, image_url FROM chapters";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $results = array();
        while($row = $result->fetch_assoc()) {
            array_push($results, $row);
        }
        echo json_encode($results);
    } else {
        echo '[{"id" : "0", "chapter_number" : "0", "title" : "No chapters found.", "meta_description" : "", "meta_keywords" : "", "content" : "", "image_url" : ""}]';
    }
}

function getSingleChapterAsJSON($connection, $id) {
    $sql = "SELECT id, chapter_number, title, meta_description, meta_keywords, content, image_url FROM chapters WHERE id=?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $chapter = $result->fetch_assoc();
        echo json_encode($chapter);
    } else {
        echo '{"id" : "0", "chapter_number" : "0", "title" : "Chapter not found.", "meta_description" : "", "meta_keywords" : "", "content" : "", "image_url" : ""}';
    }

    $stmt->close();
}

function addChapter($connection, $title, $meta_description, $meta_keywords, $content, $image_url) {
    $sql = "INSERT INTO chapters (title, meta_description, meta_keywords, content, image_url) VALUES (?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sssss", $title, $meta_description, $meta_keywords, $content, $image_url);

    if ($stmt->execute()) {
        echo "Chapter added successfully.";
    } else {
        echo "ERROR: Could not add chapter: " . $connection->error;
    }

    $stmt->close();
}

function removeChapter($connection, $id) {
    $sql = "DELETE FROM chapters WHERE id=?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Chapter removed successfully.";
    } else {
        echo "ERROR: Could not remove chapter: " . $connection->error;
    }

    $stmt->close();
}

function editChapter($connection, $id, $title, $meta_description, $meta_keywords, $content, $image_url) {
    $sql = "UPDATE chapters SET title = ?, meta_description = ?, meta_keywords = ?, content = ?, image_url = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sssssi", $title, $meta_description, $meta_keywords, $content, $image_url, $id);

    if ($stmt->execute()) {
        echo "Chapter updated successfully.";
    } else {
        echo "ERROR: Could not update chapter: " . $connection->error;
    }

    $stmt->close();
}

$conn->close();
?>
