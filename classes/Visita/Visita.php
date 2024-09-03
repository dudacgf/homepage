<?php

Namespace Shiresco\Homepage\Visita;

class Visita {
    var $idGo;
    var $idElemento;
    var $dataVisita;

    public function inserir() {
        global $global_hpDB;
        if (!$this->idElemento)
            throw new Exception("Não posso inserir sem saber qual Link foi visitado!");

        $_sql = $global_hpDB->prepare("INSERT INTO hp_visitas (idElemento) VALUES (?)");
        $_sql->bind_param("i", $this->idElemento);

        if (!$_sql->execute())
            throw new Exception("Erro ao gravar visita: $_sql->error");

        return $global_hpDB->getLastInsertId();
    }

    static function totalLinks($intervalo = '7') {
        global $global_hpDB;

        // pega o total de links diferentes visitados
        $_sql = $global_hpDB->prepare('SELECT COUNT(*) as totalLinks FROM (
          SELECT count(*)
            FROM hp_visitas
           WHERE dataVisita > NOW() + INTERVAL -? DAY
           GROUP BY idElemento) as temp');
        $_sql->bind_param('i', $intervalo);

        if (!$_sql->execute())
            throw new Exception("Erro ao ler contagem de visitas: $_sql->error");

        return $_sql->get_result()->fetch_assoc()['totalLinks'];
    }

    static function lerContagem($intervalo = '7', $numResultados = 5) {
        global $global_hpDB;

        // le a contagem agrupada por link visitado
        $_sql = $global_hpDB->prepare('
          SELECT e.descricaoElemento, count(*) as NumVisitas,
                 Max(V.dataVisita) as ultimaVisita, Min(V.dataVisita) as primeiraVisita
            FROM hp_visitas V, hp_elementos e
           WHERE V.idElemento = e.idElemento
             AND V.dataVisita > NOW() + INTERVAL -? DAY
           GROUP BY V.idElemento
           ORDER BY numVisitas DESC, ultimaVisita DESC
           LIMIT ?;');
        $_sql->bind_param('ii', $intervalo, $numResultados);

        if (!$_sql->execute())
            throw new Exception("Erro ao ler contagem de visitas: $_sql->error");

        $result = $_sql->get_result();
        while ($_line = $result->fetch_assoc())
            $_lines[] = $_line;

        return $_lines;

    }
}

