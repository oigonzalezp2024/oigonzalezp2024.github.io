<?php

interface IArticuloPresentacion
{
    function construirArticulo(int $id);
}

class ArticuloPresentacion implements IArticuloPresentacion
{
    private IArticuloRepository $articuloRepository;
    public function __construct(IArticuloRepository $articuloRepository)
    {
        $this->articuloRepository = $articuloRepository;
    }

    function construirArticulo(int $id)
    {
        $ArticulDTO = $this->articuloRepository;
        [$article, $contenido] = $ArticulDTO->getArticulo($id);

        // 2. Transformar los objetos a un arreglo asociativo (formato para JSON)
        $contenidoArray = array_map(function ($ele) {
            return [
                "type"        => $ele->getType(),
                "data"        => $ele->getData(),
                "url"         => $ele->getUrl(),
                "orientation" => $ele->getOrientation()
            ];
        }, $contenido);

        // 3. Estructurar el array final
        $data = [
            [
                "id"      => $article->getId(),
                "author"  => $article->getAuthor(),
                "date"    => $article->getDate(),
                "content" => $contenidoArray
            ]
        ];
        return $data;
    }
}
