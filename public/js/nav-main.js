document.addEventListener('DOMContentLoaded', () => {
    /**** Nav-handling for accessibility improvements *****/
    const nav = document.querySelector('#nav-main');
    if (!nav) return;

    const level1Items = nav.querySelectorAll('.level_1 > li');
    let suppressFocusOpen = false;

    // Hilfsfunktion: Alle offenen Dropdowns schließen
    const closeAllDropdowns = () => {
        level1Items.forEach(item => {
            const itemTrigger = item.querySelector(':scope > a, :scope > span, :scope > strong');
            if (itemTrigger) itemTrigger.setAttribute('aria-expanded', 'false');
            item.classList.remove('is-open');
        });
    };

    level1Items.forEach(li => {
        const trigger = li.querySelector(':scope > a, :scope > span, :scope > strong');
        const submenu = li.querySelector('ul.level_2');
        if (!trigger) return;

        if (submenu) {
            // Fokus auf Trigger: Dropdown öffnen, alle anderen schließen
            // (nicht, wenn ESC gerade gedrückt wurde)
            trigger.addEventListener('focusin', () => {
                if (suppressFocusOpen) return;
                closeAllDropdowns();
                trigger.setAttribute('aria-expanded', 'true');
                li.classList.add('is-open');
            });

            // Fokus verlässt das gesamte LI: Dropdown schließen
            li.addEventListener('focusout', (event) => {
                if (!li.contains(event.relatedTarget)) {
                    trigger.setAttribute('aria-expanded', 'false');
                    li.classList.remove('is-open');
                }
            });
        } else {
            // Kein Submenu: beim Fokus trotzdem andere Dropdowns schließen
            trigger.addEventListener('focusin', () => {
                if (suppressFocusOpen) return;
                closeAllDropdowns();
            });
        }

        li.addEventListener('keydown', (event) => {
            const isTriggerFocused = document.activeElement === trigger;
            let isMenuOpen = false;
            let focusableSubItems = [];
            let currentFocusIndex = -1;
            let isTriggerLink = false;

            if (submenu) {
                isMenuOpen = li.classList.contains('is-open');
                focusableSubItems = Array.from(submenu.querySelectorAll('a, button'));
                currentFocusIndex = focusableSubItems.indexOf(document.activeElement);
                isTriggerLink = trigger.tagName.toLowerCase() === 'a';
            }
            const currentLevel1Index = Array.from(level1Items).indexOf(li);

            switch (event.key) {
                case 'Escape':
                    event.preventDefault();
                    suppressFocusOpen = true;
                    if (submenu && isMenuOpen) {
                        // Dropdown schließen, Fokus zurück auf den Trigger
                        trigger.setAttribute('aria-expanded', 'false');
                        li.classList.remove('is-open');
                        trigger.focus();
                    } else {
                        // Kein offenes Dropdown: Fokus aus der Navigation raus
                        closeAllDropdowns();
                        document.body.setAttribute('tabindex', '-1');
                        document.body.focus();
                        document.body.removeAttribute('tabindex');
                    }
                    // Flag nach kurzem Timeout wieder freigeben
                    requestAnimationFrame(() => { suppressFocusOpen = false; });
                    break;
                case 'ArrowDown':
                    if (!submenu) {
                        if (isTriggerFocused) event.preventDefault();
                        break;
                    }
                    if (isTriggerFocused) {
                        event.preventDefault();
                        trigger.setAttribute('aria-expanded', 'true');
                        li.classList.add('is-open');
                        if (focusableSubItems.length > 0) {
                            focusableSubItems[0].focus();
                        }
                    } else if (currentFocusIndex >= 0 && currentFocusIndex < focusableSubItems.length - 1) {
                        event.preventDefault();
                        focusableSubItems[currentFocusIndex + 1].focus();
                    } else if (currentFocusIndex === focusableSubItems.length - 1) {
                        event.preventDefault();
                    }
                    break;
                case 'ArrowUp':
                    if (!submenu) {
                        if (isTriggerFocused) event.preventDefault();
                        break;
                    }
                    if (currentFocusIndex > 0) {
                        event.preventDefault();
                        focusableSubItems[currentFocusIndex - 1].focus();
                    } else if (currentFocusIndex === 0) {
                        if (isTriggerLink) {
                            event.preventDefault();
                            trigger.focus();
                        } else {
                            event.preventDefault();
                        }
                    } else if (isTriggerFocused) {
                        event.preventDefault();
                    }
                    break;
                case 'ArrowRight':
                    if (isTriggerFocused || currentFocusIndex >= 0) {
                        event.preventDefault();
                        // Aktuelles Dropdown schließen
                        closeAllDropdowns();
                        const nextIndex = (currentLevel1Index + 1) % level1Items.length;
                        const nextLi = level1Items[nextIndex];
                        const nextTrigger = nextLi.querySelector(':scope > a, :scope > span, :scope > strong');
                        if (nextTrigger) nextTrigger.focus();
                    }
                    break;
                case 'ArrowLeft':
                    if (isTriggerFocused || currentFocusIndex >= 0) {
                        event.preventDefault();
                        // Aktuelles Dropdown schließen
                        closeAllDropdowns();
                        let prevIndex = currentLevel1Index - 1;
                        if (prevIndex < 0) prevIndex = level1Items.length - 1;
                        const prevLi = level1Items[prevIndex];
                        const prevTrigger = prevLi.querySelector(':scope > a, :scope > span, :scope > strong');
                        if (prevTrigger) prevTrigger.focus();
                    }
                    break;
            }
        });
    });
});
