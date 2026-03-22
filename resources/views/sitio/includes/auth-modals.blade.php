@guest

  {{-- ── Modal: Inicio de Sesión ── --}}
  <div class="overlay" id="loginOverlay">
    <div class="modal">
      <div class="modal__header">
        <h3 class="modal__title">Iniciar Sesión</h3>
        <button type="button" class="modal__close" id="closeLoginBtn">✕</button>
      </div>
      <div class="modal__body">

        {{-- Errores de validación (solo si el envío fue desde el login) --}}
        @if($errors->any() && old('_auth_modal') === 'login')
          <div class="auth-alert auth-alert--error">
            <ul>
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        {{-- Mensaje de estado (e.g. "Link de recuperación enviado") --}}
        @if(session('status'))
          <div class="auth-alert auth-alert--success">
            {{ session('status') }}
          </div>
        @endif

        <p class="modal__intro">Ingresa a tu cuenta para agendar citas y gestionar tus apartados.</p>

        <form method="POST" action="{{ route('login') }}">
          @csrf
          <input type="hidden" name="_auth_modal" value="login">

          <div class="form-group">
            <label class="form-label" for="login_email">Correo electrónico</label>
            <input type="email"
                   class="form-input"
                   id="login_email"
                   name="email"
                   value="{{ old('_auth_modal') === 'login' ? old('email') : '' }}"
                   placeholder="tu@correo.com"
                   required>
          </div>

          <div class="form-group">
            <label class="form-label" for="login_password">Contraseña</label>
            <input type="password"
                   class="form-input"
                   id="login_password"
                   name="password"
                   placeholder="••••••••"
                   required>
          </div>

          <div class="auth-options">
            <label class="auth-remember">
              <input type="checkbox" name="remember"> Recordarme
            </label>
            <a href="{{ route('password.request') }}" class="auth-link">¿Olvidaste tu contraseña?</a>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn--primary btn--full">Iniciar Sesión</button>
          </div>
        </form>

        <div class="auth-divider">
          <span>¿No tienes cuenta?</span>
        </div>

        <button type="button" class="btn btn--outline btn--full" onclick="closeLogin(); openRegister();">
          Crear cuenta
        </button>

      </div>
    </div>
  </div>

  {{-- ── Modal: Registro ── --}}
  <div class="overlay" id="registerOverlay">
    <div class="modal">
      <div class="modal__header">
        <h3 class="modal__title">Crear Cuenta</h3>
        <button type="button" class="modal__close" id="closeRegisterBtn">✕</button>
      </div>
      <div class="modal__body">

        {{-- Errores de validación (solo si el envío fue desde el registro) --}}
        @if($errors->any() && old('_auth_modal') === 'register')
          <div class="auth-alert auth-alert--error">
            <ul>
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <p class="modal__intro">Regístrate para agendar citas y apartar productos del salón.</p>

        <form method="POST" action="{{ route('register') }}">
          @csrf
          <input type="hidden" name="_auth_modal" value="register">

          <div class="form-group">
            <label class="form-label" for="register_name">Nombre completo</label>
            <input type="text"
                   class="form-input"
                   id="register_name"
                   name="name"
                   value="{{ old('_auth_modal') === 'register' ? old('name') : '' }}"
                   placeholder="Tu nombre"
                   required>
          </div>

          <div class="form-group">
            <label class="form-label" for="register_email">Correo electrónico</label>
            <input type="email"
                   class="form-input"
                   id="register_email"
                   name="email"
                   value="{{ old('_auth_modal') === 'register' ? old('email') : '' }}"
                   placeholder="tu@correo.com"
                   required>
          </div>

          <div class="form-group">
            <label class="form-label" for="register_phone">Teléfono</label>
            <input type="tel"
                   class="form-input"
                   id="register_phone"
                   name="phone"
                   value="{{ old('_auth_modal') === 'register' ? old('phone') : '' }}"
                   placeholder="Ej: 9511234567"
                   maxlength="15"
                   required>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="register_password">Contraseña</label>
              <input type="password"
                     class="form-input"
                     id="register_password"
                     name="password"
                     placeholder="Mínimo 8 caracteres"
                     required>
            </div>
            <div class="form-group">
              <label class="form-label" for="register_password_confirmation">Confirmar</label>
              <input type="password"
                     class="form-input"
                     id="register_password_confirmation"
                     name="password_confirmation"
                     placeholder="Repite la contraseña"
                     required>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn--primary btn--full">Crear Cuenta</button>
          </div>
        </form>

        <div class="auth-divider">
          <span>¿Ya tienes cuenta?</span>
        </div>

        <button type="button" class="btn btn--outline btn--full" onclick="closeRegister(); openLogin();">
          Iniciar sesión
        </button>

      </div>
    </div>
  </div>

@endguest


<style>
/* ===== Auth Modal Styles ===== */
.auth-options {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 20px;
  flex-wrap: wrap;
  gap: 8px;
}

.auth-remember {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 13px;
  color: var(--text-light);
  cursor: pointer;
}

.auth-remember input[type="checkbox"] {
  accent-color: var(--accent);
  width: 16px;
  height: 16px;
}

.auth-link {
  font-size: 13px;
  color: var(--accent);
  text-decoration: none;
  transition: opacity 0.2s;
}

.auth-link:hover {
  opacity: 0.8;
  text-decoration: underline;
}

.auth-divider {
  display: flex;
  align-items: center;
  gap: 15px;
  margin: 20px 0;
}

.auth-divider::before,
.auth-divider::after {
  content: '';
  flex: 1;
  height: 1px;
  background: var(--line);
}

.auth-divider span {
  font-size: 13px;
  color: var(--text-muted);
  white-space: nowrap;
}

.auth-alert {
  padding: 12px 16px;
  border-radius: var(--radius-md);
  margin-bottom: 20px;
  font-size: 13px;
  line-height: 1.5;
}

.auth-alert ul {
  margin: 0;
  padding-left: 18px;
}

.auth-alert--error {
  background: rgba(239, 68, 68, 0.1);
  border: 1px solid rgba(239, 68, 68, 0.25);
  color: #fca5a5;
}

.auth-alert--success {
  background: rgba(16, 185, 129, 0.1);
  border: 1px solid rgba(16, 185, 129, 0.25);
  color: #6ee7b7;
}

.btn--full {
  width: 100%;
  justify-content: center;
}
</style>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    // ── Auto-abrir modal si hubo errores de validación ──
    @if(old('_auth_modal') === 'login')
      if (typeof openLogin === 'function') openLogin();
    @elseif(old('_auth_modal') === 'register')
      if (typeof openRegister === 'function') openRegister();
    @endif
  });
</script>
