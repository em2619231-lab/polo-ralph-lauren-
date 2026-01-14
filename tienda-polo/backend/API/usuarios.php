<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();

require_once '../conexion.php';
$database = new Database();
$db = $database->getConnection();

$action = $_GET['action'] ?? '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    
    if(isset($data->action)) {
        if($data->action == 'login') {
            // LOGIN SIMPLE
            if($data->email == 'admin@ralphlauren.com' && $data->password == '1234') {
                $_SESSION['usuario'] = [
                    'id' => 1,
                    'nombre' => 'Administrador',
                    'email' => 'admin@ralphlauren.com',
                    'tipo' => 'admin'
                ];
                echo json_encode([
                    'success' => true,
                    'user' => $_SESSION['usuario']
                ]);
            } else if($data->email == 'cliente@ejemplo.com' && $data->password == '1234') {
                $_SESSION['usuario'] = [
                    'id' => 2,
                    'nombre' => 'Juan Pérez',
                    'email' => 'cliente@ejemplo.com',
                    'tipo' => 'cliente'
                ];
                echo json_encode([
                    'success' => true,
                    'user' => $_SESSION['usuario']
                ]);
            } else {
                // Registro automático para nuevos usuarios
                $_SESSION['usuario'] = [
                    'id' => rand(100, 999),
                    'nombre' => $data->email.split('@')[0],
                    'email' => $data->email,
                    'tipo' => 'cliente'
                ];
                echo json_encode([
                    'success' => true,
                    'user' => $_SESSION['usuario']
                ]);
            }
        }
        else if($data->action == 'register') {
            // REGISTRO SIMPLE
            $_SESSION['usuario'] = [
                'id' => rand(100, 999),
                'nombre' => $data->nombre,
                'email' => $data->email,
                'tipo' => 'cliente'
            ];
            echo json_encode([
                'success' => true,
                'user' => $_SESSION['usuario']
            ]);
        }
        else if($data->action == 'logout') {
            session_destroy();
            echo json_encode(['success' => true]);
        }
    }
} 
else if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if($action == 'check') {
        if(isset($_SESSION['usuario'])) {
            echo json_encode([
                'success' => true,
                'user' => $_SESSION['usuario']
            ]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
}
?>