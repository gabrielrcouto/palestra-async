<?php
namespace App\Console\Commands;

use App\Bob\String\Slug;
use App\Models\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class BuildNormalCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'build:normal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description =  'Constrói os objetos para busca rápida da maneira convencional';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        echo 'Iniciado' . PHP_EOL;

        $start = microtime(true);
        $totalUsers = User::count();

        //Pega todos os usuários do banco, transforma a Collection em Array para
        //ser armazenada em cache
        $users = $this->getUsers($totalUsers, 0);

        echo 'Processados ' . count($users) . ' usuários' . PHP_EOL;
        echo 'Demorou ' . number_format((microtime(true) - $start) * 1000, 2, ',', '') . 'ms' . PHP_EOL;;
    }

    public function getUsers($limit, $skip)
    {
        //Pega todos os usuários do banco, transforma a Collection em Array para
        //ser armazenada em cache
        $users = User::with('company')->take($limit)->skip($skip)->get()->toArray();

        //Gera o slug do nome para cada usuário
        foreach ($users as $key => $user) {
            $users[$key]['name'] = explode('-', Slug::generate(strtolower($user['name'])));
        }

        return $users;
    }

}
