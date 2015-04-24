<?php
namespace App\Console\Commands;

use App\Bob\String\Slug;
use App\Models\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class ChildReactCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'child:react';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description =  '(Processo filho) Constrói os objetos para busca rápida usando queries async com React';

    protected $users = [];

    protected $queries = 0;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $users = $this->getUsers($this->argument('limit'), $this->argument('offset'));

        echo serialize($users);
    }

    public function getArguments()
    {
        return [
            ['limit', InputArgument::REQUIRED, 'Limit'],
            ['offset', InputArgument::REQUIRED, 'Offset']
        ];
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
