import './styles/app.css';
import './styles/table_styles.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');


// Automatically hide flash messages after 5 seconds
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.querySelectorAll('.flash-message').forEach(message => {
            message.style.display = 'none';
        });
    }, 5000);
});

document.addEventListener('DOMContentLoaded', function () {
    const dropdownButton = document.getElementById('dropdownDefaultButton30');
    const dropdownMenu = document.getElementById('dropdown30');

    dropdownButton.addEventListener('click', () => {
        // Toggle visibility manually
        if (dropdownMenu.classList.contains('hidden')) {
            dropdownMenu.classList.remove('hidden');
        } else {
            dropdownMenu.classList.add('hidden');
        }
    });
});
