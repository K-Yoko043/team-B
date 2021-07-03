<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = new User;$model->name = 'user01';$model->password = bcrypt('1234');$model->is_admin = true;$model->save();
        $model = new User;$model->name = 'kanta';$model->password = bcrypt('1234');$model->is_admin = false;$model->save();
        $model = new User;$model->name = 'naoya';$model->password = bcrypt('1234');$model->is_admin = false;$model->save();
        $model = new User;$model->name = 'jyungo';$model->password = bcrypt('1234');$model->is_admin = false;$model->save();

    }
}
