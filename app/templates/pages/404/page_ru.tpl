{extends "pages/base.tpl"}
{block 'content'}
    <section class="error-data-404">
        <div class="info">
            <h1>404'ed</h1>
            <p class="text">Запрошенная страница не может быть найдена.<br/>
                Вернитесь на <a href="/">главную страницу</a> или выполните поиск
                выше.</p>
        </div>
        <div class="picture404">
            <div class="show-for-sr">404</div>
        </div>
    </section>
{/block}