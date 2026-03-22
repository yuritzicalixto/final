@extends('sitio.layout.principal')
@section('contenido')

    <!-- Page Header -->
    <section class="page-header">
      <div class="container">
        <div class="page-header__content">
          <span class="page-header__badge">Conócenos</span>
          <h1 class="page-header__title">Nuestra <span>Historia</span></h1>
          <p class="page-header__text">Más de 5 años dedicados a realzar la belleza de nuestros clientes con pasión, técnica y profesionalismo.</p>
        </div>
      </div>
    </section>

    <!-- Historia -->
    <section class="section-padding" style="background: var(--bg-section);">
      <div class="container">
        <div class="about__grid">
          <div class="about__image">
            <img src="https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=800&q=80" alt="Nuestro salón">
          </div>
          <div class="about__content">
            <h2>El comienzo de un <span>sueño</span></h2>
            <p>
              Guillermo Gutiérrez Salón nació en 2020 con una visión clara: crear un espacio donde la belleza
              y el bienestar se fusionaran en una experiencia única. Lo que comenzó como un pequeño local
              en el corazón de Córdoba, Veracruz, se ha convertido en un referente de estilo y profesionalismo.
            </p>
            <p>
              Nuestro fundador, Guillermo Gutiérrez, inició su camino en el mundo de la estética desde muy joven,
              formándose con los mejores maestros del país y del extranjero. Su pasión por el color, las texturas
              y la transformación lo llevó a crear un salón que hoy es sinónimo de calidad.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Valores -->
    <section class="section-padding">
      <div class="container">
        <div class="section-heading">
          <h2>Nuestros Valores</h2>
          <p>Los principios que guían cada servicio que ofrecemos</p>
        </div>

        <div class="values-grid">
          <div class="value-card">
            <div class="value-card__icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
              </svg>
            </div>
            <h3 class="value-card__title">Excelencia</h3>
            <p class="value-card__text">Cada detalle cuenta. Nos esforzamos por superar las expectativas en cada servicio, utilizando las mejores técnicas y productos del mercado.</p>
          </div>

          <div class="value-card">
            <div class="value-card__icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
              </svg>
            </div>
            <h3 class="value-card__title">Pasión</h3>
            <p class="value-card__text">Amamos lo que hacemos. Cada transformación es una oportunidad de crear arte y hacer sentir especial a nuestros clientes.</p>
          </div>

          <div class="value-card">
            <div class="value-card__icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 11V6a2 2 0 0 0-2-2a2 2 0 0 0-2 2"/>
                <path d="M14 10V4a2 2 0 0 0-2-2a2 2 0 0 0-2 2v2"/>
                <path d="M10 10.5V6a2 2 0 0 0-2-2a2 2 0 0 0-2 2v8"/>
                <path d="M18 8a2 2 0 1 1 4 0v6a8 8 0 0 1-8 8h-2c-2.8 0-4.5-.86-5.99-2.34l-3.6-3.6a2 2 0 0 1 2.83-2.82L7 15"/>
              </svg>
            </div>
            <h3 class="value-card__title">Compromiso</h3>
            <p class="value-card__text">Tu satisfacción es nuestra prioridad. Escuchamos, asesoramos y trabajamos contigo para lograr el resultado que deseas.</p>
          </div>

          <div class="value-card">
            <div class="value-card__icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/>
                <path d="M9 18h6"/>
                <path d="M10 22h4"/>
              </svg>
            </div>
            <h3 class="value-card__title">Innovación</h3>
            <p class="value-card__text">Nos mantenemos actualizados con las últimas tendencias y técnicas para ofrecerte siempre lo mejor de la industria.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Equipo -->
    <!-- <section class="section-padding" style="background: var(--bg-section);">
      <div class="container">
        <div class="section-heading">
          <h2>Nuestro Equipo</h2>
          <p>Profesionales apasionados dedicados a tu belleza</p>
        </div>

        <div class="team-grid">
          <div class="team-card">
            <div class="team-card__image">
              <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80" alt="Guillermo Gutiérrez">
            </div>
            <div class="team-card__info">
              <h3 class="team-card__name">Guillermo Gutiérrez</h3>
              <p class="team-card__role">Fundador & Director Creativo</p>
              <p class="team-card__bio">Más de 20 años de experiencia en colorimetría y técnicas avanzadas de corte.</p>
            </div>
          </div>

          <div class="team-card">
            <div class="team-card__image">
              <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=400&q=80" alt="María López">
            </div>
            <div class="team-card__info">
              <h3 class="team-card__name">María López</h3>
              <p class="team-card__role">Especialista en Color</p>
              <p class="team-card__bio">Experta en balayage, mechas y técnicas de coloración natural.</p>
            </div>
          </div>

          <div class="team-card">
            <div class="team-card__image">
              <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=400&q=80" alt="Ana García">
            </div>
            <div class="team-card__info">
              <h3 class="team-card__name">Ana García</h3>
              <p class="team-card__role">Estilista Senior</p>
              <p class="team-card__bio">Especializada en cortes modernos y peinados para eventos especiales.</p>
            </div>
          </div>

          <div class="team-card">
            <div class="team-card__image">
              <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80" alt="Carlos Hernández">
            </div>
            <div class="team-card__info">
              <h3 class="team-card__name">Carlos Hernández</h3>
              <p class="team-card__role">Especialista en Tratamientos</p>
              <p class="team-card__bio">Experto en queratina, alisados y tratamientos capilares restauradores.</p>
            </div>
          </div>
        </div>
      </div>
    </section> -->

    <!-- Instalaciones -->
    <section class="section-padding">
      <div class="container">
        <div class="section-heading">
          <h2>Nuestras Instalaciones</h2>
          <p>Un espacio diseñado para tu comodidad y bienestar</p>
        </div>

        <div class="facilities-grid">
          <div class="facility-card facility-card--large">
            <img src="https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=1200&q=80" alt="Área principal">
            <div class="facility-card__overlay">
              <span>Área Principal</span>
            </div>
          </div>

          <div class="facility-card">
            <img src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=600&q=80" alt="Zona de lavado">
            <div class="facility-card__overlay">
              <span>Zona de Lavado</span>
            </div>
          </div>

          <div class="facility-card">
            <img src="https://images.unsplash.com/photo-1633681926035-ec1ac984418a?auto=format&fit=crop&w=600&q=80" alt="Área de manicure">
            <div class="facility-card__overlay">
              <span>Área de Manicure</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="cta-section">
      <div class="container">
        <div class="cta-card">
          <h2>¿Lista para tu transformación?</h2>
          <p>Agenda tu cita y descubre por qué somos el salón preferido de Córdoba.</p>
        </div>
      </div>
    </section>

@endsection
