<?php 
    require_once 'conexao.php';

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");

    $conexao = new Conexao();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $players = $conexao->getPlayers();

        echo json_encode($players);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);
        $query = trim($data['query'] ?? '');

        header('Content-Type: application/json');

        $players = $conexao->findPlayers($query);

        $result = array_values(array_filter($players, function ($player) use ($query) {
            return stripos($player['nome'] ?? '', $query) !== false;
        }));

        echo json_encode($result);
    }
?>