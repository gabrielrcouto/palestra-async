<!doctype html>
<html lang="en">

	<head>
		<meta charset="utf-8">

		<title>React e Ratchet, async e websockets com PHP</title>

		<meta name="description" content="A framework for easily creating beautiful presentations using HTML">
		<meta name="author" content="Hakim El Hattab">

		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
        <meta name="mobile-web-app-capable" content="yes">

		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui">

		<link rel="stylesheet" href="css/reveal.css">
		<link rel="stylesheet" href="css/theme/white.css" id="theme">

		<!-- Code syntax highlighting -->
		<link rel="stylesheet" href="lib/css/zenburn.css">

		<link rel="stylesheet" href="css/style.css">

		<!-- Printing and PDF exports -->
		<script>
			var link = document.createElement( 'link' );
			link.rel = 'stylesheet';
			link.type = 'text/css';
			link.href = window.location.search.match( /print-pdf/gi ) ? 'css/print/pdf.css' : 'css/print/paper.css';
			document.getElementsByTagName( 'head' )[0].appendChild( link );
		</script>

		<!--[if lt IE 9]>
		<script src="lib/js/html5shiv.js"></script>
		<![endif]-->
	</head>

	<body data-websockets-address="<?php echo $websocketsAddress; ?>" data-mode="<?php echo $mode; ?>">

    <div class="chat" style="display:none">
            <h2>Trolls Gate</h2>

            <div class="messages"></div>

            <p><input type="text" placeholder="Digite aqui uma mensagem" name="message" /></p>

            <div class="button">
                <span>ENVIAR</span>
            </div>

            <div class="ajax" style="display:none;">
                <img src="images/ajax-loader.gif" width="24" height="24">
            </div>
        </div>

		<div class="reveal">
            <div class="jequiti-background" style="display:none;"></div>

            <div class="users-counter"></div>

			<!-- Any section element inside of this container is displayed as a slide -->
			<div class="slides">
                <?php
                    if ($mode == 'presenter') :
                ?>
				<section>
					<h2>http://<?php echo $host; ?></h2>
					<h1>Palestra interativa!</h1>
					<h3>Smartphone / Tablet / Notebook</h3>
					<p><img src="images/navegadores.png" /></p>
					<h4>Aumente o volume!</h4>
				</section>
                <?php
                    else :
                ?>
                <section class="chat-slide">

                </section>
                <?php
                    endif;
                ?>

				<section class="sound-comecar">
					<h1><img src="images/react.png">  <img src="images/ratchet.png"></h1>
					<h1>async e websockets com PHP</h1>
				</section>

				<section>
					<h2>Muito prazer, pode me chamar de Bob!</h2>
					<ul>
						<li>Evangelista PHP - VIVA <span style="color: #2174C0;">PHP SP</span> <span style="color: pink;">CAMPINAS</span>! VIVA <span style="color: #8892BF;">PHP</span>!</li>
						<li>Já fiz mais de 100 sites! \o/</li>
						<li>Trabalho na Memed, fazendo uma plataforma de prescrição médica</li>
						<li>Sou fução desde quando nasci...</li>
					</ul>
				</section>

				<section>
					<h2>Gosto do PHP porque</h2>
					<h1>Nós somos piratas!</h1>
					<p><img src="images/pirate.gif"/></p>
				</section>

				<section>
					<p>Num belo dia, precisei fazer algo assim:</p>
					<p><img src="images/maquina-de-lavar-loucas.png"></p>
				</section>

				<section>
					<p>Buscando usuários:</p>
					<p><img src="images/com-usuarios.png"></p>
				</section>

				<section>
					<pre><code class="php" data-trim>
//Vou querer X usuários que encaixam nessa busca,
//batata grande e uma coca-cola
$usuarios = Usuarios::where('nome', $q)->get();

//Processa a resposta
$usuarios = $this->processarResposta($usuarios);

//Vai que é tua View!
return view('resultado', ['usuarios' => $usuarios]);
					</code></pre>
				</section>

				<section class="poll sound-suspense02" data-number="1">
                    <p>Você faz isso?</p>

                    <div class="button-level" data-value="sim">
                        <span>Sim (<b>0</b>)</span>
                        <div class="level green"></div>
                    </div>

                    <div class="button-level" data-value="não">
                        <span>Não (<b>0</b>)</span>
                        <div class="level red"></div>
                    </div>
                </section>

				<section>
					<pre><code class="php" data-trim>
$usuarios = Usuarios::where('nome', $q)->get();

//Só será executado daqui para frente após
//a resposta do banco de dados (IO) chegar

$usuarios = $this->processarResposta($usuarios);

