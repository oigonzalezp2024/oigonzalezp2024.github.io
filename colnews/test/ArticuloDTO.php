<?php
require_once "../src/domain/Article.php";
require_once "../src/domain/ArtContenido.php";
require_once "../src/domain/ArtContenido.php";

class ArticuloDTO {
    public function getArticulo() {
        $article = new Article(3, "Julio Santos", "2026-06-26");
        $contenido = [
            new ArtContenido("paragraph", "1este es el contenido de un parrafo", "NA", "NA"),
            new ArtContenido("video", "voto-fusil", "assets/video/duvalier-sanchez-voto-fusil.mp4", "vertical"),
            new ArtContenido("paragraph", "3este es el contenido de un parrafo", "NA", "NA"),
            new ArtContenido("video", "voto-fusil", "assets/video/duvalier-sanchez-voto-fusil.mp4", "vertical"),
            new ArtContenido("video", "voto-fusil", "assets/video/duvalier-sanchez-voto-fusil.mp4", "vertical"),
            new ArtContenido("video", "voto-fusil", "assets/video/duvalier-sanchez-voto-fusil.mp4", "vertical"),
        ];

        return [$article, $contenido];
    }
}

$articuloTest = new ArticuloTest();
$articuloTest->getArticulo();