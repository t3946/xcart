{extends "pages/page_2.tpl"}
{block "custom_content"}
    <section class="heading ro-header">
        <div class="row">
            <div class="column head sas-header ">
                <div class="hit-header">
                    <img class="sas-lock" src="/static/frontend/images/mail.png"><h1>{$model->name|replace:" ":"<br>"}</h1>
                </div>
            </div>
        </div>
    </section>

{/block}

{block "form"}
    <div class="ro_email">
        <form class="ro_email">
            <input class="ro_input_email" type="email" name="email" placeholder="Your Email">
            <input class="ro_input_submit" type="submit" value="Submit">
        </form>
    </div>
{/block}