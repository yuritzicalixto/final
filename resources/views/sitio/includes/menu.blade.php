<div class="container">
      <div class="header__inner">
        <!-- Brand -->
        <a href="{{ route('sitio.index')}}" class="header__brand">
          <img src="{{ asset('sitio/img/logo.png') }}" alt="Guillermo Gutiérrez Salón" class="header__logo">
          <div class="header__brand-text">
            <div class="header__title">Guillermo Gutiérrez</div>
            <div class="header__subtitle">Salón</div>
          </div>
        </a>

        <!-- Desktop Navigation -->
        <nav class="header__nav">
          <ul>
            <li><a href="{{ route('sitio.index')}}">Inicio</a></li>
            <li><a href="{{ route('sitio.nosotros')}}">Nosotros</a></li>
            <li><a href="{{ route('sitio.servicios')}}">Servicios</a></li>
            <li><a href="{{ route('sitio.productos')}}">Productos</a></li>
            <li><a href="{{ route('sitio.galeria')}}">Galería</a></li>
          </ul>
        </nav>

        <!-- Actions -->
        <div class="header__actions">
          <!-- Cart Button -->
          <button type="button" id="openCartBtn" class="header__cart">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <span class="header__cart-badge" id="cartBadge">0</span>
          </button>

@auth
  {{-- =============================== --}}
  {{-- USUARIO AUTENTICADO: Dropdown   --}}
  {{-- =============================== --}}
  <div class="header__user-menu" style="position: relative;">
    <button type="button" id="userDropdownBtn" class="header__user" aria-label="Menú de usuario">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
      </svg>
    </button>

    {{-- Dropdown que aparece al hacer clic --}}
    <div id="userDropdown" class="user-dropdown" style="display: none;">
      <div class="user-dropdown__header">
        <span class="user-dropdown__name">{{ Auth::user()->name }}</span>
        <span class="user-dropdown__email">{{ Auth::user()->email }}</span>
      </div>

      <div class="user-dropdown__divider"></div>

      {{-- Link al panel según su rol --}}
      <a href="{{ route('dashboard') }}" class="user-dropdown__item">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="1.5" stroke="currentColor" width="18" height="18">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25
                   2.25 0 0110.5 6v2.25a2.25 2.25 0
                   01-2.25 2.25H6A2.25 2.25 0 013.75
                   8.25V6z" />
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M3.75 15.75A2.25 2.25 0 016
                   13.5h2.25a2.25 2.25 0 012.25
                   2.25V18a2.25 2.25 0 01-2.25
                   2.25H6A2.25 2.25 0 013.75 18v-2.25z" />
        </svg>
        Mi Panel
      </a>

      {{-- Cerrar sesión --}}
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="user-dropdown__item user-dropdown__logout">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
               stroke-width="1.5" stroke="currentColor" width="18" height="18">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15.75 9V5.25A2.25 2.25 0
                     0013.5 3h-6a2.25 2.25 0
                     00-2.25 2.25v13.5A2.25 2.25 0
                     007.5 21h6a2.25 2.25 0
                     002.25-2.25V15m3-3l3-3m0
                     0l-3-3m3 3H9" />
          </svg>
          Cerrar Sesión
        </button>
      </form>
    </div>
  </div>
@else
  {{-- =============================== --}}
  {{-- USUARIO NO AUTENTICADO: Modal   --}}
  {{-- =============================== --}}
  <button type="button" id="openUserBtn" class="header__user" aria-label="Iniciar sesión">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
    </svg>
  </button>
@endauth

          <!-- Mobile Toggle -->
          <button type="button" id="mobileToggle" class="header__toggle" aria-label="Menú">
            <span></span>
            <span></span>
            <span></span>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <nav id="mobileMenu" class="mobile-menu">
      <div class="container">
        <div class="mobile-menu__nav">
          <a href="{{ route('sitio.index')}}">Inicio</a>
          <a href="{{ route('sitio.nosotros')}}">Nosotros</a>
          <a href="{{ route('sitio.servicios')}}">Servicios</a>
          <a href="{{ route('sitio.productos')}}">Productos</a>
          <a href="{{ route('sitio.galeria')}}">Galería</a>
        </div>
      </div>
    </nav>


<style>
/* ===== User Dropdown (autenticado) ===== */
.header__user-menu {
  position: relative;
  display: inline-flex;
}

.user-dropdown {
  position: absolute;
  top: calc(100% + 8px);
  right: 0;
  min-width: 220px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
  z-index: 100;
  overflow: hidden;
}

.user-dropdown__header {
  padding: 14px 16px;
  background: #f9fafb;
}

.user-dropdown__name {
  display: block;
  font-weight: 600;
  font-size: 0.9rem;
  color: #1f2937;
}

.user-dropdown__email {
  display: block;
  font-size: 0.78rem;
  color: #6b7280;
  margin-top: 2px;
}

.user-dropdown__divider {
  height: 1px;
  background: #e5e7eb;
}

.user-dropdown__item {
  display: flex;
  align-items: center;
  gap: 10px;
  width: 100%;
  padding: 12px 16px;
  font-size: 0.88rem;
  color: #374151;
  text-decoration: none;
  border: none;
  background: none;
  cursor: pointer;
  transition: background 0.15s;
}

.user-dropdown__item:hover {
  background: #f3f4f6;
}

.user-dropdown__logout {
  color: #dc2626;
}

.user-dropdown__logout:hover {
  background: #fef2f2;
}
</style>


<script>
  // ── Dropdown de usuario autenticado ──
  document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('userDropdownBtn');
    const dropdown = document.getElementById('userDropdown');

    if (btn && dropdown) {
      btn.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
      });

      document.addEventListener('click', function () {
        dropdown.style.display = 'none';
      });

      dropdown.addEventListener('click', function (e) {
        e.stopPropagation();
      });
    }
  });
</script>
