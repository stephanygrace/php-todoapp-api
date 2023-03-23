<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__.'/classes/Database.php';

$allHeaders = getallheaders();
$db_connection = new Database();
$conn = $db_connection->dbConnection();

try {
     
    $get_query = "SELECT * FROM `tasks`";
    $stmt = $conn->prepare($get_query);
    $stmt->execute();

    $tasks_arr = array();
   
    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        $id = $row['id'];        
        $task = $row['task'];        
     
           

        $tasks_arr[] = array(
            "id" => $id,
            "task" => $task
           
        );
    }
    $json_string = json_encode($tasks_arr);
    echo $json_string;

} catch (PDOException $e) {
    $returnData = msg(0, 500, $e->getMessage());
}

