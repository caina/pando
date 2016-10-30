<div class="aui-item">
      
      
      
        <div id="readme" class="maskable has-edit-access">
            <div class="readme file wiki-content">
  <button type="button" class="btn btn-primary btn-lg help_open" data-modal="modal-2">
  <span class="fa fa-question"></span> Fechar Ajuda
</button>
<br/>
<br/>
<br/>
<h1 id="markdown-header-colocando-varias-linhas-na-configuracao">Colocando várias linhas na configuração</h1>
<div class="codehilite"><pre><span class="p">$</span><span class="nv">configurations</span><span class="x"> = &lt;&lt;&lt;EOT</span>

<span class="x">EOT;</span>
</pre></div>


<h1 id="markdown-header-formato-json-de-configuracao">Formato JSON de Configuração</h1>
<div class="codehilite"><pre>{
          "permission": "Galery.Viagens",
          "path": "viagens/galery",
          "primary_key": "id",
          "table": "galery",
          "layout": "default",
          "module": "galery/galery_model",
          "labels": {
            "description": "Descrição",
            "user_id": "Usuário",
            "id_guide_category":"Categoria",
            "cover_photo":"Foto de Capa",
            "title":"Título",
            "bf_guide":"Viagem",
            "is_featured":"Destaque",
            "date":"Data"
          },
          "remove_list": [
            "id"
          ],
          "possible_actions":[
                "c","r","u","d"
          ],
          "components": {
            "image_galery": {
              "table": "galery_photos",
              "image_field": "image"
            },
             "out_of_form": {
                "onetomany": {
                    "table": "ligacao",
                    "texto_header": "Teste de vinculo",
                    "fieldKey": "id_teste",
                    "myfieldKey": "id"
                },
                "gallery": {
                    "type:": "default"
                }
              }
          },
          "options": {
            "foreign_keys": {
              "bf_guide": {
                "table": "guide",
                "field": "title",
                "insertable":true,
                "label":"Cor",
                "filter": "id"
              }
            },
            "fields": {
              "subtitle":{
                "validation":""
              },
              "date":{
                "plugin": "datepicker"
              },
              "description": {
                "plugin": "ckeditor"
              },
              "is_featured":{
                "plugin":"active_box"
              },
              "cover_photo": {
                "plugin": "single_upload",
                "size": "250x220"
              }
            }
          }
        }
</pre></div>

<h2 id="markdown-header-campo-fields">Tipos de Layout</h2>
<p>Por enquanto temos apenas dois tipos de layputs</p>
<p> Default: simples CRUD</p>
<p> Splip: Coloca todas as telas na mesma, util para pequenos CRUDS</p>
<div class="codehilite"><pre>
  {"layout": "default"}
</pre></div>
<p></p>
<h2 id="markdown-header-campo-fields">Campo Fields</h2>
<p>Fields é a key com as opções do imput, usamos isso pra configurar algumas coisas</p>
<div class="codehilite"><pre> <span class="s2">"fields"</span><span class="o">:</span> <span class="p">{</span>
      <span class="s2">"title"</span><span class="o">:</span><span class="p">{</span>
       <span class="s2">"required"</span><span class="o">:</span><span class="kc">true</span><span class="p">,</span>
       <span class="s2">"mask"</span><span class="o">:</span><span class="s2">"99999"</span><span class="p">,</span>
      <span class="s2">"tooltip"</span><span class="o">:</span><span class="s2">"so pra lembrar que isso existe"</span>
       <span class="p">},</span>
      <span class="s2">"icon"</span><span class="o">:</span> <span class="p">{</span>
        <span class="s2">"plugin"</span><span class="o">:</span> <span class="s2">"single_upload"</span><span class="p">,</span>
        <span class="s2">"size"</span><span class="o">:</span> <span class="s2">"500x600"</span>
      <span class="p">}</span>
    <span class="p">}</span>
</pre></div>


<p>dentro destas opções, plugin é para campos externos
o mask utiliza o bootstrao JASNY</p>
<p>Usando Transient: 
Transient são campos que podem ser colocados na listagem, executando funções ou somento mostrando um dado.
Deve ser colocado como um elemento dentro do objeto</p>
<div class="codehilite"><pre>"transient": [
            {
              "brand_token": {
                "label": "Token da marca",
                "view": "c|r|u|d",
                "eval": "md5('id')"
              }
            }
          ]
</pre></div>


