<?php
/** 
 * Factory (Fábrica)
Um dos padrões de design mais utilizados é o padrão “Factory” (Fábrica). Através dele uma classe simplesmente cria o objeto que você gostaria de usar. Considere o seguinte exemplo desse padrão de design:

<?php
class Automobile
{
    private $vehicle_make;
    private $vehicle_model;

    public function __construct($make, $model)
    {
        $this->vehicle_make = $make;
        $this->vehicle_model = $model;
    }

    public function get_make_and_model()
    {
        return $this->vehicle_make . ' ' . $this->vehicle_model;
    }
}

class AutomobileFactory
{
    public static function create($make, $model)
    {
        return new Automobile($make, $model);
    }
}

// Solicita a "Factory" que crie o objeto Automobile
$veyron = AutomobileFactory::create('Bugatti', 'Veyron');

print_r($veyron->get_make_and_model()); // imprime "Bugatti Veyron"
Esse código usa uma “Factory” para criar o objeto do tipo “Automobile”. Existem dois possíveis benefícios para criar seu código dessa forma, o primeiro é que se você precisar mudar, renomear ou substituir a classe Automobile futuramente você pode fazer e só terá que modificar o código na “Factory”, em vez de em todos os lugares do seu projeto onde você usa a classe Automobile. O segundo benefício possível é que caso a criação do objeto seja um processo complicado, você pode executar todo esse trabalho na factory, em vez de repetí-lo toda vez que precisar criar uma nova instância da classe.

Usar o padrão de “Factory” não é sempre necessário (ou esperto). O código de exemplo usado aqui é tão simples que essa “Factory” estaria simplesmente adicionando complexidade indesejada. Entretanto se você estiver realizando um projeto um pouco maior ou mais complexo você pode se salvar de muitos problemas com o uso do padrão “Factory”.

Padrão “Factory” na Wikipedia
 */