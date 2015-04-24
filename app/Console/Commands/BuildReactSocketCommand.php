<?php
namespace App\Console\Commands;

use App\Bob\String\Slug;
use App\Models\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class BuildReactSocketCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'build:socket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description =  'Constrói os objetos para busca rápida usando queries async via Socket com React';

    protected $users = [];

    protected $queries = 0;

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
        $buffer = '';

        $loop = \React\EventLoop\Factory::create();

        $dnsResolverFactory = new \React\Dns\Resolver\Factory();
        $dns = $dnsResolverFactory->createCached('8.8.8.8', $loop);

        $connector = new \React\SocketClient\Connector($loop, $dns);

        $connector->create('127.0.0.1', 1337)->then(function (\React\Stream\Stream $stream) use (&$buffer) {
            $stream->on('data', function ($data, $stream) use (&$buffer) {
                $buffer .= $data;
            });
        });

        $loop->nextTick(function() use ($totalUsers) {
            $this->users = $this->getUsers($totalUsers / 2, 0);
        });

        $loop->run();

        $buffer = unserialize($buffer);
        $this->users = array_merge($this->users, $buffer);

        echo 'Processados ' . count($this->users) . ' usuários' . PHP_EOL;
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
