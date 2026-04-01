<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json"); // Добавляем JSON ответ

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'config.php';

    $name = isset($_POST['name'])?trim($_POST['name']) : '';
    $email = isset($_POST['email'])?trim($_POST['email']) : '';
    $password = isset($_POST['article'])?trim($_POST['article']) : '';
    
    $result = $conn->query("SHOW DATABASES LIKE 'colos'");
    if ($result && $result->num_rows >0){
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
    }
    if ($stmt->execute()){
        $response = [
            'status' => 'success',
            'name' => $name,
            'email' => $email
        ];
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    else {
        throw new Exception("Ошибка выполнения запроса: " . $stmt->error);
    }
}

    else {
        echo json_encode([
            'success' => false,
            'message' => 'Метод не поддерживается'
        ]);
    }


    // Здесь должна быть логика сохранения в БД
    // Пример: $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Успешный ответ

?>