<?php
namespace App\Console\Commands;

use App\Bob\String\Slug;
use App\Models\User;
use Illuminate\Console\Command;
use Simple\SHM\Block;
use Symfony\Component\Console\Input\InputOption;

class BuildForkCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'build:fork';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description =  'Constrói os objetos para busca rápida usando processos filhos';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        echo 'Iniciado' . PHP_EOL;

        $start = microtime(true);

        $cacheKey = md5(uniqid(''));
        $totalUsers = User::count();

        $pid = pcntl_fork();

        if ($pid == -1) {
             die('could not fork =(');
        } else if ($pid) {
            // we are the parent

            //É necessário reconectar no banco, os processos vão compartilhar
            //a conexão, o que irá gerar erro
            \DB::reconnect('mysql');

            //Pega os usuários da primeira metade do total
            $users = $this->getUsers($totalUsers / 2, 0);

            //Aguarda o processo filho terminar
            pcntl_wait($status);

            //Faz um merge do que o processo pai e filho processaram
            //Os dados do processo filho estão no cache
            $users = array_merge($users, \Cache::pull($cacheKey));
        } else {
            // we are the child

            //É necessário reconectar no banco, os processos vão compartilhar
            //a conexão, o que irá gerar erro
            \DB::reconnect('mysql');

            //Pega os usuários da segunda metade do total
            $users = $this->getUsers($totalUsers / 2, $totalUsers / 2);

            //Armazena os usuários processados no cache
            \Cache::forever($cacheKey, $users);

            die();
        }

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
