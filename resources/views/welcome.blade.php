<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Unidad Educativa Vicente León - Educación de Excelencia</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@700;900&display=swap');
    
    body {
      font-family: 'Poppins', sans-serif;
    }
    
    .hero-bg {
      background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
      background-size: cover;
      background-position: center;
    }
    
    .hover-scale {
      transition: transform 0.3s ease;
    }
    
    .hover-scale:hover {
      transform: scale(1.03);
    }
    
    .nav-link {
      position: relative;
    }
    
    .nav-link:after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: -2px;
      left: 0;
      background-color: #D4AF37;
      transition: width 0.3s ease;
    }
    
    .nav-link:hover:after {
      width: 100%;
    }
    
    .btn-gold {
      background-color: #D4AF37;
      transition: all 0.3s ease;
    }
    
    .btn-gold:hover {
      background-color: #C9A227;
      transform: translateY(-2px);
    }
    
    .section-title {
      position: relative;
      display: inline-block;
    }
    
    .section-title:after {
      content: '';
      position: absolute;
      width: 50%;
      height: 3px;
      bottom: -8px;
      left: 25%;
      background-color: #B22222;
    }
  </style>
</head>
<body class="bg-gray-50">

  <!-- Barra superior -->
  <div class="bg-gray-800 text-white text-sm py-2">
    <div class="container mx-auto px-4 flex justify-between items-center">
      <div class="flex items-center space-x-4">
        <a href="mailto:info@vicenteleon.edu.ec" class="hover:text-yellow-300 transition">
          <i class="fas fa-envelope mr-1"></i> info@vicenteleon.edu.ec
        </a>
        <a href="tel:+59332810500" class="hover:text-yellow-300 transition">
          <i class="fas fa-phone-alt mr-1"></i> (03) 2810-500
        </a>
      </div>
      <div class="flex items-center space-x-4">
        <a href="#" class="hover:text-yellow-300 transition"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="hover:text-yellow-300 transition"><i class="fab fa-instagram"></i></a>
        <a href="#" class="hover:text-yellow-300 transition"><i class="fab fa-tiktok"></i></a>
        <a href="#" class="hover:text-yellow-300 transition"><i class="fab fa-youtube"></i></a>
      </div>
    </div>
  </div>

  <!-- Navegación principal -->
  <header class="bg-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
      <div class="flex items-center">
        
        <img src="app-resources/img/logos/ue-vicente-leon.jpg" alt="Escudo Vicente León" class="h-16 mr-4">
        <div>
          <h1 class="text-xl font-bold text-gray-800 font-serif">UNIDAD EDUCATIVA<br><span class="text-red-700">VICENTE LEÓN</span></h1>
        </div>
      </div>
      
      <nav class="hidden md:flex space-x-8">
        <a href="#inicio" class="nav-link text-gray-800 font-medium">INICIO</a>
        <a href="#nosotros" class="nav-link text-gray-800 font-medium">NOSOTROS</a>
        <a href="#academico" class="nav-link text-gray-800 font-medium">ACADÉMICO</a>
        <a href="#admisiones" class="nav-link text-gray-800 font-medium">ADMISIONES</a>
        <a href="#contacto" class="nav-link text-gray-800 font-medium">CONTACTO</a>
          @if (Route::has('login'))
               
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="inav-link text-gray-800 font-medium"
                        >
                            HOME
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="nav-link text-gray-800 font-medium"
                        >
                            SESION
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="nav-link text-gray-800 font-medium">
                                REGISTRO DOCENTES
                            </a>
                        @endif
                    @endauth
              
            @endif
      </nav>
      
      <button class="md:hidden text-gray-800">
        <i class="fas fa-bars text-2xl"></i>
      </button>
    </div>
  </header>

  <!-- Hero Section -->
  <section id="inicio" class="hero-bg text-white py-32">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-4xl md:text-6xl font-bold mb-6 font-serif">EDUCACIÓN CON EXCELENCIA</h2>
      <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">Formamos líderes con valores, pensamiento crítico y compromiso social</p>
      <div class="flex flex-col sm:flex-row justify-center gap-4">
        <a href="#admisiones" class="btn-gold text-gray-900 font-bold px-8 py-3 rounded-lg shadow-lg">ADMISIONES</a>
        <a href="#contacto" class="bg-transparent border-2 border-white text-white font-bold px-8 py-3 rounded-lg hover:bg-white/20 transition shadow-lg">CONTÁCTANOS</a>
      </div>
    </div>
  </section>

  <!-- Sección Destacada -->
  <section class="bg-red-700 text-white py-12">
    <div class="container mx-auto px-4">
      <div class="grid md:grid-cols-3 gap-8 text-center">
        <div class="flex flex-col items-center">
          <i class="fas fa-graduation-cap text-4xl mb-4"></i>
          <h3 class="text-xl font-bold mb-2">EXCELENCIA ACADÉMICA</h3>
          <p>Programas educativos de alto nivel académico</p>
        </div>
        <div class="flex flex-col items-center">
          <i class="fas fa-users text-4xl mb-4"></i>
          <h3 class="text-xl font-bold mb-2">FORMACIÓN INTEGRAL</h3>
          <p>Desarrollo de habilidades sociales y emocionales</p>
        </div>
        <div class="flex flex-col items-center">
          <i class="fas fa-shield-alt text-4xl mb-4"></i>
          <h3 class="text-xl font-bold mb-2">VALORES</h3>
          <p>Educación basada en principios éticos y morales</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Sobre Nosotros -->
  <section id="nosotros" class="py-16 bg-white">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl font-bold text-center mb-16 section-title font-serif">SOBRE NOSOTROS</h2>
      
      <div class="grid md:grid-cols-2 gap-12 items-center">
        <div>
          <h3 class="text-2xl font-bold text-gray-800 mb-4">UN LEGADO DE MAS DE 100 AÑOS</h3>
          <p class="text-gray-600 mb-4">Fundada en 1840, la Unidad Educativa Vicente León ha sido pionera en la educación de calidad en Latacunga, formando generaciones de profesionales y ciudadanos comprometidos con el desarrollo del país.</p>
          <p class="text-gray-600 mb-6">Nuestro lema "Inmortal Juventud Adelante" refleja el espíritu de superación y progreso que inculcamos en nuestros estudiantes.</p>
          
          <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg">
              <div class="text-red-700 text-3xl font-bold mb-2">15K+</div>
              <p class="text-gray-600">Egresados</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
              <div class="text-red-700 text-3xl font-bold mb-2">95%</div>
              <p class="text-gray-600">Ingreso universitario</p>
            </div>
          </div>
        </div>
        
        <div class="relative hover-scale">
          <img  src="app-resources/img/banners/vicente leon portada.png" alt="Historia Vicente León" class="rounded-lg shadow-xl w-full">
          <div class="absolute -bottom-6 -right-6 bg-yellow-500 text-gray-900 p-4 rounded-lg shadow-lg font-bold text-lg font-serif">
            Desde 1840
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Oferta Académica -->
  <section id="academico" class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl font-bold text-center mb-16 section-title font-serif">OFERTA ACADÉMICA</h2>
      
      <div class="grid md:grid-cols-3 gap-8">
         <!-- Educación Inicial -->
         <div class="bg-white rounded-lg overflow-hidden shadow-md hover-scale">
            <div class="h-48 overflow-hidden">
              <img src="app-resources/img/banners/banner-educacion-inicial.jpeg"alt="Educación Básica" class="w-full h-full object-cover">
            </div>
            <div class="p-6">
              <div class="text-yellow-500 text-2xl font-bold mb-2">1° - 2° EI</div>
              <h3 class="text-xl font-bold text-gray-800 mb-3">EDUCACIÓN INICIAL</h3>
              <p class="text-gray-600 mb-4">Formación integral con énfasis en valores, pensamiento crítico y habilidades fundamentales.</p>
              <ul class="space-y-2 mb-6 text-gray-600">
                <li class="flex items-start">
                  <i class="fas fa-check text-yellow-500 mr-2 mt-1"></i> Educacion inicial
                </li>
                <li class="flex items-start">
                  <i class="fas fa-check text-yellow-500 mr-2 mt-1"></i> Valores y principios
                </li>
                <li class="flex items-start">
                  <i class="fas fa-check text-yellow-500 mr-2 mt-1"></i> Valores
                </li>
              </ul>
              <a href="#" class="text-red-700 font-semibold hover:underline">Más información →</a>
            </div>
          </div>
        <!-- Educación Básica -->
        <div class="bg-white rounded-lg overflow-hidden shadow-md hover-scale">
          <div class="h-48 overflow-hidden">
            <img src="app-resources/img/banners/Educacion_general_basica_banner.png" alt="Educación Básica" class="w-full h-full object-cover">
          </div>
          <div class="p-6">
            <div class="text-yellow-500 text-2xl font-bold mb-2">1° - 10° EGB</div>
            <h3 class="text-xl font-bold text-gray-800 mb-3">EDUCACIÓN GENERAL BÁSICA</h3>
            <p class="text-gray-600 mb-4">Formación integral con énfasis en valores, pensamiento crítico y habilidades fundamentales.</p>
            <ul class="space-y-2 mb-6 text-gray-600">
              <li class="flex items-start">
                <i class="fas fa-check text-yellow-500 mr-2 mt-1"></i> Programa de lectura avanzada
              </li>
              <li class="flex items-start">
                <i class="fas fa-check text-yellow-500 mr-2 mt-1"></i> Desarrollo del pensamiento lógico-matemático
              </li>
              <li class="flex items-start">
                <i class="fas fa-check text-yellow-500 mr-2 mt-1"></i> Educación artística y cultural
              </li>
            </ul>
            <a href="#" class="text-red-700 font-semibold hover:underline">Más información →</a>
          </div>
        </div>
        
        <!-- Bachillerato General -->
        <div class="bg-white rounded-lg overflow-hidden shadow-md hover-scale">
          <div class="h-48 overflow-hidden">
            <img src="app-resources/img/banners/bachillerato general unificado_banner.png"alt="Bachillerato General" class="w-full h-full object-cover">
          </div>
          <div class="p-6">
            <div class="text-yellow-500 text-2xl font-bold mb-2">1° - 3° BGU</div>
            <h3 class="text-xl font-bold text-gray-800 mb-3">BACHILLERATO GENERAL</h3>
            <p class="text-gray-600 mb-4">Preparación universitaria con sólida base científica-humanística y desarrollo de competencias.</p>
            <ul class="space-y-2 mb-6 text-gray-600">
              <li class="flex items-start">
                <i class="fas fa-check text-yellow-500 mr-2 mt-1"></i> Orientación vocacional
              </li>
              <li class="flex items-start">
                <i class="fas fa-check text-yellow-500 mr-2 mt-1"></i> Proyectos de investigación
              </li>
              <li class="flex items-start">
                <i class="fas fa-check text-yellow-500 mr-2 mt-1"></i> Participación estudiantil
              </li>
            </ul>
            <a href="#" class="text-red-700 font-semibold hover:underline">Más información →</a>
          </div>
        </div>
        
        <!-- Bachillerato Técnico -->
        <div class="bg-white rounded-lg overflow-hidden shadow-md hover-scale">
          <div class="h-48 overflow-hidden">
             <img src="app-resources/img/banners/banner-bachillerato-tecnico.webp" alt="Bachillerato Técnico" class="w-full h-full object-cover">
          </div>
          <div class="p-6">
            <div class="text-yellow-500 text-2xl font-bold mb-2">1° - 3° BT</div>
            <h3 class="text-xl font-bold text-gray-800 mb-3">BACHILLERATO TÉCNICO</h3>
            <p class="text-gray-600 mb-4">Especialización en áreas técnicas con salida laboral inmediata o continuación universitaria.</p>
            <ul class="space-y-2 mb-6 text-gray-600">
              <li class="flex items-start">
                <i class="fas fa-check text-yellow-500 mr-2 mt-1"></i> Desarrollo de Software
              </li>
              <li class="flex items-start">
                <i class="fas fa-check text-yellow-500 mr-2 mt-1"></i> Gestion Administrativa y Logistica
              </li>
              <li class="flex items-start">
                <i class="fas fa-check text-yellow-500 mr-2 mt-1"></i> Deportes y Recreacion
              </li>
              <li class="flex items-start">
                <i class="fas fa-check text-yellow-500 mr-2 mt-1"></i> Prácticas preprofesionales
              </li>
            </ul>
            <a href="#" class="text-red-700 font-semibold hover:underline">Más información →</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Admisiones -->
  <section id="admisiones" class="py-16 bg-white">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl font-bold text-center mb-16 section-title font-serif">ADMISIONES</h2>
      
      <div class="grid md:grid-cols-2 gap-12 items-center">
        <div class="relative hover-scale">
          <img src="app-resources/img/banners/banner-admisiones-vl.png" alt="Proceso de admisión" class="rounded-lg shadow-xl w-full">
        </div>
        
        <div>
          {{-- <h3 class="text-2xl font-bold text-gray-800 mb-4">PROCESO DE ADMISIÓN 2025</h3> --}}
          <h3 class="text-2xl font-bold text-gray-800 mb-4">PROCESO DE ADMISIÓN {{ date('Y') }}</h3>
          <p class="text-gray-600 mb-6">Proceso de Admision en la Pagina del Ministerio de Educacion.</p>
          
          {{-- <div class="space-y-4">
            <div class="flex items-start">
              <div class="bg-red-700 text-white rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">
                1
              </div>
              <div>
                <h4 class="font-bold text-gray-800 mb-1">Inscripción en línea</h4>
                <p class="text-gray-600">Completa el formulario de pre-inscripción en nuestro portal.</p>
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="bg-red-700 text-white rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">
                2
              </div>
              <div>
                <h4 class="font-bold text-gray-800 mb-1">Entrevista familiar</h4>
                <p class="text-gray-600">Conoce nuestro proyecto educativo y resuelve tus dudas.</p>
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="bg-red-700 text-white rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">
                3
              </div>
              <div>
                <h4 class="font-bold text-gray-800 mb-1">Evaluación diagnóstica</h4>
                <p class="text-gray-600">Para conocer el nivel académico del aspirante.</p>
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="bg-red-700 text-white rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">
                4
              </div>
              <div>
                <h4 class="font-bold text-gray-800 mb-1">Matrícula</h4>
                <p class="text-gray-600">Formaliza tu ingreso a nuestra institución.</p>
              </div>
            </div>
          </div> --}}
          
          <a href="https://juntos.educacion.gob.ec/" class="btn-gold text-gray-900 font-bold px-8 py-3 rounded-lg shadow-lg inline-block mt-8">IR A INSCRIPCIONES</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Instalaciones -->
  <section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl font-bold text-center mb-16 section-title font-serif">NUESTRAS INSTALACIONES</h2>
      
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="relative group overflow-hidden rounded-lg h-48 hover-scale">
          <img src="https://images.unsplash.com/photo-1571260899304-425eee4c7efc?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Aulas" class="w-full h-full object-cover transition transform group-hover:scale-110 duration-500">
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-4">
            <h3 class="text-white font-bold">Aulas modernas</h3>
          </div>
        </div>
        
        <div class="relative group overflow-hidden rounded-lg h-48 hover-scale">
          <img src="https://images.unsplash.com/photo-1588072432836-e10032774350?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Laboratorios" class="w-full h-full object-cover transition transform group-hover:scale-110 duration-500">
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-4">
            <h3 class="text-white font-bold">Laboratorios</h3>
          </div>
        </div>
        
        <div class="relative group overflow-hidden rounded-lg h-48 hover-scale">
          <img src="https://images.unsplash.com/photo-1541178735493-479c1a27ed24?ixlib=rb-1.2.1&auto=format&fit=crop&w=1351&q=80" alt="Biblioteca" class="w-full h-full object-cover transition transform group-hover:scale-110 duration-500">
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-4">
            <h3 class="text-white font-bold">Biblioteca</h3>
          </div>
        </div>
        
        <div class="relative group overflow-hidden rounded-lg h-48 hover-scale">
          <img src="https://images.unsplash.com/photo-1552674605-db6ffd4facb5?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Áreas deportivas" class="w-full h-full object-cover transition transform group-hover:scale-110 duration-500">
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-4">
            <h3 class="text-white font-bold">Áreas deportivas</h3>
          </div>
        </div>
      </div>
      
      <div class="text-center mt-8">
        <a href="#" class="inline-flex items-center text-red-700 font-semibold hover:underline">
          Ver galería completa <i class="fas fa-arrow-right ml-2"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- Contacto -->
  <section id="contacto" class="py-16 bg-white">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl font-bold text-center mb-16 section-title font-serif">CONTÁCTANOS</h2>
      
      <div class="grid md:grid-cols-2 gap-12">
        <div>
          <h3 class="text-2xl font-bold text-gray-800 mb-6">INFORMACIÓN DE CONTACTO</h3>
          
          <div class="space-y-6">
            <div class="flex items-start">
              <div class="bg-red-700 text-white p-3 rounded-full mr-4">
                <i class="fas fa-map-marker-alt"></i>
              </div>
              <div>
                <h4 class="font-bold text-gray-800 mb-1">Dirección</h4>
                <p class="text-gray-600">Av. Tahuantinsuyo y Cañaris, sector la Cocha, Latacunga, Ecuador</p>
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="bg-red-700 text-white p-3 rounded-full mr-4">
                <i class="fas fa-phone-alt"></i>
              </div>
              <div>
                <h4 class="font-bold text-gray-800 mb-1">Teléfonos</h4>
                <p class="text-gray-600">(03) 2810-500</p>
                <p class="text-gray-600">(03) 2811-600</p>
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="bg-red-700 text-white p-3 rounded-full mr-4">
                <i class="fas fa-envelope"></i>
              </div>
              <div>
                <h4 class="font-bold text-gray-800 mb-1">Correo electrónico</h4>
                <p class="text-gray-600">info@vicenteleon.edu.ec</p>
                <p class="text-gray-600">admisiones@vicenteleon.edu.ec</p>
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="bg-red-700 text-white p-3 rounded-full mr-4">
                <i class="fas fa-clock"></i>
              </div>
              <div>
                <h4 class="font-bold text-gray-800 mb-1">Horario de atención</h4>
                <p class="text-gray-600">Lunes a Viernes: 8:30 12:00 y 14:00- 16:30</p>
                {{-- <p class="text-gray-600">Sábados: 8:00 - 12:00</p> --}}
              </div>
            </div>
          </div>
        </div>
        
        <div class="bg-gray-50 p-8 rounded-lg shadow-md">
          <h3 class="text-2xl font-bold text-gray-800 mb-6">ENVÍANOS UN MENSAJE</h3>
          
          <form class="space-y-4">
            <div>
              <label for="nombre" class="block text-gray-700 font-medium mb-1">Nombre completo</label>
              <input type="text" id="nombre" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
            </div>
            
            <div>
              <label for="email" class="block text-gray-700 font-medium mb-1">Correo electrónico</label>
              <input type="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
            </div>
            
            <div>
              <label for="telefono" class="block text-gray-700 font-medium mb-1">Teléfono</label>
              <input type="tel" id="telefono" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
            </div>
            
            <div>
              <label for="asunto" class="block text-gray-700 font-medium mb-1">Asunto</label>
              <select id="asunto" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                <option>Información general</option>
                <option>Detalles de cursos</option>
                <option>Solicitudes</option>
                <option>Otro</option>
              </select>
            </div>
            
            <div>
              <label for="mensaje" class="block text-gray-700 font-medium mb-1">Mensaje</label>
              <textarea id="mensaje" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"></textarea>
            </div>
            
            <button type="submit" class="btn-gold text-gray-900 font-bold px-8 py-3 rounded-lg shadow-lg w-full">ENVIAR MENSAJE</button>
          </form>
        </div>
      </div>
    </div>
  </section>
 
  <!-- Mapa -->
