<?php
class MemberRepository {
    private $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function findAll() {
        $st = $this->pdo->query("SELECT * FROM membres ORDER BY id DESC");
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $st = $this->pdo->prepare("SELECT * FROM membres WHERE id = ? LIMIT 1");
        $st->execute([(int)$id]);
        return $st->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data) {
        $st = $this->pdo->prepare("INSERT INTO membres (nom, prenom, etu, photo, bio) VALUES (?, ?, ?, ?, ?)");
        $st->execute([
            $data['nom'] ?? null,
            $data['prenom'] ?? null,
            $data['etu'] ?? null,
            $data['photo'] ?? null,
            $data['bio'] ?? null
        ]);
        return $this->pdo->lastInsertId();
    }

    public function delete($id) {
        $st = $this->pdo->prepare("DELETE FROM membres WHERE id = ?");
        return $st->execute([(int)$id]);
    }
}
