import './bootstrap';
import "@fontsource/inter/400.css";  // normal
import "@fontsource/inter/500.css";  // medium
import "@fontsource/inter/600.css";  // semibold
import "@fontsource/inter/700.css";  // bold
import "@fontsource/inter/800.css";  // extrabold
import {createIcons, icons} from 'lucide';

// Initialize icons on page load
document.addEventListener('DOMContentLoaded', () => {
    createIcons({icons});
});

// For Livewire 3.x navigation
document.addEventListener('livewire:navigated', () => {
    createIcons({icons});
});

// Listen for our custom dispatch event
document.addEventListener('livewire:init', () => {
    Livewire.on('reinit-lucide-icons', () => {
        // Small delay to ensure DOM is fully updated
        requestAnimationFrame(() => {
            createIcons({icons});
        });
    });
});

// Additional fallback for morphing
Livewire.hook('morph.updated', ({el}) => {
    // Only reinit if the updated element contains lucide icons
    if (el.querySelector('[data-lucide]') || el.hasAttribute('data-lucide')) {
        requestAnimationFrame(() => {
            createIcons({icons});
        });
    }
});

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/service-worker.js')
            .then(registration => {
                console.log('ServiceWorker registration successful');
            })
            .catch(err => {
                console.log('ServiceWorker registration failed: ', err);
            });
    });
}

// After service worker registration
let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    // Show your custom install button here
    // You can make your install button visible here
});

// Add this function to handle the install click
function installPWA() {
    if (deferredPrompt) {
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                console.log('User accepted the install prompt');
            }
            deferredPrompt = null;
        });
    }
}
