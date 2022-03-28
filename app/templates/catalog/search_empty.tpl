{extends  "base.tpl"}

{block "content"}
  <div class="container">
      <div class="row">
          <div class="columns large-12">
              <h1 class="title">{t 'Showing result for'} "{$model|escape}"</h1>
              <article>
                  {t 'We try to search your query across all storefront products, but nothing found. Please specify your request...'}
              </article>
          </div>
      </div>
  </div>
{/block}
