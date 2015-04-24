<?php
namespace App\Console\Commands;

use App\Bob\Slides\Controller;
use Illuminate\Console\Command;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Input\InputOption;

class SlidesServerCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'slides:server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description =  'Inicia o servidor de slides';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        echo 'Iniciando servidor...' . PHP_EOL;

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Controller()
                )
            ),
            777
        );

        $server->run();
    }
}
