<?php
require __DIR__ . '/classes/JwtHandler.php';

class Auth extends JwtHandler
{
    protected $db;
    protected $headers;
    protected $token;

    public function __construct($db, $headers)
    {
        parent::__construct();
        $this->db = $db;
        $this->headers = $headers;
    }

    public function isValid()
    {

        if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {

            $data = $this->jwtDecodeData($matches[1]);

            if (
                isset($data['data']->user_id) &&
                $user = $this->fetchUser($data['data']->user_id)
            ) :
                return [
                    "success" => 1,
                    "user" => $user
                ];
            else :
                return [
                    "success" => 0,
                    "message" => $data['message'],
                ];
            endif;
        } else {
            return [
                "success" => 0,
                "message" => "Token not found in request"
            ];
        }
    }

    protected function fetchTasks($user_id)
    {
       
       
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
       
       
       
       
    }
}