<?php
namespace App\Console\Commands;

use App\Bob\String\Slug;
use App\Models\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class BuildChildCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'build:child';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description =  'Constrói os objetos para busca rápida usando processos filhos';

    protected $users = [];

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

        $loop = \React\EventLoop\Factory::create();

        $this->getUsers($loop, $totalUsers / 4, 0);
        $this->getUsers($loop, $totalUsers / 4, ($totalUsers / 4) * 1);
        $this->getUsers($loop, $totalUsers / 4, ($totalUsers / 4) * 2);
        $this->getUsers($loop, $totalUsers / 4, ($totalUsers / 4) * 3);

        $loop->run();

        echo 'Processados ' . count($this->users) . ' usuários' . PHP_EOL;
        echo 'Demorou ' . number_format((microtime(true) - $start) * 1000, 2, ',', '') . 'ms' . PHP_EOL;
    }

    public function getUsers($loop, $limit, $offset)
    {
        $process = new \React\ChildProcess\Process('php artisan child:react ' . $limit . ' ' . $offset);

        $data = '';

        $process->on('exit', function($exitCode, $termSignal) use (&$data) {
            $this->users = array_merge($this->users, unserialize($data));
        });

        $loop->addTimer(0.001, function($timer) use ($process, &$data) {
            $process->start($timer->getLoop());

            $process->stdout->on('data', function($output) use (&$data) {
                $data .= $output;
            });
        });
    }
}
