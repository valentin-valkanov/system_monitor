import './styles/app.css';
import './styles/table_styles.css';
import './styles/all.css'

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// Automatically hide flash messages after 5 seconds
// document.addEventListener('DOMContentLoaded', () => {
//     setTimeout(() => {
//         document.querySelectorAll('.alert').forEach(message => {
//             message.style.display = 'none';
//         });
//     }, 5000);
// });

// Automatically hide flash messages after 5 seconds
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.querySelectorAll('.flash-message').forEach(message => {
            message.style.display = 'none';
        });
    }, 5000);
});
