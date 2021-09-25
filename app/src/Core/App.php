<?php

namespace LifeRaft\Core;

class App
{
    private string $path = '/';
    private array $url = [];

    public function __construct(
        private array $config = []
    ) {}

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
        $this->parse_url();
    }

    private function parse_url(): void
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
            $this->url = ['/'];
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
}

?>