class msshextExpandableEvents extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				button: '.msshext-events-show-all',
				hiddenItems: '.msshext-events-hidden',
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings('selectors');
		return {
			$button: this.$element.find(selectors.button),
			$hiddenItems: this.$element.find(selectors.hiddenItems),
		};
	}

	bindEvents() {
		this.elements.$button.on('click', this.onButtonClick.bind(this));
	}

	onButtonClick(event) {
		event.preventDefault();
		this.elements.$button.remove();
		this.elements.$hiddenItems.removeClass('msshext-events-hidden');
	}
}

jQuery(window).on('elementor/frontend/init', () => {
	const addHandler = ($element) => {
		elementorFrontend.elementsHandler.addHandler(msshextExpandableEvents, {
			$element,
		});
	};

	elementorFrontend.hooks.addAction('frontend/element_ready/msshext-events.default', addHandler);
});
