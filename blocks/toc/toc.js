/*!
 * Immediately Invoked Function Expression Boilerplate
 * (c) 2019 Chris Ferdinandi, MIT License, https://gomakethings.com
 */
;(function () {

    'use strict';

	var settings = {
		'count': 3,
		'expandText': 'View All',
		'collapseText': 'View Less', // set to false to remove button after expanding
		'buttonClass': [ 'wp-element-button', 'is-style-arrow' ]
	};

	const elementExists = function(element) {
    	if ( typeof(element) != 'undefined' && element != null ) {
    		return true;
    	}
    	return false;
    }

	const tocs = document.querySelectorAll('.yoast-table-of-contents');
		if ( elementExists( tocs ) && tocs.length ) {
			for( let toc of tocs ) {
				let items = toc.querySelector(':scope > ul').querySelectorAll('li, ul');
				let itemsArray = Array.from(items);
				let count = 0;
				for( let [index,item] of itemsArray.entries() ) {
					if ( count >= settings.count ) {
						item.setAttribute('hidden', true);
					}
					if ( count === settings.count ) {
						item.setAttribute('data-focus', true);
					}
					if ( item.tagName === "LI" ){
						count++;
					}
				}
				let container = document.createElement('div');
				container.classList.add('yoast-table-of-contents__footer');
				let reveal = document.createElement('button');
				reveal.classList.add('yoast-table-of-contents__reveal');
				reveal.classList.add(...settings.buttonClass);
				reveal.innerText = settings.expandText;
				reveal.setAttribute('aria-label', 'Expand table of contents');
				if ( count > settings.count ) {
					container.append(reveal);
					toc.append(container);
					toc.classList.remove('yoast-table-of-contents--no-js');
				}
			}
		}

		const toggleTocReveal = function(event) {
			if ( ! event.target.closest('.yoast-table-of-contents__reveal') ) {
				return;
			}
			let button = event.target.closest('.yoast-table-of-contents__reveal');
			let items = button.closest('.yoast-table-of-contents');
			items = items.querySelector(':scope > ul').querySelectorAll('li, ul');
			let itemsArray = Array.from(items);
			let dataReveal = button.getAttribute('data-reveal');
			let count = 0;
			if ( dataReveal === "true" ) {
				button.removeAttribute('data-reveal');
				button.innerText = settings.expandText;
				button.setAttribute('aria-label', 'Expand table of contents');
			} else {
				button.setAttribute('data-reveal', true);
				button.innerText = settings.collapseText;
				button.setAttribute('aria-label', 'Collapse table of contents');
			}
			for( let [index,item] of itemsArray.entries() ) {
				if ( item.getAttribute('hidden') ) {
					item.removeAttribute('hidden');
				} else {
					if ( count >= 3 ) {
						item.setAttribute('hidden', true);
					}
					if ( item.tagName === "LI" ){
						count++;
					}
				}
			}
			if ( dataReveal !== "true" ) {
				button.closest('.yoast-table-of-contents').querySelector('[data-focus] a').focus();

				if ( false === settings.collapseText ) {
					button.setAttribute('hidden', true );
					button.closest('.yoast-table-of-contents__footer').setAttribute('hidden', true );
				}
			}
		}

       document.addEventListener('click', function(event) {
       	toggleTocReveal(event);
       });

})();
