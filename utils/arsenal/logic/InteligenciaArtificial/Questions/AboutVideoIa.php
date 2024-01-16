<?php
/**
 * Rotinas de Inclusão de Dados
 */

namespace SiLogic\InteligenciaArtificial\Questions;

class AboutVideoIa
{
    public static function objetivo()
    {
        return [
            'Só oferecer videos que os usuários irão gostar muito!'
        ];
    }
    
    public static function resultado()
    {
        return [
            'tags mais Relevantes para o Video'
        ];
    }


    public static function algoritmo()
    {
        return [
            'Verificar cada Usuario que Assistiu',
            'Pegar as Caracteristicas de Cada Usuario e Fazer a Media. (Armazenar Resultados)',
            'Verificar Perguntas'
        ];
    }
}
