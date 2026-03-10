function initNavbar() {
    const header = document.querySelector('header'); // <header class="navigation-bar">
    const headerBar = document.querySelector('.header-top-bar'); // Die obere Leiste

    if (!header) {
        console.error('Navbar: Header nicht gefunden.');
        return;
    }

    // 1. DYNAMISCHER WRAPPER
    const wrapper = document.createElement('div');
    wrapper.className = 'navigation-wrapper';

    header.parentNode.insertBefore(wrapper, header);
    wrapper.appendChild(header);

    // Initialisierungsvariablen
    let scrollThreshold = 0;
    let isFixed = false;

    // Funktion zum Messen und Fixieren der Wrapper-Höhe
    function updateLayout() {
        // GHOST-MESSUNG
        const clone = header.cloneNode(true);

        // Klon säubern & Transitions deaktivieren
        // .no-transition Klasse muss im CSS definiert sein: transition: none !important (auch für .logo!)
        clone.classList.add('no-transition');
        clone.classList.remove('is-fixed', 'is-scrolled');
        clone.removeAttribute('id');

        // Klon unsichtbar positionieren
        clone.style.position = 'absolute';
        clone.style.visibility = 'hidden';
        clone.style.width = '100%';
        clone.style.height = 'auto';
        clone.style.top = '0';
        clone.style.left = '0';

        // Kontext herstellen
        const prevPos = wrapper.style.position;
        if (getComputedStyle(wrapper).position === 'static') {
            wrapper.style.position = 'relative';
        }

        // Messen
        wrapper.appendChild(clone);
        const navHeight = clone.offsetHeight;
        wrapper.removeChild(clone);

        // Reset
        wrapper.style.position = prevPos;

        // Schwellenwert messen
        scrollThreshold = headerBar ? headerBar.offsetHeight : 0;

        // Wrapper fixieren
        wrapper.style.height = `${navHeight}px`;

        // Sofort prüfen
        checkScroll();
    }

    function checkScroll() {
        const scrollPosition = window.scrollY || window.pageYOffset;

        if (scrollPosition > scrollThreshold) {
            if (!isFixed) {
                header.classList.add('is-fixed');
                header.classList.add('is-scrolled');
                isFixed = true;
            }
        } else {
            if (isFixed) {
                header.classList.remove('is-fixed');
                header.classList.remove('is-scrolled');
                isFixed = false;
            }
        }
    }

    // --- EVENT LISTENER ---

    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                checkScroll();
                ticking = false;
            });
            ticking = true;
        }
    });

    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            updateLayout();
        }, 500);
    });

    // Init
    if (document.readyState === 'complete') {
        updateLayout();
    } else {
        window.addEventListener('load', updateLayout);
    }

    // --- TOUCH LOGIK ---
    let touchUsed = false;
    const hasSubmenus = document.querySelectorAll('li.submenu');
    document.addEventListener('touchstart', () => { touchUsed = true; }, { once: true });
    hasSubmenus.forEach(li => {
        const el = li.querySelector('a') || li.querySelector('strong');
        if (el) {
            el.addEventListener('click', (e) => {
                if (touchUsed && !li.classList.contains('is-active') && el.tagName === 'A') {
                    e.preventDefault();
                    document.querySelectorAll('li.is-active').forEach(n => n !== li && n.classList.remove('is-active'));
                    li.classList.add('is-active');
                }
            });
        }
    });
    document.addEventListener('click', (e) => {
        if (touchUsed && !e.target.closest('.mod_navigation')) {
            document.querySelectorAll('li.is-active').forEach(n => n.classList.remove('is-active'));
        }
    });
}

document.addEventListener('DOMContentLoaded', initNavbar);
