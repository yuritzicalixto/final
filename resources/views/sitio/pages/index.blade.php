@extends('sitio.layout.principal')
@section('contenido')
    <!-- ═══════════════════════════════════════════════════════════════════════
         HERO SECTION
         ═══════════════════════════════════════════════════════════════════════ -->
    <section class="hero section-padding" id="inicio">
      <div class="container">
        <div class="hero__content">



          <!-- Title -->
          <h1 class="hero__title">
            Tu mejor <span>versión</span> comienza aquí
          </h1>

          <!-- Text -->
          <p class="hero__text">
            Una experiencia moderna de belleza: servicios premium, agendamiento inteligente y productos profesionales.
            Todo en un solo lugar para que luzcas increíble.
          </p>

          <!-- Actions -->
          <div class="hero__actions">
            <button type="button" id="heroBookingBtn" class="btn btn--primary"> <a href="{{ route('sitio.servicios')}}">Agendar Cita</a></button>
            <a href="{{ route('sitio.servicios')}}" class="btn btn--outline">Ver Servicios</a>
          </div>

          <!-- Features -->
          <div class="hero__features">
            <div class="hero__feature">
              <div class="hero__feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="hero__feature-text">
                Reservas Rápidas
                <small>En menos de 1 minuto</small>
              </div>
            </div>

            <div class="hero__feature">
              <div class="hero__feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
              </div>
              <div class="hero__feature-text">
                Estética Premium
                <small>Resultados de calidad</small>
              </div>
            </div>

            <div class="hero__feature">
              <div class="hero__feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
              </div>
              <div class="hero__feature-text">
                Tienda Online
                <small>Productos profesionales</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════════════════════
         ABOUT / NOSOTROS SECTION
         ═══════════════════════════════════════════════════════════════════════ -->
    <section class="about section-padding" id="nosotros">
      <div class="container">
        <div class="about__grid">
          <!-- Image -->
          <div class="about__image">
            <img src="https://images.unsplash.com/photo-1562322140-8baeececf3df?auto=format&fit=crop&w=800&q=80" alt="Nuestro salón">
          </div>

          <!-- Content -->
          <div class="about__content">
            <h2>Somos <span>Guillermo Gutiérrez</span> Salón</h2>
            <p>
              Con años de experiencia en la industria de la belleza, nos dedicamos a realzar tu imagen
              con técnicas modernas y productos de la más alta calidad. Nuestro equipo de profesionales
              está comprometido con la excelencia.
            </p>
            <p>
              Creemos que cada persona merece sentirse única y especial. Por eso, personalizamos cada
              servicio para adaptarnos a tu estilo y necesidades.
            </p>

            <!-- Features Grid -->
            <div class="about__features">
              <div class="about__feature-item">
                <div class="about__feature-icon">✓</div>
                <div>
                  <h4>Técnica Profesional</h4>
                  <p>Ejecución precisa y resultados consistentes</p>
                </div>
              </div>

              <div class="about__feature-item">
                <div class="about__feature-icon">✓</div>
                <div>
                  <h4>Estética Elegante</h4>
                  <p>Look moderno y sofisticado</p>
                </div>
              </div>

              <div class="about__feature-item">
                <div class="about__feature-icon">✓</div>
                <div>
                  <h4>Productos Premium</h4>
                  <p>Solo las mejores marcas</p>
                </div>
              </div>

              <div class="about__feature-item">
                <div class="about__feature-icon">✓</div>
                <div>
                  <h4>Cuidado Post-Servicio</h4>
                  <p>Recomendaciones personalizadas</p>
                </div>
              </div>
            </div>

            <a href="{{ route('sitio.nosotros')}}" class="btn btn--primary">Ver más sobre nosotros →</a>
          </div>
        </div>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════════════════════
         SERVICES SECTION - CAROUSEL
         ═══════════════════════════════════════════════════════════════════════ -->
    <section class="services section-padding" id="servicios">
      <div class="container">
        <!-- Heading -->
        <div class="section-heading">
          <h2>Nuestros Servicios</h2>
          <p>Conoce nuestro trabajo y los servicios premium que ofrecemos para realzar tu belleza</p>
        </div>

        <!-- Carousel -->
        <div class="services-carousel">
          <div class="services-carousel__track" id="servicesTrack">

            <!-- Slide 1: Colorimetría -->
            <article class="service-slide">
              <div class="service-slide__image">
                <img src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=800&q=80" alt="Colorimetría profesional" loading="lazy">
                <span class="service-slide__tag">Signature</span>
              </div>
              <div class="service-slide__content">
                <h3 class="service-slide__title">Colorimetría</h3>
                <p class="service-slide__text">Realizamos un diagnóstico completo de tu tono de piel para crear una propuesta de color personalizada. Utilizamos técnicas avanzadas para lograr resultados naturales y duraderos que realcen tu belleza única.</p>
              </div>
            </article>

            <!-- Slide 2: Cortes -->
            <article class="service-slide">
              <div class="service-slide__image">
                <img src="https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=800&q=80" alt="Cortes de cabello" loading="lazy">
                <span class="service-slide__tag">Cabello</span>
              </div>
              <div class="service-slide__content">
                <h3 class="service-slide__title">Cortes</h3>
                <p class="service-slide__text">Líneas limpias y modernas adaptadas a tu estilo y tipo de rostro. Nuestros estilistas expertos crean looks únicos con técnicas de vanguardia y un acabado profesional impecable.</p>
              </div>
            </article>

            <!-- Slide 3: Tinturas -->
            <article class="service-slide">
              <div class="service-slide__image">
                <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=800&q=80" alt="Tinturas y color" loading="lazy">
                <span class="service-slide__tag">Color</span>
              </div>
              <div class="service-slide__content">
                <h3 class="service-slide__title">Tinturas</h3>
                <p class="service-slide__text">Aplicación precisa con productos de alta calidad para tonos elegantes y consistentes. Desde coberturas de canas hasta transformaciones completas de color con resultados vibrantes.</p>
              </div>
            </article>
          </div>
        </div>

        <!-- Carousel Navigation -->


        <!-- Ver más -->
        <div class="text-center" style="margin-top: 40px;">
          <a href="{{ route('sitio.servicios')}}" class="btn btn--outline">Ver todos los servicios →</a>
        </div>

      </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════════════════════
         PRODUCTS SECTION
         ═══════════════════════════════════════════════════════════════════════ -->
    <section class="products section-padding" id="productos">
      <div class="container">
        <!-- Heading -->
        <div class="section-heading">
          <h2>Productos</h2>
          <p>Aparta tus favoritos y recógelos en el salón</p>
        </div>

        <!-- Grid -->
        <div class="products__grid">
          <article class="product-card" data-id="1" data-name="Shampoo Profesional" data-price="350" data-image="https://images.unsplash.com/photo-1526947425960-945c6e72858f?auto=format&fit=crop&w=400&q=80">
            <div class="product-card__image">
              <img src="https://images.unsplash.com/photo-1526947425960-945c6e72858f?auto=format&fit=crop&w=400&q=80" alt="Shampoo Profesional">
              <span class="product-card__badge">Nuevo</span>
            </div>
            <div class="product-card__body">
              <h3 class="product-card__name">Shampoo Profesional</h3>
              <p class="product-card__desc">Limpieza profunda con ingredientes naturales</p>
            </div>
          </article>

          <article class="product-card" data-id="2" data-name="Mascarilla Nutritiva" data-price="420" data-image="https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?auto=format&fit=crop&w=400&q=80">
            <div class="product-card__image">
              <img src="https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?auto=format&fit=crop&w=400&q=80" alt="Mascarilla Nutritiva">
            </div>
            <div class="product-card__body">
              <h3 class="product-card__name">Mascarilla Nutritiva</h3>
              <p class="product-card__desc">Hidratación intensiva para cabello dañado</p>
            </div>
          </article>

          <article class="product-card" data-id="3" data-name="Tratamiento Capilar" data-price="580" data-image="https://images.unsplash.com/photo-1599305090598-fe179d501227?auto=format&fit=crop&w=400&q=80">
            <div class="product-card__image">
              <img src="https://images.unsplash.com/photo-1599305090598-fe179d501227?auto=format&fit=crop&w=400&q=80" alt="Tratamiento Capilar">
              <span class="product-card__badge">Popular</span>
            </div>
            <div class="product-card__body">
              <h3 class="product-card__name">Tratamiento Capilar</h3>
              <p class="product-card__desc">Reparación profunda y brillo intenso</p>
            </div>
          </article>
        </div>

        <!-- Ver más -->
        <div class="text-center" style="margin-top: 40px;">
          <a href="{{ route('sitio.productos')}}" class="btn btn--outline">Ver todos los productos →</a>
        </div>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════════════════════
         GALLERY SECTION
         ═══════════════════════════════════════════════════════════════════════ -->
    <section class="gallery section-padding" id="galeria">
      <div class="container">
        <!-- Heading -->
        <div class="section-heading">
          <h2>Galería</h2>
          <p>Trabajos recientes: color, corte, styling y acabado final</p>
        </div>

        <!-- Grid -->
        <div class="gallery__grid">
          <figure class="gallery__item">
            <img src="{{ asset('sitio/img/assets/6.jpg') }}" alt="Colorimetría" loading="lazy">
            <div class="gallery__overlay">
              <span class="gallery__caption">Colorimetría</span>
            </div>
          </figure>

          <figure class="gallery__item">
            <img src="{{ asset('sitio/img/assets/man.jpg') }}" alt="Corte" loading="lazy">
            <div class="gallery__overlay">
              <span class="gallery__caption">Corte</span>
            </div>
          </figure>

          <figure class="gallery__item">
            <img src="{{ asset('sitio/img/assets/alizado.jpg') }}" alt="Alisado" loading="lazy">
            <div class="gallery__overlay">
              <span class="gallery__caption">Alisado</span>
            </div>
          </figure>

          <figure class="gallery__item">
            <img src="https://images.pexels.com/photos/8467962/pexels-photo-8467962.jpeg?_gl=1*172g7tq*_ga*MTI4ODkzNzY2MC4xNjcxMjU0NDkz*_ga_8JE65Q40S6*czE3NzA3NjcwMTEkbzIzJGcxJHQxNzcwNzY3NDQ0JGoyOSRsMCRoMA.." alt="Peinado" loading="lazy">
            <div class="gallery__overlay">
              <span class="gallery__caption">Peinado</span>
            </div>
          </figure>

          <figure class="gallery__item">
            <img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=800&q=80" alt="Manicure" loading="lazy">
            <div class="gallery__overlay">
              <span class="gallery__caption">Manicure</span>
            </div>
          </figure>

          <figure class="gallery__item">
            <img src="{{ asset('sitio/img/assets/pestania.jpg') }}" alt="Pestañas" loading="lazy">
            <div class="gallery__overlay">
              <span class="gallery__caption">Pestañas</span>
            </div>
          </figure>
        </div>

        <!-- Ver más -->
        <div class="text-center" style="margin-top: 40px;">
          <a href="{{ route('sitio.galeria')}}" class="btn btn--outline">Ver galería completa →</a>
        </div>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════════════════════
         CTA SECTION
         ═══════════════════════════════════════════════════════════════════════ -->
    <section class="cta-section">
      <div class="container">
        <div class="cta-card">
          <h2>¿Necesitas asesoría personalizada?</h2>
          <p>Contáctanos y te ayudamos a elegir el servicio perfecto para ti.</p>
          <a href="https://wa.me/522712147539" target="_blank" class="btn btn--primary">WhatsApp</a>
        </div>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════════════════════
         CONTACT SECTION
         ═══════════════════════════════════════════════════════════════════════ -->
    <section class="contact section-padding" id="contacto">
      <div class="container">
        <!-- Heading -->
        <div class="section-heading">
          <h2>Contacto</h2>
        </div>

        <!-- Grid -->
        <div class="contact__grid">
          <!-- Info Panel -->
          <article class="contact__info">
            <h3>Información</h3>

            <div class="contact__list">
              <div class="contact__item">
                <div class="contact__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                </div>
                <div class="contact__item-content">
                  <h4>WhatsApp / Teléfono</h4>
                  <a href="https://wa.me/522712147539" target="_blank">+52 271 214 7539</a>
                </div>
              </div>

              <div class="contact__item">
                <div class="contact__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </div>
                <div class="contact__item-content">
                  <h4>Ubicación</h4>
                  <p>Córdoba, Veracruz</p>
                </div>
              </div>

              <div class="contact__item">
                <div class="contact__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                </div>
                <div class="contact__item-content">
                  <h4>Dirección</h4>
                  <p>Calle 6 (Av. 3 y 5) — Córdoba, Ver.</p>
                </div>
              </div>

              <div class="contact__item">
                <div class="contact__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div class="contact__item-content">
                  <h4>Horario</h4>
                  <p>Lun – Sáb: 10:00 – 19:00</p>
                </div>
              </div>
            </div>

            <div class="contact__actions">
              <a href="https://wa.me/522712147539" target="_blank" class="btn btn--primary">
                WhatsApp
              </a>
              <a href="https://instagram.com/guillermo.salon/" target="_blank" class="btn btn--outline">
                Instagram
              </a>
            </div>
          </article>

          <!-- Map Panel -->
          <article class="contact__map">
            <div class="contact__map-header">
              <h4>Ubicación</h4>
              <a href="https://maps.google.com" target="_blank">Abrir en Maps →</a>
            </div>
            <div class="contact__map-frame">
              <iframe
                title="Ubicación en Google Maps"
                src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d3774.8255235946635!2d-96.93751597582067!3d18.894819957637438!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1scalle%206%20av%203%20y%205!5e0!3m2!1ses-419!2sus!4v1769455373332!5m2!1ses-419!2sus"
                loading="lazy"
                allowfullscreen
              ></iframe>
            </div>
          </article>
        </div>
      </div>
    </section>
@endsection
