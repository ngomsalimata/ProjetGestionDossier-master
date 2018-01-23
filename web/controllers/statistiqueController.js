/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

angular.module('GestionDossier')
        .controller('StatistiqueController', function ($http, $scope) {
            $scope.entites = [];
            $scope.entiteSelectionnee = {'nom': ''};
            $http.get(Routing.generate('user_entite_statistique'))
                    .success(function (data) {
                        $scope.entites = JSON.parse(data);
                    }).error(function () {
                alert('Erreur survenue lors de la recuperation de la liste des entites');
            });
        });
