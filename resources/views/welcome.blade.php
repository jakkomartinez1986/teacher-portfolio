<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yogu Vida - Kéfir Natural | El Yogurt que te da Salud</title>
    <meta name="description" content="Yogurt de kéfir 100% natural con sabores de uvilla y remolacha. Producto artesanal lleno de probióticos para tu salud digestiva.">
    <style>
        :root {
            --primary-green: #50C878; /* Verde principal de la marca */
            --dark-green: #3CB371; /* Verde oscuro para contrastes */
            --light-green: #E8F5E9; /* Verde muy claro para fondos */
            --natural-white: #FFFFFF; 
            --cream-bg: #F5F5F5;
            --gold-accent: #FFD700; /* Amarillo dorado para destacados */
            --text-dark: #333333;
            --text-medium: #555555;
            --text-light: #777777;
        }
        
        body {
            font-family: 'Montserrat', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: var(--text-dark);
            line-height: 1.6;
            background-color: var(--cream-bg);
        }
        
        /* Tipografía */
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap');
        
        h1, h2, h3 {
            font-weight: 600;
        }
        
        /* Header */
        header {
            background-color: var(--natural-white);
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s;
        }
        
        header.scrolled {
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            padding: 5px 0;
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 5%;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-green);
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
        }
        
        .logo img {
            height: 50px;
            margin-right: 10px;
        }
        .logo-container {
            display: flex;
            align-items: center;
            text-decoration: none !important;
        }
        
        .responsive-circle-logo {
            width: 8vmin;            /* Unidad relativa al viewport */
            height: 8vmin;
            min-width: 50px;         /* Límites mínimos/máximos */
            min-height: 50px;
            max-width: 80px;
            max-height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 12px;
        }
        .nav-links {
            display: flex;
            list-style: none;
            gap: 1.5rem;
        }
        
        .nav-links a {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            transition: all 0.3s;
            position: relative;
            padding: 5px 0;
        }
        
        .nav-links a:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: var(--primary-green);
            bottom: 0;
            left: 0;
            transition: width 0.3s;
        }
        
        .nav-links a:hover {
            color: var(--primary-green);
        }
        
        .nav-links a:hover:after {
            width: 100%;
        }
        
        .nav-cta {
            background-color: var(--gold-accent);
            color: var(--text-dark) !important;
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            margin-left: 1rem;
            box-shadow: 0 3px 10px rgba(255,215,0,0.3);
        }
        
        .nav-cta:hover {
            background-color: var(--primary-green);
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(80,200,120,0.4);
        }
        
        .nav-cta:after {
            display: none;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, rgba(80,200,120,0.1) 0%, rgba(255,255,255,1) 100%);
            padding: 10rem 5% 5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* background: url('https://images.unsplash.com/photo-1542838132-92c53300491e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') center/cover; */
            /* background: url('{{ asset('app-resources/img/welcome/kefir-subfondo-final.png') }}') center/cover; */
            background: url('{{ asset('app-resources/img/welcome/kefir-subfondo-final.png') }}') center/cover;
            background-size: 30%;
            opacity: 0.3;
            z-index: 0;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .natural-badge {
            display: inline-block;
            background-color: var(--gold-accent);
            color: var(--text-dark);
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 700;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            box-shadow: 0 3px 10px rgba(255,215,0,0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .hero h1 {
            font-size: 3rem;
            color: var(--primary-green);
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .hero .tagline {
            font-size: 2rem;
            color: var(--dark-green);
            font-weight: 600;
            margin-bottom: 1.5rem;
            line-height: 1.3;
        }
        
        .hero p {
            font-size: 1.2rem;
            color: var(--text-medium);
            max-width: 700px;
            margin: 0 auto 2.5rem;
        }
        
        .cta-button {
            display: inline-block;
            background-color: var(--primary-green);
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(80,200,120,0.4);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 1.1rem;
            margin: 0 0.5rem 1rem;
            border: 2px solid var(--primary-green);
        }
        
        .cta-button.secondary {
            background-color: transparent;
            color: var(--primary-green);
            border: 2px solid var(--primary-green);
        }
        
        .cta-button:hover {
            background-color: var(--dark-green);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(60,179,113,0.5);
        }
        
        .cta-button.secondary:hover {
            background-color: var(--primary-green);
            color: white;
        }
        
        /* Secciones */
        section {
            padding: 5rem 5%;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            color: var(--dark-green);
            margin-bottom: 3rem;
            position: relative;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .section-title:after {
            content: "";
            display: block;
            width: 100px;
            height: 4px;
            background: var(--gold-accent);
            margin: 1rem auto 0;
            border-radius: 2px;
        }
        
        /* About Section */
        .about {
            background-color: var(--natural-white);
        }
        
        .about-content {
            display: flex;
            align-items: center;
            gap: 4rem;
        }
        
        .about-text {
            flex: 1;
        }
        
        .about-text p {
            margin-bottom: 1.5rem;
            color: var(--text-medium);
            font-size: 1.1rem;
            line-height: 1.8;
        }
        
        .about-text .highlight {
            color: var(--primary-green);
            font-weight: 600;
        }
        
        .about-image {
            flex: 1;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.5s;
        }
        
        .about-image:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .about-image img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        /* Beneficios */
        .benefits {
            background-color: var(--light-green);
        }
        
        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .benefit-card {
            background-color: var(--natural-white);
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s;
            border-top: 4px solid var(--primary-green);
        }
        
        .benefit-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .benefit-icon {
            font-size: 3rem;
            color: var(--primary-green);
            margin-bottom: 1.5rem;
        }
        
        .benefit-title {
            font-size: 1.5rem;
            color: var(--dark-green);
            margin-bottom: 1rem;
        }
        
        .benefit-desc {
            color: var(--text-medium);
            font-size: 1rem;
        }
        
        /* Productos */
        .products {
            background-color: var(--natural-white);
        }
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.5rem;
            margin-top: 3rem;
        }
        
        .product-card {
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.4s;
            position: relative;
        }
        
        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: var(--gold-accent);
            color: var(--text-dark);
            padding: 0.3rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            z-index: 2;
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .product-image {
            height: 300px;
            overflow: hidden;
            position: relative;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .product-card:hover .product-image img {
            transform: scale(1.1);
        }
        
        .product-info {
            padding: 1.8rem;
            text-align: center;
        }
        
        .product-title {
            font-size: 1.5rem;
            margin-bottom: 0.8rem;
            color: var(--dark-green);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .product-description {
            margin-bottom: 1.2rem;
            color: var(--text-medium);
            min-height: 60px;
        }
        
        .product-price {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-green);
            margin-bottom: 1.5rem;
        }
        
        .order-button {
            display: inline-block;
            background-color: var(--gold-accent);
            color: var(--text-dark);
            padding: 0.8rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: 2px solid var(--gold-accent);
            width: 100%;
            max-width: 200px;
            margin: 0 auto;
        }
        
        .order-button:hover {
            background-color: var(--primary-green);
            color: white;
            border-color: var(--primary-green);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(80,200,120,0.3);
        }
        
        /* Testimonios */
        .testimonials {
            background-color: var(--light-green);
        }
        
        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .testimonial-card {
            background-color: var(--natural-white);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            position: relative;
        }
        
        .testimonial-card:before {
            content: '"';
            position: absolute;
            top: 10px;
            left: 20px;
            font-size: 5rem;
            color: rgba(80,200,120,0.1);
            font-family: serif;
            line-height: 1;
        }
        
        .testimonial-text {
            color: var(--text-medium);
            font-style: italic;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
        }
        
        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 1rem;
        }
        
        .author-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .author-info h4 {
            margin: 0;
            color: var(--dark-green);
        }
        
        .author-info p {
            margin: 0;
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        /* Galería */
        .gallery {
            background-color: var(--natural-white);
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 3rem;
        }
        
        .gallery-item {
            height: 280px;
            overflow: hidden;
            border-radius: 15px;
            position: relative;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .gallery-item:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .gallery-item:hover img {
            transform: scale(1.1);
        }
        
        /* Contacto */
        .contact {
            background: linear-gradient(135deg, var(--light-green) 0%, var(--natural-white) 100%);
        }
        
        .contact-container {
            display: flex;
            gap: 3rem;
            align-items: center;
        }
        
        .contact-info {
            flex: 1;
        }
        
        .contact-info h3 {
            color: var(--dark-green);
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }
        
        .contact-detail {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .contact-icon {
            font-size: 1.5rem;
            color: var(--primary-green);
            margin-right: 1rem;
            width: 40px;
            text-align: center;
        }
        
        .contact-text h4 {
            margin: 0 0 0.3rem;
            color: var(--text-dark);
        }
        
        .contact-text p, .contact-text a {
            margin: 0;
            color: var(--text-medium);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .contact-text a:hover {
            color: var(--primary-green);
        }
        
        .contact-form {
            flex: 1;
            background-color: var(--natural-white);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-green);
            outline: none;
            box-shadow: 0 0 0 3px rgba(80,200,120,0.2);
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        .submit-btn {
            background-color: var(--primary-green);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .submit-btn:hover {
            background-color: var(--dark-green);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(80,200,120,0.4);
        }
        
        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--dark-green) 0%, var(--primary-green) 100%);
            color: white;
            padding: 4rem 5% 2rem;
            text-align: center;
        }
        
        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .footer-logo {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .footer-logo span {
            color: var(--gold-accent);
        }
        
        .footer-tagline {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            font-weight: 500;
            opacity: 0.9;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .footer-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: var(--gold-accent);
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin: 2rem 0;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 50%;
            color: white;
            font-size: 1.2rem;
            transition: all 0.3s;
        }
        
        .social-links a:hover {
            background-color: var(--gold-accent);
            color: var(--text-dark);
            transform: translateY(-3px);
        }
        
        .copyright {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
        }
        
        /* WhatsApp Float */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #25D366;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            box-shadow: 0 5px 20px rgba(37,211,102,0.4);
            z-index: 100;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .whatsapp-float:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(37,211,102,0.6);
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .about-content, .contact-container {
                flex-direction: column;
            }
            
            .about-image, .about-text, .contact-info, .contact-form {
                width: 100%;
            }
            
            .about-image {
                order: -1;
            }
        }
        
        @media (max-width: 768px) {
            .hero {
                padding-top: 8rem;
            }
            
            .hero h1 {
                font-size: 2.2rem;
            }
            
            .hero .tagline {
                font-size: 1.6rem;
            }
            
            .nav-links {
                display: none;
            }
            
            .mobile-menu-btn {
                display: block;
                background: none;
                border: none;
                color: var(--primary-green);
                font-size: 1.8rem;
                cursor: pointer;
            }
            
            .section-title {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 480px) {
            .hero h1 {
                font-size: 1.8rem;
            }
            
            .hero .tagline {
                font-size: 1.3rem;
            }
            
            .cta-buttons {
                display: flex;
                flex-direction: column;
            }
            
            .cta-button {
                margin-bottom: 1rem;
                width: 100%;
            }
        }
    </style>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- WhatsApp Float Button -->
    <a href="https://wa.me/593997331912?text=Hola,%20me%20interesa%20el%20yogurt%20de%20kéfir%20Yogu%20Vida" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <header id="header">
        <nav>
            <a href="#" class="logo-container">
                <img src="app-resources/img/welcome/kefir_logo_final.jpg"
                     class="responsive-circle-logo"
                     alt="Logo Yogu Vida">
                <span style="font-weight: 600; color: #333;">YOGU</span>
                <span style="font-weight: 600; color: #50C878;">VIDA</span>
            </a>
            
            <ul class="nav-links">
                <li><a href="#inicio">Inicio</a></li>
                <li><a href="#beneficios">Beneficios</a></li>
                <li><a href="#productos">Productos</a></li>
                <li><a href="#testimonios">Testimonios</a></li>
                <li><a href="#contacto">Contacto</a></li>
                <li><a href="https://wa.me/593997331912" class="nav-cta">Pedir Ahora</a></li>
            </ul>
            
            <button class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
        </nav>
    </header>

    <section class="hero" id="inicio">
        <div class="hero-content">
            <div class="natural-badge">100% Natural</div>
            <h1>YOGU VIDA KÉFIR</h1>
            <div class="tagline">EL YOGURT QUE TE DA SALUD</div>
            <p>Descubre los beneficios de nuestro kéfir artesanal con sabores naturales de uvilla y remolacha. Elaborado con amor y con los más altos estándares de calidad orgánica para cuidar tu salud digestiva.</p>
            <div class="cta-buttons">
                <a href="#productos" class="cta-button">Ver Productos</a>
                <a href="https://wa.me/5937331912?text=Hola,%20me%20interesa%20comprar%20su%20yogurt%20de%20kéfir%20Yogu%20Vida" class="cta-button secondary">Pedir por WhatsApp</a>
            </div>
        </div>
    </section>

    <section class="about" id="nosotros">
        <h2 class="section-title">Nuestra Historia</h2>
        <div class="about-content">
            <div class="about-text">
                <p><span class="highlight">Yogu Vida</span> es un emprendimiento desarrollado por estudiantes del Bachillerato Técnico en Comercializacion y Ventas de la Unidad Educativa Vicente León, que nace como parte de nuestro proyecto educativo. Combinamos conocimientos técnicos con pasión por la alimentación saludable.</p>
                <p>Utilizamos únicamente ingredientes <span class="highlight">100% naturales</span> y aplicamos los procesos de fermentación tradicionales que hemos aprendido en nuestras clases, evitando completamente conservantes y aditivos artificiales. Cada lote es preparado con dedicación estudiantil para garantizar calidad y beneficios probióticos.</p>
                <p>"El yogurt que te da salud" no es solo nuestro eslogan, es el resultado de nuestro aprendizaje técnico y compromiso con la comunidad. Creemos en el poder de los jóvenes para innovar en alimentación saludable y en el valor de los probióticos para el bienestar familiar.</p>
                <p>Este proyecto nos permite poner en práctica nuestras competencias técnicas mientras contribuimos a promover hábitos alimenticios más sanos en nuestra localidad.</p>
            </div>
            <div class="about-image">
                <img src="app-resources/img/welcome/uvilla_yogu_vida.jpg" alt="Proceso artesanal de kéfir Yogu Vida">
            </div>
        </div>
    </section>

    <section class="benefits" id="beneficios">
        <h2 class="section-title">Beneficios del Kéfir</h2>
        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3 class="benefit-title">Salud Digestiva</h3>
                <p class="benefit-desc">Contiene probióticos que mejoran la flora intestinal, ayudando a la digestión y absorción de nutrientes.</p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="benefit-title">Sistema Inmune</h3>
                <p class="benefit-desc">Fortalece tus defensas naturales gracias a sus propiedades inmunomoduladoras.</p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3 class="benefit-title">Energía Natural</h3>
                <p class="benefit-desc">Rico en vitaminas B y minerales que te proporcionan energía sostenida durante el día.</p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <h3 class="benefit-title">100% Natural</h3>
                <p class="benefit-desc">Sin conservantes, colorantes ni aditivos artificiales. Solo ingredientes puros y naturales.</p>
            </div>
        </div>
    </section>

    <section class="products" id="productos">
        <h2 class="section-title">Nuestros Productos</h2>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-badge">Más Vendido</div>
                <div class="product-image">
                    <img src="app-resources/img/welcome/uvilla_mas_vendido.jpg" alt="Kéfir de Uvilla Yogu Vida">
                </div>
                <div class="product-info">
                    <h3 class="product-title">KÉFIR UVILLA</h3>
                    <p class="product-description">Delicioso yogurt de kéfir con el sabor tropical y antioxidante de la uvilla orgánica. Perfecto para desayunos y meriendas saludables.</p>
                    <p class="product-price">$1.50 / 200ml</p>
                    <a href="https://wa.me/593997331912?text=Hola,%20quiero%20ordenar%20el%20kéfir%20de%20uvilla%20Yogu%20Vida" class="order-button">Pedir Ahora</a>
                </div>
            </div>
            
            <div class="product-card">
                <div class="product-badge">Novedad</div>
                <div class="product-image">
                    <img src="app-resources/img/welcome/remolacha_mas_vendido.jpg" alt="Kéfir de Remolacha Yogu Vida">
                </div>
                <div class="product-info">
                    <h3 class="product-title">KÉFIR REMOLACHA</h3>
                    <p class="product-description">Nuestra especialidad: kéfir rosado naturalmente con remolacha orgánica. Rico en hierro, probióticos y beneficios para tu salud digestiva.</p>
                    <p class="product-price">$1.50 / 200ml</p>
                    <a href="https://wa.me/593997331912?text=Hola,%20quiero%20ordenar%20el%20kéfir%20de%20remolacha%20Yogu%20Vida" class="order-button">Pedir Ahora</a>
                </div>
            </div>
            
            <div class="product-card">
                <div class="product-image">
                    <img src="app-resources/img/welcome/kefir_natural.jpg" alt="Kéfir Natural Yogu Vida">
                </div>
                <div class="product-info">
                    <h3 class="product-title">KÉFIR NATURAL</h3>
                    <p class="product-description">El clásico yogurt de kéfir en su forma más pura, sin aditivos, con todo el poder probiótico de nuestra fermentación natural.</p>
                    <p class="product-price">$1.50 / 200ml</p>
                    <a href="https://wa.me/593997331912?text=Hola,%20quiero%20ordenar%20el%20kéfir%20natural%20Yogu%20Vida" class="order-button">Pedir Ahora</a>
                </div>
            </div>
        </div>
    </section>

    <section class="testimonials" id="testimonios">
        <h2 class="section-title">Lo Que Dicen Nuestros Clientes</h2>
        <div class="testimonial-grid">
            <div class="testimonial-card">
                <p class="testimonial-text">"El kéfir de uvilla de Yogu Vida cambió mi salud digestiva por completo. Después de años de problemas, por fin encontré un producto natural que realmente funciona."</p>
                <div class="testimonial-author">
                    <div class="author-avatar">
                        <img src="https://randomuser.me/api/portraits/women/43.jpg" alt="María G.">
                    </div>
                    <div class="author-info">
                        <h4>María G.</h4>
                        <p>Cliente desde 2024</p>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <p class="testimonial-text">"Me encanta el sabor del kéfir de remolacha y saber que estoy consumiendo algo 100% natural. Lo recomiendo a todos mis amigos y familiares."</p>
                <div class="testimonial-author">
                    <div class="author-avatar">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Carlos R.">
                    </div>
                    <div class="author-info">
                        <h4>Carlos R.</h4>
                        <p>Cliente desde 2024</p>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <p class="testimonial-text">"Como nutricionista, recomiendo Yogu Vida a mis pacientes por su calidad y beneficios probióticos. Es el mejor kéfir que he probado en el mercado."</p>
                <div class="testimonial-author">
                    <div class="author-avatar">
                        <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Dra. Laura M.">
                    </div>
                    <div class="author-info">
                        <h4>Dra. Laura M.</h4>
                        <p>Nutricionista</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="gallery" id="galeria">
        <h2 class="section-title">Nuestro Proceso Natural</h2>
        <div class="gallery-grid">
            <div class="gallery-item">
                <img src="app-resources/img/welcome/remolacha_cocecha.jpg" alt="Ingredientes orgánicos Yogu Vida">
            </div>
            <div class="gallery-item">
                <img src="app-resources/img/welcome/uvilla_materia_prima.jpg" alt="Proceso de fermentación artesanal">
            </div>
            <div class="gallery-item">
                <img src="app-resources/img/welcome/kefir-artesano.jpg" alt="Producto final Yogu Vida Kéfir">
            </div>
            <div class="gallery-item">
                <img src="app-resources/img/welcome/kefir-producto-final-lineal.jpg" alt="Presentación del producto Yogu Vida">
            </div>
        </div>
    </section>

    <section class="contact" id="contacto">
        <h2 class="section-title">Haz Tu Pedido</h2>
        <div class="contact-container">
            <div class="contact-info">
                <h3>Contáctanos</h3>
                
                <div class="contact-detail">
                    <div class="contact-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="contact-text">
                        <h4>Teléfono</h4>
                        <a href="tel:+593997331912">+593 997331912</a>
                    </div>
                </div>
                
                <div class="contact-detail">
                    <div class="contact-icon">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div class="contact-text">
                        <h4>WhatsApp</h4>
                        <a href="https://wa.me/593997331912">Envíanos un mensaje</a>
                    </div>
                </div>
                
                <div class="contact-detail">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-text">
                        <h4>Email</h4>
                        <a href="mailto:uevicenteleonlogistica@gmail.com">uevicenteleonlogistica@gmail.com</a>
                    </div>
                </div>
                
                <div class="contact-detail">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="contact-text">
                        <h4>Ubicación</h4>
                        <p>Latacunga, Ecuador</p>
                    </div>
                </div>
            </div>
            
            <div class="contact-form">
                <form id="pedidoForm">
                    <div class="form-group">
                        <label for="nombre">Nombre Completo</label>
                        <input type="text" id="nombre" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="producto">Producto de Interés</label>
                        <select id="producto" class="form-control" required>
                            <option value="">Seleccione un producto</option>
                            <option value="kefir_uvilla">Kéfir de Uvilla ($5.99)</option>
                            <option value="kefir_remolacha">Kéfir de Remolacha ($5.99)</option>
                            <option value="kefir_natural">Kéfir Natural ($4.99)</option>
                            <option value="combo">Combo Familiar (3 unidades - $16.99)</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="mensaje">Mensaje o Instrucciones Especiales</label>
                        <textarea id="mensaje" class="form-control"></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">Enviar Pedido</button>
                </form>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-logo">YOGU<span>VIDA</span></div>
            <div class="footer-tagline">EL YOGURT QUE TE DA SALUD</div>
            
            <div class="footer-links">
                <a href="#inicio">Inicio</a>
                <a href="#beneficios">Beneficios</a>
                <a href="#productos">Productos</a>
                <a href="#testimonios">Testimonios</a>
                <a href="#contacto">Contacto</a>
            </div>
            
            <div class="social-links">
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="https://wa.me/593997331912" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
            </div>
            
            <div class="copyright">
                <p>100% Natural • Producido con amor en Latacunga/Ecuador</p>
                <p>© 2025 Yogu Vida. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Efecto de scroll en el header
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
        
        // Formulario de pedido
        document.getElementById('pedidoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Aquí iría la lógica para enviar el pedido
            const nombre = document.getElementById('nombre').value;
            const producto = document.getElementById('producto').value;
            
            // Simulación de éxito
            alert(`¡Gracias ${nombre}! Tu pedido de ${producto.replace('_', ' ')} ha sido recibido. Nos comunicaremos contigo pronto para confirmar.`);
            
            // Redirigir a WhatsApp con los detalles del pedido
            const mensaje = `Hola Yogu Vida, acabo de hacer un pedido a través de su web:\n\nNombre: ${nombre}\nProducto: ${document.getElementById('producto').options[document.getElementById('producto').selectedIndex].text}\nMensaje: ${document.getElementById('mensaje').value || 'Ninguno'}`;
            window.open(`https://wa.me/TUNUMERODEWHATSAPP?text=${encodeURIComponent(mensaje)}`, '_blank');
            
            // Resetear el formulario
            this.reset();
        });
        
        // Mobile menu toggle (simplificado)
        document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
            const navLinks = document.querySelector('.nav-links');
            navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
        });
    </script>
</body>
</html>