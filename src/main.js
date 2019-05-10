/**
 * Enhance the frontend listing submission form with custom inline templates.
 */

/*global Vue:true*/
import './components/select2.js'
import './components/flatpickr.js'
import './components/social-profiles-field.js'
import './components/listing-location-selector.js'

new Vue().$mount('#pno-form-listingSubmission, #pno-form-listing-edit')
