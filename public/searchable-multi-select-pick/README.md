# jQuery-MultiPick
Seletor multiplo jQuery de facil utilização.

## Requisitos

[jQuery](https://jquery.com/)

## Utilização

Importe os arquivos css e js em seu html.

```html
  <link rel="stylesheet" href="multiPick.css">
  <script src="multipick.js"></script>
```

Usando o selector jQuery faça a chamada para o componente passando os parametros desejados.

```js
  $('#multiPick').multiPick({
        limit: 3,
        image: true,
        closeAfterSelect: false,
        search: true,
        placeholder: 'Select',
        slim: false
    });
```

Para recuperar os dados que foram selecionados use o seletor jQuery e utilize da função: `getMultiPick`.

```js
    let values = $('#multiPick').getMultiPick();
```

## Personalização
Você pode personalizar as cores do seletor dentro do arquivo [Style-config.less](https://github.com/Kaynan13/jQuery-MultiPick/blob/main/Style-config.less)


## Exemplos

![Versão grande do componente](https://github.com/Kaynan13/jQuery-MultiPick/blob/main/img/huge-ex.png)

![Versão pequena do componente](https://github.com/Kaynan13/jQuery-MultiPick/blob/main/img/slim-ex.png)
