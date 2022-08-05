{{-- <div ng-app="backPackTableApp" 
     ng-controller="TuitionDetailsController">
		
	<table class="table">
		<thead>
			<th></th>
			<th>Cash</th>
			<th>Semi Annual</th>
			<th>Quarterly</th>
			<th>Monthly</th>
		</thead>
		<tbody>
			<tr>
				<td>Mandatory Fee</td>
				<td><% data.mandatory.cash | number %></td>
				<td><% data.mandatory.semi_annual | number %></td>
				<td><% data.mandatory.quarterly | number %></td>
				<td><% data.mandatory.monthly | number %></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td><b>Grand Total</b></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</tbody>
	</table>
</div>

@push('crud_fields_scripts')
<script>

    window.angularApp = window.angularApp || angular.module('backPackTableApp', ['ui.sortable'], function($interpolateProvider){
        $interpolateProvider.startSymbol('<%');
        $interpolateProvider.endSymbol('%>');
    });


    window.angularApp.controller('TuitionDetailsController', function($scope, $timeout, $rootScope, TuitionService){

    	var data = TuitionService.data;
    	$rootScope.$on('user:updated', function() {
    		$scope.data = TuitionService.data;
    		console.log("ASD");
    	});
		$scope.$watch('data', function(val, key) {
			$scope.data = TuitionService.data;
		})
    });

    

</script>
@endpush --}}