//Aqui após um processo intensivo de CPU

return view('resultado', ['usuarios' => $usuarios]);
					</code></pre>
				</section>

				<section>
					<h2>Blocking IO</h2>
					<p><img src="images/blocking-io.png"></p>
				</section>

				<section>
					<h2>Preciso ser mais rápido!</h2>
					<p>Vou processar metade dos usuários em paralelo com a outra metade</p>
				</section>

				<section>
					<h2>Node.js</h2>

					<pre><code class="javascript" data-trim>
Usuarios.find({
	where: {nome: q},
	limit: 5000
}).then(function(usuarios) {
	//Só executa aqui quando chegar o resultado
	processarResultado(usuarios);
});

Usuarios.find({
	where: {nome: q},
	offset: 5000,
	limit: 5000
}).then(function(usuarios) {
	//Só executa aqui quando chegar o resultado
	processarResultado(usuarios);
});
					</code></pre>
				</section>

				<section>
					<h2>E no PHP?</h2>
				</section>

				<section class="poll" data-number="2">
                    <p>Você sabe como resolver isso?</p>

                    <div class="button-level" data-value="sim">
                        <span>Sim (<b>0</b>)</span>
                        <div class="level green"></div>
                    </div>

                    <div class="button-level" data-value="não">
                        <span>Não (<b>0</b>)</span>
                        <div class="level red"></div>
                    </div>
                </section>

				<section>
					<h2>Threading</h2>
					<p><img src="images/threading.png"></p>
				</section>

				<section>
					<h2>FORK</h2>
					<p><img src="images/fork.png"></p>
				</section>

				<section class="poll sound-qualresposta" data-number="3">
                    <p>O que um fork faz?</p>

                    <div class="button-level" data-value="1">
                        <span>Cria novo processo, do zero! (<b>0</b>)</span>
                        <div class="level green"></div>
                    </div>

                    <div class="button-level" data-value="2">
                        <span>Cria um clone e copia a memória (<b>0</b>)</span>
                        <div class="level red"></div>
                    </div>

                    <div class="button-level" data-value="3">
                        <span>Cria um clone e usa a mesma memória (<b>0</b>)</span>
                        <div class="level blue"></div>
                    </div>
                </section>

				<section class="jequiti">
					<pre><code class="php" data-trim>
$pid = pcntl_fork();

if ($pid == -1) {
     die('could not fork =(');
} else if ($pid) {
    // I'm your father!

    \DB::reconnect('mysql');
    $users = $this->getUsers($totalUsers / 2, 0);

    //Aguarda o processo filho terminar
    pcntl_wait($status);

    //Faz um merge do que o processo pai e filho processaram
    //Os dados do processo filho estão no cache
    $users = array_merge($users, \Cache::pull($cacheKey));
}
					</code></pre>
				</section>

				<section>
					<pre><code class="php" data-trim>
} else {
    // we are the child
    \DB::reconnect('mysql');

    $users = $this->getUsers($totalUsers / 2, $totalUsers / 2);

    //Armazena os usuários processados no cache
    \Cache::forever($cacheKey, $users);

    die();
}
					</code></pre>
				</section>

				<section>
					<h3>Buscando 10.000 usuários (com ORM)</h3>
					<h2>Forma tradicional: 3063,82ms</h2>
					<h2>Fork: 2029,68ms</h2>
				</section>

				<section>
					<h3>O Fork tem um problema:</h3>
					<h2>Não funciona quando o PHP é executado através do Apache e do NGINX</h2>
				</section>

				<section>
					<h2>Non blocking IO</h2>
					<p><img src="images/non-blocking-io.png"></p>
				</section>

				<section>
					<h1><img src="images/react.png"></h1>
				</section>

				<section>
					<p><img src="images/event-loop.png"></p>
				</section>

				<section>
					<pre><code class="php" data-trim>
//cria o main loop
$loop = \React\EventLoop\Factory::create();

//cria a conexão com o MySQL
$connection = new \React\MySQL\Connection($loop, array(
    'dbname' => $_ENV['DB_DATABASE'],
    'user'   => $_ENV['DB_USERNAME'],
    'passwd' => $_ENV['DB_PASSWORD'],
));

