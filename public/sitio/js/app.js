// ─────────────────────────────────────────────────────────────────────────────
// Header scroll effect
// ─────────────────────────────────────────────────────────────────────────────
const header = document.getElementById('header');
window.addEventListener('scroll', () => {
  if (window.scrollY > 50) {
    header?.classList.add('scrolled');
  } else {
    header?.classList.remove('scrolled');
  }
});

// ─────────────────────────────────────────────────────────────────────────────
// Mobile Menu
// ─────────────────────────────────────────────────────────────────────────────
const mobileToggle = document.getElementById('mobileToggle');
const mobileMenu = document.getElementById('mobileMenu');

mobileToggle?.addEventListener('click', () => {
  mobileToggle.classList.toggle('active');
  mobileMenu.classList.toggle('is-open');
});

// Close mobile menu when clicking a link
mobileMenu?.querySelectorAll('a').forEach(link => {
  link.addEventListener('click', () => {
    mobileToggle?.classList.remove('active');
    mobileMenu?.classList.remove('is-open');
  });
});

// ─────────────────────────────────────────────────────────────────────────────
// Cart Functionality
// ─────────────────────────────────────────────────────────────────────────────
let cart = [];
const cartBadge = document.getElementById('cartBadge');
const cartItems = document.getElementById('cartItems');
const cartEmpty = document.getElementById('cartEmpty');
const cartTotal = document.getElementById('cartTotal');
const clearCartBtn = document.getElementById('clearCartBtn');
const checkoutBtn = document.getElementById('checkoutBtn');

// Load cart from localStorage
function loadCart() {
  const savedCart = localStorage.getItem('salonCart');
  if (savedCart) {
    cart = JSON.parse(savedCart);
    updateCartUI();
  }
}

// Save cart to localStorage
function saveCart() {
  localStorage.setItem('salonCart', JSON.stringify(cart));
}

// Add item to cart
// Add item to cart (máximo 5 productos diferentes)
function addToCart(product) {
  const existingItem = cart.find(item => item.id === product.id);

  if (existingItem) {
    // Si ya está en el carrito, avisar al usuario
    showAlreadyInCartToast(product.name);
    return;
  }

  // Si es un producto nuevo, verificar el límite de 5
  if (cart.length >= 5) {
    showLimitToast();
    return;
  }

  cart.push({ ...product, quantity: 1 });
  saveCart();
  updateCartUI();
  showAddedToast(product.name);
}

// Remove item from cart
function removeFromCart(productId) {
  cart = cart.filter(item => item.id !== productId);
  saveCart();
  updateCartUI();
}

// Update item quantity
function updateQuantity(productId, change) {
  const item = cart.find(item => item.id === productId);
  if (item) {
    item.quantity += change;
    if (item.quantity <= 0) {
      removeFromCart(productId);
    } else {
      saveCart();
      updateCartUI();
    }
  }
}

// Clear cart
function clearCart() {
  cart = [];
  saveCart();
  updateCartUI();
}

// Calculate total
function calculateTotal() {
  return cart.reduce((total, item) => total + (item.price * item.quantity), 0);
}

// Update cart UI
function updateCartUI() {
  // Update badge
  const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
  if (cartBadge) {
    cartBadge.textContent = totalItems;
    cartBadge.style.display = totalItems > 0 ? 'flex' : 'none';
  }

  // Update total
  if (cartTotal) {
    cartTotal.textContent = `$${calculateTotal().toLocaleString('es-MX')}`;
  }

  // Update items list
  if (cartItems && cartEmpty) {
    if (cart.length === 0) {
      cartEmpty.style.display = 'block';
      // Remove all cart item elements
      const itemElements = cartItems.querySelectorAll('.cart-item');
      itemElements.forEach(el => el.remove());
    } else {
      cartEmpty.style.display = 'none';

      // Clear existing items
      const itemElements = cartItems.querySelectorAll('.cart-item');
      itemElements.forEach(el => el.remove());

      // Add items
      cart.forEach(item => {
        const itemEl = document.createElement('div');
        itemEl.className = 'cart-item';
        itemEl.innerHTML = `
          <div class="cart-item__image">
            <img src="${item.image}" alt="${item.name}">
          </div>
          <div class="cart-item__info">
            <h4 class="cart-item__name">${item.name}</h4>
            <p class="cart-item__price">$${item.price}</p>
          </div>
          <button type="button" class="cart-item__remove" onclick="removeFromCart('${item.id}')" aria-label="Eliminar">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>
        `;
        cartItems.appendChild(itemEl);
      });
    }
  }
}

