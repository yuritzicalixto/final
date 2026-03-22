@extends('sitio.layout.principal')
@section('contenido')

    <!-- Page Header -->
    <section class="page-header">
      <div class="container">
        <div class="page-header__content">
          <span class="page-header__badge">Catálogo Completo</span>
          <h1 class="page-header__title">Nuestros <span>Servicios</span></h1>
          <p class="page-header__text">Descubre todos los servicios profesionales que ofrecemos para realzar tu belleza.</p>
        </div>
      </div>
    </section>

    <!-- Categorías de servicios (dinámicas desde BD) -->
    <section class="section-padding" style="background: var(--bg-section);">
      <div class="container">

        @forelse($servicesByCategory as $categoryName => $services)
          <div class="service-category">

            {{-- Título de categoría con ícono dinámico --}}
            <h2 class="category-title">
              <span class="category-icon">
                @switch($categoryName)
                  @case('Cabello')
                    {{-- Ícono de tijeras --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="6" r="3"/><path d="M8.12 8.12 12 12"/><path d="M20 4 8.12 15.88"/><circle cx="6" cy="18" r="3"/><path d="M14.8 14.8 20 20"/></svg>
                    @break
                  @case('Tratamientos')
                    {{-- Ícono de estrella/sparkle --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/></svg>
                    @break
                  @case('Estética & Uñas')
                    {{-- Ícono de escudo/check --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/></svg>
                    @break
                  @default
                    {{-- Ícono genérico para "Paquetes Especiales" u otras categorías --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                @endswitch
              </span>
              {{ $categoryName }}
            </h2>

            <div class="services-full-grid">
              @foreach($services as $service)
                <article class="service-full-card {{ $service->is_highlighted ? 'service-full-card--highlight' : '' }}">

                  {{-- Imagen --}}
                  <div class="service-full-card__image">
                    <img src="{{ $service->image_url }}"
                         alt="{{ $service->name }}"
                         loading="lazy">

                    {{-- Badge/etiqueta si existe --}}
                    @if($service->tag)
                      <span class="service-full-card__tag">{{ $service->tag }}</span>
                    @endif
                  </div>

                  {{-- Contenido --}}
                  <div class="service-full-card__content">
                    <h3>{{ $service->name }}</h3>
                    <p>{{ $service->description }}</p>

                    {{-- Características (features) --}}
                    @if($service->features)
                      <ul class="service-full-card__features">
                        @foreach($service->features_array as $feature)
                          <li>{{ $feature }}</li>
                        @endforeach
                      </ul>
                    @endif

                    {{-- Footer: Precio + Botón --}}
                    <div class="service-full-card__footer">
                      <span class="price">Desde <strong>{{ $service->price_formatted }}</strong></span>
                      {{-- <button type="button" class="btn btn--primary btn--sm" onclick="openLogin()">Reservar</button> --}}
                      {{-- AUTENTICACIÓN --}}
                        @auth
                          {{-- Usuario autenticado: lo lleva directo a agendar con el servicio preseleccionado --}}
                          <a href="{{ route('client.appointments.create', ['service_id' => $service->id]) }}"
                             class="btn btn--primary btn--sm">
                            Agendar
                          </a>
                        @else
                          {{-- Usuario NO autenticado: lo manda a login, guardando a dónde quería ir --}}
                          <a href="{{ route('login', ['redirect_to' => route('client.appointments.create', ['service_id' => $service->id])]) }}"
                             class="btn btn--primary btn--sm">
                            Agendar
                          </a>
                        @endauth
                      {{-- AUTENTICACIÓN --}}
                    </div>
                  </div>
                </article>
              @endforeach
            </div>
          </div>
        @empty
          {{-- Si no hay servicios aún --}}
          <div style="text-align: center; padding: 60px 20px;">
            <p style="color: var(--text-muted); font-size: 18px;">Próximamente estaremos publicando nuestros servicios.</p>
            <p style="color: var(--text-muted); font-size: 14px; margin-top: 10px;">Contáctanos por WhatsApp para más información.</p>
          </div>
        @endforelse

      </div>
    </section>

    <!-- CTA -->
    <section class="cta-section">
      <div class="container">
        <div class="cta-card">
          <h2>¿Necesitas asesoría personalizada?</h2>
          <p>Contáctanos y te ayudamos a elegir el servicio perfecto para ti.</p>
          <a href="https://wa.me/522712147539" target="_blank" class="btn btn--primary">WhatsApp</a>
        </div>
      </div>
    </section>

@endsection
