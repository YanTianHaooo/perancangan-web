// ============================================
// GLOBAL JAVASCRIPT - Orang Hutan Heaven
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    // Mobile navbar toggle
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (navToggle) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            navToggle.classList.toggle('active');
        });
    }

    // Close menu when clicking a link
    document.querySelectorAll('.nav-menu a').forEach(link => {
        link.addEventListener('click', () => {
            navMenu.classList.remove('active');
            navToggle.classList.remove('active');
        });
    });

    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }

    // Scroll animations (Intersection Observer)
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });

    // Auto-hide flash messages
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Star rating interactive
    const starInputs = document.querySelectorAll('.star-rating-input');
    starInputs.forEach(container => {
        const stars = container.querySelectorAll('.star');
        const input = container.querySelector('input[type="hidden"]');
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = this.dataset.value;
                input.value = value;
                stars.forEach(s => {
                    s.classList.toggle('active', s.dataset.value <= value);
                });
            });
            
            star.addEventListener('mouseenter', function() {
                const value = this.dataset.value;
                stars.forEach(s => {
                    s.classList.toggle('hover', s.dataset.value <= value);
                });
            });
            
            star.addEventListener('mouseleave', function() {
                stars.forEach(s => s.classList.remove('hover'));
            });
        });
    });

    // Counter animation
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = parseInt(counter.dataset.target);
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;

        const updateCounter = () => {
            current += step;
            if (current < target) {
                counter.textContent = Math.floor(current).toLocaleString('id-ID');
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target.toLocaleString('id-ID');
            }
        };

        const counterObserver = new IntersectionObserver(entries => {
            if (entries[0].isIntersecting) {
                updateCounter();
                counterObserver.unobserve(counter);
            }
        });
        counterObserver.observe(counter);
    });
});

// Format number to Rupiah
function formatRupiah(number) {
    return 'Rp ' + number.toLocaleString('id-ID');
}


// Popup detail orangutan
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('orangutanModal');
    const closeBtn = document.getElementById('closeOrangutanModal');

    if (!modal) return;

    const setText = (id, value) => {
        const element = document.getElementById(id);
        if (element) element.textContent = value || '-';
    };

    document.querySelectorAll('.orangutan-card').forEach(card => {
        card.addEventListener('click', function() {
            const gambar = document.getElementById('modalGambar');
            if (gambar) {
                gambar.src = this.dataset.gambar;
                gambar.alt = this.dataset.nama;
            }

            setText('modalNama', this.dataset.nama);
            setText('modalJenis', this.dataset.jenis);
            setText('modalTahunPenyelamatan', this.dataset.tahunPenyelamatan);
            setText('modalTahunLahir', this.dataset.tahunLahir);
            setText('modalJenisKelamin', this.dataset.jenisKelamin);
            setText('modalKondisi', this.dataset.kondisi);

            const fullDesc = this.querySelector('.orangutan-full-desc');
            const modalDeskripsi = document.getElementById('modalDeskripsi');
            if (modalDeskripsi) {
                modalDeskripsi.innerHTML = fullDesc ? fullDesc.innerHTML : '-';
            }

            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });
});

// Popup detail fasilitas
document.addEventListener('DOMContentLoaded', function() {
    const fasilitasModal = document.getElementById('fasilitasModal');
    const fasilitasClose = document.querySelector('.fasilitas-close');

    if (!fasilitasModal) return;

    document.querySelectorAll('.facility-card .fasilitas-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation();

            const card = this.closest('.facility-card');
            const nama = card.dataset.nama;
            const fullDesc = card.querySelector('.fasilitas-full-desc');

            document.getElementById('fasilitasNama').textContent = nama || '-';
            document.getElementById('fasilitasDeskripsi').innerHTML = fullDesc ? fullDesc.innerHTML : '-';

            fasilitasModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    function closeFasilitasModal() {
        fasilitasModal.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (fasilitasClose) {
        fasilitasClose.addEventListener('click', closeFasilitasModal);
    }

    fasilitasModal.addEventListener('click', function(event) {
        if (event.target === fasilitasModal) {
            closeFasilitasModal();
        }
    });
});

// ganti tema
document.addEventListener('DOMContentLoaded', function () {
    const themeBtn = document.getElementById('toggleTheme');
    const savedTheme = localStorage.getItem('theme');

    if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
    }

    function updateThemeButton() {
        if (!themeBtn) return;
        const isDark = document.body.classList.contains('dark-theme');
        themeBtn.innerHTML = isDark
            ? '<i class="fas fa-sun"></i> Ganti Tema'
            : '<i class="fas fa-moon"></i> Ganti Tema';
    }

    updateThemeButton();

    if (themeBtn) {
        themeBtn.addEventListener('click', function (e) {
            e.preventDefault();
            document.body.classList.toggle('dark-theme');

            localStorage.setItem(
                'theme',
                document.body.classList.contains('dark-theme') ? 'dark' : 'light'
            );

            updateThemeButton();
        });
    }
});
