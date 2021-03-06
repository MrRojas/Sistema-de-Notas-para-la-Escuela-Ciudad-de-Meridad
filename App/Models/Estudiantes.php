<?php
namespace App\Models;
defined("PATH_RAIZ") OR die("Access denied");

use \Core\Database;
use \Core\Model; 
use \App\Interfaces\Crud ;

class Estudiantes extends Model  
{

    public function __construct(){

        self::set_table("estudiantes");
    }

    public function insertar($form = array()){


        $sql = "INSERT into estudiantes ( nombre,  fecha_n , lugar_id , sexo) values ";
        $sql .= " (:nombre , :fecha , :lugar , :sexo) "; 

    	$this->set_sql($sql);

        return $this->execute_query($form); 
 
    }

    public function consultarPorNombre($nombre){


        $sql = "SELECT * FROM estudiantes where nombre = :nombre";
       
    	$this->set_sql($sql);

        return $this->execute_query(array("nombre" => $nombre)); 
 
    }

    public function add_EstudianteCurso($form){
    	
    	$sql = "INSERT INTO cursos_estudiantes (curso_id , estudiante_id , nota ) values ( :curso , :estudiante , :nota)";
       	$this->set_sql($sql);

        return $this->execute_query($form); 
    }

    public function delete($id){

        $sql = "DELETE FROM estudiantes where estudiante_id = :id "; 
        $this->set_sql($sql);
        return $this->execute_query( array( 'id' => $id ) ); 
    }

    public function deleteEstudianteCurso($id){

        $sql = "DELETE FROM cursos_estudiantes where estudiante_id = :id "; 
        $this->set_sql($sql);
        return $this->execute_query( array( 'id' => $id ) ); 
    }


    public function consultar(){

        $sql = "SELECT e.estudiante_id as id , e.nombre as nombre , c.nota as nota , g.descripcion as grado, ";
        $sql .="e.sexo as sexo, l.nota as literal, l.descripcion as texto, ";
        $sql .=" (TO_DAYS(now()) - TO_DAYS(e.fecha_n) ) / 365  as EDAD2 ,  ";
        $sql .="e.fecha_n as edad, ll.descripcion as lugar, cc.seccion as  seccion, ";
        $sql .='m.nombre as maestro, CONCAT( p.inicio , "-" , p.cierre ) as periodo, l.valor as interpretacion  ';
        $sql .="from estudiantes as e ";
        $sql .="INNER JOIN cursos_estudiantes as c ON c.estudiante_id = e.estudiante_id ";
        $sql .="INNER JOIN cursos as cc ON cc.curso_id = c.curso_id ";
        $sql .="INNER JOIN grados as g ON g.grado_id = cc.grado_id ";
        $sql .="inner JOIN literales as l ON  e.sexo = l.sexo and c.nota between l.rango_d and l.rango_h ";
        $sql .="inner JOIN lugares as ll ON ll.lugar_id = e.lugar_id ";
        $sql .="inner JOIN maestros as m ON cc.maestro_id = m.maestro_id ";
        $sql .="inner JOIN periodos as p ON p.periodo_id = cc.periodo_id "; 
        $sql .="WHERE m.maestro_id = :id  AND l.grado_id = g.grado_id "; 

        $sql .= "order by e.sexo DESC , e.nombre ASC   "; 
    
        $this->set_sql($sql);

        return $this->execute_query(array("id" => $_SESSION['id_maestro'] )); 

    }


    public function consultarByID($id){

        $sql = "SELECT e.nombre as nombre , c.nota as nota , g.descripcion as grado, ";
        $sql .="e.sexo as sexo, l.valor as valor  , l.nota as literal, l.descripcion as texto, ";
        $sql .=" (TO_DAYS(now()) - TO_DAYS(e.fecha_n) ) / 365  as EDAD2 ,  ";
        $sql .="e.fecha_n as edad, ll.descripcion as lugar, cc.seccion as  seccion, ";
        $sql .='m.nombre as maestro, CONCAT( p.inicio , "-" , p.cierre ) as periodo, l.valor as interpretacion ';
        $sql .="from estudiantes as e ";
        $sql .="INNER JOIN cursos_estudiantes as c ON c.estudiante_id = e.estudiante_id ";
        $sql .="INNER JOIN cursos as cc ON cc.curso_id = c.curso_id ";
        $sql .="INNER JOIN grados as g ON g.grado_id = cc.grado_id ";
        $sql .="inner JOIN literales as l ON  e.sexo = l.sexo and c.nota between l.rango_d and l.rango_h ";
        $sql .="inner JOIN lugares as ll ON ll.lugar_id = e.lugar_id ";
        $sql .="inner JOIN maestros as m ON cc.maestro_id = m.maestro_id ";
        $sql .="inner JOIN periodos as p ON p.periodo_id = cc.periodo_id "; 
        $sql .="WHERE m.maestro_id = :id and e.estudiante_id = :estudiante "; 
 
        $this->set_sql($sql);

        return $this->execute_query(array("id" => $_SESSION['id_maestro'] , "estudiante" => $id  )); 

    }



    
}

