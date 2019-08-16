{extends "admin/base_login.tpl"}

{block 'menu_block'}
{/block}

{block 'main_block_class'}wide{/block}

{block 'main_block'}
    <div class="admin-page login-page">
        <div class="login-block">
            <h1>Login</h1>

            <form action="" method="post">
                {raw $form->render()}

                <div class="buttons">
                    <button type="submit" class="button round default">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
{/block}