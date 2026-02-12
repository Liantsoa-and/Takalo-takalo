<?php
class PhotoService
{
    private $pdo;
    private $uploadDir = __DIR__ . '/../../public/uploads/photos/';
    private $maxFileSize = 5 * 1024 * 1024; // 5MB
    private $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        // Créer le répertoire s'il n'existe pas
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    /**
     * Upload les photos pour un objet
     * @param int $objetId
     * @param array $files Tableau $_FILES['photos']
     * @return array URLs des photos uploadées
     */
    public function uploadPhotos($objetId, $files)
    {
        $uploadedPhotos = [];

        if (!isset($files['name'])) {
            return $uploadedPhotos;
        }

        // Vérifier si c'est un tableau (plusieurs fichiers)
        $isMultiple = is_array($files['name']);
        $names = $isMultiple ? $files['name'] : [$files['name']];
        $tmp_names = $isMultiple ? $files['tmp_name'] : [$files['tmp_name']];
        $types = $isMultiple ? $files['type'] : [$files['type']];
        $sizes = $isMultiple ? $files['size'] : [$files['size']];
        $errors = $isMultiple ? $files['error'] : [$files['error']];

        for ($i = 0; $i < count($names); $i++) {
            if ($errors[$i] !== UPLOAD_ERR_OK) {
                continue;
            }

            // Validation
            if ($sizes[$i] > $this->maxFileSize) {
                continue;
            }

            if (!in_array($types[$i], $this->allowedTypes)) {
                continue;
            }

            // Générer un nom unique
            $filename = $this->generateFilename($names[$i]);
            $filepath = $this->uploadDir . $filename;

            // Déplacer le fichier
            if (move_uploaded_file($tmp_names[$i], $filepath)) {
                // Sauvegarder en base de données
                $url = $filename;
                $this->savePhotoUrl($objetId, $url);
                $uploadedPhotos[] = $url;
            }
        }

        return $uploadedPhotos;
    }

    /**
     * Récupérer toutes les photos d'un objet
     * @param int $objetId
     * @return array
     */
    public function getPhotosByObjetId($objetId)
    {
        $st = $this->pdo->prepare("
            SELECT id, url FROM tt_photos_objet 
            WHERE objet_id = ? 
            ORDER BY id ASC
        ");
        $st->execute([(int) $objetId]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer la première photo d'un objet
     * @param int $objetId
     * @return string|null
     */
    public function getFirstPhoto($objetId)
    {
        $st = $this->pdo->prepare("
            SELECT url FROM tt_photos_objet 
            WHERE objet_id = ? 
            LIMIT 1
        ");
        $st->execute([(int) $objetId]);
        $result = $st->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['url'] : null;
    }

    /**
     * Supprimer une photo
     * @param int $photoId
     * @return bool
     */
    public function deletePhoto($photoId)
    {
        // Récupérer l'URL de la photo
        $st = $this->pdo->prepare("SELECT url FROM tt_photos_objet WHERE id = ?");
        $st->execute([(int) $photoId]);
        $photo = $st->fetch(PDO::FETCH_ASSOC);

        if (!$photo) {
            return false;
        }

        // Supprimer le fichier
        $filepath = __DIR__ . '/../../public/uploads/photos/' . $photo['url'];
        if (file_exists($filepath)) {
            unlink($filepath);
        }

        // Supprimer de la base de données
        $st = $this->pdo->prepare("DELETE FROM tt_photos_objet WHERE id = ?");
        $st->execute([(int) $photoId]);

        return true;
    }

    /**
     * Supprimer toutes les photos d'un objet
     * @param int $objetId
     * @return void
     */
    public function deletePhotosByObjetId($objetId)
    {
        $photos = $this->getPhotosByObjetId($objetId);
        foreach ($photos as $photo) {
            $filepath = __DIR__ . '/../../public' . $photo['url'];
            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }

        $st = $this->pdo->prepare("DELETE FROM tt_photos_objet WHERE objet_id = ?");
        $st->execute([(int) $objetId]);
    }

    /**
     * Sauvegarder l'URL de la photo en base de données
     * @param int $objetId
     * @param string $url
     * @return void
     */
    private function savePhotoUrl($objetId, $url)
    {
        $st = $this->pdo->prepare("
            INSERT INTO tt_photos_objet(objet_id, url) 
            VALUES(?, ?)
        ");
        $st->execute([(int) $objetId, (string) $url]);
    }

    /**
     * Générer un nom de fichier unique
     * @param string $originalName
     * @return string
     */
    private function generateFilename($originalName)
    {
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $filename = sha1(time() . mt_rand()) . '.' . $ext;
        return $filename;
    }
}
