(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	document.addEventListener('DOMContentLoaded', function() {
        const compareButtons = document.querySelectorAll('.compare-button');
        const compareList = document.getElementById('compare-list');
        const generateCompareButton = document.getElementById('generate-compare');
        const ajaxUrlElement = document.getElementById('ajax-url');
        const ajaxUrl = ajaxUrlElement.getAttribute('data-url');       
        const fixedButton = document.getElementById('fixed-button'); // Get the fixed button element
        const clearSessionButton = document.getElementById('clear-session');

        // Dapatkan nilai selectedIds dari atribut data di elemen HTML
        const selectedIdsElement = document.getElementById('selected-ids');
        const selectedIds = JSON.parse(localStorage.getItem('compare_list')) || [];

        function updateCompareList() {
            compareList.innerHTML = ''; // Clear the previous content

            selectedIds.forEach(id => {
                // Fetch data from the server based on the ID
                fetch(ajaxUrl, {
                    method: 'POST',
                    body: new URLSearchParams({
                        action: 'get_propfirm_data', // Adjust the action to your actual server-side handler
                        propfirm_id: id,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    // Create and append the card
                    const listItem = document.createElement('div');
                    listItem.classList.add('col-md-6', 'col-sm-6', 'mb-4');
                    listItem.innerHTML = `
                        <div class="card position-relative">
                        <button data-propfirm-id="${data.post_id}" type="button" class="remove-compare btn-close position-absolute top-0 end-0 text-reset" aria-label="Close"></button>
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        <img src="${data.post_thumbnail_url}" class="card-img-top" alt="${data.post_title}">
                        </div>
                    `;
                    compareList.appendChild(listItem);
                });
            });

            // Disable compare buttons for selected items
            compareButtons.forEach(button => {
                const propfirmId = parseInt(button.getAttribute('data-propfirm-id'));                
                button.disabled = selectedIds.includes(propfirmId);
            });

            // Enable/Disable generateCompareButton based on compare_list length
            generateCompareButton.disabled = selectedIds.length === 0;

            // Store updated data in local storage
            localStorage.setItem('compare_list', JSON.stringify(selectedIds));

            // Update fixed button text with total item count
            fixedButton.innerHTML = `<i class="fa-solid fa-code-compare"></i> ${selectedIds.length}`;
        }

        function updateSession() {
            const data = {
                action: 'update_compare_session',
                compare_list: selectedIds,
            };

            fetch(ajaxUrl, {
                method: 'POST',
                body: new URLSearchParams(data),
            });
        }


        updateCompareList();

        compareButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                let propfirmId = parseInt(e.target.getAttribute('data-propfirm-id'));
                if (!selectedIds.includes(propfirmId)) {
                    selectedIds.push(propfirmId);
                    updateCompareList();

                    const data = {
                        action: 'add_to_compare',
                        propfirm_id: propfirmId,
                    };

                    fetch(ajaxUrl, {
                        method: 'POST',
                        body: new URLSearchParams(data),
                    });
                }
            });
        });

        compareList.addEventListener('click', function(e) {
		    if (e.target.classList.contains('remove-compare')) {
		        let propfirmId = parseInt(e.target.getAttribute('data-propfirm-id')); 
		        console.log('Clicked Remove for PropFirm ID:', propfirmId);

		        const index = selectedIds.indexOf(propfirmId);
		        console.log('Index of PropFirm ID in selectedIds:', index);

		        if (index !== -1) {
		            selectedIds.splice(index, 1);

		            updateCompareList();

		            const data = {
		                action: 'remove_from_compare',
		                propfirm_id: propfirmId,
		            };

		            fetch(ajaxUrl, {
		                method: 'POST',
		                body: new URLSearchParams(data),
		            });
		        }
		    }
		});


        generateCompareButton.addEventListener('click', function() {
            if (selectedIds.length > 0) {
                const selectedIds = JSON.parse(localStorage.getItem('compare_list')) || [];
                const ajaxUrlCon = ajax_object.ajax_url;

                // Perform AJAX request to retrieve propfirm slugs based on IDs
                $.ajax({
                    url: ajaxUrlCon,
                    type: 'POST',
                    data: {
                        action: 'get_propfirm_slugs',
                        selected_ids: selectedIds
                    },
                    success: function(response) {
                        const propfirmSlugs = response.data.propfirm_slugs;
                        const propfirmUrl = generateCompareButton.getAttribute('data-propfirm-url'); // Get the URL from the data attribute

                        const compareSlugs = propfirmSlugs.join('-vs-'); // Create the slug format

                        const url = `/${propfirmUrl}?propfirm_ids=${compareSlugs}`;
                        window.location.href = url;
                    }
                });
            }
        });


        clearSessionButton.addEventListener('click', function() {
            // Use WP Ajax to clear session
            localStorage.removeItem('compare_list'); // Clear local storage

            const data = {
                action: 'clear_session',
            };

            fetch(ajaxUrl, {
                method: 'POST',
                body: new URLSearchParams(data),
            }).then(response => response.json())
            .then(data => {
                if (data.success) {                    
                    location.reload();
                } else {
                    console.error('Error clearing session.');
                }
            });
        });
    });


})( jQuery );
