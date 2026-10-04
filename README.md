<div align="center">

# APP-CNAB

**Aplicação web para processamento de arquivos bancários FEBRABAN nos layouts CNAB 200 e CNAB 240.**

![PHP](https://img.shields.io/badge/PHP-Legacy-777BB4?logo=php&logoColor=white)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-2.1.4-EF4223?logo=codeigniter&logoColor=white)
![Domain](https://img.shields.io/badge/Domain-Banking%20%2F%20CNAB-0A66C2)

</div>

---

## Contexto

O **APP-CNAB** é um projeto histórico da minha fase de desenvolvimento backend em PHP. Ele foi criado para trabalhar com arquivos bancários estruturados segundo padrões FEBRABAN, especialmente **CNAB 200** e **CNAB 240**.

Mantive o repositório público porque ele mostra um tipo de experiência que projetos de estudo normalmente não demonstram: regras de domínio financeiro, processamento de arquivos de largura fixa e integração de fluxos administrativos em uma aplicação web.

## Funcionalidades encontradas no projeto

A aplicação possui fluxos para:

- processamento de arquivos CNAB 200 e 240;
- bancos;
- contas bancárias;
- empresas e filiais;
- funcionários;
- usuários e autenticação;
- tipos de operação;
- relatórios;
- logs de processamento;
- lançamentos e processos financeiros.

A estrutura inclui implementações específicas para os layouts:

```text
application/models/
├── cnab200.php
├── cnab240.php
├── Model_Cnab_Novo.php
├── model_lancamento_financeiro.php
├── model_relatorio.php
└── ...
```

## Organização

```text
application/
├── controllers/
│   ├── banco.php
│   ├── cnab.php
│   ├── conta_bancaria.php
│   ├── empresa.php
│   ├── relatorio.php
│   └── ...
├── models/
└── views/
```

## Stack original

- PHP
- CodeIgniter 2.1.4
- aplicação web server-rendered
- regras específicas de arquivos FEBRABAN/CNAB

## O que este projeto demonstra

Mesmo sendo legado, ele continua útil no portfólio para mostrar experiência com:

- domínio bancário/financeiro;
- parsing e transformação de arquivos estruturados;
- regras de negócio;
- aplicações administrativas;
- rastreabilidade por logs e relatórios;
- manutenção de sistemas PHP tradicionais.

## Sobre o estado atual

> **Projeto histórico / legado.**

As versões de framework e dependências refletem o período em que a aplicação foi construída. Este repositório não deve ser interpretado como recomendação de stack para um projeto novo.

Hoje eu preservo esse código principalmente como registro da minha trajetória técnica e experiência de domínio.

---

<div align="center">

De PHP e integrações bancárias a arquiteturas modernas em TypeScript — este projeto faz parte dessa trajetória.

</div>
