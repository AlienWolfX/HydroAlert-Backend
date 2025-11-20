<?php
class Controller
{
    protected function render(string $view, array $data = [])
    {
        extract($data, EXTR_SKIP);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "View not found: $view";
        }
    }
}
