angular.module('dbpradha-admin', ['ngRoute'])
        .constant('API', {
            getAdmin: 'http://localhost/api/admin.php',
            getOrders: 'http://localhost/api/orders.php',
            getOrder: 'http://localhost/api/orders.php',
            approveOrder: 'http://localhost/api/approveorder.php'
        })
        .config(function ($routeProvider) {
            $routeProvider
                    .when('/orders/:type', {
                        templateUrl: 'orders.html',
                        controller: 'OrdersCtrl'
                    })
                    .when('/order/:id', {
                        templateUrl: 'order.html',
                        controller: 'OrderCtrl'
                    })
                    .when('/', {
                        redirectTo: '/orders/all'
                    })
                    .otherwise({
                        redirectTo: '/orders'
                    });
        })
        .run(function (API, $http, $q, $rootScope) {

            // Models --
            $rootScope.admin = {};



            // Functions --
            $rootScope.init = function () {
                return $q(function (resolve) {
                    
                    var adminID = prompt('Please enter your admin ID:');
                    $http.post(API.getAdmin, {'username': adminID}).success(function (response) {
                        $rootScope.admin = response;
                        resolve();
                    }).error(function () {
                        
                        window.location.href = 'error.html';
                    });
                });
            };

            // Run --
            // Nothing

        })
        .controller('OrderCtrl', ['API', '$http', '$routeParams', '$scope', function (API, $http, $routeParams, $scope) {
                // Models --
                $scope.order = {};

                // Functions --
                $scope.getOrder = function (id) {
                    $http.get(API.getOrder, {params: {'id': id}}).success(function (response) {
                        $scope.order = response.orders[0];
                    }).error(function () {
                        alert('Couldn\'t fetch order.');
                    });
                };

                $scope.approveOrder = function (id) {
                    $http.get(API.approveOrder, {params: {order_id: id}}).success(function () {
                        
                        window.location.href = 'http://localhost/admin/#/orders/processed';
                    }).error(function () {
                        alert('Couldn\'t approve.');
                    });
                };

                // Run --

                if ($routeParams.id) {
                    $scope.getOrder($routeParams.id);
                }


            }])
        .controller('OrdersCtrl', ['API', '$http', '$location', '$routeParams', '$scope', function (API, $http, $location, $routeParams, $scope) {

                // Models --
                $scope.orders = [];

                // Functions --
                $scope.getOrders = function (type) {
                    $http.get(API.getOrders, {params: {'type': type}}).success(function (response) {
                        $scope.orders = response.orders;
                    }).error(function () {
                        alert('Couldn\'t fetch orders.');
                    });
                };

                $scope.approveOrder = function (id) {
                    $http.get(API.approveOrder, {params: {order_id: id}}).success(function () {
                        window.location.href = 'http://localhost/admin/#/orders/processed';
                    }).error(function () {
                        alert('Couldn\'t approve.');
                    });
                };

                // Run --
                if ($routeParams.type === 'processed' || $routeParams.type === 'pending' || $routeParams.type === 'all') {
                    $scope.getOrders($routeParams.type);
                } else {
                    $location.path('/orders/all');
                }

            }])
.controller('MainCtrl', ['API', '$http', '$rootScope', '$scope', function(API, $http, $rootScope, $scope) {

        
        // Run --
        $rootScope.init().then(function() {
            // $scope.getProducts();
        });

    }]);