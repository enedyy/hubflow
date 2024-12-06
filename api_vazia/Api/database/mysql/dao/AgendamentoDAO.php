<?php
namespace Api\database\mysql\dao;

use Api\database\mysql\model\Agendamento;
use Api\database\mysql\MySqlPDO;
use PDO;

class AgendamentoDAO {
    public function getAll() {
        $pdo = MySqlPDO::getInstance();
        $sql = "SELECT * FROM Agendamentos";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(\PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }

    public function getById($id) {
        $pdo = MySqlPDO::getInstance();
        $sql = "SELECT * FROM Agendamentos WHERE AgendamentoID = :id";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(\PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }

    public function insert(Agendamento $agendamento) {
        $pdo = MySqlPDO::getInstance();

        $sql = "INSERT INTO Agendamentos (ClienteID, ServicoID, FuncionarioID, DataHoraAgendamento, Status) 
                VALUES (:ClienteID, :ServicoID, :FuncionarioID, :DataHoraAgendamento, :Status)";

        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([
                ':ClienteID' => $agendamento->ClienteID,
                ':ServicoID' => $agendamento->ServicoID,
                ':FuncionarioID' => $agendamento->FuncionarioID,
                ':DataHoraAgendamento' => $agendamento->DataHoraAgendamento,
                ':Status' => $agendamento->Status ?? 'Pendente'
            ]);
            return $pdo->lastInsertId();
        } catch(\PDOException $th) {
            var_dump($th->getMessage());
        } catch(\Throwable $th) {
            var_dump($th->getMessage());
        }
    }

    public function update(Agendamento $agendamento) {
        $pdo = MySqlPDO::getInstance();

        $sql = "UPDATE Agendamentos 
                SET ClienteID = :ClienteID,
                    ServicoID = :ServicoID,
                    FuncionarioID = :FuncionarioID,
                    DataHoraAgendamento = :DataHoraAgendamento,
                    Status = :Status
                WHERE AgendamentoID = :AgendamentoID";

        $stmt = $pdo->prepare($sql);

        try {
            return $stmt->execute([
                ':AgendamentoID' => $agendamento->AgendamentoID,
                ':ClienteID' => $agendamento->ClienteID,
                ':ServicoID' => $agendamento->ServicoID,
                ':FuncionarioID' => $agendamento->FuncionarioID,
                ':DataHoraAgendamento' => $agendamento->DataHoraAgendamento,
                ':Status' => $agendamento->Status
            ]);
        } catch(\PDOException $th) {
            var_dump($th->getMessage());
        }
    }

    public function delete($id) {
        $pdo = MySqlPDO::getInstance();
        $sql = "DELETE FROM Agendamentos WHERE AgendamentoID = :id";
        $stmt = $pdo->prepare($sql);

        try {
            return $stmt->execute([':id' => $id]);
        } catch(\PDOException $th) {
            var_dump($th->getMessage());
        }
    }
}