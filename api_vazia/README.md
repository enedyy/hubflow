# API

## Resumo

A Api é feita com PHP puro e com a utilização do **composer** para a utilização do Autoload e trabalhar com classes de maneira mais simples

## Estruturação

A Api é feita de forma simples em 3 camadas, camada de entrada, serviço e base de dados, que estão divididas em três pastas 
<ul>
    <li>
    controller
    </li>
    <li>
    database
    </li>
    <li>
    service
    </li>
</ul>
    <hr>

### Controller
<p>
Essa camada define a porta de entrada da API, atravez do nome das classes que foi definida, vou explicar melhor.
</p>

Primeiramente o nome do **arquivo** e a **classe** tem que ser identicos devido a funcionalidade do **autoload**.

```shel
ProdutoController.php
```
A classe tem que seguir o mesmo nome do arquivo
```php
<?php
    //Não esquecer no namespace

    class ProdutoController{}
```
<hr>
<h4>E como a rota é definida dessa maneira?</h4>

É uma explicação que demandaria algumas linhas, então vou resumir, mas para ewntender melhor ela basta estudar o arquivo **Rest.php** dentro da pasta **controller**. 

Vamos supor que quero acessar minha classe *ProdutoController*, a URL da api deve estar da seguinte maneira
```shell
http://localhost/api/produtos
```
Sim a rota ficaria como **produtos** no plural, o Rest vai pegar tirar a ultima letra da palavra, deixar a primeira letra maiuscula e concatenar com a palavvra Controller, 


***Estou dando o exemplo com a classe ProdutoController mas irá funcionar para qualquer arquivo***

Dentro dessa classe deve se conter uma função publica com o metodo HTTP que deseja acessar, segue exemplo com o metodo GET.

```php
<?php
    //Não esquecer no namespace

    class ProdutoController{
        public function GET(){}
    }
```

Qualquer outra coisa depois dessa primeira barra que define a rota vai ser tratado como parametro e pode ser pego como parametro na função.

***Exemplo:***
```shell
http://localhost/api/produtos/1
```

O **1** vai ser tratado como parametro e pode ser pego da seguinte maneira


```php
<?php
    //Não esquecer no namespace

    class ProdutoController{
        public function GET($parametro = null){
            echo $parametro; //Neste exemplo aqui será printado o 1
        }
    }
```

A variavel dentro da função é declarada dessa maneira, para que não seja obrigatorio sempre estar passando a mesma, assim, quando não tiver mais parametros na URL não irá dar erro. 
```php
    ...($parametro = null){
        ...
    }
```

<h4>Qual a função das Controllers?</h4>

A função das controller além de que são elas que definem as rotas da API, elas definem qual serviço que aquela rota tem que acessar.

```php
<?php
    //Não esquecer no namespace
    // Não esquecer de importar o serviço

    class ProdutoController{
        public function GET($parametro = null){
            $produtoService = new ProdutoService();
            $produtoService->suaFuncao();
        }
    }
```
<hr>

### Service

As classes de serviço, são cada uma das funções que a Api pode executar, seja de buscar, cadastrar, atualizar. Ela trata os dados e chama as classes de manipulaçãod e dados, que serão explicadas mais a frente nesta documentação.

As funções criadas dentro desta classe são utilizadas pelas controllers como foi mostrado no exemploa  cima. Não esquecer que o arquivo e a classe tem que ter o mesmo nome.

```php
<?php
    //Não esquecer no namespace

    class ProdutoService{
        // Exemplo de um serviço
        public function cadastrar(){
            // Aqui pode se usar uma ou mais funções de manipulação ao banco de dados.
        }

        // Mais funções se necessarias ....
    }
```

### Database

Esta pasta contem duas pastas os **Models** e os **DAOs** (Data Access Object | Objeto de Acesso a Dados).

Os Models são nossos modelos de tabelas, são classes simples que utilizamos para mover dados do service para os DAO e retornalos também.

***Model***

```php
<?php
    //Não esquecer no namespace

    class Produto{
        public $id;
        public $nome;
        public $preco;
    }
```

E temos as classe sde manipulação ao banco de dados, elas quer vão abrir a conexão e executar as Querys necessarias.


***DAO***
```php
<?php
    //Não esquecer no namespace

    class ProdutoDAO{
        public function insert(Produto $produto){
            // Toda parte de relacioanda ao banco de dados
        }

        public function delete($id){
            // Seu código para deletar produto aqui ....
        }

        // Mais funções se necessarias ....
    }
```

A API trabalha com o **PDO** para acesso e manipulação a banco de dados, para facilitar foi criado uma classe para sempre retornar a instancia do PDO.

```php
    class MySqlPDO{
        public static function getInstance(){
            // Gera e retorna a instancia com o banco de dados
        }
    }
```

Para utilizar essa classe nos seus arquivos de Acesso a Dados é bem simples seque exemplo.

```php
<?php
    //Não esquecer no namespace
    // Não esquecer de importar a classe

    class ProdutoDAO{
        public function insert(Produto $produto){
            // Pegando a instancia do PDO
            $pdo = MySqlPDO::getInstance();
        }

        public function delete($id){
            // Seu código para deletar produto aqui ....
        }

        // Mais funções se necessarias ....
    }
```

Eles podem retornar os dados para o service chamais mais funções.

## Utils

Dentro da pasta **util** temos a classe **MessagensUtil** que contem varias mensagens de retorno da API para auxilias

```php
<?php

    namespace Api\util;

    class MensagensUtil{}
```

FIM