$connection->connect(function () {});
					</code></pre>
				</section>

				<section>
					<pre><code class="php" data-trim>
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
					</code></pre>
				</section>

				<section class="poll" data-number="4">
                    <p>Quem ganhou?</p>

                    <div class="button-level" data-value="1">
                        <span>Forma tradicional (<b>0</b>)</span>
                        <div class="level green"></div>
                    </div>

                    <div class="button-level" data-value="2">
                        <span>React (<b>0</b>)</span>
                        <div class="level red"></div>
                    </div>
                </section>

				<section>
					<h3>Buscando 10.000 usuários (sem ORM)</h3>
					<h2>Forma tradicional: 659,02ms</h2>
					<h2>React MySQL: 1123,89ms</h2>
				</section>

				<section>
					<h3>Por que com REACT não foi melhor?</h3>
					<h2>IO - CPU - CPU</h2>
					<h2>react/mysql v0.2.0</h2>
				</section>

				<section>
					<h2>Calma, o REACT nos deu super poderes!</h2>
				</section>

				<section data-background="#33342D">
					<img src="images/componentes-react.png"/>
				</section>

				<section>
					<h2>Exemplo de servidor</h2>
					<pre><code class="php" data-trim>
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
					</code></pre>
				</section>

				<section>
					<h2>Exemplo de cliente</h2>
					<pre><code class="php" data-trim>
$loop = \React\EventLoop\Factory::create();

$dnsResolverFactory = new \React\Dns\Resolver\Factory();
$dns = $dnsResolverFactory->createCached('8.8.8.8', $loop);

$connector = new \React\SocketClient\Connector($loop, $dns);

$connector->create('127.0.0.1', 1337)->then(function (\React\Stream\Stream $stream) use (&$buffer) {
    $stream->on('data', function ($data, $stream) use (&$buffer) {
        $buffer .= $data;
    });
});

$loop->run();
					</code></pre>
				</section>

				<section class="poll" data-number="5">
                    <p>Quem ganhou?</p>

                    <div class="button-level" data-value="1">
                        <span>Forma tradicional (<b>0</b>)</span>
                        <div class="level green"></div>
                    </div>

                    <div class="button-level" data-value="2">
                        <span>React (<b>0</b>)</span>
                        <div class="level red"></div>
                    </div>
                </section>

				<section>
					<h3>Buscando 10.000 usuários (com ORM)</h3>
					<h2>Forma tradicional: 3063,82ms</h2>
					<h2>Fork: 2029,68ms</h2>
					<h2>REACT Socket: 1910ms</h2>
				</section>

				<section>
					<h2>Eu quero mais!</h2>
					<img src="images/garoto-tang.jpg"/>
				</section>

				<section>
					<h1><img src="images/ratchet.png"></h1>
				</section>

				<section>
                    <h2>RFC 6455 - Dezembro de 2011</h2>
                    <ul>
                        <li class="fragment"><img src="images/google.jpg" width="271" height="96" /></li>
                        <li class="fragment">Em 12 de Setembro de 2011 o Google Chrome 15 já implementava</li>
                        <li class="fragment">The WebSocket Protocol enables two-way communication between a client
   running untrusted code in a controlled environment to a remote host
   that has opted-in to communications from that code.</li>
                    </ul>
                </section>

                <section class="poll" data-number="6">
                    <p>Já usou Websockets?</p>

                    <div class="button-level" data-value="sim">
                        <span>Sim (<b>0</b>)</span>
                        <div class="level green"></div>
                    </div>

                    <div class="button-level" data-value="não">
                        <span>Não (<b>0</b>)</span>
                        <div class="level red"></div>
                    </div>
                </section>

                <section>
                    <img src="images/handshake.png" width="640" height="279" />
                </section>

                <section>
                    <h2>Vantagem de usar WebSockets:</h2>
                    <ul>
                        <li class="fragment">O servidor websocket pode ficar na mesma porta que o HTTP padrão, ele usa campos do cabeçalho HTTP distintos</li>
                        <li class="fragment">Suporta um servidor rodando múltiplos domínios</li>
                        <li class="fragment">Suporta proxies HTTP</li>
                        <li class="fragment">Só envia cabeçalhos HTTP no ato da conexão, depois é dado puro (em UTF-8 ou binário)</li>
                    </ul>
                </section>

                <section>
                    <h2>Socket.io</h2>
                    <h3>Biblioteca para NodeJS de servidor de WebSockets</h3>
                    <p>Funciona bem, mas não é mais Websocket puro, vai muito além!</p>
                </section>

                <section>
                    <h2>Servidor</h2>
                    <pre><code class="php" data-trim>
$controller = new Controller();

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            $controller
        )
    ),
    777
);

$loop = $server->loop;

$loop->addPeriodicTimer(5, function () use ($controller) {
    $controller->sendCounterMessage();
});

