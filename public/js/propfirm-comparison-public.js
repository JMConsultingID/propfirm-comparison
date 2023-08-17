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
        const ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
        const fixedButton = document.getElementById('fixed-button'); // Get the fixed button element
        const clearSessionButton = document.getElementById('clear-session');

        // Check if the session variable exists and is an array
        let selectedIds = <?php echo isset($_SESSION['compare_list']) && is_array($_SESSION['compare_list']) ? json_encode($_SESSION['compare_list']) : '[]'; ?>;

        function updateCompareList() {
            compareList.innerHTML = '';
            selectedIds.forEach(id => {
                const propfirmButton = document.querySelector(`.compare-button[data-propfirm-id="${id}"]`);
                const listItem = document.createElement('div');
                listItem.classList.add('col-6', 'mb-2');
                listItem.innerHTML = `
                	<div class="card w-100">
					  <img src="${propfirmButton.getAttribute('data-image-url')}" class="card-img-top" alt="${propfirmButton.getAttribute('data-propfirm-title')}">
					  <div class="card-body">
					    <h5 class="card-title">${propfirmButton.getAttribute('data-propfirm-title')}</h5>
					    <p class="card-text">${propfirmButton.getAttribute('data-custom-field')}</p>
					    <button class="remove-compare btn btn-danger" data-propfirm-id="${id}">Remove</button>
					  </div>
					</div>
                `;
                compareList.appendChild(listItem);
            });

            // Disable compare buttons for selected items
            compareButtons.forEach(button => {
                const propfirmId = parseInt(button.getAttribute('data-propfirm-id'));                
                button.disabled = selectedIds.includes(propfirmId);
            });

            // Enable/Disable generateCompareButton based on compare_list length
            generateCompareButton.disabled = selectedIds.length === 0;

            // Update fixed button text with total item count
            fixedButton.textContent = `Compare List (${selectedIds.length})`;
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
                const propfirmIdsParam = selectedIds.map(id => encodeURIComponent(id)).join(',');
    			const url = '/compare-page?propfirm_ids=' + propfirmIdsParam;
                window.location.href = url;
            }
        });

        clearSessionButton.addEventListener('click', function() {
            // Use WP Ajax to clear session
            const data = {
                action: 'clear_session',
            };

            fetch(ajaxUrl, {
                method: 'POST',
                body: new URLSearchParams(data),
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page after clearing session
                    location.reload();
                } else {
                    console.error('Error clearing session.');
                }
            });
        });
    });


})( jQuery );
