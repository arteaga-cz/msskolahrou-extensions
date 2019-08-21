(function($) {
	window.isEditMode = false;

	$(window).on("elementor/frontend/init", function() {
		window.isEditMode = elementorFrontend.isEditMode();
	});
})(jQuery);
;var msshextExpandableEvents = function($scope, $) {
	if (!isEditMode) {
		var $gallery = $(".wpupee-filterable-gallery-container", $scope),
			$settings = $gallery.data("settings"),
			$gallery_items = $gallery.data("gallery-items"),
			$layout_mode =
			$settings.grid_style == "masonry" ? "masonry" : "fitRows",
			$gallery_enabled =
			$settings.gallery_enabled == "yes" ? true : false;

		// init isotope
		var $isotope_gallery = $gallery.isotope({
			itemSelector: ".gallery-item",
			layoutMode: $layout_mode,
			percentPosition: true,
			stagger: 30,
			//transitionDuration: $settings.duration + "ms",
			transitionDuration: "500ms",
			filter: $(
				".wpupee-filterable-gallery-filter .filter-item.active",
				$scope
			).data("filter")
		});

		// layout Isotope after each image loads
		$isotope_gallery.imagesLoaded().progress( function( instance, image ) {
			var result = image.isLoaded ? 'loaded' : 'broken';
			$isotope_gallery.isotope('layout');
		});

		// layout gal, on click tabs
		$isotope_gallery.on("arrangeComplete", function() {
			$isotope_gallery.isotope("layout");
		});

		// layout gal, after window loaded
		$(window).on("load", function() {
			$isotope_gallery.isotope("layout");
		});

		// filter
		$scope.on("click", ".filter-item", function() {
			var $this = $(this),
				$filterValue = $this.data("filter");

			$this.siblings().removeClass("active");
			$this.addClass("active");
			$isotope_gallery.isotope({
				filter: $filterValue
			});
		});

		// Load more button
		$scope.on("click", ".wpupee-filterable-gallery-load-more-button-wrapper .wpupee-button", function(e) {
			e.preventDefault();

			var $this = $(this),
				$init_show = $(
					".wpupee-filterable-gallery-container",
					$scope
				).children(".gallery-item").length,
				$total_items = $gallery.data("total-gallery-items"),
				$images_per_page = $gallery.data("images-per-page"),
				$nomore_text = $gallery.data("nomore-item-text"),
				$items = [];

			if ($init_show == $total_items || $images_per_page >= $total_items) {
				$this.html(
					'<div class="no-more-items-text">' + $nomore_text + "</div>"
				);
				setTimeout(function() {
					$this.fadeOut("slow");
				}, 600);
			}

			// new items html
			for (var i = $init_show; i < $init_show + $images_per_page; i++) {
				$items.push($($gallery_items[i])[0]);
			}

			// append items
			$gallery.append($items);
			$isotope_gallery.isotope("appended", $items);
			$isotope_gallery.imagesLoaded().progress( function( instance, image ) {
				var result = image.isLoaded ? 'loaded' : 'broken';
				$isotope_gallery.isotope('layout');
			});

		});
	}
};

jQuery(window).on("elementor/frontend/init", function() {
	elementorFrontend.hooks.addAction(
		"frontend/element_ready/wpupee-filterable-gallery.default",
		msshextExpandableEvents
	);
});
