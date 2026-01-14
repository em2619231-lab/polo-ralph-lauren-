<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../conexion.php';

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        // Obtener productos
        $destacados = isset($_GET['destacados']) ? true : false;
        
        $query = "SELECT * FROM productos WHERE activo = 1";
        if ($destacados) {
            $query .= " AND destacado = 1";
        }
        $query .= " ORDER BY fecha_creacion DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        $productos = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $productos[] = $row;
        }
        
        echo json_encode($productos);
        break;
        
    case 'POST':
        // Agregar producto (solo admin)
        $data = json_decode(file_get_contents("php://input"));
        
        if(isset($data->nombre) && isset($data->precio)) {
            $query = "INSERT INTO productos (nombre, descripcion, precio, stock, categoria, imagen_url) 
                     VALUES (:nombre, :descripcion, :precio, :stock, :categoria, :imagen_url)";
            
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':nombre', $data->nombre);
            $stmt->bindParam(':descripcion', $data->descripcion);
            $stmt->bindParam(':precio', $data->precio);
            $stmt->bindParam(':stock', $data->stock);
            $stmt->bindParam(':categoria', $data->categoria);
            $stmt->bindParam(':imagen_url', $data->imagen_url);
            
            if($stmt->execute()) {
                echo json_encode(array("message" => "Producto agregado"));
            } else {
                echo json_encode(array("message" => "Error al agregar producto"));
            }
        }
        break;
}
?>