angular.module('dbpradha', [])
    .constant('API', {
        'getProducts': 'http://ecs-test.dipenpradhan.com/api/products.php',
        'getUser': 'http://ecs-test.dipenpradhan.com/api/user.php',
        'placeOrder': 'http://ecs-test.dipenpradhan.com/api/placeorder.php'
    })
    .run(function(API, $http, $q, $rootScope) {

        // Models --

        $rootScope.user = {};

        // Functions --
        $rootScope.init = function() {
            return $q(function(resolve) {
                    var userID = prompt('Please enter your user ID:');
                    // var password = prompt('Please enter your user ID:');
                    $http.post(API.getUser, {
                        'username': userID
                    }).success(function(response) {
                        $rootScope.user = response;
                        resolve();
                    }).error(function() {
                        // alert('Couldn\'t identify you.');
                        window.location.href = 'error.html';
                    });
            });
        };

        // Run --
        // Nothing

    })
    .controller('MainCtrl', ['API', '$http', '$rootScope', '$scope', function(API, $http, $rootScope, $scope) {

        // Models --
        $scope.products = [];
        $scope.cart = [];

        // Functions --
        $scope.getProducts = function() {
            $http.post(API.getProducts, {
                    'username': $rootScope.user.email
                })
                .success(function(response) {
                    $scope.products = response.products;
                })
                .error(function() {
                    alert('Error fetching data.');
                });
        };

        $scope.addItem = function(i) {
            $scope.cart.push(angular.copy($scope.products[i]));
        };

        $scope.removeItem = function() {
            $scope.cart = [];
        };

        $scope.placeOrder = function() {
            $http.post(API.placeOrder, {
                'order': {
                    'cart': $scope.cart
                },
                'username': $rootScope.user.email
            }).success(function(response) {
                alert('Order ID: ' + response.order_id);
                window.location.reload();
            }).error(function() {
                alert('Couldn\'t place your order. Please try again.');
                window.location.reload();
            });
        };

        // Run --
        $rootScope.init().then(function() {
            $scope.getProducts();
        });

    }]);
