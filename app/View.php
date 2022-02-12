<?php

declare(strict_types=1);
// this will make our project easy to work with diffrent classes
namespace App;
// here we include the file ViewNotFoundException which is in exception folder

use App\Exceptions\ViewNotFoundException;

class View
{
    public function __construct(
        // varaibles are made protected here so that we can not be able to access them oustide the class
        protected string $view,
        protected array $params = []
    ) {
    }


    // this is static method here 
    public static function make(string $view, array $params = []): static
    {
        return new static($view, $params);
    }

    public function render(): string
    {
        // this wil get the path of file followed by / and filename
        $viewPath = VIEW_PATH . '/' . $this->view . '.php';

        if (! file_exists($viewPath)) {
            // if it  doesnot  exists it will throw the exception
            throw new ViewNotFoundException();
        }

        foreach($this->params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        //  file path is included here which we got above
        include $viewPath;

        return (string) ob_get_clean();
    }

    public function __toString(): string
    {
        // the render function si called here
        return $this->render();
    }

    public function __get(string $name)
    {
        // will return the passed argument  value of function that is name
        return $this->params[$name] ?? null;
    }
}
