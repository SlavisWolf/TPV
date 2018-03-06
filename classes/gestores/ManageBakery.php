<?php

class ManageBakery {

    private $db;

    function __construct(DataBase $db) {
        $this->db = $db;
    }

    
    /* =============================================================
                                    Member
    ==================================================================*/
    
    
    /*========== Añadir un miembro ========= */
    public function addMember(Member $objeto) {
        $sql = 'insert into member(login, password)
                            values (:login, :password)';
        $params = array(
            'login' => $objeto->getLogin(),
            'password' => Util::encriptar($objeto->getPassword()),
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $id = $this->db->getId();
            $objeto->setId($id);
        } else {
            $id = 0;
        }
        return $id;
    }
    
    /*============= editar a un miembro ============= */
    public function editMember(Member $objeto) {
        $sql = 'update member set login= :login,
                                  password= :password
                                  where id=:id';
        $params = array(
            'login'=>$objeto->getLogin(),
            'password'=> Util::encriptar($objeto->getPassword()),
            'id' => $objeto->getId()
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $filasAfectadas = $this->db->getRowNumber();
        } else {
            $filasAfectadas = -1;
        }
        return $filasAfectadas;
    }
    
    /*============= editar a un miembro ============= */
    public function editMemberNoPassword(Member $objeto) {
        $sql = 'update member set login = :login where id=:id';
        $params = array(
            'login'=>$objeto->getLogin(),            
            'id' => $objeto->getId()
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $filasAfectadas = $this->db->getRowNumber();
        } else {
            $filasAfectadas = -1;
        }
        return $filasAfectadas;
    }
    
    /* =========== devuelve un miembro ================ */
    public function getMember($id) {
        $sql = 'select * from member where id = :id';
        $params = array(
            'id' => $id
        );
        $resultado = $this->db->execute($sql, $params);
        $sentencia = $this->db->getStatement();
        $objeto = new Member();
        if($resultado && $fila = $sentencia->fetch()) {
            $objeto->set($fila);
        } else {
            $objeto = null;
        }
        return $objeto;
    }
    
     /* =========== devuelve un miembro pero con un correo como parámetro ================ */
    public function getMemberFromEmail($email) {
        $sql = 'select * from member where login = :email';
        $params = array(
            'email' => $email
        );
        $resultado = $this->db->execute($sql, $params);
        $sentencia = $this->db->getStatement();
        $objeto = new Member();
        if($resultado && $fila = $sentencia->fetch()) {
            $objeto->set($fila);
        } else {
            $objeto = null;
        }
        return $objeto;
    }

    /* ============== devuelve todos los miembros ============= */
    public function getAllMember() {
        $sql = 'select * from member';
        $resultado = $this->db->execute($sql);
        $objetos = array();
        if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new Member();
                $objeto->set($fila);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }
    
    /* ============= devuelve todos los miembros con unos límites ===============*/
    function getAlllimitMember($offset, $rpp) {
        $sql = 'select * from member limit '. $offset . ', ' . $rpp;
        $resultado = $this->db->execute($sql);
        $objetos = array();
        if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new Member();
                $objeto->set($fila);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }
    
