<?php

namespace LifeRaft\Core;

class App
{
    private string $path = '/';
    private array $url = [];
    private Request $request;

    public function __construct(
        private array $config = []
    ) {
        $this->request = new Request( app: $this );
        if ($this->request->method() === RequestMethod::OPTIONS)
        {
            http_response_code(HttpStatus::OK()->code());
            exit;
        }
    }

    /**
     * Gets or sets the application configuration.
     */
    public function config(array|null $config = null): array
    {
        if (!empty($config))
        {
            $this->config = $config;
        }

        return $this->config = !is_null($config) ? $config : [];
    }

    public function run(): void
    {
        $this->parseURL();
        $response = ($this->getActivatedController())->handleRequest( url: $this->url );

        header('Content-Type: ' . $response->type());
        http_response_code( response_code: $response->status()->code() );
        echo $response;
        exit;
    }

    private function parseURL(): void
    {
        if (isset($_GET['path']) && !empty($_GET['path']))
        {
            $this->path = $_GET['path'];
        }
        else
        {
            $this->path = '/';
        }

        $this->url = explode('/', $this->path);

        if (empty($this->url) || $this->url[0] == 'index.php')
        {
            $this->url = ['home'];
        }
    }

    /**
     * Returns the requested path.
     * 
     * @return string Returns the requested path.
     */
    public function path(): string
    {
        return $this->path;
    }
    /**
     * Returns the requested url.
     * 
     * @return string Returns the requested url.
     */
    public function url(): array
    {
        return $this->url;
    }

    /**
     * 
     * Returns a `LifeRaft\Core\Controller` that best matches the requested endpoint
     */
    private function getActivatedController(): Controller
    {
        $controller = null;

        # Get route base
        $endpoint = $this->url()[0];

        # Load routes
        $routes = require_once('app/routes.php');
        $route_controller = isset($routes['/']) ? $routes['/'] : LifeRaft\Modules\Home\HomeController::class;

        # If route base matches registered route call controller else call Home controller
        if (isset($routes[$endpoint]))
        {
            $route_controller = $routes[$endpoint];
        }

        return new $route_controller( request: $this->request );
    }
}

?>