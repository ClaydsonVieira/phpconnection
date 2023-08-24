<?php 

class Conexao{
	 private $db;

    public function __construct($servidor, $base, $usuario, $senha)
    {
        try {
            $this->db = new PDO("mysql:host=$servidor;dbname=$base", $usuario, $senha);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Falha na conexão: " . $e->getMessage());
        }
    }
	
	public function Insert($tabela, $dados)
    {
        try {
            $columns = implode(", ", array_keys($dados));
            $values = ":" . implode(", :", array_keys($dados));

            $query = "INSERT INTO $tabela ($columns) VALUES ($values)";
            $stmt = $this->db->prepare($query);

            foreach ($dados as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
	
	public function Select($tabela, $filtro)
    {
		if(empty($filtro)){
			$filtro = 1;
		}
        try {
            $query = "SELECT * FROM $tabela WHERE :filtro";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":filtro", $filtro);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
	
	 public function Update($tabela, $dados, $filtro)
    {
		if(empty($filtro)){
			echo "Necessário informar um filtro";
			exit();
		}
        try {
            $updates = "";
            foreach ($dados as $key => $value) {
                $updates .= "$key = :$key, ";
            }
            $updates = rtrim($updates, ", ");

            $query = "UPDATE $tabela SET $updates WHERE :filtro";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":filtro", $filtro);

            foreach ($dados as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
	
	 public function Deletar($tabela, $filtro)
    {
		if(empty($filtro)){
			echo "Necessário informar um filtro";
			exit();
		}
        try {
            $query = "DELETE FROM $tabela WHERE :filtro";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":filtro", $filtro);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}

?>