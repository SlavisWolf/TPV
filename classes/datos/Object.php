<?php



// clase generica que contendra todos los metodos generales que usaran todas nuestras clases POJO
// tener en cuenta que para el buen funcionamiento, las variables de las clases hijos deben estar declaradas como PROTECTED
abstract class  Object {
    
    function getAttributes(){
        $attributes = [];
        foreach ($this as $attribute => $value) {
            $attributes[] = $attribute;
        }
        return $attributes;
    }
    
    function getValues() {
        $values = [];        
        foreach ($this as $value) {
            $values[] = $value;
        }
        return $values;
    }
    
    function  toArray() {
        $array = [];
        foreach ($this as $attribute => $value) {
            
            // añadido para que el método pueda convertir a array objetos que hereden de Object
            if ($value instanceof Object) {
                $array[$attribute] = $value->toArray();
            }
            else {
                $array[$attribute] = $value;
            }
        }        
        return $array;
    }
    
    public function read() { // lee los valores de un objeto con la clase Request
        foreach ($this as $attribute => $value) {
            if ($resquestValue = Request::read($attribute)) {
                 $this->$attribute = Request::read($attribute);
                 
                 if(is_string($this->$attribute)) {
                     $this->$attribute = trim($this->$attribute);
                 }
            }           
        }
    }
    
    public function set(array $array ,$initialPost = 0) {               
        foreach ($this as $attribute => $value) {
            if (isset($array[$initialPost]))  {
                $this->$attribute = $array[$initialPost];                
            } 
            $initialPost++;
        }
    }
    
    public function setAssociative(array $array) {        
        foreach ($this as $attribute => $value) {
            if (isset($array[$attribute])) {
                $this->$attribute = $array[$attribute];
            }
        }
    }
    
    public function __toString() {
        $text = get_called_class() . ": {<br>";
        foreach ($this as $attribute => $value) {
            $text .= $attribute . ':' . $value . '<br>';
        }        
        $text .= "}<br>";
        return $text;
    }
    
    
    // esta funcion la usaremos para validar que los datos insertados por el cliente son correctos y aptos para insertar en la BD
    // hay que tener en cuenta que este método solo tiene en cuenta la estructura de la clase, no los unique ni las foreign key de la BD
    abstract public function isValid();
}
