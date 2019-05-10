/*global Vue:true*/
/*global jQuery:true*/
/*global pno_submission:true*/
import EventBus from './event-bus'
import axios from 'axios'

Vue.component('pno-listing-tags-selector', {
	data() {
		return {
			selectedTags: [],
			availableTags: [],
			loading: false,
			timeout: null,
		}
	},
	watch: {
		/**
		 * Watch for changes to the vue model and store changes into the frontend field
		 * so that we can use it via php when submitting the form.
		 */
		selectedTags: {
			handler: function () {
				document.getElementById('pno-field-listing_tags').value = JSON.stringify(this.selectedTags);
			},
			deep: true
		},
		/**
		 * Clear tags storage when clearing the categories selection.
		*/
		availableTags: {
			handler: function () {
				if ( ! this.tagsAreAvailable() ) {
					this.selectedTags = []
				}
			},
			deep: true
		}
	},
	mounted() {

		var vm = this

		var savedTags = this.getSavedTags()

		// Load tags stored into the database when viewing the edit form, otherwise load the starter tags.
		if ( savedTags.length > 0 ) {
			savedTags.forEach((tag) => {
				this.selectedTags.push(tag)
			});
		}

		/**
		 * Catch changes within the listings category selector and load appropriate tags.
		 */
		EventBus.$on( 'categories-changed', function (payLoad) {

			clearTimeout(this.timeout);

			// Load tags via ajax.
			this.timeout = setTimeout(function () {
				vm.loading = true
				vm.loadTags( payLoad )
			}, 500);

		});
	},
	methods: {
		/**
		 * Determine if there are tags available.
		 */
		tagsAreAvailable() {
			if ( Array.isArray( this.availableTags ) && this.availableTags.length > 0 ) {
				return true
			} else {
				return false
			}
		},
		/**
		 * Load tags related to the selected listings categories.
		 *
		 * @param {mixed} selectedCategories list of selected categories.
		 */
		loadTags( selectedCategories ) {

			this.loading = true

			axios.get( pno_submission.ajax, {
				params: {
					categories: selectedCategories,
					nonce: pno_submission.get_tags_nonce,
					action: 'pno_get_tags_from_categories_for_submission'
				}
			})
			.then( response => {

				this.loading = false

				var newTags = []

				response.data.data.forEach( ( tag ) => {
					newTags.push({
						id: tag.term_id,
						text: tag.name
					})
				});

				this.availableTags = newTags

			})
			.catch( error => {
				this.loading = false
				this.availableTags = []
			})

		},

		/**
		 * Retrieve the tags stored into the database when viewing the editing form.
		 */
		getSavedTags() {
			return document.getElementById('pno-field-listing_tags').value ? JSON.parse(document.getElementById('pno-field-listing_tags').value) : false
		}

	}
});
