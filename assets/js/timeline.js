/**
 * Visual Timeline Widget - Scroll Progress Handler
 *
 * @package MSSHEXT
 */

(function($) {
	'use strict';

	/**
	 * Timeline Handler Class
	 *
	 * @param {jQuery} $scope The widget wrapper element.
	 */
	var TimelineHandler = function($scope) {
		var $widget = $scope.find('.msshext-timeline');

		if (!$widget.length) {
			return;
		}

		var widget = $widget[0];
		var $line = $widget.find('.msshext-timeline-line');
		var $progress = $widget.find('.msshext-timeline-line-progress');
		var $items = $widget.find('.msshext-timeline-item');
		var isVisible = false;
		var rafId = null;

		/**
		 * Update the timeline line position to span from first to last icon.
		 */
		function updateLinePosition() {
			if ($items.length === 0) {
				return;
			}

			var $firstIcon = $items.first().find('.msshext-timeline-icon');
			var $lastIcon = $items.last().find('.msshext-timeline-icon');

			if (!$firstIcon.length || !$lastIcon.length) {
				return;
			}

			var widgetRect = widget.getBoundingClientRect();
			var firstIconRect = $firstIcon[0].getBoundingClientRect();
			var lastIconRect = $lastIcon[0].getBoundingClientRect();

			// Calculate icon centers relative to widget
			var firstIconCenterY = (firstIconRect.top + firstIconRect.height / 2) - widgetRect.top;
			var lastIconCenterY = (lastIconRect.top + lastIconRect.height / 2) - widgetRect.top;

			// Set line position
			var lineHeight = lastIconCenterY - firstIconCenterY;
			$line.css({
				'top': firstIconCenterY + 'px',
				'bottom': 'auto',
				'height': lineHeight + 'px'
			});
		}

		/**
		 * Calculate and update the scroll progress.
		 */
		function updateProgress() {
			if (!isVisible) {
				return;
			}

			if ($items.length === 0) {
				return;
			}

			var $firstIcon = $items.first().find('.msshext-timeline-icon');
			var $lastIcon = $items.last().find('.msshext-timeline-icon');

			if (!$firstIcon.length || !$lastIcon.length) {
				return;
			}

			var viewportMiddle = window.innerHeight / 2;
			var firstIconRect = $firstIcon[0].getBoundingClientRect();
			var lastIconRect = $lastIcon[0].getBoundingClientRect();

			// Line boundaries (absolute screen positions)
			var lineStartY = firstIconRect.top + firstIconRect.height / 2;
			var lineEndY = lastIconRect.top + lastIconRect.height / 2;
			var lineHeight = lineEndY - lineStartY;

			// Handle edge case where lineHeight is 0 or negative
			if (lineHeight <= 0) {
				return;
			}

			// Progress: 0 at first icon center, 1 at last icon center
			var progress = (viewportMiddle - lineStartY) / lineHeight;
			progress = Math.max(0, Math.min(1, progress));

			// Update CSS custom property for progress line
			widget.style.setProperty('--timeline-progress', progress);

			// Update icon states based on their position relative to line span
			$items.each(function() {
				var $item = $(this);
				var $icon = $item.find('.msshext-timeline-icon');

				if (!$icon.length) {
					return;
				}

				var iconRect = $icon[0].getBoundingClientRect();
				var iconCenterY = iconRect.top + (iconRect.height / 2);

				// Icon's position as proportion of line span
				var iconProgress = (iconCenterY - lineStartY) / lineHeight;

				// Icon is "passed" when the progress line has reached it
				if (progress >= iconProgress) {
					$item.addClass('is-passed');
				} else {
					$item.removeClass('is-passed');
				}
			});
		}

		/**
		 * Handle scroll events with requestAnimationFrame for performance.
		 */
		function onScroll() {
			if (rafId) {
				return;
			}

			rafId = requestAnimationFrame(function() {
				rafId = null;
				updateProgress();
			});
		}

		/**
		 * Set up Intersection Observer for performance optimization.
		 * Only track scroll when widget is visible in viewport.
		 */
		function setupObserver() {
			if (!('IntersectionObserver' in window)) {
				// Fallback for older browsers: always track scroll
				isVisible = true;
				window.addEventListener('scroll', onScroll, { passive: true });
				updateProgress();
				return;
			}

			var observer = new IntersectionObserver(function(entries) {
				entries.forEach(function(entry) {
					isVisible = entry.isIntersecting;

					if (isVisible) {
						window.addEventListener('scroll', onScroll, { passive: true });
						updateProgress();
					} else {
						window.removeEventListener('scroll', onScroll);
					}
				});
			}, {
				rootMargin: '50px 0px',
				threshold: 0
			});

			observer.observe(widget);
		}

		/**
		 * Handle window resize to recalculate positions.
		 */
		function onResize() {
			updateLinePosition();
			if (isVisible) {
				updateProgress();
			}
		}

		/**
		 * Initialize the handler.
		 */
		function init() {
			setupObserver();
			window.addEventListener('resize', onResize, { passive: true });

			// Initial line position and progress update
			updateLinePosition();
			updateProgress();
		}

		// Start initialization
		init();
	};

	/**
	 * Register handler with Elementor frontend.
	 */
	$(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/msshext-visual-timeline.default',
			TimelineHandler
		);
	});

})(jQuery);
