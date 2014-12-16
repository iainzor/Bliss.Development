bliss.controller("tests.ResultCtrl", ["$scope", "$sce", "result", function($scope, $sce, result) {
	result.response = $sce.trustAsHtml(result.response);
	
	$scope.result = result;
}]);