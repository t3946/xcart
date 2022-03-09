<?php

namespace Modules\Core\Commands;

use Modules\User\Models\UserModel;
use Xcart\App\Commands\Command;

class ConvertPasswordsCommand extends Command
{

    public function handle($arguments = [])
    {
        $i = 0;

        while ($users = UserModel::objects()->paginate(++$i, 100)->all()) {
            foreach ($users as $user) {
                
                $old = text_decrypt($user->password);

                $new = password_hash($old, PASSWORD_DEFAULT, []);

                $user->setAttribute('password', $new);
                $user->save();
            }
        }

        echo "DONE\n";
    }
}