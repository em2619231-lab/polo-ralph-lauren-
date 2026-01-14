<?php
// API para CREAR ÓRDENES/COMPRAS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../conexion.php';

$database = new Database();
$db = $database->getConnection();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    
    // 1. Generar folio único
    $folio = 'PL-' . date('Ymd-His') . '-' . rand(100, 999);
    
    // 2. Calcular totales
    $subtotal = 0;
    foreach($data->productos as $producto) {
        $subtotal += $producto->precio * $producto->cantidad;
    }
    $iva = $subtotal * 0.16;
    $total = $subtotal + $iva;
    
    // 3. Guardar orden principal
    $query = "INSERT INTO ordenes (folio, nombre_cliente, email_cliente, telefono_cliente, 
              direccion_envio, metodo_pago, subtotal, iva, total) 
              VALUES (:folio, :nombre, :email, :telefono, :direccion, :metodo, :subtotal, :iva, :total)";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':folio', $folio);
    $stmt->bindParam(':nombre', $data->nombre_cliente);
    $stmt->bindParam(':email', $data->email_cliente);
    $stmt->bindParam(':telefono', $data->telefono_cliente);
    $stmt->bindParam(':direccion', $data->direccion_envio);
    $stmt->bindParam(':metodo', $data->metodo_pago);
    $stmt->bindParam(':subtotal', $subtotal);
    $stmt->bindParam(':iva', $iva);
    $stmt->bindParam(':total', $total);
    
    if($stmt->execute()) {
        $orden_id = $db->lastInsertId();
        
        // 4. Guardar detalles de la orden
        foreach($data->productos as $producto) {
            $query2 = "INSERT INTO detalles_orden (orden_id, producto_id, nombre_producto, precio_unitario, cantidad, subtotal) 
                      VALUES (:orden_id, :producto_id, :nombre, :precio, :cantidad, :subtotal)";
            
            $stmt2 = $db->prepare($query2);
            $subtotal_item = $producto->precio * $producto->cantidad;
            
            $stmt2->bindParam(':orden_id', $orden_id);
            $stmt2->bindParam(':producto_id', $producto->id);
            $stmt2->bindParam(':nombre', $producto->nombre);
            $stmt2->bindParam(':precio', $producto->precio);
            $stmt2->bindParam(':cantidad', $producto->cantidad);
            $stmt2->bindParam(':subtotal', $subtotal_item);
            $stmt2->execute();
        }
        
        echo json_encode([
            "success" => true,
            "folio" => $folio,
            "orden_id" => $orden_id,
            "total" => $total,
            "message" => "Orden guardada exitosamente"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Error al guardar orden"
        ]);
    }
}
?>