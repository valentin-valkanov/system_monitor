import './bootstrap.js';
import './styles/app.css';
import './styles/table_styles.css';
import { Application } from '@hotwired/stimulus'
import Dropdown from '@stimulus-components/dropdown';


const application = Application.start()
application.register('dropdown', Dropdown)

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// Automatically hide flash messages after 5 seconds
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.querySelectorAll('.flash-message').forEach(message => {
            message.style.display = 'none';
        });
    }, 5000);
});

// Add an event listener to toggle the 'is-open' class on dropdowns
// document.addEventListener('click', (event) => {
//     const dropdownToggle = event.target.closest('.dropdown button'); // Find the button inside a dropdown
//     if (dropdownToggle) {
//         const dropdown = dropdownToggle.closest('.dropdown');
//         dropdown.classList.toggle('is-open'); // Toggle the visibility of the dropdown menu
//     } else {
//         // If the click is outside, hide any open dropdowns
//         document.querySelectorAll('.dropdown.is-open').forEach(dropdown => {
//             dropdown.classList.remove('is-open');
//         });
//     }
// });
