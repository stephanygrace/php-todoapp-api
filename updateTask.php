<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
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

// DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];


if ($_SERVER["REQUEST_METHOD"] != "POST") :

    $returnData = msg(0, 404, 'Page Not Found!');

elseif (
    !isset($data->task)
    || !isset($data->id)
    || empty(trim($data->task))
    || empty(trim($data->id))
    
) :

    $fields = ['fields' => ['id','task']];
    $returnData = msg(0, 422, 'Please Fill in all Required Fields!', $fields);

// IF THERE ARE NO EMPTY FIELDS THEN-
else :

    $task = trim($data->task);
    $id = trim($data->id);
 
        try {
            
                $update_query = "UPDATE `tasks` SET task = :task WHERE id = :id";

                $stmt = $conn->prepare($update_query);

                // DATA BINDING
                $stmt->bindValue(':task', $task, PDO::PARAM_STR);
                $stmt->bindValue(':id', $id, PDO::PARAM_STR);

                $stmt->execute();

                $returnData = msg(1, 201, 'Task updated.');

        } catch (PDOException $e) {
            $returnData = msg(0, 500, $e->getMessage());
        }
    endif;


echo json_encode($returnData);