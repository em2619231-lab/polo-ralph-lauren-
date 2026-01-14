<?php
// API para MANEJAR PRODUCTOS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../conexion.php';

$database = new Database();
$db = $database->getConnection();

// Obtener método (GET, POST, DELETE)
$method = $_SERVER['REQUEST_METHOD'];

if($method == 'GET') {
    // OBTENER PRODUCTOS
    $query = "SELECT * FROM productos WHERE activo = 1 ORDER BY fecha_creacion DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $productos = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $productos[] = $row;
    }
    
    echo json_encode($productos);
    
} elseif($method == 'POST') {
    // AGREGAR PRODUCTO (admin)
    $data = json_decode(file_get_contents("php://input"));
    
    if(isset($data->nombre) && isset($data->precio)) {
        $query = "INSERT INTO productos (nombre, precio, stock, imagen_url) 
                 VALUES (:nombre, :precio, :stock, :imagen_url)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nombre', $data->nombre);
        $stmt->bindParam(':precio', $data->precio);
        $stmt->bindParam(':stock', $data->stock ?? 10);
        $stmt->bindParam(':imagen_url', $data->imagen_url);
        
        if($stmt->execute()) {
            echo json_encode(["message" => "Producto agregado"]);
        } else {
            echo json_encode(["message" => "Error"]);
        }
    }
    
} elseif($method == 'DELETE') {
    // ELIMINAR PRODUCTO (admin)
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $query = "UPDATE productos SET activo = 0 WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if($stmt->execute()) {
            echo json_encode(["message" => "Producto eliminado"]);
        }
    }
}
?>