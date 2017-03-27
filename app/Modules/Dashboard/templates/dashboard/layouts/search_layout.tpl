{extends 'dashboard/layouts/dashboard_layout.tpl'}

{block 'heading'}
    <h1 align="center">
        Order search

        <a href="#help_search" class="float-right mmodal">
            <i class="fa fa-question-circle"></i>
        </a>
    </h1>
{/block}

{block 'content'}

    {if !$form_collapse}
        {smarty_admin_block name='Search form'}
                {include 'dashboard/layouts/_search_form_block.tpl'}
        {/smarty_admin_block}
    {else}
        <fieldset class="{if $form_collapse}collapsed-force collapsed{else}expanded{/if}">
        <legend>Order search form</legend>
            {include 'dashboard/layouts/_search_form_block.tpl'}
        </fieldset>
    {/if}

    {if count($models) > 0}
        {*{smarty_admin_block name='Search results'}*}
        <div class="row">
            <div class="columns large-12">
                {raw $pager}
            </div>
        </div>
        <div class="row">
            <div class="columns large-12">
                {if $new_template}
                    {include 'order/orders_list.tpl' orders=$models}
                {else}
                    {include 'order/orders_list_old.tpl' orders=$models}
                {/if}
            </div>
        </div>
        <div class="row">
            <div class="columns large-12">
                {raw $pager}
            </div>
        </div>
        {*{/smarty_admin_block}*}
    {/if}

    <div class="hidden">
        <div id="help_search">
            <h2>Заполнение формы поиска/фильтрации</h2>
            <p>
                На форме доступны поля прямого ввода, <br>
                поля выбора элементов, <br>
                комбинированные поля выбора\ввода значения, <br>
                поля выбора единсвенного значения и отрицающий модификатор для поля.
            </p>
            <ol>
                <li>
                    <h3>Отрицающие поля "Not"\"Not selected" </h3>
                    <p>
                        В самом правом блоке расположены "Галочки" отрицающего значения. <br>
                        Они позволяют инвертировать выбор в поле расположеном правее. <br>
                        "Not" - не выбранное значение или не пустое <br>
                        "Not selected" - Не выбранное значение (для некторых полей проверку на не пустое значение не имеет смысла)
                    </p>
                </li>
                <li>
                    <h3>Поля множественного выбора</h3>
                    <p>
                        Поля с подсказкой и выпадающим списком позволяют выбрать несколько значений. <br>
                        Заказы по данному полю будут выбиратся по условию ИЛИ (пр. Город 'NY' или 'SA').
                    </p>
                </li>
                <li>
                    <h3>Комбинированные поля</h3>
                    <p>
                        Комбинированное поле предлагает функциональность поля "Множественного выбора" <br>
                        Добавляя в него возможность указания произвольного шаблона для ввода. <br>
                        Поле выглядит аналогично полю "Множественного выбора", но имеет подсказку "Start typing for hint"
                        <br>
                        При выборе этого поля и вводе в него не менее 3х символов будут выведены все возможные совпадения, <br>
                        из полученного списка можно выбрать любое количество элементов, а также элемент с префиксом "=>" <br>
                        Элементы с данным префиксом говорят что будут искатся все заказы <br>
                        в которых есть вхождение данной последовательности символов по данному полю.
                    </p>
                </li>
            </ol>

        </div>
    </div>
{/block}