    /* ============= devuelve todos los miembros con unos límites excepto el actual ===============*/
    function getAllLimitMemberNotCurrent($offset, $rpp, $id) {
        $sql = 'select * from member  where id != :id limit '. $offset . ', ' . $rpp;
        
        $params = array(
            'id' => $id
        );
        
        $resultado = $this->db->execute($sql, $params);
        $objetos = array();
        if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new Member();
                $objeto->set($fila);
                $objetos[] = $objeto;
            }
        }
                
        return $objetos;
    }
    
    /* Devuelve la cantidad de miembros que hay en la BD*/
     public function getCountAllMember() {
        $sql = 'select count(*) from member';        
        $resultado = $this->db->execute($sql);
        $cantidad = 0;
        if ($resultado) {
            $sentencia = $this->db->getStatement();
            if ($fila  = $sentencia->fetch()) {
                $cantidad = $fila[0];
            }
        }
        return $cantidad;
    }
    
    // este método discrimina al usuario conectado, el no se vera a si mismo en la tabla
    public function getCountAllMemberNotCurrent($id) {
        $sql = 'select count(*) from member where id != :id';  
        $params = array(
            'id' => $id
        );
        $resultado = $this->db->execute($sql, $params);
        $cantidad = 0;
        if ($resultado) {
            $sentencia = $this->db->getStatement();
            if ($fila  = $sentencia->fetch()) {
                $cantidad = $fila[0];
            }
        }
        return $cantidad;
    }

    /* ============= Borrar un miembro ===================== */
    public function removeMember($id) {
        $sql = 'delete from member where id = :id';
        $params = array(
            'id' => $id
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $filasAfectadas = $this->db->getRowNumber();
        } else {
            $filasAfectadas = -1;
        }
        return $filasAfectadas;
    }
    
    /* =============================================================
                                    Client
    ==================================================================*/
    
    
    /*========== Añadir un cliente ========= */
    public function addClient(Client $objeto) {
        $sql = 'insert into client(name, surname, tin, address, location, postalCode, province, email)
                            values (:name, :surname, :tin, :address, :location, :postalCode, :province, :email)';
        $params = array(
            'name' => $objeto->getName(),
            'surname' => $objeto->getSurname(),
            'tin' => $objeto->getTin(),
            'address' => $objeto->getAddress(),
            'location' => $objeto->getLocation(),
            'postalCode' => $objeto->getpostalCode(),
            'province' => $objeto->getProvince(),
            'email' => $objeto->getEmail()
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $id = $this->db->getId();
            $objeto->setId($id);
        } else {
            $id = 0;
        }
        return $id;
    }
    
    /* ============= editar a un cliente ============= */
    public function editClient(Client $objeto) {
        $sql = 'update client set name= :name, 
                                  surname= :surname,
                                  tin = :tin,
                                  address = :address,
                                  location= :location,
                                  postalCode = :postalCode,
                                  province = :province,
                                  email = :email
                        where id=:id';
        $params = array(
            'name' => $objeto->getName(),
            'surname' => $objeto->getSurname(),
            'tin' => $objeto->getTin(),
            'address' => $objeto->getAddress(),
            'location' => $objeto->getLocation(),
            'postalCode' => $objeto->getPostalCode(),
            'province' => $objeto->getProvince(),
            'email' => $objeto->getEmail(),
            'id' => $objeto->getId()
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $filasAfectadas = $this->db->getRowNumber();
        } else {
            $filasAfectadas = -1;
        }
        return $filasAfectadas;
    }
    
    /* =========== devuelve un cliente ================ */
    public function getClient($id) {
        $sql = 'select * from client where id = :id';
        $params = array(
            'id' => $id
        );
        $resultado = $this->db->execute($sql, $params);
        $sentencia = $this->db->getStatement();
        $objeto = new Client();
        if($resultado && $fila = $sentencia->fetch()) {
            $objeto->set($fila);
        } else {
            $objeto = null;
        }
        return $objeto;
    }

    /* ============== devuelve todos los clientes ============= */
    public function getAllClient() {
        $sql = 'select * from client';
        $resultado = $this->db->execute($sql);
        $objetos = array();
        if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new Client();
                $objeto->set($fila);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }
    
    /* ============= devuelve todos los clientes con unos límites ===============*/
    function getAlllimitClient($offset, $rpp) {
        $sql = 'select * from client limit '. $offset . ', ' . $rpp;
        $resultado = $this->db->execute($sql);
        $objetos = array();
        if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new Client();
                $objeto->set($fila);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }
    
    public function getCountAllClient() {
        $sql = 'select count(*) from client';        
        $resultado = $this->db->execute($sql);
        $cantidad = 0;
        if ($resultado) {
            $sentencia = $this->db->getStatement();
            if ($fila  = $sentencia->fetch()) {
                $cantidad = $fila[0];
            }
        }
        return $cantidad;
    }

    /* ============= Borrar un cliente ===================== */
    public function removeClient($id) {
        $sql = 'delete from client where id = :id';
        $params = array(
            'id' => $id
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $filasAfectadas = $this->db->getRowNumber();
        } else {
            $filasAfectadas = -1;
        }
        return $filasAfectadas;
    }
    
    /* =============================================================
                                    Family
    ==================================================================*/
    
    
    /*========== Añadir un family ========= */
    public function addFamily(Family $objeto) {
        $sql = 'insert into family(family)
                            values (:family)';
        $params = array(
            'family'=> $objeto->getFamily()
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $id = $this->db->getId();
            $objeto->setId($id);
        } else {
            $id = 0;
        }
        return $id;
    }
    
    /*============= editar a un family ============= */
    public function editFamily(Family $objeto) {
        $sql = 'update family
                    set family= :family
                        where id=:id';
        $params = array(
            'family' => $objeto->getFamily(),
            'id' => $objeto->getId()
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $filasAfectadas = $this->db->getRowNumber();
        } else {
            $filasAfectadas = -1;
        }
        return $filasAfectadas;
    }
    
    /* =========== devuelve un family ================ */
    public function getFamily($id) {
        $sql = 'select * from family where id = :id';
        $params = array(
            'id' => $id
        );
        $resultado = $this->db->execute($sql, $params);
        $sentencia = $this->db->getStatement();
        $objeto = new Family();
        if($resultado && $fila = $sentencia->fetch()) {
            $objeto->set($fila);
        } else {
            $objeto = null;
        }
        return $objeto;
    }

    /* ============== devuelve todos los familys ============= */
    
    public function getAllFamily() {
        $sql = 'select * from family where visible = 1  order by family';
        $resultado = $this->db->execute($sql);
        $objetos = array();
        if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new Family();
                $objeto->set($fila);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }
    
    public function getAllFamilyOLD() {
        $sql = 'select * from family  order by family';
        $resultado = $this->db->execute($sql);
        $objetos = array();
        if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new Family();
                $objeto->set($fila);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }
    
    /* ============= devuelve todos los familys con unos límites ===============*/
    function getAlllimitFamily($offset, $rpp) {
        $sql = 'select * from family where visible = 1 order by family limit '. $offset . ', ' . $rpp;
        $resultado = $this->db->execute($sql);
        $objetos = array();
        if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new Family();
                $objeto->set($fila);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }
    
    function getAlllimitFamilyOLD($offset, $rpp) {
        $sql = 'select * from family order by family limit '. $offset . ', ' . $rpp;
        $resultado = $this->db->execute($sql);
        $objetos = array();
        if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new Family();
                $objeto->set($fila);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }
    
    public function getCountAllFamily() {
        $sql = 'select count(*) from family where visible = 1';        
        $resultado = $this->db->execute($sql);
        $cantidad = 0;
        if ($resultado) {
            $sentencia = $this->db->getStatement();
            if ($fila  = $sentencia->fetch()) {
                $cantidad = $fila[0];
            }
        }
        return $cantidad;
    }
    
    public function getCountAllFamilyOLD() {
        $sql = 'select count(*) from family';        
        $resultado = $this->db->execute($sql);
        $cantidad = 0;
        if ($resultado) {
            $sentencia = $this->db->getStatement();
            if ($fila  = $sentencia->fetch()) {
                $cantidad = $fila[0];
            }
        }
        return $cantidad;
    }

    /* ============= Borrar un familys ===================== */
    public function removeFamily($id) {
        //UPDATE family SET family = CONCAT("$#", family ) WHERE id = :id
        $sql = 'UPDATE family SET visible = 0 WHERE id = :id';
        $params = array(
            'id' => $id
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $filasAfectadas = $this->db->getRowNumber();
        } else {
            $filasAfectadas = -1;
        }
        return $filasAfectadas;
    }
    
    
    public function removeFamilyOLD($id) {
        
        $sql = 'delete from family where id = :id';
        $params = array(
            'id' => $id
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $filasAfectadas = $this->db->getRowNumber();
        } else {
            $filasAfectadas = -1;
        }
        return $filasAfectadas;
    }
    
    /* =============================================================
                                    Product
    ==================================================================*/
    
    
    /*========== Añadir un product ========= */
    public function addProduct(Product $objeto) {
        $sql = 'insert into product(idFamily, product, price, description)
                            values (:idFamily, :product, :price, :description)';
        $params = array(
            'idFamily'=> $objeto->getIdFamily(),
            'product'=> $objeto->getProduct(),
            'price'=> $objeto->getPrice(),
            'description'=> $objeto->getDescription()
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $id = $this->db->getId();
            $objeto->setId($id);
        } else {
            $id = 0;
        }
        return $id;
    }
    
    /*============= editar a un product ============= */
    public function editProduct(Product $objeto) {
        $sql = 'update product
                    set idFamily = :idFamily,
                    product = :product,
                    price = :price,
                    description = :description
                        where id=:id';
        $params = array(
            'idFamily'=> $objeto->getIdFamily(),
            'product'=> $objeto->getProduct(),
            'price'=> $objeto->getPrice(),
            'description'=> $objeto->getDescription(),
            'id' => $objeto->getId()
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $filasAfectadas = $this->db->getRowNumber();
        } else {
            $filasAfectadas = -1;
        }
        return $filasAfectadas;
    }
    
    /* =========== devuelve un product ================ */
    public function getProduct($id) {
        $sql = 'select * from product where id = :id and visible = 1';
        $params = array(
            'id' => $id
        );
        $resultado = $this->db->execute($sql, $params);
        $sentencia = $this->db->getStatement();
        $objeto = new Product();
        if($resultado && $fila = $sentencia->fetch()) {
            $objeto->set($fila);
        } else {
            $objeto = null;
        }
        return $objeto;
    }
    
    /* =========== devuelve un product ================ */
    public function getProductTicketDetail($id) {
        $sql = 'select * from product where id = :id';
        $params = array(
            'id' => $id
        );
        $resultado = $this->db->execute($sql, $params);
        $sentencia = $this->db->getStatement();
        $objeto = new Product();
        if($resultado && $fila = $sentencia->fetch()) {
            $objeto->set($fila);
        } else {
            $objeto = null;
        }
        return $objeto;
    }

    /* ============== devuelve todos los products ============= */
    public function getAllProduct() {
        $sql = 'select * from product P inner join family F on P.idFamily = F.id where P.visible = 1 order by P.idFamily,P.product';
        $resultado = $this->db->execute($sql);
        $objetos = array();
        if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new Product();
                $objeto->set($fila);
                $family = new Family();
                $family->set($fila, 6);
                $objeto->setIdFamily($family);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }
    
     /* ============== devuelve todos los products ============= */
    public function getAllProductOLD() {
        $sql = 'select * from product P inner join family F on P.idFamily = F.id order by P.idFamily,P.product';
        $resultado = $this->db->execute($sql);
        $objetos = array();
        if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new Product();
                $objeto->set($fila);
                $family = new Family();
                $family->set($fila, 6);
                $objeto->setIdFamily($family);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }
    
    /* ============= devuelve todos los products con unos límites ===============*/
    function getAlllimitProduct($offset, $rpp) {
        $sql = 'select * from product P inner join family F on P.idFamily = F.id where P.visible = 1 order by P.idFamily,P.product limit '. $offset . ', ' . $rpp;
        $resultado = $this->db->execute($sql);
        $objetos = array();
        if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new Product();
                $objeto->set($fila);
                $family = new Family();
                $family->set($fila, 6);
                //$objetos[] = array_merge($objeto, $family);
                $objeto->setIdFamily($family);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }
    
     /* ============= devuelve todos los products con unos límites ===============*/
    function getAlllimitProductOLD($offset, $rpp) {
        $sql = 'select * from product P inner join family F on P.idFamily = F.id order by P.idFamily,P.product limit '. $offset . ', ' . $rpp;
        $resultado = $this->db->execute($sql);
        $objetos = array();
        if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new Product();
                $objeto->set($fila);
                $family = new Family();
                $family->set($fila, 6);
                //$objetos[] = array_merge($objeto, $family);
                $objeto->setIdFamily($family);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }
    
    // filtra por familia.
    function getAlllimitProductByFamily($idFamilia, $offset, $rpp) {
        $sql = 'select * from product P inner join family F on P.idFamily = F.id  where P.idFamily = :idFamily and P.visible = 1 order by product limit '. $offset . ', ' . $rpp;
        $params = array(
            'idFamily' => $idFamilia,
        );
        $resultado = $this->db->execute($sql, $params);
        $objetos = array();
        if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new Product();
                $objeto->set($fila);
                $family = new Family();
                $family->set($fila, 6);
                //$objetos[] = array_merge($objeto, $family);
                $objeto->setIdFamily($family);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }
    
     function getAlllimitProductByFamilyOLD($idFamilia, $offset, $rpp) {
        $sql = 'select * from product P inner join family F on P.idFamily = F.id  where P.idFamily = :idFamily order by product limit '. $offset . ', ' . $rpp;
        $params = array(
            'idFamily' => $idFamilia,
        );
        $resultado = $this->db->execute($sql, $params);
        $objetos = array();
        if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new Product();
                $objeto->set($fila);
                $family = new Family();
                $family->set($fila, 5);
                //$objetos[] = array_merge($objeto, $family);
                $objeto->setIdFamily($family);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }
    
    // filtra por palabra de busqueda.
    function getAlllimitProductSearch ($texto, $offset, $rpp) {
        // lower es necesario para que sea case-insensitive
        $sql = 'select * from product P inner join family F on P.idFamily = F.id where lower(description) like :patron ||  lower(product) like :patron ||  lower(price) like :patron and P.visible = 1 order by idFamily,product limit '. $offset . ', ' . $rpp;
        $params = array(
            'patron' => '%' . strtolower($texto) . '%',
        );
        $resultado = $this->db->execute($sql, $params);
        $objetos = array();
        if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new Product();
                $objeto->set($fila);
                $family = new Family();
                $family->set($fila, 6);
                //$objetos[] = array_merge($objeto, $family);
                $objeto->setIdFamily($family);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }
    
    function getAlllimitProductSearchOLD($texto, $offset, $rpp) {
        // lower es necesario para que sea case-insensitive
        $sql = 'select * from product P inner join family F on P.idFamily = F.id where lower(description) like :patron ||  lower(product) like :patron ||  lower(price) like :patron order by idFamily,product limit '. $offset . ', ' . $rpp;
        $params = array(
            'patron' => '%' . strtolower($texto) . '%',
        );
        $resultado = $this->db->execute($sql, $params);
        $objetos = array();
        if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new Product();
                $objeto->set($fila);
                $family = new Family();
                $family->set($fila, 5);
                //$objetos[] = array_merge($objeto, $family);
                $objeto->setIdFamily($family);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }
    
    
    public function getCountAllProduct() {
        $sql = 'select count(*) from product where visible = 1';        
        $resultado = $this->db->execute($sql);
        $cantidad = 0;
        if ($resultado) {
            $sentencia = $this->db->getStatement();
            if ($fila  = $sentencia->fetch()) {
                $cantidad = $fila[0];
            }
        }
        return $cantidad;
    }
    
     public function getCountAllProductOLD() {
        $sql = 'select count(*) from product';        
        $resultado = $this->db->execute($sql);
        $cantidad = 0;
        if ($resultado) {
            $sentencia = $this->db->getStatement();
            if ($fila  = $sentencia->fetch()) {
                $cantidad = $fila[0];
            }
        }
        return $cantidad;
    }
    
    
    public function getCountAllProductSearch($texto) {
         $sql = 'select count(*) from product where lower(description) like :patron || lower(product) like :patron || lower(price) like :patron and visible = 1';
        $params = array(
            'patron' => "%". strtolower($texto) ."%",
        );
        $resultado = $this->db->execute($sql, $params);
        $cantidad = 0;
        if ($resultado) {
            $sentencia = $this->db->getStatement();
            if ($fila  = $sentencia->fetch()) {
                $cantidad = $fila[0];
            }
        }
        return $cantidad;
    }
    
    public function getCountAllProductSearchOLD($texto) {
         $sql = 'select count(*) from product where lower(description) like :patron || lower(product) like :patron || lower(price) like :patron';
        $params = array(
            'patron' => "%". strtolower($texto) ."%",
        );
        $resultado = $this->db->execute($sql, $params);
        $cantidad = 0;
        if ($resultado) {
            $sentencia = $this->db->getStatement();
            if ($fila  = $sentencia->fetch()) {
                $cantidad = $fila[0];
            }
        }
        return $cantidad;
    }
    
    public function getCountAllProductByFamily($idFamilia) {
        $sql = 'select count(*) from product where idFamily = :idFamily and visible = 1';
        $params = array(
            'idFamily' => $idFamilia,
        );
        $resultado = $this->db->execute($sql, $params);
        $cantidad = 0;
        if ($resultado) {
            $sentencia = $this->db->getStatement();
            if ($fila  = $sentencia->fetch()) {
                $cantidad = $fila[0];
            }
        }
        return $cantidad;
    }
    
    public function getCountAllProductByFamilyOLD($idFamilia) {
        $sql = 'select count(*) from product where idFamily = :idFamily';
        $params = array(
            'idFamily' => $idFamilia,
        );
        $resultado = $this->db->execute($sql, $params);
        $cantidad = 0;
        if ($resultado) {
            $sentencia = $this->db->getStatement();
            if ($fila  = $sentencia->fetch()) {
                $cantidad = $fila[0];
            }
        }
        return $cantidad;
    }

    /* ============= Borrar un products ===================== */
    public function removeProduct($id) {
        $sql = 'update product set visible = 0 where id = :id';
        $params = array(
            'id' => $id
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $filasAfectadas = $this->db->getRowNumber();
        } else {
            $filasAfectadas = -1;
        }
        return $filasAfectadas;
    }
    
    public function removeProductOLD($id) {
        $sql = 'delete from product where id = :id';
        $params = array(
            'id' => $id
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $filasAfectadas = $this->db->getRowNumber();
        } else {
            $filasAfectadas = -1;
        }
        return $filasAfectadas;
    }
    
     /* ============= Borra todos los productos de una familia ===================== */
    public function removeAllProductsOfFamily($idFamily) {
        $sql = 'update product set visible = 0 where idFamily = :idFamily';
        $params = array(
            'idFamily' => $idFamily
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $filasAfectadas = $this->db->getRowNumber();
        } else {
            $filasAfectadas = -1;
        }
        return $filasAfectadas;
    }
    
    public function removeAllProductsOfFamilyOLD($idFamily) {
        $sql = 'delete from product where idFamily = :idFamily';
        $params = array(
            'idFamily' => $idFamily
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $filasAfectadas = $this->db->getRowNumber();
        } else {
            $filasAfectadas = -1;
        }
        return $filasAfectadas;
    }
    
    /* =============================================================
                                    Ticket
    ==================================================================*/
    
    
    /* ========== Añadir un ticket ========= */
    public function addTicket(Ticket $objeto) {
        $sql = 'insert into ticket( idMember, idClient)
                            values ( :idMember, :idClient)';
        
        $idMember = $objeto->getIdMember() instanceof Member ? $objeto->getIdMember()->getId() : $objeto->getIdMember();
        $idClient = $objeto->getIdClient() instanceof Client ? $objeto->getIdClient()->getId() : $objeto->getIdClient();


        $params = array(
            //'date'=> $objeto->getDate(),
            'idMember'=> $idMember,
            'idClient'=> $idClient,
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $id = $this->db->getId();
            $objeto->setId($id);
        } else {
            $id = 0;
        }
        return $id;
    }
    
    /*============= editar a un ticket ============= */
    public function editTicket(Ticket $objeto) {
        $sql = 'update ticket set
                    date = :date,
                    idMember = :idMember,
                    idClient = :idClient
                                where id=:id';
        
        $idMember = $objeto->getIdMember() instanceof Member ? $objeto->getIdMember()->getId() : $objeto->getIdMember();
        $idClient = $objeto->getIdClient() instanceof Client ? $objeto->getIdClient()->getId() : $objeto->getIdClient();
        
        $params = array(
            'date'=> $objeto->getDate(),
            'idMember'=> $idMember,
            'idClient'=> $idClient,
            'id' => $objeto->getId()
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $filasAfectadas = $this->db->getRowNumber();
        } else {
            $filasAfectadas = -1;
        }
        return $filasAfectadas;
    }
    
    /* =========== devuelve un ticket ================ */
    public function getTicket($id) {
        $sql = 'select * from ticket where id = :id';
        $params = array(
            'id' => $id
        );
        $resultado = $this->db->execute($sql, $params);
        $sentencia = $this->db->getStatement();
        $objeto = new Ticket();
        if($resultado && $fila = $sentencia->fetch()) {
            $objeto->set($fila);
        } else {
            $objeto = null;
        }
        return $objeto;
    }

    /* ============== devuelve todos los tickets ============= */
    public function getAllTicket() {
        $sql = 'select * from ticket';
        $resultado = $this->db->execute($sql);
        $objetos = array();
        if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new Ticket();
                $objeto->set($fila);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }
    
    /* ============= devuelve todos los tickets con unos límites ===============*/
    function getAlllimitTicket($offset, $rpp, Array $args = array()) { // añadido inner y left join
        $sql = "SELECT * FROM  ticket T inner join member M on  T.idMember = M.id left join client C on T.idClient = C.id";
        
        
        $sql_params = $this->createWhereAndParamsTicketIndex($args);
        
        $sql .= $sql_params['sql'];
        $sql .= " order by  T.date DESC limit $offset, $rpp";
        
        $resultado = $this->db->execute($sql, $sql_params['params']);
        $objetos = array();
        if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                
                $objeto = new Ticket();
                $objeto->set($fila);
                $member = new Member();
                $member->set($fila, 4);
                
                if ($fila[7] !== null) { // los tickets pueden no tener clientes asociados.
                    $client = new Client();
                    $client->set($fila, 7);
                    $objeto->setIdClient($client);
                }
                
                $objeto->setIdMember($member);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }
    
    private function createWhereAndParamsTicketIndex(Array $args) {
        $sql = ""; // este sera el string
        $params = array(); // y estos los parámetros
        
        if ($args) {
            
            $sql .= " WHERE";
            
            if (isset($args ['date']) ) {
                $sql .= " DATE_FORMAT(T.date ,\"%Y-%m-%d\") = :date";
                $params['date'] = $args ['date']->format("Y-m-d");
            }
            
            
            if(isset($args ['id']) ) {
                
                if (strcmp(" WHERE" , $sql) !== 0 ) {
                    $sql .= " AND";
                }
                
                if (strpos($args ['id'], '-') !== false) {
                    $sql .= " T.id != :id";
                    $params['id'] = str_replace('-' , '', $args ['id']);
                }
                
                else {
                    $sql .= " T.id = :id";
                    $params['id'] = $args ['id'];
                }
                
            }
            
            
            if(isset($args ['idClient']) ) {
                
                if (strcmp(" WHERE" , $sql) !== 0 ) {
                    $sql .= " AND";
                }
                
                // al ser número se filtrara por = o !=
                if(Filter::isInteger($args ['idClient'] ) ) {
                    if (strpos($args ['idClient'], '-') !== false) {
                        $sql .= " T.idClient != :idClient";
                        $params['idClient'] = str_replace('-' , '', $args ['idClient']);
                    }
                
                    else {
                        $sql .= " T.idClient = :idClient";
                        $params['idClient'] = $args ['idClient'];
                    }
                }
                
                // si es texto, buscaremos con LIKE en el nombre y el apellido del cliente
                else {
                    
                    $sql .= " (lower(C.name) like :client OR  lower(C.surname) like :client)";
                    $params['client'] = '%' . strtolower($args ['idClient'] )  . '%';
                }
            }
        }
        $res = array('sql' => $sql, 'params' => $params);
        return $res;
    }
    
     public function getCountAllTicket() {
        $sql = 'select count(*) from ticket';        
        $resultado = $this->db->execute($sql);
        $cantidad = 0;
        if ($resultado) {
            $sentencia = $this->db->getStatement();
            if ($fila  = $sentencia->fetch()) {
                $cantidad = $fila[0];
            }
        }
        return $cantidad;
    }
    
    public function getCountAllTicketWhere($args = array() ) {
        $sql = 'select count(*) from ticket T inner join member M on  T.idMember = M.id left join client C on T.idClient = C.id'; 
        
        $sql_params = $this->createWhereAndParamsTicketIndex($args);
        
        //echo Util::varDump($sql_params);exit;
        
        $sql .= $sql_params['sql'];
        $resultado = $this->db->execute($sql, $sql_params['params']);
        $cantidad = 0;
        if ($resultado) {
            $sentencia = $this->db->getStatement();
            if ($fila  = $sentencia->fetch()) {
                $cantidad = $fila[0];
            }
        }
        
        
        return $cantidad;
    }

    /* ============= Borrar un tickets ===================== */
    public function removeTicket($id) {
        $sql = 'delete from ticket where id = :id';
        $params = array(
            'id' => $id
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $filasAfectadas = $this->db->getRowNumber();
        } else {
            $filasAfectadas = -1;
        }
        return $filasAfectadas;
    }
    
    /* =============================================================
                                    ticketDetail
    ==================================================================*/
    
    
    /*========== Añadir un ticketDetail ========= */
    public function addticketDetail(ticketDetail $objeto) {
        $sql = 'insert into ticketDetail(idTicket, idProduct, quantity, price)
                            values (:idTicket, :idProduct, :quantity, :price)';
        $params = array(
            'idTicket'=> $objeto->getIdTicket(),
            'idProduct'=> $objeto->getIdProduct(),
            'quantity'=> $objeto->getQuantity(),
            'price'=> $objeto->getPrice(),
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $id = $this->db->getId();
            $objeto->setId($id);
        } else {
            $id = 0;
        }
        return $id;
    }
    
    /*============= editar a un ticketDetail ============= */
    public function editticketDetail(ticketDetail $objeto) {
        $sql = 'update ticketDetail set
                    idTicket = :idTicket,
                    idProduct = :idProduct,
                    quantity = :quantity,
                    price = :price
                                where id=:id';
        $params = array(
            'idTicket'=> $objeto->getIdTicket(),
            'idProduct'=> $objeto->getIdProduct(),
            'quantity'=> $objeto->getQuantity(),
            'price'=> $objeto->getPrice(),
            'id' => $objeto->getId()
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $filasAfectadas = $this->db->getRowNumber();
        } else {
            $filasAfectadas = -1;
        }
        return $filasAfectadas;
    }
    
    /* =========== devuelve un ticketDetail ================ */
    public function getticketDetail($id) {
        $sql = 'select * from ticketDetail where id = :id';
        $params = array(
            'id' => $id
        );
        $resultado = $this->db->execute($sql, $params);
        $sentencia = $this->db->getStatement();
        $objeto = new ticketDetail();
        if($resultado && $fila = $sentencia->fetch()) {
            $objeto->set($fila);
        } else {
            $objeto = null;
        }
        return $objeto;
    }
    
    
    

    /* ============== devuelve todos los ticketDetails ============= */
    public function getAllticketDetail() {
        $sql = 'select * from ticketDetail';
        $resultado = $this->db->execute($sql);
        $objetos = array();
        if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new ticketDetail();
                $objeto->set($fila);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }
    
    /* ============= devuelve todos los ticketDetails con unos límites ===============*/
    function getAlllimitticketDetail($offset, $rpp) {
        $sql = 'select * from ticketDetail limit '. $offset . ', ' . $rpp;
        $resultado = $this->db->execute($sql);
        $objetos = array();
        if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new ticketDetail();
                $objeto->set($fila);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }
    
    /* =========== devuelve todos los ticket detail de un ticket ================ */
    public function getAllTicketDetailByTicket($ticket) {
        
        $idTicket = ($ticket instanceof Ticket) ? $ticket->getId() : $ticket;
        
        $sql = 'select * from ticketDetail where idTicket = :idTicket';
        $params = array(
            'idTicket' => $idTicket
        );
        $resultado = $this->db->execute($sql, $params);
       if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new ticketDetail();
                $objeto->set($fila);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }

    /* ============= Borrar un ticketDetails ===================== */
    public function removeticketDetail($id) {
        $sql = 'delete from ticketDetail where id = :id';
        $params = array(
            'id' => $id
        );
        $resultado = $this->db->execute($sql, $params);
        if($resultado) {
            $filasAfectadas = $this->db->getRowNumber();
        } else {
            $filasAfectadas = -1;
        }
        return $filasAfectadas;
    }
    
    
    /* ============= Devolver las lineas de un ticket =========== */
    public function getAllTicketDetailsById($id){
        $sql = 'select * from ticketDetail where idTicket = :idTicket';
        $params = array(
            'idTicket' => $id
        );
        $resultado = $this->db->execute($sql, $params);
       if($resultado){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()) {
                $objeto = new TicketDetail();
                $objeto->set($fila);
                $objetos[] = $objeto;
            }
        }
        return $objetos;
    }
    
    
    // exclude negative es un parámetro que hace que cuando introduzcamos un número negativo, en vez de buscar elementos que coincidan con el, buscara los que sea distintos a el.
    // si esta a false, buscara elementos con el mismo valor en negativo.
    private function createDinamicWhereAndParams($tableAlias, Array $args, $excludeNegative = false) {
        $sql = ""; // este sera el string
        $params = array(); // y estos los parámetros
        if($args) {
            $sql .= " WHERE";
            foreach($args as $i => $value) {
                
                if (strcmp(" WHERE" , $sql) !== 0 ) {
                    $sql .= " AND";
                }
                
                if($value instanceof DateTime) {
                    $sql .= " DATE_FORMAT($tableAlias.$i ,\"%Y-%m-%d\") = :$i";
                    $params[$i] = $value->format("Y-m-d");
                }
                
                else {
                    
                    if($excludeNegative && Filter::isInteger($value) && strpos($value, '-') !== false ) {
                        $sql .= " $tableAlias.$i != :$i";
                        $params[$i] = str_replace('-' , '', $value);
                    }
                    
                    else {
                        $sql .= " $tableAlias.$i = :$i";
                        $params[$i] = $value;
                    }
                }
            }
        }
        
        $res = array('sql' => $sql, 'params' => $params);
        return $res;
    }
    
}