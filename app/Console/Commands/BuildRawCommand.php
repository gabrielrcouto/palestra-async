<?php
namespace App\Console\Commands;

use App\Bob\String\Slug;
use App\Models\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class BuildRawCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'build:raw';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description =  'Constrói os objetos para busca rápida usando uma query em modo raw';

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

        //Pega todos os usuários do banco, transforma a Collection em Array
        $users = $this->getUsers();

        echo 'Processados ' . count($users) . ' usuários' . PHP_EOL;
        echo 'Demorou ' . number_format((microtime(true) - $start) * 1000, 2, ',', '') . 'ms' . PHP_EOL;;
    }

    public function getUsers()
    {
        //Pega todos os usuários do banco, transforma a Collection em Array para
        //ser armazenada em cache
        $users = \DB::table('users')
            ->join('companies', 'users.company_id', '=', 'companies.id')
            ->get();

        //Gera o slug do nome para cada usuário
        foreach ($users as $key => $user) {
            $users[$key] = $user = (array) $user;
            $users[$key]['name'] = explode('-', Slug::generate(strtolower($user['name'])));
        }

        return $users;
    }

}
