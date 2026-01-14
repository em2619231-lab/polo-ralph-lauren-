<?php
// API para MANEJAR CARRITO (guardar en sesión PHP)
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../conexion.php';

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

if($method == 'POST') {
    // AGREGAR AL CARRITO
    $data = json_decode(file_get_contents("php://input"));
    
    if(!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }
    
    // Buscar si ya existe
    $encontrado = false;
    foreach($_SESSION['carrito'] as &$item) {
        if($item['producto_id'] == $data->producto_id) {
            $item['cantidad'] += $data->cantidad ?? 1;
            $encontrado = true;
            break;
        }
    }
    
    if(!$encontrado) {
        // Obtener info del producto
        $query = "SELECT nombre, precio FROM productos WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $data->producto_id);
        $stmt->execute();
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $_SESSION['carrito'][] = [
            'producto_id' => $data->producto_id,
            'nombre' => $producto['nombre'],
            'precio' => $producto['precio'],
            'cantidad' => $data->cantidad ?? 1
        ];
    }
    
    echo json_encode([
        "success" => true,
        "carrito" => $_SESSION['carrito'],
        "total_items" => count($_SESSION['carrito'])
    ]);
    
} elseif($method == 'GET') {
    // OBTENER CARRITO
    if(!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }
    
    echo json_encode($_SESSION['carrito']);
    
} elseif($method == 'DELETE') {
    // VACIAR CARRITO
    $_SESSION['carrito'] = [];
    echo json_encode(["message" => "Carrito vaciado"]);
}
?>