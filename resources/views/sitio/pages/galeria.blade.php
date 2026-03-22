@extends('sitio.layout.principal')
@section('contenido')

    <!-- Page Header -->
    <section class="page-header">
      <div class="container">
        <div class="page-header__content">
          <span class="page-header__badge">Nuestro Trabajo</span>
          <h1 class="page-header__title">Galería de <span>Resultados</span></h1>
          <p class="page-header__text">Descubre las transformaciones y trabajos que hemos realizado para nuestros clientes.</p>
        </div>
      </div>
    </section>

    <!-- Gallery Grid -->
    <section class="section-padding" style="background: var(--bg-section);">
      <div class="container">
        <!-- Colorimetría -->
        <div class="gallery-category">
          <h2 class="category-title">
            <span class="category-icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.555C21.965 6.012 17.461 2 12 2z"/></svg></span>
            Colorimetría & Color
          </h2>

          <div class="gallery-masonry">
            <figure class="gallery-masonry__item gallery-masonry__item--tall">
              <img src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=800&q=80" alt="Colorimetría profesional" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Balayage Caramelo</span>
                <span class="gallery-masonry__desc">Iluminación natural con tonos cálidos</span>
              </figcaption>
            </figure>

            <figure class="gallery-masonry__item">
              <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=800&q=80" alt="Mechas rubias" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Mechas Platinadas</span>
                <span class="gallery-masonry__desc">Contraste perfecto</span>
              </figcaption>
            </figure>

            <figure class="gallery-masonry__item">
              <img src="https://images.unsplash.com/photo-1595476108010-b4d1f102b1b1?auto=format&fit=crop&w=800&q=80" alt="Color fantasía" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Color Cobrizo</span>
                <span class="gallery-masonry__desc">Tonos vibrantes y elegantes</span>
              </figcaption>
            </figure>

            <figure class="gallery-masonry__item gallery-masonry__item--wide">
              <img src="https://images.unsplash.com/photo-1492106087820-71f1a00d2b11?auto=format&fit=crop&w=1200&q=80" alt="Transformación color" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Transformación Completa</span>
                <span class="gallery-masonry__desc">De oscuro a rubio cenizo</span>
              </figcaption>
            </figure>

            <figure class="gallery-masonry__item">
              <img src="https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=800&q=80" alt="Reflejos naturales" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Reflejos Naturales</span>
                <span class="gallery-masonry__desc">Efecto sol</span>
              </figcaption>
            </figure>

            <figure class="gallery-masonry__item">
              <img src="https://images.unsplash.com/photo-1580618672591-eb180b1a973f?auto=format&fit=crop&w=800&q=80" alt="Cobertura de canas" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Cobertura Total</span>
                <span class="gallery-masonry__desc">Resultado natural</span>
              </figcaption>
            </figure>
          </div>
        </div>

        <!-- Cortes -->
        <div class="gallery-category">
          <h2 class="category-title">
            <span class="category-icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="6" r="3"/><path d="M8.12 8.12 12 12"/><path d="M20 4 8.12 15.88"/><circle cx="6" cy="18" r="3"/><path d="M14.8 14.8 20 20"/></svg></span>
            Cortes & Estilos
          </h2>

          <div class="gallery-masonry">
            <figure class="gallery-masonry__item">
              <img src="https://images.unsplash.com/photo-1526045478516-99145907023c?auto=format&fit=crop&w=800&q=80" alt="Corte bob" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Bob Moderno</span>
                <span class="gallery-masonry__desc">Líneas limpias y elegantes</span>
              </figcaption>
            </figure>

            <figure class="gallery-masonry__item gallery-masonry__item--tall">
              <img src="https://images.unsplash.com/photo-1527799820374-9f61f297199d?auto=format&fit=crop&w=800&q=80" alt="Corte largo" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Capas Largas</span>
                <span class="gallery-masonry__desc">Movimiento y volumen</span>
              </figcaption>
            </figure>

            <figure class="gallery-masonry__item">
              <img src="https://images.unsplash.com/photo-1605980776721-e5a8d7fd0b1f?auto=format&fit=crop&w=800&q=80" alt="Corte pixie" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Pixie Cut</span>
                <span class="gallery-masonry__desc">Atrevido y moderno</span>
              </figcaption>
            </figure>

            <figure class="gallery-masonry__item">
              <img src="https://images.unsplash.com/photo-1600948836101-f9ffda59d250?auto=format&fit=crop&w=800&q=80" alt="Corte masculino" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Fade Clásico</span>
                <span class="gallery-masonry__desc">Degradado perfecto</span>
              </figcaption>
            </figure>

            <figure class="gallery-masonry__item gallery-masonry__item--wide">
              <img src="https://images.unsplash.com/photo-1562322140-8baeececf3df?auto=format&fit=crop&w=1200&q=80" alt="Corte elegante" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Lob Elegante</span>
                <span class="gallery-masonry__desc">Versátil y sofisticado</span>
              </figcaption>
            </figure>

            <figure class="gallery-masonry__item">
              <img src="https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&w=800&q=80" alt="Corte con flequillo" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Flequillo Cortina</span>
                <span class="gallery-masonry__desc">Enmarca el rostro</span>
              </figcaption>
            </figure>
          </div>
        </div>

        <!-- Tratamientos -->
        <div class="gallery-category">
          <h2 class="category-title">
            <span class="category-icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/></svg></span>
            Tratamientos & Alisados
          </h2>

          <div class="gallery-masonry">
            <figure class="gallery-masonry__item gallery-masonry__item--wide">
              <img src="https://images.unsplash.com/photo-1520975958225-75c9f1a3f7a7?auto=format&fit=crop&w=1200&q=80" alt="Alisado keratina" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Keratina Brasileña</span>
                <span class="gallery-masonry__desc">Transformación total - Antes y después</span>
              </figcaption>
            </figure>

            <figure class="gallery-masonry__item">
              <img src="https://images.unsplash.com/photo-1599305090598-fe179d501227?auto=format&fit=crop&w=800&q=80" alt="Tratamiento brillo" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Brillo Intenso</span>
                <span class="gallery-masonry__desc">Hidratación profunda</span>
              </figcaption>
            </figure>

            <figure class="gallery-masonry__item">
              <img src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=800&q=80" alt="Botox capilar" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Botox Capilar</span>
                <span class="gallery-masonry__desc">Rejuvenecimiento</span>
              </figcaption>
            </figure>

            <figure class="gallery-masonry__item gallery-masonry__item--tall">
              <img src="https://images.unsplash.com/photo-1487412947147-5cebf100ffc2?auto=format&fit=crop&w=800&q=80" alt="Ondas suaves" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Ondas Suaves</span>
                <span class="gallery-masonry__desc">Efecto natural</span>
              </figcaption>
            </figure>

            <figure class="gallery-masonry__item">
              <img src="https://images.unsplash.com/photo-1516975080664-ed2fc6a32937?auto=format&fit=crop&w=800&q=80" alt="Reparación" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Reparación Total</span>
                <span class="gallery-masonry__desc">Cabello dañado restaurado</span>
              </figcaption>
            </figure>
          </div>
        </div>

        <!-- Uñas y Estética -->
        <div class="gallery-category">
          <h2 class="category-title">
            <span class="category-icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg></span>
            Uñas & Estética
          </h2>

          <div class="gallery-masonry">
            <figure class="gallery-masonry__item">
              <img src="https://images.unsplash.com/photo-1604654894610-df63bc536371?auto=format&fit=crop&w=800&q=80" alt="Manicure elegante" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Manicure Francés</span>
                <span class="gallery-masonry__desc">Clásico y elegante</span>
              </figcaption>
            </figure>

            <figure class="gallery-masonry__item">
              <img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=800&q=80" alt="Diseño uñas" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Diseño Artístico</span>
                <span class="gallery-masonry__desc">Creatividad en cada detalle</span>
              </figcaption>
            </figure>

            <figure class="gallery-masonry__item gallery-masonry__item--tall">
              <img src="https://images.unsplash.com/photo-1519014816548-bf5fe059798b?auto=format&fit=crop&w=800&q=80" alt="Pedicure spa" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Pedicure Spa</span>
                <span class="gallery-masonry__desc">Cuidado completo</span>
              </figcaption>
            </figure>

            <figure class="gallery-masonry__item gallery-masonry__item--wide">
              <img src="https://images.unsplash.com/photo-1526040652367-ac003a0475fe?auto=format&fit=crop&w=1200&q=80" alt="Lifting pestañas" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Lifting de Pestañas</span>
                <span class="gallery-masonry__desc">Mirada definida</span>
              </figcaption>
            </figure>

            <figure class="gallery-masonry__item">
              <img src="https://images.unsplash.com/photo-1631214524020-7e18db9a8f92?auto=format&fit=crop&w=800&q=80" alt="Nail art" loading="lazy">
              <figcaption>
                <span class="gallery-masonry__title">Nail Art</span>
                <span class="gallery-masonry__desc">Diseños únicos</span>
              </figcaption>
            </figure>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="cta-section">
      <div class="container">
        <div class="cta-card">
          <h2>¿Lista para tu transformación?</h2>
          <p>Agenda tu cita y deja que nuestros expertos realcen tu belleza.</p>
        </div>
      </div>
    </section>

@endsection
