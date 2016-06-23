<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of estadisticaModel
 *
 * @author MatiasBarrera
 */
class estadisticaModel extends Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }

    public function getVentasGenerales($fechai, $fechaf) {

        $f1 = date_format(date_create($fechai), 'Y-m-d');
        $f2 = date_format(date_create($fechaf), 'Y-m-d');

        $query = $this->_db->prepare("SELECT  e.nombre as Especie
                                      FROM movimientos m
                                      INNER JOIN especie e on (m.idespecie=e.idespecie)
                                      INNER JOIN color c on (m.idcolor=c.idcolor)
                                      AND (m.fecha between ? and ?)
                                      AND m.tipo='venta'
                                      GROUP BY m.idespecie");


        $query->execute(array($f1, $f2));

        $query2 = $this->_db->prepare("SELECT SUM( m.cantidad ) as Stock 
                                      FROM movimientos m
                                      INNER JOIN especie e on (m.idespecie=e.idespecie)
                                      INNER JOIN color c on (m.idcolor=c.idcolor)
                                      AND (m.fecha between ? and ?)
                                      AND m.tipo='venta'
                                      GROUP BY m.idespecie");


        $query2->execute(array($f1, $f2));


        $rows = array();
        $rows['name'] = 'ESPECIE';
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $rows['data'][] = strtoupper($row['Especie']);
            // $rows['data'][]=$row['Stock'];
        }

        $rows1 = array();
        $rows1['name'] = "C. VENDIDA";
        while ($row1 = $query2->fetch(PDO::FETCH_ASSOC)) {
            $rows1['data'][] = abs((int) $row1['Stock']);
        }


        $result = array();
        array_push($result, $rows);
        array_push($result, $rows1);
        // return $query->fetchAll(PDO::FETCH_ASSOC);


        return $result;
    }

    public function getVentas() {


        $query = $this->_db->prepare("SELECT  e.nombre as Especie
                                      FROM movimientos m
                                      INNER JOIN especie e on (m.idespecie=e.idespecie)
                                      INNER JOIN color c on (m.idcolor=c.idcolor)
                                      AND m.tipo='venta'
                                      GROUP BY m.idespecie");


        $query->execute();

        $query2 = $this->_db->prepare("SELECT SUM( m.cantidad ) as Stock 
                                      FROM movimientos m
                                      INNER JOIN especie e on (m.idespecie=e.idespecie)
                                      INNER JOIN color c on (m.idcolor=c.idcolor)                                    
                                      AND m.tipo='venta'
                                      GROUP BY m.idespecie");


        $query2->execute();


        $rows = array();
        $rows['name'] = 'ESPECIE';
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $rows['data'][] = strtoupper($row['Especie']);
            // $rows['data'][]=$row['Stock'];
        }

        $rows1 = array();
        $rows1['name'] = "C. VENDIDA";
        while ($row1 = $query2->fetch(PDO::FETCH_ASSOC)) {
            $rows1['data'][] = abs((int) $row1['Stock']);
        }


        $result = array();
        array_push($result, $rows);
        array_push($result, $rows1);
        // return $query->fetchAll(PDO::FETCH_ASSOC);


        return $result;
    }

    public function getMarkers() {


        $query = $this->_db->prepare("SELECT  p.latitud, p.longitud, SUM(ABS(m.cantidad) ) as Stock 
                                      FROM movimientos m
                                      INNER JOIN usuario u  on (m.idusuario=u.idusuario)
                                      INNER JOIN persona p  on (p.idpersona=u.idpersona)
                                      AND m.tipo='venta'
                                      GROUP BY m.idusuario
                                      ORDER BY Stock DESC
                                      LIMIT 0,3");


        $query->execute();
        $i = 0;
        $result = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[$i]['latitud'] = ($row['latitud']);
            $result[$i]['longitud'] = ($row['longitud']);
            $i++;

        }
       
        return $result;
    }

    public function getMarkersMenor() {


        $query = $this->_db->prepare("SELECT  p.latitud, p.longitud, SUM(ABS(m.cantidad) ) as Stock 
                                      FROM movimientos m
                                      INNER JOIN usuario u  on (m.idusuario=u.idusuario)
                                      INNER JOIN persona p  on (p.idpersona=u.idpersona)
                                      AND m.tipo='venta'
                                      GROUP BY m.idusuario
                                      ORDER BY Stock ASC
                                      LIMIT 0,3");


        $query->execute();
        $i = 0;
        $rows = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $rows[$i]['latitud'] = ($row['latitud']);
            $rows[$i]['longitud'] = ($row['longitud']);
            // $rows['data'][]=$row['Stock'];
            $i++;
        }
        $result = array();
        array_push($result, $rows);



        return $result;
    }

    public function getProductoresMenosVentas() {


        $query = $this->_db->prepare("SELECT  p.latitud, p.longitud, SUM(ABS(m.cantidad) ) as Stock 
                                      FROM movimientos m
                                      INNER JOIN usuario u  on (m.idusuario=u.idusuario)
                                      INNER JOIN persona p  on (p.idpersona=u.idpersona)
                                      AND m.tipo='venta'
                                      GROUP BY m.idusuario
                                      ORDER BY Stock ASC
                                      LIMIT 0,3");


        $query->execute();
        $i = 0;
        $rows = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $rows[$i]['latitud'] = ($row['latitud']);
            $rows[$i]['longitud'] = ($row['longitud']);
            // $rows['data'][]=$row['Stock'];
            $i++;
        }
        $result = array();
        array_push($result, $rows);



        return $result;
    }

    public function getEspeciesPorMes($mes) {



        $query = $this->_db->prepare("SELECT  e.nombre as Especie,SUM( m.cantidad ) as Stock 
                                      FROM movimientos m
                                      INNER JOIN especie e on (m.idespecie=e.idespecie)
                                      AND (month(m.fecha) = ?)
                                      AND m.tipo='venta'
                                      GROUP BY m.idespecie
                                      ORDER BY Stock
                                      LIMIT 0,5");


        $query->execute(array($mes));

        $query2 = $this->_db->prepare("SELECT  e.nombre as Especie,SUM( m.cantidad ) as Stock 
                                      FROM movimientos m
                                      INNER JOIN especie e on (m.idespecie=e.idespecie)
                                      AND (month(m.fecha) = ?)
                                      AND m.tipo='venta'
                                      GROUP BY m.idespecie
                                      ORDER BY Stock
                                      LIMIT 0,5");


        $query2->execute(array($mes));



        $rows = array();
        $rows['name'] = 'ESPECIE';
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $rows['data'][] = strtoupper($row['Especie']);
            // $rows['data'][]=$row['Stock'];
        }

        $rows1 = array();
        $rows1['name'] = "C. VENDIDA";
        while ($row1 = $query2->fetch(PDO::FETCH_ASSOC)) {
            $rows1['data'][] = abs((int) $row1['Stock']);
        }


        $result = array();
        array_push($result, $rows);
        array_push($result, $rows1);
        // return $query->fetchAll(PDO::FETCH_ASSOC);


        return $result;
    }

    public function getEspecieMasyMenosVendida($m, $a, $m, $a) {
        $query = $this->_db->prepare("(SELECT e.nombre,SUM(abs(m.cantidad)) as Stock 
                                      FROM movimientos m
                                      INNER JOIN especie e on (m.idespecie=e.idespecie)
                                      INNER JOIN color c on (m.idcolor=c.idcolor)
                                         AND m.tipo='venta'
                                      where month(m.fecha)=? and year(m.fecha)=?
                                      GROUP BY m.idespecie
                                      order by Stock DESC
                                      LIMIT 0,1 ) 
                                      UNION (SELECT e.nombre,SUM(abs(m.cantidad)) as Stock 
                                      FROM movimientos m
                                      INNER JOIN especie e on (m.idespecie=e.idespecie)
                                      INNER JOIN color c on (m.idcolor=c.idcolor)
                                         AND m.tipo='venta'
                                      where month(m.fecha)=? and year(m.fecha)=? 
                                      GROUP BY m.idespecie
                                      order by Stock ASC
                                      LIMIT 0,1)");
        $query->execute(array($m, $a, $m, $a));



        $query2 = $this->_db->prepare("(SELECT e.nombre,SUM(abs(m.cantidad)) as Stock 
                                      FROM movimientos m
                                      INNER JOIN especie e on (m.idespecie=e.idespecie)
                                      INNER JOIN color c on (m.idcolor=c.idcolor)
                                         AND m.tipo='venta'
                                      where month(m.fecha)=? and year(m.fecha)=?
                                      GROUP BY m.idespecie
                                      order by Stock DESC
                                      LIMIT 0,1 ) 
                                      UNION (SELECT e.nombre,SUM(abs(m.cantidad)) as Stock 
                                      FROM movimientos m
                                      INNER JOIN especie e on (m.idespecie=e.idespecie)
                                      INNER JOIN color c on (m.idcolor=c.idcolor)
                                         AND m.tipo='venta'
                                      where month(m.fecha)=? and year(m.fecha)=?
                                      GROUP BY m.idespecie
                                      order by Stock ASC
                                      LIMIT 0,1)");

        $query2->execute(array($m, $a, $m, $a));
        $rows = array();
        $rows['name'] = 'ESPECIE';
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $rows['data'][] = strtoupper($row['nombre']);
        }

        $rows1 = array();
        $rows1['name'] = "C. VENDIDA";
        while ($row1 = $query2->fetch(PDO::FETCH_ASSOC)) {
            $rows1['data'][] = abs((int) $row1['Stock']);
        }


        $result = array();
        array_push($result, $rows);
        array_push($result, $rows1);
        // return $query->fetchAll(PDO::FETCH_ASSOC);


        return $result;
    }

    public function getComprasClientes() {
        $query = $this->_db->prepare("SELECT nombre  FROM especie");
        $query->execute();

        $query1 = $this->_db->prepare("select abs(sum(m.cantidad))as cantidad,e.nombre,p.nombre as cliente
    from movimientos m inner join cliente c on c.idcliente=m.idcliente
    inner join especie e on e.idespecie=m.idespecie
    inner join persona p on p.idpersona=c.idpersona
    group by c.idcliente,e.nombre");

        $query1->execute();


        $query2 = $this->_db->prepare("select abs(sum(m.cantidad))as cantidad,e.nombre,p.nombre as cliente
    from movimientos m inner join cliente c on c.idcliente=m.idcliente
    inner join especie e on e.idespecie=m.idespecie
    inner join persona p on p.idpersona=c.idpersona
    group by c.idcliente,e.nombre");
        $query2->execute();

        $query3 = $this->_db->prepare("select abs(sum(m.cantidad))as cantidad,e.nombre,p.nombre as cliente
    from movimientos m inner join cliente c on c.idcliente=m.idcliente
    inner join especie e on e.idespecie=m.idespecie
    inner join persona p on p.idpersona=c.idpersona
    group by c.idcliente,e.nombre");
        $query3->execute();

        $rows = array();
        $rows['name'] = 'ESPECIE';
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $rows['data'][] = strtoupper($row['nombre']);
        }

        $rows1 = array();
        $rows1['name'] = "UNIDADES";
        while ($row1 = $query1->fetch(PDO::FETCH_ASSOC)) {
            $rows1['data'][] = abs((int) $row1['cantidad']);
        }

        $rows2 = array();
        $rows2['name'] = "CLIENTE";
        while ($row2 = $query3->fetch(PDO::FETCH_ASSOC)) {
            $rows2['data'][] = $row2['cliente'];
        }


        $result = array();
        array_push($result, $rows);
        array_push($result, $rows1);
        array_push($result, $rows2);
        return $result;
    }

    public function getSocios() {
        $query = $this->_db->prepare("select s.idsocio,per.perfil,p.nombre,p.apellido,p.cuit,p.direccion,p.mail,p.razonsocial,usuario from usuario u inner join persona p on p.idpersona =u.idpersona inner join perfil per on per.idperfil=u.idperfil inner join socio s on s.idpersona=p.idpersona where s.estado=?");
        $data = array("activo");
        $query->execute($data);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ventasPorSocio($idsocio) {
        $query = $this->_db->prepare("SELECT SUM(abs(m.cantidad) ) as Stock,e.nombre 
                            FROM movimientos m
                            INNER JOIN especie e on (m.idespecie=e.idespecie)
                            INNER JOIN usuario u on u.idusuario=m.idusuario 
                            INNER JOIN socio s on s.idpersona=u.idpersona
                            INNER JOIN persona p on p.idpersona=s.idpersona
                            where m.tipo='venta' and s.idsocio=? 
                            GROUP BY m.idespecie");
       $parametros1=array($idsocio);
       $query->execute($parametros1);
       
       
       
       $query2 = $this->_db->prepare("SELECT SUM(abs(m.cantidad) ) as Stock,e.nombre 
                            FROM movimientos m
                            INNER JOIN especie e on (m.idespecie=e.idespecie)
                            INNER JOIN usuario u on u.idusuario=m.idusuario 
                            INNER JOIN socio s on s.idpersona=u.idpersona
                            INNER JOIN persona p on p.idpersona=s.idpersona
                            where m.tipo='venta' and s.idsocio=? 
                            GROUP BY m.idespecie");
       $parametros2=array($idsocio);
       $query2->execute($parametros2);
       
       
        $rows = array();
        $rows['name'] = 'ESPECIE';
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $rows['data'][] = strtoupper($row['nombre']);
        }

        $rows1 = array();
        $rows1['name'] = "UNIDADES";
        while ($row1 = $query2->fetch(PDO::FETCH_ASSOC)) {
            $rows1['data'][] = abs((int) $row1['Stock']);
        }



        $result = array();
        array_push($result, $rows);
        array_push($result, $rows1);
        return $result;
        
        
    }
  public function ventasPorSocioEntreFechas($idsocio,$f1,$f2){
 
    $fecha1 = date_format(date_create($f1), 'Y-m-d');
    $fecha2 = date_format(date_create($f2), 'Y-m-d');
      
   $query = $this->_db->prepare("SELECT SUM(abs(m.cantidad) ) as Stock,e.nombre 
                            FROM movimientos m
                            INNER JOIN especie e on (m.idespecie=e.idespecie)
                            INNER JOIN usuario u on u.idusuario=m.idusuario 
                            INNER JOIN socio s on s.idpersona=u.idpersona
                            INNER JOIN persona p on p.idpersona=s.idpersona
                            where m.tipo='venta' and s.idsocio=? and m.fecha between ? and ?
                            GROUP BY m.idespecie");
       $parametros1=array($idsocio,$fecha1,$fecha2);
       $query->execute($parametros1);
       
       
       
       $query2 = $this->_db->prepare("SELECT SUM(abs(m.cantidad) ) as Stock,e.nombre 
                            FROM movimientos m
                            INNER JOIN especie e on (m.idespecie=e.idespecie)
                            INNER JOIN usuario u on u.idusuario=m.idusuario 
                            INNER JOIN socio s on s.idpersona=u.idpersona
                            INNER JOIN persona p on p.idpersona=s.idpersona
                            where m.tipo='venta' and s.idsocio=? and m.fecha between ? and ?
                            GROUP BY m.idespecie");
       $parametros2=array($idsocio,$fecha1,$fecha2);
       $query2->execute($parametros2);
       
       
        $rows = array();
        $rows['name'] = 'ESPECIE';
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $rows['data'][] = strtoupper($row['nombre']);
        }

        $rows1 = array();
        $rows1['name'] = "UNIDADES";
        while ($row1 = $query2->fetch(PDO::FETCH_ASSOC)) {
            $rows1['data'][] = abs((int) $row1['Stock']);
        }



        $result = array();
        array_push($result, $rows);
        array_push($result, $rows1);
        return $result;
            
      
      
      
      
      
      
  }
    
    
    
    
    
}
