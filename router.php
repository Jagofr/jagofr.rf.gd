<?php
    require("./controllers/page.controller.php");
    const GLOBAL_PAGE_CONTROLLER = new PageController();
    /* Creating a PHP Router Class.
     * This class will pull a list of routes from a JSON file located at "/config/routes.json"
     * Then this router will have an additional method to add routes, which takes the parameters "name", "route", "req_type", "controller"; where "controller" is optional.
     * This router will also have the main method to match routes, in which it will compare the route and the request type and match to the list of routes stored in the previously saved array.
     * If no route is found, this router will display a 404 page.
     */
    class Router {
        protected $routes = []; // Initialize Routes Array bucket.
        protected $routesPath = "./config/routes.json";

        // Read JSON file
        private function getRoutes() {
            $json = file_get_contents($this->routesPath);
            $this->routes = json_decode($json, true);
        }

        // Match Route Function
        private function matchRoute() {
            $this->getRoutes();
            $req_route = strtolower($_SERVER['REQUEST_URI']);
            $req_method = $_SERVER['REQUEST_METHOD'];
            //Normalize URL
            $req_route = preg_replace('/\/+/', '/', $req_route);
            //First Check if the user has a request
            if($req_route != "/") {
            
                // Then Check the request type.
                // All "GET" Requests go to the "page.controller.php" controller
                if($req_method == "GET") {
                    // If this is a "GET" request, pass the route name to GLOBAL_PAGE_CONTROLLER.
                    foreach($this->routes as $route)
                    {
                        if($route['route'] == $req_route && $route['controller'] == "/controllers/page.controller.php") {
                            return GLOBAL_PAGE_CONTROLLER->render($route["name"]);
                        }
                    }
                } 
                // Check all routes and match URL
                foreach($this->routes as $route) {
                    if($route["route"] == "/") continue;
                    if(strpos($req_route, $route["route"]) != "" && $route["method"] == $req_method)
                    {
                        $tempView = 
                            "<h1>Route Accessed:</h1>
                            <p>" . 
                                $route["route"] . " - " . $route["name"] . 
                            "</p>
                            <hr>
                            <pre>" . 
                                print_r($route, true) . 
                            "</pre>";
                        return GLOBAL_PAGE_CONTROLLER->preRender($tempView);
                    }
                }
                // If no route is found, return a 404 page.
                return GLOBAL_PAGE_CONTROLLER->render("404");
            }
            else {
                return GLOBAL_PAGE_CONTROLLER->render("home");
            }
        }

        // Get Route Page Name function
        public function getRoutePageName() {
            $this->getRoutes();
            $req_route = strtolower($_SERVER['REQUEST_URI']);
            //Normalize URL
            $req_route = preg_replace('/\/+/', '/', $req_route);
            foreach($this->routes as $route) {
                if(strpos($req_route, $route["route"]) != "" && $route["route"] != "/")
                {
                    return " - " . $route["name"];
                }
            }
        }

        // Render Page function
        public function returnPage() {
            return $this->matchRoute();
        }
    }
?>
