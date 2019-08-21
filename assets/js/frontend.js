(function($) {
	window.isEditMode = false;

	$(window).on("elementor/frontend/init", function() {
		window.isEditMode = elementorFrontend.isEditMode();
	});
})(jQuery);

var msshextExpandableEvents = function($scope, $) {
	if (!isEditMode) {
		var $events = $(".elementor-widget-msshext-events", $scope);

		// Load more button
		$scope.on("click", ".elementor-button-wrapper .msshext-events-show-all", function(e) {
			e.preventDefault();
			console.log('click');
			$(this).remove();
			$scope.find('.msshext-events-hidden').removeClass('msshext-events-hidden');
		});
	}
};

jQuery(window).on("elementor/frontend/init", function() {
	elementorFrontend.hooks.addAction(
		"frontend/element_ready/msshext-events.default",
		msshextExpandableEvents
	);
});
