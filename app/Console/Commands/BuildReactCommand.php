<?php
namespace App\Console\Commands;

use App\Bob\String\Slug;
use App\Models\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class BuildReactCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'build:react';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description =  'Constrói os objetos para busca rápida usando queries async com React';

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

        //cria o main loop
        $loop = \React\EventLoop\Factory::create();

        //cria a conexão MySQL
        $connection = new \React\MySQL\Connection($loop, array(
            'dbname' => $_ENV['DB_DATABASE'],
            'user'   => $_ENV['DB_USERNAME'],
            'passwd' => $_ENV['DB_PASSWORD'],
        ));

        $connection->connect(function () {});

        $query1 = '
            SELECT * FROM users
                LEFT JOIN companies ON users.company_id = companies.id
            LIMIT ' . $totalUsers / 2 . '
        ;';

        $query2 = '
            SELECT * FROM users
                LEFT JOIN companies ON users.company_id = companies.id
            LIMIT ' . $totalUsers / 2 . '
            OFFSET ' . $totalUsers / 2 . '
        ;';

        $this->getUsers($connection, $query1, $loop);
        $this->getUsers($connection, $query2, $loop);

        $loop->run();

        echo 'Processados ' . count($this->users) . ' usuários' . PHP_EOL;
        echo 'Demorou ' . number_format((microtime(true) - $start) * 1000, 2, ',', '') . 'ms' . PHP_EOL;;
    }

    public function getUsers($connection, $query, $loop)
    {
        $this->queries++;

        $connection->query($query, function ($command, $conn) use ($loop) {
            if ($command->hasError()) {
                $error = $command->getError();
            } else {
                $results = $command->resultRows;
                $this->users = array_merge($this->users, $this->processUsers($results));
                $this->queries--;

                if ($this->queries == 0) {
                    $loop->stop();
                }
            }
        });
    }

    public function processUsers($users)
    {
        //Gera o slug do nome para cada usuário
        foreach ($users as $key => $user) {
            $users[$key]['name'] = explode('-', Slug::generate(strtolower($user['name'])));
        }

        return $users;
    }

}
