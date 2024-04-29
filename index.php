<?php
$host="localhost";
$usuario="root";
$password="";
$basededatos="videojuegos";

$conexion=new mysqli($host,$usuario,$password,$basededatos);

if($conexion->connect_error){
    echo "Conexion no establecida";

}

header("Content-Type: application/json");

$metodo= $_SERVER['REQUEST_METHOD'];

$path=isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'/';
$buscarid=explode('/',$path);
$id= ($path!=='/')?end($buscarid):NULL; 

switch($metodo){
    case 'GET':
        consulta($conexion,$id);
        break;
    case 'POST':
        insertar($conexion);
        break;
    case 'DELETE':
        borrar($conexion,$id);
        break;
    case 'PUT':
        actualizar($conexion,$id);
        break;            
    default:
        break;        
}

function consulta($conexion,$id){
    $sql=($id==null)?"SELECT*FROM torneos":"SELECT*FROM torneos WHERE id_torneo=$id";
    $resultado=$conexion->query($sql);
    
    if($resultado){
        $dato=array();
        while($fila=$resultado->fetch_assoc()){
            $datos[]=$fila;
        }
        echo json_encode($datos);


    }
}

function insertar($conexion){
    $dato=json_decode(file_get_contents('php://input'),true);
    $nombre=$dato['nombre'];
    $id_usuario=$dato['id_usuario'];
    $id_categoria=$dato['id_categoria'];
    $id_juego=$dato['id_juego'];
    $fechaInicio=$dato['fechaInicio'];
    $fechaFinal=$dato['fechaFinal'];
    $linkAcceso=$dato['linkAcceso'];

    $sql= "INSERT INTO torneos(nombre,id_usuario,id_categoria,id_juego,fechaInicio,fechaFinal,linkAcceso) VALUES ('$nombre','$id_usuario','$id_categoria','$id_juego','$fechaInicio','$fechaFinal','$linkAcceso')";
    $resultado=$conexion->query($sql);

    if($resultado){
        $dato['id_torneo']=$conexion->insert_id;
        echo json_encode(array('mensaje'=>'Exitoso'));
    }else{
        echo json_encode(array('error'=>'Erro al crear usuario'));
    }
}

function borrar($conexion,$id){
    $sql="DELETE FROM torneos WHERE id_torneo=$id";
    $resultado=$conexion->query($sql);
    if($resultado){
        echo json_encode(array('mensaje'=>' Usuario eliminado'));
    }else{
        echo json_encode(array('error'=> 'Error al eliminar'));
    }
}

function actualizar($conexion,$id){
    $dato=json_decode(file_get_contents('php://input'),true);
    $nombre=$dato['nombre'];
    $id_usuario=$dato['id_usuario'];
    $id_categoria=$dato['id_categoria'];
    $id_juego=$dato['id_juego'];
    $fechaInicio=$dato['fechaInicio'];
    $fechaFinal=$dato['fechaFinal'];
    $linkAcceso=$dato['linkAcceso'];

    $sql= "UPDATE torneos SET nombre='$nombre',id_usuario='$id_usuario',id_categoria='$id_categoria',id_juego='$id_juego',fechaInicio='$fechaInicio',fechaFinal='$fechaFinal',linkAcceso='$linkAcceso' WHERE id_torneo=$id";
    $resultado=$conexion->query($sql);

    if($resultado){
        echo json_encode(array('mensaje'=>' Usuario actualizado'));
    }else{
        echo json_encode(array('error'=> 'Error al actualizar'));
    }
}

?>