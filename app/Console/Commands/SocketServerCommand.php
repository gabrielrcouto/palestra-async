<?php
namespace App\Console\Commands;

use App\Bob\String\Slug;
use App\Models\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class SocketServerCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'socket:server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description =  'Envia objetos para busca rápida através de um servidor socket';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        echo 'Iniciando servidor...' . PHP_EOL;
        $totalUsers = User::count();

        $loop = \React\EventLoop\Factory::create();

        $socket = new \React\Socket\Server($loop);

        $socket->on('connection', function ($conn) use ($totalUsers) {
            echo 'Enviando mensagem...' . PHP_EOL;
            $users = $this->getUsers($totalUsers / 2, $totalUsers / 2);
            $conn->end(serialize($users));
            echo 'Enviada' . PHP_EOL;
        });

        $socket->listen(1337);

        $loop->run();
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
