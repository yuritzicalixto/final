@extends('sitio.layout.principal')
@section('contenido')

    <!-- Page Header -->
    <section class="page-header">
      <div class="container">
        <div class="page-header__content">
          <span class="page-header__badge">Tienda</span>
          <h1 class="page-header__title">Nuestros <span>Productos</span></h1>
          <p class="page-header__text">Productos profesionales que usamos en el salón, ahora disponibles para ti.</p>
        </div>
      </div>
    </section>

    <!-- Productos -->
    <section class="section-padding" style="background: var(--bg-section);">
      <div class="container">

        {{-- Verificar si hay productos --}}
        @if($categories->isEmpty())
            {{-- Mensaje cuando no hay productos --}}
            <div class="empty-products text-center" style="padding: 60px 20px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 20px; opacity: 0.3;">
                    <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <h3 style="font-family: var(--font-display); font-size: 24px; margin-bottom: 10px; color: var(--text-white);">
                    Próximamente
                </h3>
                <p style="color: var(--text-muted); max-width: 400px; margin: 0 auto;">
                    Estamos preparando nuestra tienda de productos profesionales. ¡Vuelve pronto!
                </p>
            </div>
        @else
            {{-- Iterar por cada categoría --}}
            @foreach($categories as $category)
                @if($category->products->isNotEmpty())
                    <div class="product-category">
                        {{-- Título de categoría con icono dinámico --}}
                        <h2 class="category-title">
                            <span class="category-icon">
                                {{-- Iconos según el slug de la categoría --}}
                                @switch($category->slug)
                                    @case('shampoos')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M10 2v2"/><path d="M14 2v2"/><path d="M16 8a1 1 0 0 1 1 1v8a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V9a1 1 0 0 1 1-1h14a4.5 4.5 0 1 1 0 9H17"/><path d="M6 8V6a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        </svg>
                                        @break
                                    @case('tratamientos')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/>
                                        </svg>
                                        @break
                                    @case('styling')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><circle cx="12" cy="12" r="3"/><path d="m16 16-1.5-1.5"/>
                                        </svg>
                                        @break
                                    @case('acondicionadores')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M8 2h8"/><path d="M9 2v2.789a4 4 0 0 1-.672 2.219l-.656.984A4 4 0 0 0 7 10.212V20a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-9.789a4 4 0 0 0-.672-2.219l-.656-.984A4 4 0 0 1 15 4.788V2"/>
                                        </svg>
                                        @break
                                    @case('tintes-color')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.555C21.965 6.012 17.461 2 12 2z"/>
                                        </svg>
                                        @break
                                    @case('accesorios')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="6" cy="6" r="3"/><path d="M8.12 8.12 12 12"/><path d="M20 4 8.12 15.88"/><circle cx="6" cy="18" r="3"/><path d="M14.8 14.8 20 20"/>
                                        </svg>
                                        @break
                                    @default
                                        {{-- Icono por defecto --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                @endswitch
                            </span>
                            {{ $category->name }}
                        </h2>

                        {{-- Grid de productos de esta categoría --}}
                        <div class="products__grid">
                            @foreach($category->products as $index => $product)
                                <article class="product-card"
                                         data-id="{{ $product->id }}"
                                         data-name="{{ $product->name }}"
                                         data-price="{{ $product->price }}"
                                         data-image="{{ $product->image_url }}"
                                         data-stock="{{ $product->stock }}">
                                    <div class="product-card__image">
                                        <img src="{{ $product->image_url }}"
                                             alt="{{ $product->name }}"
                                             loading="lazy">

                                        {{-- Badge dinámico --}}
                                        @if($product->stock <= 0)
                                            <span class="product-card__badge product-card__badge--soldout">Agotado</span>
                                        @elseif($product->stock <= 5)
                                            <span class="product-card__badge product-card__badge--warning">¡Últimos!</span>
                                        @elseif($product->created_at->diffInDays(now()) <= 7)
                                            <span class="product-card__badge">Nuevo</span>
                                        @endif
                                    </div>
                                    <div class="product-card__body">
                                        <h3 class="product-card__name">{{ $product->name }}</h3>
                                        <p class="product-card__desc">
                                            {{ $product->description ?? 'Producto profesional de alta calidad.' }}
                                        </p>

                                        {{-- Footer con precio y botón --}}
                                        <div class="product-card__footer">
                                            {{-- Precio formateado --}}
                                            <span class="product-card__price">${{ number_format($product->price, 2) }}</span>

                                            {{-- Botón de agregar al carrito --}}
                                            @if($product->stock > 0)
                                                <button type="button"
                                                        class="product-card__btn add-to-cart-btn"
                                                        aria-label="Agregar al carrito">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                </button>
                                            @else
                                                <button type="button"
                                                        class="product-card__btn product-card__btn--disabled"
                                                        disabled
                                                        aria-label="Producto agotado">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        @endif



      </div>
    </section>

@endsection
