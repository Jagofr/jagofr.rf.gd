<?php

class PageController {
    public function preRender (string $routeView) {
        $viewHeader = file_get_contents("./views/shared/header.view.php");
        $viewFooter = file_get_contents("./views/shared/footer.view.php");

        $finalView = $viewHeader . $routeView . $viewFooter;
        return $finalView;
    }
    public function render(string $routeName) {
        // Pre-render logic here
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            // error was suppressed with the @-operator
            if (0 === error_reporting()) {
                return false;
            }
            
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });
        if($routeName == "404") {
            http_response_code(404);
        }
        try {
            $routeViewFilePath = "./views/" . strtolower($routeName) . ".view.php";
            $routeView = file_get_contents($routeViewFilePath);
            return $this->preRender($routeView);
        }
        catch (Exception $e) {
            $routeView = file_get_contents("./views/418.view.php");
            http_response_code(418);
            $errorMsg = "<hr>
                    <h4>Error Message: </h4> 
                    <pre>" . 
                        $e->getMessage() . 
                    "</pre>
                    <h5> Error Stack Trace: </h5>
                    <pre>" .
                        $e->getTraceAsString() .
                    "</pre><br><hr>";
            return $this->preRender($routeView . $errorMsg);
        }
    }
}

?>