<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__.'/classes/Database.php';

$allHeaders = getallheaders();
$db_connection = new Database();
$conn = $db_connection->dbConnection();

function msg($success, $status, $message, $extra = [])
{
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ], $extra);
}

// GET TASK ID FROM URL PARAMS
$id = isset($_GET['id']) ? $_GET['id'] : '';

$returnData = [];

if ($_SERVER["REQUEST_METHOD"] != "DELETE") {
    $returnData = msg(0, 404, 'Page Not Found!');
} elseif (empty(trim($id))) {
    $fields = ['fields' => ['id']];
    $returnData = msg(0, 422, 'Please provide a task id to delete!', $fields);
} else {
    try {
        $delete_query = "DELETE FROM `tasks` WHERE id = :id";
        $stmt = $conn->prepare($delete_query);
        // DATA BINDING
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $returnData = msg(1, 200, 'Task deleted.');
        } else {
            $returnData = msg(0, 404, 'Task not found.');
        }
    } catch (PDOException $e) {
        $returnData = msg(0, 500, $e->getMessage());
    }
}

echo json_encode($returnData);