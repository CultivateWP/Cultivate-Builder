/*!
 * Immediately Invoked Function Expression Boilerplate
 * (c) 2019 Chris Ferdinandi, MIT License, https://gomakethings.com
 */
;(function () {

    'use strict';

    // Element variables
    const menuToggle = document.querySelector('.menu-toggle');
	const siteHeader = document.querySelector('.site-header');
    const headerSearch = document.querySelector('.header-search');
    const navMenu = document.querySelector('.nav-menu[role="navigation"]');
    const searchToggles = document.querySelectorAll('.search-toggle');
    const searchField = document.querySelector('.header-search .wp-block-search__input');

    const elementExists = function(element) {
    	if ( typeof(element) != 'undefined' && element != null ) {
    		return true;
    	}
    	return false;
    }

    // Event functions
    const toggleMenu = function(event) {
    	if ( !event.target.closest('.menu-toggle') ) return;
    	if ( elementExists(searchToggles) && searchToggles.length ) {
    		for (let searchToggle of searchToggles) {
    			searchToggle.classList.remove('active');
    		}
    	}
    	if ( elementExists(headerSearch) ) {
    		headerSearch.classList.remove('active');
    	}
    	if ( elementExists(navMenu) ) {
			navMenu.classList.toggle('active');
		}
    	menuToggle.classList.toggle('active');
		siteHeader.classList.toggle('menu-active');
    }

    const toggleSubMenu = function(event) {
    	if ( !event.target.closest('.submenu-expand') ) return;
    	event.target.closest('.submenu-expand').classList.toggle('expanded');
		event.target.closest('.menu-item').classList.toggle('has-expanded-submenu');
    	event.preventDefault();
    }

    const toggleSearch = function(event) {
    	if ( !event.target.closest('.search-toggle') ) return;
    	if ( elementExists(menuToggle) ) {
			menuToggle.classList.remove('active');
		}
		if ( elementExists(navMenu) ) {
			navMenu.classList.remove('active');
			siteHeader.classList.remove('menu-active');
		}
		if ( cwp.search === 'slickstream' ) {
			document.dispatchEvent(new CustomEvent('slick-show-discovery'));
		} else {
			if ( elementExists(headerSearch) ) {
	    		for( let searchToggle of searchToggles ) {
	    			searchToggle.classList.toggle('active');
	    		}
	    		headerSearch.classList.toggle('active');
	    		if ( elementExists(searchField) ) {
					searchField.focus();
				}
	    	}
		}
    }

	const closeSearch = function(event) {
		if ( event.target.closest('#grow-me-root') ) {
			if ( elementExists(headerSearch) ) {
				for( let searchToggle of searchToggles ) {
					searchToggle.classList.remove('active');
				}
				headerSearch.classList.remove('active');
			}
		}
	}

    const toggleFave = function(event) {
    	if ( !event.target.closest('.favorite-toggle') ) return;
		if ( cwp.favorites === 'slickstream' ) {
			document.dispatchEvent(new CustomEvent('slick-show-discovery', { detail: { page: 'favorites' } }));
		}
    }

	// Yoast FAQ Toggle
	const yoastFaqs = document.querySelectorAll('.schema-faq');
	if ( yoastFaqs.length ) {
		for( let [faqIndex, faq] of yoastFaqs.entries() ) {
			faq.classList.add('schema-faq--has-toggle');
			let questions = faq.querySelectorAll('.schema-faq-question');
			if ( questions.length ) {
				for( let [questionIndex, question] of questions.entries() ) {
					let btn = document.createElement('button');
					btn.setAttribute('aria-label', 'Display answer');
					btn.classList.add('schema-faq-toggle');
					question.append(btn);
					question.style.cursor = 'pointer';

					// Close all faqs by default.
					question.closest('.schema-faq-section').querySelector('.schema-faq-answer').toggleAttribute('hidden');

					// Open all faqs by default.
					// question.closest('.schema-faq-section').classList.toggle('active');

					// Open first faq question in each faq block.
					// if ( questionIndex !== 0 ) {
					// 	question.closest('.schema-faq-section').querySelector('.schema-faq-answer').toggleAttribute('hidden');
					// } else {
					// 	question.closest('.schema-faq-section').classList.toggle('active');
					// }
				}
			}
		}
	}

	const faqToggle = function(event) {
		if( !event.target.closest('.schema-faq-question') ) return;
		let parent = event.target.closest('.schema-faq-section');
		parent.classList.toggle('active');
		parent.querySelector('.schema-faq-answer').toggleAttribute('hidden');
	}

    // Add functions to click event listener
    document.addEventListener('click', function(event) {
    	toggleMenu(event);
    	toggleSubMenu(event);
    	toggleSearch(event);
		closeSearch(event);
    	toggleFave(event);
		faqToggle(event);
    });

    // Grow JS
    function initGrowMeSdk() {
	  if (!window.growMe) {
		  window.growMe = function (e) {
		    window.growMe._.push(e);
		  }
		  window.growMe._ = [];
		}
	}

	let isBookmarked = null;
	initGrowMeSdk();
	window.growMe(() => {
		isBookmarked = window.growMe.getIsBookmarked();
	  	window.growMe.on("isBookmarkedChanged", (params) => {
	    isBookmarked = params.isBookmarked;
	    if ( isBookmarked ) {
	    	document.querySelector('.post-header__favorite').classList.add('active');
	    }
	  });
	});

	async function callAddBookmark() {
	  try {
	    await window.growMe.addBookmark({
	      source: "create",
	      tooltipReferenceElement: document.querySelector(".post-header__favorite")
	    });
	  } catch (e) {
	    // Handle Error
	  }
	}

	document.addEventListener( 'click', function(event) {
		if ( !event.target.closest('.post-header__favorite') ) return;
		if ( ! window.growMe.getIsBookmarked() ) {
			callAddBookmark();
		}
	});

	// Slickstream JS
	const sr = document.querySelectorAll('.post-header__favorite');

	// This returns the Slickstream API object,
	// waiting if necessary for loading to complete

	async function ensureSlickstream() {
	   if (window.slickstream) {
	       return window.slickstream.v1;
	   }
	   return new Promise((resolve, reject) => {
	      document.addEventListener('slickstream-ready', () => {
	         resolve(window.slickstream.v1);
	      });
	   });
	}

	async function updateFavoriteButtonState() {
	   const slickstream = await ensureSlickstream();
	   const isFavorite = slickstream.favorites.getState();
	   for( var i = 0, len = sr.length; i < len; i++ ) {
		   if ( slickstream.favorites.getState() ) {
			   sr[i].classList.add('active');
		   } else {
			   sr[i].classList.remove('active');
		   }
		}
	}

	for( var i = 0, len = sr.length; i < len; i++ ) {
		sr[i].addEventListener( 'click', function(e) {
			e.preventDefault();
		});
		sr[i].addEventListener('click', async() => {
		   const slickstream = await ensureSlickstream();
		   const state = slickstream.favorites.getState();
		   slickstream.favorites.setState(!state);
		});
	}

	// If the favorite state has changed this event will fire and
	// this ensures your display of the state remains correct
	document.addEventListener('slickstream-favorite-change', () => {
	  updateFavoriteButtonState();
	});
	// After the page loads, this will ensure your display
	// is updated as soon as the info is available
	updateFavoriteButtonState();

})();
