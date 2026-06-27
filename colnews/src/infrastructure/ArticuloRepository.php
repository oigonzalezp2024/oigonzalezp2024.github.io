<?php

interface IArticuloRepository {
    public function getArticulo(int $id);
}

class ArticuloRepositoryMySQL implements IArticuloRepository {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getArticulo(int $id): array {
        // 1. Obtener el artículo
        $stmtArt = $this->db->prepare("SELECT id, autor, fecha_publicacion FROM articulos WHERE id = :id");
        $stmtArt->execute(['id' => $id]);
        $data = $stmtArt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            throw new Exception("Artículo no encontrado.");
        }

        $article = new Article($data['id'], $data['autor'], $data['fecha_publicacion']);

        // 2. Obtener los contenidos asociados
        $stmtCont = $this->db->prepare("SELECT tipo, texto, url_recurso, formato 
                                        FROM art_contenidos 
                                        WHERE articulo_id = :articulo_id 
                                        ORDER BY id ASC"); // Puedes usar el campo 'orden' si lo prefieres
        $stmtCont->execute(['articulo_id' => $id]);
        
        $contenido = [];
        while ($row = $stmtCont->fetch(PDO::FETCH_ASSOC)) {
            $contenido[] = new ArtContenido(
                $row['tipo'], 
                $row['texto'], 
                $row['url_recurso'], 
                $row['formato']
            );
        }

        return [$article, $contenido];
    }
}

class ArticuloRepositoryTest implements IArticuloRepository {
    public function getArticulo(int $id) {
        $article = new Article(3, "Julio Santos", "2026-06-26");
        $contenido = [
            new ArtContenido("paragraph", "1rrrrrrrrrrreste es el contenido de un parrafo", "NA", "NA"),
            new ArtContenido("video", "voto-fusil", "assets/video/duvalier-sanchez-voto-fusil.mp4", "vertical"),
            new ArtContenido("paragraph", "3este es el contenido de un parrafo", "NA", "NA"),
            new ArtContenido("video", "voto-fusil", "assets/video/duvalier-sanchez-voto-fusil.mp4", "vertical"),
            new ArtContenido("video", "voto-fusil", "assets/video/duvalier-sanchez-voto-fusil.mp4", "vertical"),
            new ArtContenido("video", "voto-fusil", "assets/video/duvalier-sanchez-voto-fusil.mp4", "vertical"),
        ];

        return [$article, $contenido];
    }
}