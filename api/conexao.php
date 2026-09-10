<?php
 class Conexao {
     private $host = "24.144.125.141";
     private $port = "5432";
     private $dbname = "pclevy_dbxadrez";
     private $user = "esfinge_arbitro";
     private $password = "scly1411";
     private $conn;

     public function __construct() {
         $this->connect();
     }

    private function connect() {
        $this->conn = pg_connect("host={$this->host} port={$this->port} dbname={$this->dbname} user={$this->user} password={$this->password}");
        if (!$this->conn) {
            throw new Exception("Erro na conexão com o banco de dados.");
        }
    }

    public function getPlayers(){
        $sql = pg_query($this->conn, "SELECT reg, sobrenome, nome, clube, municipio, rating FROM cadastro ORDER BY nome");
        $players = [];
        while ($row = pg_fetch_assoc($sql)) {
            $players[] = [
                "reg"        => trim($row["reg"]),
                "nome"       => trim($row["nome"] . " " . $row["sobrenome"]),
                "clube"      => trim($row["clube"]),
                "municipio"  => trim(" ".$row["municipio"]),
                "rating"     => trim($row["rating"])
            ];
        }
        return $players;
    }

    public function findPlayers($query) {
        $sql = pg_query_params(
            $this->conn,
            "SELECT reg, sobrenome, nome, clube, municipio, rating
             FROM cadastro
             WHERE CONCAT(nome, ' ', sobrenome) ILIKE $1
             ORDER BY nome",
            ["%{$queryZ}%"]
        );

        $players = [];
        while ($row = pg_fetch_assoc($sql)) {
            $players[] = [
                "reg"        => trim($row["reg"]),
                "nome"       => trim($row["nome"] . " " . $row["sobrenome"]),
                "clube"      => trim($row["clube"]),
                "municipio"  => trim(" ".$row["municipio"]),
                "rating"     => trim($row["rating"])
            ];
        }
        return $players;
    }
 }
?>