{extends "pages/base_2.tpl"}
{block "content"}
    <article class="page_s_content">
        <section class="heading ro-header">
            <div class="row">
                <div class="column head sas-header ">
                    <div class="hit-header">
                        <img class="sas-lock" src="/static/frontend/images/pages/mail.png"><h1>{$model->name|replace:" ":"<br>"}</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="page-container">
            <div class="row">
                <div class="column large-12">
                    <div class="large-2column">
                        {raw $model->content}
                        <div class="ro_email">
                            <form class="ro_email" method="post" action="{url "retrieve:retrieve_order"}">
                                <input class="ro_input_email" type="email" name="email" placeholder="Your Email" required>
                                <input class="ro_input_submit" type="submit" value="Submit">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </article>
{/block}