# Restrição do Cliente

## Instalação

Para baixar o módulo via composer, execute o código abaixo.

```sh
  composer require magezil/module-customer-block
```

### Requisitos do Sistema

> **Versão mínima:** 2.O
>
> **Versão desenvolvida:** 2.3
>
> **Versão do PHP:** 7.0
>
> **Versão estável**: 1.0
>
> **Licença:** OSL-3.0/AFL-3.0

---

## Admin do Magento

Este módulo adiciona atributos que podem retringir ou não os clientes individualmente para as seguintes áreas:

- Restrição para entrar com o usuário
- Restrição para comprar
- Restrição para adicionar itens a lista de desejos
- Restrição para adicionar itens a lista de comparação
- Restrição para adicionar avaliações aos produtos

![ScreenShot](https://github.com/santanaluc94/Magezil_CustomerBlock/blob/master/Readme/Images/pt_BR/magezil-modulo.jpg)

Para habilitar o módulo siga os passos abaixo:
  - **Passo 1:** Magento admin --> Lojas --> Configurações --> Configuração
  - **Passo 2:** Tab _Magezil_ --> Seção _Restrição de Cliente_ --> Grupo _Configurações Gerais_
  - **Passo 3:** Habilitar Módulo = Sim

![ScreenShot](https://github.com/santanaluc94/Magezil_CustomerBlock/blob/master/Readme/Images/pt_BR/configuracoes-gerais.jpg)

### Restrição

Para retringir alguma das funcionalidades do cliente siga os passos abaixo:
  - **Passo 1:** Magento admin --> Clientes --> Todos os Clientes --> _Cliente_
  - **Passo 2:** Informações da conta --> _Selecione a Funcionalidade_
  - **Passo 3:** Sim/Não

### Valor Padrão

Este módulo fornece configurações no admin para definir os valores padrões no cadastro do cliente:
  - **Cliente Bloqueado**: Todos os clientes, ao se cadastrarem, conseguirão entrar com o seu usuário na loja?
  - **Cliente Pode Comprar**: Todos os clientes, ao se cadastrarem, conseguirão adicionar itens ao carrinho e comprar esses itens?
  - **Cliente Possui Lista de Desejos**: Todos os clientes, ao se cadastrarem, conseguirão adicionar itens a Lista de Desejos
  - **Cliente Possui Lista de Comparação**: Todos os clientes, ao se cadastrarem, conseguirão adicionar itens a Lista de Comparação
  - **Cliente Pode Avaliar Produtos**: Todos os clientes, ao se cadastrarem, conseguirão avaliar produtos?

![ScreenShot](https://github.com/santanaluc94/Magezil_CustomerBlock/blob/master/Readme/Images/pt_BR/valor-padrao.jpg)

> Para mais informações consulte a Wiki do repositório.
