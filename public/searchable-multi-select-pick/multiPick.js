$.fn.extend({
    multiPick: function (config) {

        var settings = $.extend({
            limit: 2,
            image: false,
            closeAfterSelect: true,
            search: false,
            placeholder: 'Select',
            slim: false
        }, config);

        //Cria o elemento de multilesect        
        let item = $(this);
        let id = $(this).prop('id');        

        let options = $(`#${id} option`);

        let itens = $.map(options, function (option) {
            return option;
        });

        var itemFormat = ``;        

        $.each(itens, function (i, v) {

            console.log($(v));
            if (settings.image === true) {
                itemFormat += `<div class="option-item" data-value="${$(v).val()}" id="${$(v).text()}">        
                                    <div class="image" style="background-image: url(${ $(v).data('img')})" data-image="${ $(v).data('img')}"></div>
                                ${$(v).text()}
                            </div>`;
            } else {
                itemFormat += `<div class="option-item" data-value="${$(v).val()}" id="${$(v).text()}">
                                ${$(v).text()}
                            </div>`;
            }

        });

        var search = ``;
        if (settings.search === true)
            search = `<div class="option-item select" id="search">
                        <input type="text" class="form-control" id="form-search" placeholder="Search">
                    </div>`;

        var slim = '';
        if (settings.slim === true)
            slim = 'slim';

        let stringSetting = JSON.stringify(settings).replaceAll('"', `'`);

        $(item).parent().prepend(`<div class="mutiple-select ${slim}" id="${id}" data-settings="${stringSetting}">
                <div class="main-content">
                    <span>${settings.placeholder}</span>
                    <div class="selected-itens">                          
                    </div>

                    <?xml version="1.0" encoding="utf-8"?>
                    <!-- Generator: Adobe Illustrator 24.2.3, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
                    <svg version="1.1" id="chevron" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                        viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve">                                          
                    <path class="st1" d="M19.24,5.82c-0.34-0.34-0.9-0.34-1.24,0l-7.65,7.65c-0.24,0.24-0.62,0.24-0.86,0L1.83,5.82
                        c-0.34-0.34-0.9-0.34-1.24,0c-0.34,0.34-0.34,0.9,0,1.24l8.35,8.35c0.54,0.54,1.4,0.54,1.94,0l8.35-8.35
                        C19.58,6.72,19.58,6.16,19.24,5.82z"/>
                    </svg>

                </div>

                <div class="itens-list" style="display: none;">
                    ${search}
                    ${itemFormat}
                </div>
            </div>`);

        //Funcionalidade multiselect
        $(window).click(function (e) {
            $('.mutiple-select .itens-list').slideUp('fast');
        });

        $(document).ready(function () {
            //Função abrir ou fechar o select
            $(`#${id}`).click(function (e) {
                e.stopPropagation();
                $(`.mutiple-select:not(#${id}) .itens-list`).slideUp('fast');
                $(`#${id}`).find(`.itens-list`).slideToggle('fast');
            });

            //Seleção de item do select
            $(`#${id}`).find(`.option-item`).click(function (e) {

                
                e.stopPropagation();
                
                let Imagem = $(this).find('.image').data('image');
                let text = $(this).text();
                let value = $(this).data('value');
                
                let itens = $(`#${id}`).find(`.main-content .selected-itens .item`);
                
                let selectedContentWidth = $(`#${id}`).find(`.main-content .selected-itens`).width();

                let same = false;

                //Verifica se o item já existe dentro dos selecionados
                for (var i = 0; i < itens.length; i++) {
                    let item = itens[i];

                    if (value == $(item).data('value'))
                        same = true;
                }                

                let imageblock = '';

                if (settings.image === true) {
                    imageblock = `<div class="image" style="background-image: url(${Imagem})"></div>`;
                }

                if ($(this).prop('id') !== 'search' && itens.length < settings.limit && same === false) {                    
                    $(`#${id}`).find(`.selected-itens`).append(`<div class="item" data-value="${value}">
                                                            ${imageblock}
                                                            ${text}
                                                            <button type="button" class="btn-remove">

                                                                <?xml version="1.0" encoding="utf-8"?>
                                                                <!-- Generator: Adobe Illustrator 24.2.3, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
                                                                <svg version="1.1" id="Camada_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                                    viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve">
                                                                <style type="text/css">
                                                                    .st0{fill:#FFFFFF;}
                                                                </style>
                                                                <g>
                                                                    <path class="st0" d="M19.2,0.8L19.2,0.8c-0.63-0.63-1.66-0.63-2.3,0L10,7.7L3.1,0.8c-0.63-0.63-1.66-0.63-2.3,0l0,0
                                                                        c-0.63,0.63-0.63,1.66,0,2.3L7.7,10l-6.9,6.9c-0.63,0.63-0.63,1.66,0,2.3l0,0c0.63,0.63,1.66,0.63,2.3,0l6.9-6.9l6.9,6.9
                                                                        c0.63,0.63,1.66,0.63,2.3,0l0,0c0.63-0.63,0.63-1.66,0-2.3L12.3,10l6.9-6.9C19.83,2.47,19.83,1.44,19.2,0.8z"/>
                                                                </g>
                                                                </svg>
                                                            
                                                            </button>
                                                        </div>`);
                    if (settings.closeAfterSelect === true)
                        $(`#${id}`).find(`.itens-list`).slideToggle();

                    $(`#${id}`).find(`.btn-remove`).click(function (e) {
                        e.stopPropagation();

                        $(this).parent().remove();

                        let refreshItens = $(`#${id}`).find(`.main-content .selected-itens .item`);
                        let itensWidth = 0;
                        for (var i = 0; i < refreshItens.length; i++) {
                            let item = refreshItens[i];
                            itensWidth += $(item).width() + 13;

                            console.log(selectedContentWidth,  itensWidth);

                            if(selectedContentWidth < itensWidth){
                                console.log('teste');
                                $(item).hide();
                            }else{
                                $(item).show();
                            }
                        } 


                        placeHide();
                    });
                }

                let refreshItens = $(`#${id}`).find(`.main-content .selected-itens .item`);
                let itensWidth = 0;

                for (var i = 0; i < refreshItens.length; i++) {
                    let item = refreshItens[i];
                    itensWidth += $(item).width() + 13;

                    console.log(selectedContentWidth,  itensWidth);

                    if(selectedContentWidth < itensWidth){
                        
                        $(`#${id}`).find(`.main-content .selected-itens`).addClass('more')

                        $(item).hide();
                    }else{
                        $(item).show();
                    }
                } 

                placeHide();
            });

            $(this).find(`.option-item.select`).click(function (e) {
                e.stopPropagation();
            });

            $(`#form-search`).on('keyup', function () {
                let options = $(`#${id}`).find(`.option-item`);

                if ($(`#${id}`).find(`#form-search`).val().length > 0) {
                    for (var i = 0; i < options.length; i++) {
                        let option = options[i];
                        console.log(option);
                        var nome = $(option).prop('id');
                        var expressao = new RegExp(this.value, "i");

                        if (expressao.test(nome)) {
                            if (nome !== 'search')
                                $(option).css('display', 'flex');
                        } else {
                            if (nome !== 'search')
                                $(option).css('display', 'none');
                        }
                    }
                } else {
                    for (var i = 0; i < options.length; i++) {
                        let option = options[i];
                        if (nome !== 'search')
                            $(option).css('display', 'flex');
                    }
                }
            });
        });

        function placeHide() {
            if ($(`#${id}`).find(`.main-content .selected-itens .item`).length === 0) {
                $(`#${id}`).find(`.main-content span`).css('display', 'block');
            } else {
                $(`#${id}`).find(`.main-content span`).css('display', 'none');
            }
        }

        $(this).remove();
    },

    getMultiPick: function () {

        let idItem = $(this).prop('id');

        var value = [];

        let itens = $(`#${idItem}`).find(`.main-content .selected-itens .item`);

        for (var i = 0; i < itens.length; i++) {
            let item = itens[i];

            value.push($(item).data('value'));
        }
        return value;        
    },

    updateMultiPick: function () {
        let id = $(this).prop('id');
        let options = $(`#${id} option`);

        let itens = $.map(options, function (option) {
            return option;
        });

        $(`#${id} option`).remove();

        let itemFormat = ``;

        let settings = JSON.parse($(this).data('settings').replaceAll(`'`, `"`));

        $.each(itens, function (i, v) {            
            console.log($(v).data('img'));
            if (settings.image === true) {
                itemFormat += `<div class="option-item" data-value="${$(v).val()}" id="${$(v).text()}">        
                                    <div class="image" style="background-image: url(${ $(v).data('img')})" data-image="${ $(v).data('img')}"></div>
                                ${$(v).text()}
                            </div>`;
            } else {
                itemFormat += `<div class="option-item" data-value="${$(v).val()}" id="${$(v).text()}">
                                ${$(v).text()}
                            </div>`;
            }

        });

        $(this).find('.itens-list').append(itemFormat);
    }
});