<p>Foreign Keys: É o componente que cria uma pop list na tela vinculando duas telas, se o parametro insertable for mandado como true, 
gera um botão que inseri elementos na outra tabela.</p>
<p>Deve ser adicionado dentro de options</p>
<div class="codehilite"><pre>"foreign_keys": {
              "bf_guide": {
                "table": "guide",
                "field": "title",
                "insertable":true,
                "filter": "id"
              }
</pre></div>


<h1 id="markdown-header-componente-de-1-pra-n">Componente de 1 pra N</h1>
<div class="codehilite"><pre>"components": {
    "out_of_form": {
      "onetomany": {
        "table": "ligacao",
        "texto_header":"Teste de vinculo",
        "fieldKey": "id_teste",
        "myfieldKey": "id"
      }
    }
  },
</pre></div>
<br/>
<h1 id="markdown-header-componente-de-1-pra-n">Galeria de imagens</h1>
<p>
  Esse componente cria uma galeria de imagens, ela mesmo gerencia a tabela de vinculo,
  muito simples de implementar
</p>
<div class="codehilite"><pre>
"components": {
  "out_of_form": {
    "gallery": {
      "type:": "default"
    }
  }
 },
</pre></div>
<br/>
<h1 id="markdown-header-componente-de-1-pra-n">Campos Checkbox</h1>
<p>
 Componente que faz um checkbox entre duas tabelas, ele vai criar a tabela n pra n
 o field title é o que vai ser o titulo, o table é a tabela que tu quer vincular a tabela atual, e o field_display é o campo dentro do 'table' que vai exibir

</p>
<div class="codehilite"><pre>
"components": {
  "out_of_form": {
    "checkbox": {
        "title": "titulo que vai aparecer",
        "table": "blog_category",
        "field_display": "title"
    }
  }
 },
</pre></div>
<br/>


<h1 id="markdown-header-lista-negra">Lista negra</h1>
<p>Remove o campo de tudo</p>
<div class="codehilite"><pre>"blacklist":[
            "brand_id"
          ]
</pre></div>


<h1 id="markdown-header-data-filter">Data filter</h1>
<p>Filtra os dados do select e faz o insert mandar esse dado junto</p>
<div class="codehilite"><pre><span class="x">"data_filter":</span><span class="cp">{</span>
            <span class="s2">"field"</span><span class="o">:</span><span class="s2">"brand_id"</span><span class="o">,</span>
            <span class="s2">"value"</span><span class="o">:</span><span class="s2">"{$brand-&gt;id}"</span>
          <span class="cp">}</span><span class="x"></span>
</pre></div>


<h2 id="markdown-header-componentes-existentes">Componentes existentes:</h2>
<p>É um array de objetos na raiz que adiciona elementos não comuns á forms</p>
<h1 id="markdown-header-combo-options">Combo options</h1>
<p>Combo de checkbox, as tabelas são geradas sozinhas, não se deve mudar este comportamento.</p>
<p>btn_adictional é opcional e cria um botão no rodapé do plugin</p>
<div class="codehilite"><pre>"combo_options": {
    "table": "vehicles_options",
    "label": "Opcionais",
    "btn_adictional":{
        "link":"admin/content/optional",
        "label":"Adicionar novo",
        "icon":"icon-plus icon-white "
    }
}
</pre></div>


<h1 id="markdown-header-image-galery">Image Galery</h1>
<p>Galeria de imagens com upload multiplo. Pode haver um bug que o uploader não está logado na hora de fazer o upload em browsers diferentes
do Chrome.</p>
<div class="codehilite"><pre>"image_galery": {
              "table": "galery_photos",
              "image_field": "image"
            }
</pre></div>


<h2 id="markdown-header-opcoes-de-formuario">Opções de formuário</h2>
<h1 id="markdown-header-seletor-dinamico">Seletor Dinâmico</h1>
<p>Colocar um dropdown customizado num campo:</p>
<div class="codehilite"><pre>"nome_do_campo": {
    "plugin": "dinamic_selector",
    "label":"Tipo",
    "options":{
        "0":"Selecione",
        "1":"Novo",
        "2":"Semi-novo"
    }
}
</pre></div>
            </div>
          
        </div>
    </div>

      <button type="button" class="btn btn-primary btn-lg help_open" data-modal="modal-2">
  <span class="fa fa-question"></span> Fechar Ajuda
</button>