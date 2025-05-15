// Initialize DOM elements
document.addEventListener('DOMContentLoaded', () => {
  initializeNavigation();
  initializeTheme();
});

function initializeNavigation() {
  const navbar = document.querySelector('.nav');
  const navLinks = document.querySelector('.nav-links');

  // Scroll effect
  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });

  // Mobile Menu Toggle
  const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
  if (mobileMenuToggle && navLinks) {
    mobileMenuToggle.addEventListener('click', () => {
      mobileMenuToggle.classList.toggle('active');
      navLinks.classList.toggle('active');
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
      if (!navLinks.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
        mobileMenuToggle.classList.remove('active');
        navLinks.classList.remove('active');
      }
    });

    // Close mobile menu when clicking on a link
    navLinks.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        mobileMenuToggle.classList.remove('active');
        navLinks.classList.remove('active');
      });
    });
  }
}

function initializeTheme() {
  const themeToggle = document.querySelector('.theme-toggle');
  const body = document.body;
  
  // Theme toggle click handler
  themeToggle?.addEventListener('click', () => {
    body.classList.toggle('dark');
    const isDark = body.classList.contains('dark');
    localStorage.setItem('darkMode', isDark);
    
    // Update icon
    const icon = themeToggle.querySelector('i');
    icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
  });

  // Check for saved theme preference
  const savedTheme = localStorage.getItem('darkMode');
  if (savedTheme === 'true') {
    body.classList.add('dark');
    themeToggle.querySelector('i').className = 'fas fa-sun';
  }
}

// Skills Animation
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const progressBars = entry.target.querySelectorAll('.skill-item');
      progressBars.forEach(item => {
        const progress = item.querySelector('.progress-bar').getAttribute('data-progress');
        item.querySelector('.progress-bar').style.setProperty('--progress', progress + '%');
        item.classList.add('animate');
      });
    }
  });
}, { threshold: 0.5 });

const skillsSection = document.querySelector('.skills-content');
if (skillsSection) {
  observer.observe(skillsSection);
}

// Project Details Modal Functionality
document.addEventListener('DOMContentLoaded', () => {
  // Get all detail buttons and modals
  const detailButtons = document.querySelectorAll('[data-modal]');
  const modals = document.querySelectorAll('.modal');
  const closeButtons = document.querySelectorAll('.modal .close');

  // Open modal function
  const openModal = (modalId) => {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.style.display = 'block';
      document.body.style.overflow = 'hidden'; // Prevent scrolling
      setTimeout(() => modal.classList.add('active'), 10);
    }
  };

  // Close modal function
  const closeModal = (modal) => {
    modal.classList.remove('active');
    setTimeout(() => {
      modal.style.display = 'none';
      document.body.style.overflow = ''; // Restore scrolling
    }, 300);
  };

  // Add click event to detail buttons
  detailButtons.forEach(button => {
    button.addEventListener('click', (e) => {
      e.preventDefault();
      const modalId = button.getAttribute('data-modal');
      openModal(modalId);
    });
  });

  // Add click event to close buttons
  closeButtons.forEach(button => {
    button.addEventListener('click', () => {
      const modal = button.closest('.modal');
      closeModal(modal);
    });
  });

  // Close modal when clicking outside
  modals.forEach(modal => {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        closeModal(modal);
      }
    });
  });

  // Close modal with Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      modals.forEach(modal => {
        if (modal.style.display === 'block') {
          closeModal(modal);
        }
      });
    }
  });
});

// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      target.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
      // Close mobile menu if open
      mobileMenuToggle.classList.remove('active');
      navLinks.classList.remove('active');
    }
  });
}); 