<?php

Namespace Shiresco\Homepage\Fortunes;

class Fortunes {

    static function processarArquivoQuote($file) {
        $fd = @fopen($file, "r");
        if (!$fd)
            throw new Exception("File error: $file");

        $totalQuotes = $totalCreated = $totalFailSize = $totalFailExists = $totalFailNull = 0;
        $aQuote = '';
        while (!feof($fd)) {
            $line = fgets($fd);

            if ($line[0] == "%") {
                $f = new Fortune();

                $posSeparador = strpos($aQuote, '--');
                if ($posSeparador) {
                    $f->textoQuote = substr($aQuote, 0, $posSeparador-1);
                    $f->autorQuote = substr($aQuote, $posSeparador+2);
                } else    {
                    $f->textoQuote = $aQuote;
                    $f->autorQuote = '';
                }
                $f->textoQuote = trim(preg_replace(array('/\r\n/', '/\n\r/', '/\n/',), '', $f->textoQuote));
                $f->autorQuote = trim(preg_replace(array('/\r\n/', '/\n\r/', '/\n/',), '', $f->autorQuote));

                switch ($f->inserir()) {
                    case OKCREATE:
                        $totalCreated++;
                        break;
                    case FAILSIZE:
                        $totalFailSize++;
                        break;
                    case FAILEXISTS;
                        $totalFailExists++;
                        break;
                    case FAILNULL;
                        $totalFailNull++;
                        break;
                }
                $totalQuotes++;
                $aQuote = '';
            } else {
                $aQuote .= $line;
            }
        }

        fclose($fd);

        return array('totalQuotes' => $totalQuotes, 
                'totalCreated' => $totalCreated,
                'totalFailSize' => $totalFailSize,
                'totalFailNull' => $totalFailNull,
                'totalFailExists' => $totalFailExists);
    }

    public static function getCount()
    {
        global $global_hpDB;

        $_sql = "select count(*) as nFortunes from hp_fortunes where 1 = 1";
        $result = $global_hpDB->query($_sql);
        if (!$result)
            return false;
        else 
            return $result[0]['nFortunes'];
    }

}
?>

