/**
 * TAKALO-TAKALO - SCRIPT PRINCIPAL
 * Gestion des interactions et de la responsivité
 */

(function () {
  "use strict";

  // ===================================
  // VARIABLES GLOBALES
  // ===================================
  let sidebarOpen = false;
  let lastScrollTop = 0;

  // ===================================
  // INITIALISATION AU CHARGEMENT
  // ===================================
  document.addEventListener("DOMContentLoaded", function () {
    initMobileSidebar();
    initScrollEffects();
    initTooltips();
    initFormValidation();
    initImageLazyLoad();
    initConfirmActions();
    initNotifications();
  });

  // ===================================
  // SIDEBAR MOBILE
  // ===================================
  function initMobileSidebar() {
    // Créer le bouton toggle et l'overlay si nécessaire
    const sidebar = document.querySelector(".sidebar");
    if (!sidebar) return;

    // Créer l'overlay
    let overlay = document.querySelector(".sidebar-overlay");
    if (!overlay) {
      overlay = document.createElement("div");
      overlay.className = "sidebar-overlay";
      document.body.appendChild(overlay);
    }

    // Créer le bouton toggle mobile si absent
    let toggleBtn = document.querySelector(".sidebar-toggle");
    if (!toggleBtn) {
      const navbar = document.querySelector(".navbar");
      if (navbar) {
        toggleBtn = document.createElement("button");
        toggleBtn.className =
          "btn btn-sm btn-outline-primary sidebar-toggle d-md-none me-2";
        toggleBtn.innerHTML = '<i class="bi bi-list"></i>';
        toggleBtn.setAttribute("aria-label", "Toggle sidebar");

        const navbarContainer = navbar.querySelector(
          ".container, .container-fluid",
        );
        if (navbarContainer) {
          const firstChild = navbarContainer.querySelector(
            ".d-flex, .navbar-brand",
          );
          if (firstChild) {
            firstChild.insertBefore(toggleBtn, firstChild.firstChild);
          }
        }
      }
    }

    // Event listeners
    if (toggleBtn) {
      toggleBtn.addEventListener("click", toggleSidebar);
    }

    overlay.addEventListener("click", closeSidebar);

    // Fermer au clic sur un lien
    const sidebarLinks = sidebar.querySelectorAll(".nav-link");
    sidebarLinks.forEach((link) => {
      link.addEventListener("click", function () {
        if (window.innerWidth < 768) {
          closeSidebar();
        }
      });
    });

    // Gestion du redimensionnement
    window.addEventListener("resize", function () {
      if (window.innerWidth >= 768) {
        closeSidebar();
      }
    });
  }

  function toggleSidebar() {
    const sidebar = document.querySelector(".sidebar");
    const overlay = document.querySelector(".sidebar-overlay");

    if (sidebarOpen) {
      closeSidebar();
    } else {
      sidebar.classList.add("show");
      overlay.classList.add("show");
      document.body.style.overflow = "hidden";
      sidebarOpen = true;
    }
  }

  function closeSidebar() {
    const sidebar = document.querySelector(".sidebar");
    const overlay = document.querySelector(".sidebar-overlay");

    sidebar.classList.remove("show");
    overlay.classList.remove("show");
    document.body.style.overflow = "";
    sidebarOpen = false;
  }

  // ===================================
  // EFFETS DE SCROLL
  // ===================================
  function initScrollEffects() {
    const header = document.querySelector(
      "header.navbar-header, header.admin-header",
    );
    if (!header) return;

    window.addEventListener("scroll", function () {
      const scrollTop =
        window.pageYOffset || document.documentElement.scrollTop;

      // Ajouter ombre au header
      if (scrollTop > 10) {
        header.classList.add("scrolled");
      } else {
        header.classList.remove("scrolled");
      }

      lastScrollTop = scrollTop;
    });

    // Smooth scroll pour les ancres
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
      anchor.addEventListener("click", function (e) {
        const targetId = this.getAttribute("href");
        if (targetId === "#") return;

        const target = document.querySelector(targetId);
        if (target) {
          e.preventDefault();
          target.scrollIntoView({
            behavior: "smooth",
            block: "start",
          });
        }
      });
    });
  }

  // ===================================
  // TOOLTIPS BOOTSTRAP
  // ===================================
  function initTooltips() {
    const tooltipTriggerList = [].slice.call(
      document.querySelectorAll('[data-bs-toggle="tooltip"], [title]'),
    );

    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
      if (typeof bootstrap !== "undefined" && bootstrap.Tooltip) {
        new bootstrap.Tooltip(tooltipTriggerEl);
      }
    });
  }

  // ===================================
  // VALIDATION DES FORMULAIRES
  // ===================================
  function initFormValidation() {
    const forms = document.querySelectorAll(".needs-validation");

    Array.from(forms).forEach(function (form) {
      form.addEventListener(
        "submit",
        function (event) {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();

            // Focus sur le premier champ invalide
            const firstInvalid = form.querySelector(":invalid");
            if (firstInvalid) {
              firstInvalid.focus();
            }
          }

          form.classList.add("was-validated");
        },
        false,
      );
    });

    // Validation en temps réel
    const inputs = document.querySelectorAll(".form-control, .form-select");
    inputs.forEach(function (input) {
      input.addEventListener("blur", function () {
        if (this.value.trim() !== "") {
          if (this.checkValidity()) {
            this.classList.remove("is-invalid");
            this.classList.add("is-valid");
          } else {
            this.classList.remove("is-valid");
            this.classList.add("is-invalid");
          }
        }
      });
    });
  }

  // ===================================
  // LAZY LOADING DES IMAGES
  // ===================================
  function initImageLazyLoad() {
    if ("IntersectionObserver" in window) {
      const imageObserver = new IntersectionObserver(function (
        entries,
        observer,
      ) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            const img = entry.target;

            if (img.dataset.src) {
              img.src = img.dataset.src;
              img.removeAttribute("data-src");
            }

            img.classList.add("fade-in");
            imageObserver.unobserve(img);
          }
        });
      });

      const images = document.querySelectorAll("img[data-src]");
      images.forEach(function (img) {
        imageObserver.observe(img);
      });
    } else {
      // Fallback pour les navigateurs plus anciens
      const images = document.querySelectorAll("img[data-src]");
      images.forEach(function (img) {
        img.src = img.dataset.src;
        img.removeAttribute("data-src");
      });
    }
  }

  // ===================================
  // CONFIRMATIONS D'ACTIONS
  // ===================================
  function initConfirmActions() {
    // Boutons de suppression
    document.querySelectorAll("[data-confirm]").forEach(function (btn) {
      btn.addEventListener("click", function (e) {
        const message =
          this.dataset.confirm ||
          "Êtes-vous sûr de vouloir effectuer cette action ?";

        if (!confirm(message)) {
          e.preventDefault();
          e.stopPropagation();
          return false;
        }
      });
    });
  }

  // ===================================
  // SYSTÈME DE NOTIFICATIONS
  // ===================================
  function initNotifications() {
    // Auto-fermeture des alertes après 5 secondes
    const alerts = document.querySelectorAll(".alert:not(.alert-permanent)");
    alerts.forEach(function (alert) {
      setTimeout(function () {
        const bsAlert = new bootstrap.Alert(alert);
        if (bsAlert) {
          bsAlert.close();
        }
      }, 5000);
    });
  }

  // ===================================
  // UTILITAIRES GLOBAUX
  // ===================================

  /**
   * Afficher une notification toast
   */
  window.showToast = function (message, type = "info") {
    const toastContainer = getOrCreateToastContainer();

    const toastEl = document.createElement("div");
    toastEl.className = `toast align-items-center text-white bg-${type} border-0`;
    toastEl.setAttribute("role", "alert");
    toastEl.setAttribute("aria-live", "assertive");
    toastEl.setAttribute("aria-atomic", "true");

    toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

    toastContainer.appendChild(toastEl);

    const toast = new bootstrap.Toast(toastEl);
    toast.show();

    toastEl.addEventListener("hidden.bs.toast", function () {
      toastEl.remove();
    });
  };

  function getOrCreateToastContainer() {
    let container = document.querySelector(".toast-container");

    if (!container) {
      container = document.createElement("div");
      container.className = "toast-container position-fixed top-0 end-0 p-3";
      container.style.zIndex = "9999";
      document.body.appendChild(container);
    }

    return container;
  }

  /**
   * Loader overlay
   */
  window.showLoader = function () {
    let loader = document.querySelector(".page-loader");

    if (!loader) {
      loader = document.createElement("div");
      loader.className = "page-loader";
      loader.innerHTML = `
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            `;

      const style = document.createElement("style");
      style.textContent = `
                .page-loader {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(255, 255, 255, 0.9);
                    z-index: 9998;
                    display: flex;
                }
            `;
      document.head.appendChild(style);
      document.body.appendChild(loader);
    }

    loader.style.display = "flex";
  };

  window.hideLoader = function () {
    const loader = document.querySelector(".page-loader");
    if (loader) {
      loader.style.display = "none";
    }
  };

  /**
   * Debounce function
   */
  window.debounce = function (func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  };

  // ===================================
  // GESTION DES IMAGES
  // ===================================

  /**
   * Prévisualisation d'images avant upload
   */
  window.setupImagePreview = function (inputId, previewContainerId) {
    const input = document.getElementById(inputId);
    const container = document.getElementById(previewContainerId);

    if (!input || !container) return;

    input.addEventListener("change", function (e) {
      container.innerHTML = "";

      const files = e.target.files;
      if (!files.length) return;

      Array.from(files).forEach(function (file) {
        if (!file.type.startsWith("image/")) return;

        const reader = new FileReader();
        reader.onload = function (e) {
          const div = document.createElement("div");
          div.className = "col-md-3 mb-3";
          div.innerHTML = `
                        <div class="position-relative">
                            <img src="${e.target.result}" class="img-fluid rounded" alt="Preview">
                            <small class="d-block text-muted mt-1 text-truncate">${file.name}</small>
                        </div>
                    `;
          container.appendChild(div);
        };
        reader.readAsDataURL(file);
      });

      container.parentElement.classList.remove("d-none");
    });
  };

  // ===================================
  // EXPOSITION DES FONCTIONS GLOBALES
  // ===================================
  window.TakaloApp = {
    toggleSidebar: toggleSidebar,
    closeSidebar: closeSidebar,
    showToast: window.showToast,
    showLoader: window.showLoader,
    hideLoader: window.hideLoader,
    debounce: window.debounce,
    setupImagePreview: window.setupImagePreview,
  };
})();
