import './bootstrap';
import "@fontsource/inter/400.css";  // normal
import "@fontsource/inter/500.css";  // medium
import "@fontsource/inter/600.css";  // semibold
import "@fontsource/inter/700.css";  // bold
import "@fontsource/inter/800.css";  // extrabold
import { createIcons, icons } from 'lucide';

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
Livewire.hook('morph.updated', ({ el }) => {
    // Only reinit if the updated element contains lucide icons
    if (el.querySelector('[data-lucide]') || el.hasAttribute('data-lucide')) {
        requestAnimationFrame(() => {
            createIcons({icons});
        });
    }
});

