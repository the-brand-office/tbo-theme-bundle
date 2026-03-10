document.addEventListener('DOMContentLoaded', () => {
    // Mobile navigation
    // Dieses Skript implementiert die mobile Navigation in Vanilla JS
    // mit Fokus auf WCAG-konforme Barrierefreiheit (ARIA, ESC-Taste, Fokus).

    const nav = document.querySelector('#nav-mobile');

    if (!nav) {
        return;
    }

    const hasI18n = typeof i18n_a11y !== 'undefined';
    let isOpened = false;
    let lastFocusedElement = null;

    const skipNavLink = nav.querySelector('a.invisible');
    if (skipNavLink) skipNavLink.remove();

    const getFocusableElements = () =>
        Array.from(nav.querySelectorAll('a[href], button, [tabindex="0"]'));

    const getVisibleFocusableElements = () =>
        Array.from(nav.querySelectorAll(
            'a[href]:not([tabindex="-1"]), button:not([tabindex="-1"]), [tabindex="0"]:not([tabindex="-1"])'
        )).filter(el => el.offsetParent !== null);

    // --- Open / Close ---

    const openMenu = (openButton) => {
        isOpened = true;
        nav.classList.add('is-active');
        document.body.classList.add('navi-active');
        nav.setAttribute('aria-hidden', 'false');
        getFocusableElements().forEach(el => el.removeAttribute('tabindex'));

        if (openButton) {
            openButton.setAttribute('aria-expanded', 'true');
            lastFocusedElement = openButton;
        }
        const closeBtn = nav.querySelector('button.nav-mobile-close');
        if (closeBtn) closeBtn.focus();
    };

    const closeMenu = (openButton) => {
        isOpened = false;
        nav.classList.remove('is-active');
        document.body.classList.remove('navi-active');
        nav.setAttribute('aria-hidden', 'true');
        getFocusableElements().forEach(el => el.setAttribute('tabindex', '-1'));

        if (openButton) openButton.setAttribute('aria-expanded', 'false');
        if (lastFocusedElement) {
            lastFocusedElement.focus();
            lastFocusedElement = null;
        }
    };

    // --- Setup: Init, Open-Button, Wrapper, Close-Button ---

    nav.classList.add('is-enabled');
    nav.setAttribute('aria-hidden', 'true');

    let navMobileOpenButton = document.querySelector('#header .nav-mobile-open');

    const setupOpenButton = (button) => {
        button.setAttribute('aria-controls', nav.id);
        button.setAttribute('aria-expanded', 'false');
        if (hasI18n) button.setAttribute('aria-label', i18n_a11y.openNav);

        button.addEventListener('click', (e) => {
            e.preventDefault();
            if (nav.classList.contains('is-active')) {
                closeMenu(button);
                button.blur();
            } else {
                openMenu(button);
            }
        });
    };

    if (navMobileOpenButton) {
        setupOpenButton(navMobileOpenButton);
    } else {
        navMobileOpenButton = document.createElement('button');
        if (hasI18n) navMobileOpenButton.textContent = i18n_a11y.openNavText;
        navMobileOpenButton.classList.add('nav-mobile-open', 'icon-menu');
        const navInner = document.querySelector('#header > .inside');
        if (navInner) {
            navInner.prepend(navMobileOpenButton);
        }
        setupOpenButton(navMobileOpenButton);
    }

    // Wrap existing children in .menu-wrapper
    const wrapper = document.createElement('div');
    wrapper.classList.add('menu-wrapper');
    wrapper.setAttribute('tabindex', '-1');
    Array.from(nav.children).forEach(child => wrapper.appendChild(child));
    nav.appendChild(wrapper);

    // Close-Button
    const closeButton = document.createElement('button');
    if (hasI18n) {
        closeButton.textContent = i18n_a11y.closeNavText;
        closeButton.setAttribute('aria-label', i18n_a11y.closeNav);
    }
    closeButton.classList.add('nav-mobile-close', 'icon-cross');
    closeButton.addEventListener('click', (e) => {
        e.preventDefault();
        if (isOpened) {
            closeMenu(navMobileOpenButton);
            navMobileOpenButton.blur();
        }
    });
    nav.prepend(closeButton);

    // --- Expandable Sub-Menus ---

    const collapseItem = (item) => {
        item.classList.add('collapsed-icon');
        item.classList.remove('expanded-icon');

        const btn = item.querySelector('.nav-mobile-expand');
        if (btn) {
            btn.setAttribute('aria-expanded', 'false');
            btn.setAttribute('tabindex', '-1');
        }

        const sub = item.querySelector('ul');
        if (sub) {
            sub.setAttribute('aria-hidden', 'true');
            sub.querySelectorAll('a[href]').forEach(link => link.setAttribute('tabindex', '-1'));
        }
    };

    const buttonOnClick = (event) => {
        event.preventDefault();
        const button = event.currentTarget;
        const parent = button.parentElement;
        const subMenu = parent.querySelector('ul');

        // Collapse all siblings
        nav.querySelectorAll('.expanded-icon').forEach(item => {
            if (item !== parent && !item.contains(parent)) collapseItem(item);
        });

        parent.classList.toggle('collapsed-icon');
        parent.classList.toggle('expanded-icon');
        const isExpanded = parent.classList.contains('expanded-icon');
        button.setAttribute('aria-expanded', isExpanded);

        if (subMenu) {
            subMenu.setAttribute('aria-hidden', !isExpanded);
            subMenu.querySelectorAll('a[href]').forEach(link => {
                isExpanded ? link.removeAttribute('tabindex') : link.setAttribute('tabindex', '-1');
            });
        }
        isExpanded ? button.removeAttribute('tabindex') : button.setAttribute('tabindex', '-1');
    };

    if (!nav.classList.contains('no-expandable')) {
        nav.querySelectorAll('ul ul').forEach(subMenu => {
            const expandButton = document.createElement('button');
            expandButton.textContent = 'Untermenü erweitern';
            expandButton.classList.add('nav-mobile-expand');
            expandButton.addEventListener('click', buttonOnClick);
            expandButton.setAttribute('aria-expanded', 'false');
            subMenu.before(expandButton);

            const parent = subMenu.parentElement;
            const isActive = parent.classList.contains('active') || parent.classList.contains('trail');

            if (isActive) {
                parent.classList.add('expanded-icon');
                expandButton.setAttribute('aria-expanded', 'true');
                expandButton.removeAttribute('tabindex');
                subMenu.querySelectorAll('a[href]').forEach(link => link.removeAttribute('tabindex'));
            } else {
                parent.classList.add('collapsed-icon');
                subMenu.setAttribute('aria-hidden', 'true');
                expandButton.setAttribute('tabindex', '-1');
            }
        });
    }

    // Initial: alle fokussierbaren Elemente deaktivieren
    getFocusableElements().forEach(el => el.setAttribute('tabindex', '-1'));

    // --- Keyboard Navigation ---

    document.addEventListener('keydown', (event) => {
        if (!isOpened) return;

        const focusableElements = getVisibleFocusableElements();
        const currentIndex = focusableElements.indexOf(document.activeElement);
        let newIndex = -1;

        switch (event.key) {
            case 'Escape':
                event.preventDefault();
                closeMenu(navMobileOpenButton);
                break;
            case 'ArrowDown':
                event.preventDefault();
                newIndex = currentIndex !== -1
                    ? (currentIndex + 1) % focusableElements.length
                    : 0;
                break;
            case 'ArrowUp':
                event.preventDefault();
                newIndex = currentIndex !== -1
                    ? (currentIndex - 1 + focusableElements.length) % focusableElements.length
                    : focusableElements.length - 1;
                break;
        }

        if (newIndex >= 0 && focusableElements[newIndex]) {
            focusableElements[newIndex].focus();
        }
    });
});