// Show toast notification
function showAddedToast(productName) {
  // Remove existing toast
  const existingToast = document.querySelector('.toast');
  if (existingToast) existingToast.remove();

  const toast = document.createElement('div');
  toast.className = 'toast';
  toast.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
    </svg>
    <span>${productName} agregado al carrito</span>
  `;
  document.body.appendChild(toast);

  setTimeout(() => toast.classList.add('show'), 10);
  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 300);
  }, 2500);
}

// Show limit reached toast
function showLimitToast() {
  const existingToast = document.querySelector('.toast');
  if (existingToast) existingToast.remove();

  const toast = document.createElement('div');
  toast.className = 'toast toast--warning';
  toast.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
    </svg>
    <span>Máximo 5 productos diferentes en el carrito</span>
  `;
  document.body.appendChild(toast);

  setTimeout(() => toast.classList.add('show'), 10);
  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

// Show already in cart toast
function showAlreadyInCartToast(productName) {
  const existingToast = document.querySelector('.toast');
  if (existingToast) existingToast.remove();

  const toast = document.createElement('div');
  toast.className = 'toast toast--warning';
  toast.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
    </svg>
    <span>${productName} ya está en el carrito</span>
  `;
  document.body.appendChild(toast);

  setTimeout(() => toast.classList.add('show'), 10);
  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 300);
  }, 2500);
}

// Add to cart buttons
document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
  btn.addEventListener('click', (e) => {
    const card = e.target.closest('.product-card');
    if (card) {
      const product = {
        id: card.dataset.id,
        name: card.dataset.name,
        price: parseFloat(card.dataset.price),
        image: card.dataset.image
      };
      addToCart(product);
    }
  });
});

// Clear cart button
clearCartBtn?.addEventListener('click', () => {
  if (cart.length > 0) {
    Swal.fire({
      title: '¿Vaciar el carrito?',
      text: 'Se eliminarán todos los productos agregados.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#1a1a1a',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, vaciar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        clearCart();
        Swal.fire({
          title: '¡Carrito vaciado!',
          icon: 'success',
          timer: 1500,
          showConfirmButton: false
        });
      }
    });
  }
});

// Checkout button - Reservar productos
checkoutBtn?.addEventListener('click', () => {
  if (cart.length === 0) return;

  closeCart();

  // window.isAuthenticated lo inyecta Blade (ver Paso 3)
  if (window.isAuthenticated) {
    // Usuario logueado: ir directo a crear reserva
    window.location.href = window.reservationCreateUrl;
  } else {
    // Usuario NO logueado: ir a login con redirect_to
    window.location.href = window.loginUrl + '?redirect_to=' + encodeURIComponent(window.reservationCreateUrl);
  }
});

// Initialize cart
loadCart();

// ─────────────────────────────────────────────────────────────────────────────
// Cart Drawer
// ─────────────────────────────────────────────────────────────────────────────
const cartBtn = document.getElementById('openCartBtn');
const cartDrawer = document.getElementById('cartDrawer');
const cartOverlay = document.getElementById('cartOverlay');
const closeCartBtn = document.getElementById('closeCartBtn');

function openCart() {
  cartDrawer?.classList.add('is-open');
  cartOverlay?.classList.add('is-open');
}

function closeCart() {
  cartDrawer?.classList.remove('is-open');
  cartOverlay?.classList.remove('is-open');
}

cartBtn?.addEventListener('click', openCart);
closeCartBtn?.addEventListener('click', closeCart);
cartOverlay?.addEventListener('click', closeCart);

// ─────────────────────────────────────────────────────────────────────────────
// Booking Modal - Now redirects to Login
// ─────────────────────────────────────────────────────────────────────────────
const bookingBtns = document.querySelectorAll('#openBookingBtn, #openBookingBtnMobile, #heroBookingBtn, .service-slide__btn');
const bookingOverlay = document.getElementById('bookingOverlay');
const closeBookingBtn = document.getElementById('closeBookingBtn');
const cancelBookingBtn = document.getElementById('cancelBookingBtn');

function openBooking() {
  // Now redirects to login instead
  openLogin();
}

function closeBooking() {
  bookingOverlay?.classList.remove('is-open');
}

bookingBtns.forEach(btn => btn?.addEventListener('click', openBooking));
closeBookingBtn?.addEventListener('click', closeBooking);
cancelBookingBtn?.addEventListener('click', closeBooking);
bookingOverlay?.addEventListener('click', (e) => {
  if (e.target === bookingOverlay) closeBooking();
});

// ─────────────────────────────────────────────────────────────────────────────
// Login Modal
// ─────────────────────────────────────────────────────────────────────────────
const loginOverlay = document.getElementById('loginOverlay');
const closeLoginBtn = document.getElementById('closeLoginBtn');
const openUserBtn = document.getElementById('openUserBtn');

function openLogin() {
  loginOverlay?.classList.add('is-open');
  document.body.style.overflow = 'hidden';
  // Resetear el scroll interno del modal para que siempre muestre el encabezado
  const modal = loginOverlay?.querySelector('.modal');
  if (modal) modal.scrollTop = 0;
  mobileToggle?.classList.remove('active');
  mobileMenu?.classList.remove('is-open');
  closeCart();
}

function closeLogin() {
  loginOverlay?.classList.remove('is-open');
  document.body.style.overflow = '';
}

closeLoginBtn?.addEventListener('click', closeLogin);
loginOverlay?.addEventListener('click', (e) => {
  if (e.target === loginOverlay) closeLogin();
});

// User button opens login
openUserBtn?.addEventListener('click', openLogin);

// Link "Iniciar Sesión" in mobile menu
document.getElementById('openLoginMobile')?.addEventListener('click', (e) => {
  e.preventDefault();
  openLogin();
});

// ─────────────────────────────────────────────────────────────────────────────
// Register Modal
// ─────────────────────────────────────────────────────────────────────────────
const registerBtns = document.querySelectorAll('#openRegisterMobile');
const registerOverlay = document.getElementById('registerOverlay');
const closeRegisterBtn = document.getElementById('closeRegisterBtn');
const cancelRegisterBtn = document.getElementById('cancelRegisterBtn');

function openRegister() {
  registerOverlay?.classList.add('is-open');
  document.body.style.overflow = 'hidden';
  // Resetear el scroll interno del modal para que siempre muestre el encabezado
  const modal = registerOverlay?.querySelector('.modal');
  if (modal) modal.scrollTop = 0;
  mobileToggle?.classList.remove('active');
  mobileMenu?.classList.remove('is-open');
}

function closeRegister() {
  registerOverlay?.classList.remove('is-open');
  document.body.style.overflow = '';
}

registerBtns.forEach(btn => btn?.addEventListener('click', openRegister));
closeRegisterBtn?.addEventListener('click', closeRegister);
cancelRegisterBtn?.addEventListener('click', closeRegister);
registerOverlay?.addEventListener('click', (e) => {
  if (e.target === registerOverlay) closeRegister();
});

// ─────────────────────────────────────────────────────────────────────────────
// Close modals with Escape key
// ─────────────────────────────────────────────────────────────────────────────
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    closeCart();
    closeBooking();
    closeRegister();
    closeLogin();
  }
});

// ─────────────────────────────────────────────────────────────────────────────
// Services Carousel
// ─────────────────────────────────────────────────────────────────────────────
const track = document.getElementById('servicesTrack');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const dotsContainer = document.getElementById('carouselDots');

let currentIndex = 0;
let slidesPerView = 3;
let isDragging = false;
let startPos = 0;
let currentTranslate = 0;
let prevTranslate = 0;

// Calculate slides per view based on screen width
function updateSlidesPerView() {
  if (window.innerWidth < 768) {
    slidesPerView = 1;
  } else if (window.innerWidth < 1200) {
    slidesPerView = 2;
  } else {
    slidesPerView = 3;
  }
}

// Get total slides
function getTotalSlides() {
  return track ? track.children.length : 0;
}

// Get max index
function getMaxIndex() {
  return Math.max(0, getTotalSlides() - slidesPerView);
}

// Create dots
function createDots() {
  if (!dotsContainer) return;
  dotsContainer.innerHTML = '';
  const totalDots = getMaxIndex() + 1;

  for (let i = 0; i < totalDots; i++) {
    const dot = document.createElement('button');
    dot.classList.add('services-carousel__dot');
    dot.setAttribute('aria-label', `Ir al slide ${i + 1}`);
    if (i === currentIndex) dot.classList.add('active');
    dot.addEventListener('click', () => goToSlide(i));
    dotsContainer.appendChild(dot);
  }
}

// Update dots
function updateDots() {
  const dots = dotsContainer?.querySelectorAll('.services-carousel__dot');
  dots?.forEach((dot, index) => {
    dot.classList.toggle('active', index === currentIndex);
  });
}

// Go to specific slide
function goToSlide(index) {
  if (!track) return;
  currentIndex = Math.max(0, Math.min(index, getMaxIndex()));
  const slideWidth = track.children[0]?.offsetWidth + 25 || 0; // 25 is the gap
  track.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
  updateDots();
  updateButtons();
}

// Update navigation buttons
function updateButtons() {
  if (prevBtn) prevBtn.disabled = currentIndex === 0;
  if (nextBtn) nextBtn.disabled = currentIndex >= getMaxIndex();
}

// Next slide
function nextSlide() {
  if (currentIndex < getMaxIndex()) {
    goToSlide(currentIndex + 1);
  }
}

// Previous slide
function prevSlide() {
  if (currentIndex > 0) {
    goToSlide(currentIndex - 1);
  }
}

// Touch/Mouse events for dragging
function touchStart(e) {
  isDragging = true;
  startPos = e.type.includes('mouse') ? e.pageX : e.touches[0].clientX;
  track?.classList.add('dragging');
}

function touchMove(e) {
  if (!isDragging || !track) return;
  const currentPosition = e.type.includes('mouse') ? e.pageX : e.touches[0].clientX;
  const diff = currentPosition - startPos;
  currentTranslate = prevTranslate + diff;
}

function touchEnd() {
  if (!track) return;
  isDragging = false;
  track.classList.remove('dragging');

  const movedBy = currentTranslate - prevTranslate;
  const threshold = 100;

  if (movedBy < -threshold && currentIndex < getMaxIndex()) {
    currentIndex++;
  } else if (movedBy > threshold && currentIndex > 0) {
    currentIndex--;
  }

  goToSlide(currentIndex);
  prevTranslate = currentTranslate;
}

// Initialize carousel
function initCarousel() {
  updateSlidesPerView();
  createDots();
  goToSlide(0);

  // Event listeners
  prevBtn?.addEventListener('click', prevSlide);
  nextBtn?.addEventListener('click', nextSlide);

  // Touch events
  track?.addEventListener('touchstart', touchStart);
  track?.addEventListener('touchmove', touchMove);
  track?.addEventListener('touchend', touchEnd);

  // Mouse events
  track?.addEventListener('mousedown', touchStart);
  track?.addEventListener('mousemove', touchMove);
  track?.addEventListener('mouseup', touchEnd);
  track?.addEventListener('mouseleave', () => {
    if (isDragging) touchEnd();
  });

  // Resize handler
  window.addEventListener('resize', () => {
    updateSlidesPerView();
    createDots();
    goToSlide(Math.min(currentIndex, getMaxIndex()));
  });
}

// Initialize when DOM is ready
if (track) {
  initCarousel();
}

// ─────────────────────────────────────────────────────────────────────────────
// Smooth scroll for anchor links
// ─────────────────────────────────────────────────────────────────────────────
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
    const targetId = this.getAttribute('href');
    if (targetId === '#') return;

    const target = document.querySelector(targetId);
    if (target) {
      e.preventDefault();
      const headerOffset = 90;
      const elementPosition = target.getBoundingClientRect().top;
      const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

      window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
      });
    }
  });
});


// menu.blade.php