$server->run();
                    </code></pre>
                </section>

                <section>
                    <pre><code class="php" data-trim>
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class App implements MessageComponentInterface
{
    public static $connections;
    public static $lastMessage;

    public function __construct()
    {
        self::$connections = new \SplObjectStorage;
    }
                    </code></pre>
                </section>

                <section>
                    <pre><code class="php" data-trim>
public function onOpen(ConnectionInterface $connection)
{
    self::$connections->attach($connection);

    if (isset(App::$lastMessage)) {
        Sender::send(App::$lastMessage, $connection);
    }

    Log::d('Espectador conectado: ' . $connection->resourceId);
}
                    </code></pre>
                </section>

                <section>
                    <pre><code class="php" data-trim>

public function onMessage(ConnectionInterface $connection, $message)
{
    App::$lastMessage = $message;

    foreach (self::$connections as $anotherConnection) {
        if ($anotherConnection !== $connection) {
            Sender::send($message, $anotherConnection);
        }
    }

    Log::d($message);
}
                    </code></pre>
                </section>

                <section>
                    <h2>Padronize seus objetos</h2>
                    <pre><code class="stretch">
class HornMessage extends Message
{
    protected $type = 'horn';

    //getters and setters
}

class SlideMessage extends Message
{
    protected $type = 'slide';
    protected $indexh;
    protected $indexv;
    protected $indexf;

    //getters and setters
}
                    </code></pre>
                </section>

                <section>
                    <h1>Escalabilidade</h1>
                </section>

                <section>
                    <h3>Seu servidor WebSocket somente cuida dos WebSockets</h3>
                </section>

                <section>
                    <img src="images/fluxograma-servidores.png" width="500" height="487"/>
                </section>

                <section>
                    <img src="images/fluxograma-servidores-2.png" width="500" height="512"/>
                </section>

                <section class="sound-tchau">
                    <h2>Tchau pessoal, até a próxima!</h2>
                    <p><a href="https://joind.in/talk/view/14360" target="_blank">Avalie minha palestra! CLIQUE AQUI</a></p>
                    <p><a href="https://twitter.com/gabrielrcouto" target="_blank">@gabrielrcouto</a></p>
                    <h3><a href="http://phpsp.org.br/" target="_blank">PHPSP</a></h3>
                    <h3><a href="https://www.facebook.com/groups/grupo.campinas/" target="_blank">PHPSP Campinas</a></h3>
                    <p><a href="https://github.com/gabrielrcouto/palestra-async" target="_blank">Essa palestra está no GitHub</a></p>
                </section>

                <section>
                    <h2 class="horn counter">0 / 0</h2>

                    <div class="horn button">
                        <span>Buzinar</span>
                    </div>
                </section>
			</div>

		</div>

		<script src="js/plugins/jquery.min.js"></script>
		<script src="lib/js/head.min.js"></script>
		<script src="js/plugins/reveal.js"></script>
        <script src="js/plugins/soundjs-0.6.0.combined.js"></script>

		<script>
            var mode = $('body').attr('data-mode');
            var websocketsAddress = $('body').attr('data-websockets-address');

            var revealConfig = {
                controls: false,
                progress: true,
                history: false,
                keyboard: false,
                overview: false,
                touch: false,
                center: true,
                autoSlideStoppable: false,
                help: false,

                transition: 'slide', // none/fade/slide/convex/concave/zoom

                // Optional reveal.js plugins
                dependencies: [
                    { src: 'lib/js/classList.js', condition: function() { return !document.body.classList; } },
                    { src: 'plugin/markdown/marked.js', condition: function() { return !!document.querySelector( '[data-markdown]' ); } },
                    { src: 'plugin/markdown/markdown.js', condition: function() { return !!document.querySelector( '[data-markdown]' ); } },
                    { src: 'plugin/highlight/highlight.js', async: true, condition: function() { return !!document.querySelector( 'pre code' ); }, callback: function() { hljs.initHighlightingOnLoad(); } },
                    { src: 'plugin/multiplex-ratchet/ratchet.js', async: true },
                    { src: 'js/code.min.js', async: true },
                    //{ src: 'plugin/zoom-js/zoom.js', async: true },
                    //{ src: 'plugin/notes/notes.js', async: true }
                ],

                multiplex: {
                    secret: 'issoehumsegredo',
                    id: 'nossaqueidlegal',
                    url: websocketsAddress
                },
            };

            if (mode == 'presenter') {
                revealConfig.controls = true;
                revealConfig.keyboard = true;
                revealConfig.touch = true;
                revealConfig.dependencies.push({ src: 'plugin/multiplex-ratchet/master.js', async: true });
            } else {
                revealConfig.dependencies.push({ src: 'plugin/multiplex-ratchet/client.js', async: true });
            }

			// Full list of configuration options available at:
			// https://github.com/hakimel/reveal.js#configuration
			Reveal.initialize(revealConfig);
		</script>

	</body>
</html>
