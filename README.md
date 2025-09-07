# Eventos Plugin

Plugin WordPress para criar, gerir e apresentar eventos de forma simples.

## Funcionalidades

- Custom Post Type `Eventos`  
- Campos personalizados via ACF:
  - Data do evento
  - Local
  - Organizador
- Imagem de destaque do evento  
- Shortcode `[eventos_futuros]` para listar eventos futuros:
  - Grelha responsiva: 3 colunas (desktop), 2 colunas (tablet), 1 coluna (mobile)  
  - Bloco clicável  
  - Ícones do Bootstrap para data, local e organizador  
- Template single para cada evento:
  - Imagem à esquerda; título, data, local e organizador à direita  
  - Conteúdo do evento abaixo  
  - Botões “Anterior” e “Próximo” para navegar entre eventos  
- Estilos com Bootstrap e Montserrat  

## Instalação

1. Fazer upload da pasta `eventos-plugin` para `wp-content/plugins/`  
2. Ativar o plugin no WordPress  
3. Certificar-se de que o plugin **Advanced Custom Fields** está ativo  
4. Criar eventos via menu “Eventos” no WordPress  
5. Inserir o shortcode `[eventos_futuros]` na página desejada  

## Exemplo de uso do shortcode

```php
[eventos_futuros limite="6"]