<div class="h-96 bg-gray-200">
  <iframe 
    src="https://www.google.com/maps?q=-0.922039,-78.610117&hl=es&z=16&output=embed&layer=c" 
    width="100%" 
    height="100%" 
    style="border:0;" 
    allowfullscreen="" 
    loading="lazy">
  </iframe>
</div>

  <!-- Footer -->
  <footer class="bg-gray-800 text-white py-12">
    <div class="container mx-auto px-4">
      <div class="grid md:grid-cols-4 gap-8 mb-8">
        <div>
          <h3 class="text-xl font-bold mb-4 font-serif">VICENTE LEÓN</h3>
          <p class="text-gray-300 mb-4">Institución educativa con mas de 100 años de tradición y excelencia en Latacunga.</p>
          <p class="italic text-gray-300">"Inmortal Juventud Adelante"</p>
        </div>
        
        <div>
          <h4 class="font-bold text-white mb-4">ENLACES RÁPIDOS</h4>
          <ul class="space-y-2 text-gray-300">
            <li><a href="#inicio" class="hover:text-yellow-300 transition">Inicio</a></li>
            <li><a href="#nosotros" class="hover:text-yellow-300 transition">Nosotros</a></li>
            <li><a href="#academico" class="hover:text-yellow-300 transition">Académico</a></li>
            <li><a href="#admisiones" class="hover:text-yellow-300 transition">Admisiones</a></li>
            <li><a href="#contacto" class="hover:text-yellow-300 transition">Contacto</a></li>
          </ul>
        </div>
        
        <div>
          <h4 class="font-bold text-white mb-4">ADMISIONES</h4>
          <ul class="space-y-2 text-gray-300">
            <li><a href="#" class="hover:text-yellow-300 transition">Proceso de admisión</a></li>
            {{-- <li><a href="#" class="hover:text-yellow-300 transition">Requisitos</a></li> --}}
            {{-- <li><a href="#" class="hover:text-yellow-300 transition">Becas</a></li> --}}
            <li><a href="#" class="hover:text-yellow-300 transition">Preguntas frecuentes</a></li>
          </ul>
        </div>
        
        <div>
          <h4 class="font-bold text-white mb-4">SÍGUENOS</h4>
          <div class="flex space-x-4 mb-4">
            <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center hover:bg-yellow-500 transition">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center hover:bg-yellow-500 transition">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center hover:bg-yellow-500 transition">
              <i class="fab fa-youtube"></i>
            </a>
          </div>
          <p class="text-gray-300 mb-2">Suscríbete a nuestro boletín</p>
          <form class="flex">
            <input type="email" placeholder="Tu correo" class="px-4 py-2 w-full rounded-l-lg focus:outline-none text-gray-800">
            <button type="submit" class="bg-yellow-500 text-gray-900 px-4 py-2 rounded-r-lg font-bold hover:bg-yellow-400 transition">
              <i class="fas fa-paper-plane"></i>
            </button>
          </form>
        </div>
      </div>
      
      <div class="border-t border-gray-700 pt-8 text-center text-gray-400">
       <p>&copy; {{ date('Y') }} Unidad Educativa Vicente León. Todos los derechos reservados.</p>
        <p class="mt-2">Latacunga - Ecuador</p>
      </div>
    </div>
  </footer>

  <script>
    // Simple script para el menú móvil (puedes expandirlo según necesidades)
    document.querySelector('header button').addEventListener('click', function() {
      const nav = document.querySelector('header nav');
      nav.classList.toggle('hidden');
      nav.classList.toggle('flex');
      nav.classList.toggle('flex-col');
      nav.classList.toggle('absolute');
      nav.classList.toggle('bg-white');
      nav.classList.toggle('w-full');
      nav.classList.toggle('left-0');
      nav.classList.toggle('top-24');
      nav.classList.toggle('p-4');
      nav.classList.toggle('space-y-4');
    });
  </script>
</body>
</html>