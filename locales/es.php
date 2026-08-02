<?php
/**
 * Spanish site content — extend/override keys from locales/en.php as needed.
 */

require __DIR__ . '/en.php';

$ui = array_merge($ui, [
    'hero_eyebrow' => 'Controla todas tus suscripciones en un solo lugar',
    'header_download' => 'Descargar',
    'header_get_started' => 'Empezar',
    'blog_resources' => 'Recursos',
    'blog_latest_title' => 'Lo último en nuestro blog',
    'blog_latest_desc' => 'Descubre consejos, tutoriales y novedades para sacar el máximo partido a %s.',
    'blog_read_article' => 'Leer artículo',
    'blog_view_all' => 'Ver todos los artículos',
    'blog_our_blog' => 'Nuestro blog',
    'blog_latest_articles' => 'Artículos recientes',
    'blog_category_filter' => 'Categoría',
    'blog_clear_filter' => 'Quitar filtro',
    'blog_empty' => 'Aún no hay artículos. ¡Vuelve pronto!',
    'blog_read' => 'Leer artículo',
    'blog_previous' => 'Anterior',
    'blog_next' => 'Siguiente',
    'blog_back' => 'Volver a todos los artículos',
    'blog_home' => 'Inicio',
    'blog_blogs' => 'Blog',
    'blog_page_title' => 'Blog',
    'blog_category_page_title' => 'Artículos de %s',
    'blog_list_page_desc' => 'Lee los últimos tutoriales, guías y novedades del equipo de %s.',
    'blog_category_page_desc' => 'Explora todos nuestros artículos sobre %s.',
    'contact_title' => 'Contáctanos',
    'contact_subtitle' => 'Nos encantaría saber de ti. Envíanos un mensaje y te responderemos lo antes posible.',
    'faq_still_questions' => '¿Aún tienes preguntas?',
    'faq_chat_team' => '¿No encuentras la respuesta? Habla con nuestro equipo.',
    'faq_contact_support' => 'Contactar soporte',
    'footer_navigation' => 'Navegación',
    'footer_follow' => 'Síguenos',
    'footer_view_all' => 'Ver todo',
    'reviews_verified' => 'Usuario verificado',
    'store_rating_reviews' => 'reseñas',
    'lang_switcher_label' => 'Idioma',
]);

$home['title'] = "Controla tus <span class='text-highlight'>suscripciones</span> sin esfuerzo";
$home['description'] = 'SubFox es tu app para gestionar suscripciones, hacer seguimiento y ahorrar dinero.';

$common['appTitle'] = 'No pierdas el control de tus suscripciones';
$common['appDescription'] = 'SubFox te ayuda a hacer seguimiento de tus suscripciones y ahorrar dinero.';

$bottomCta['title'] = 'Descarga la app';
$bottomCta['description'] = 'SubFox te ayuda a hacer seguimiento de tus suscripciones y ahorrar dinero.';

$howItWorks['badge'] = 'Proceso simple';
$howItWorks['title'] = 'Cómo funciona';
$howItWorks['description'] = 'Toma el control de tus suscripciones en tres pasos.';
$howItWorks['steps'][0]['title'] = 'Añade tus suscripciones';
$howItWorks['steps'][0]['description'] = 'Elige servicios populares o añade personalizados con coste y ciclo de facturación.';
$howItWorks['steps'][1]['title'] = 'Organiza y haz seguimiento';
$howItWorks['steps'][1]['description'] = 'Configura recordatorios, categorías y consulta todo en un solo lugar.';
$howItWorks['steps'][2]['title'] = 'Ahorra dinero';
$howItWorks['steps'][2]['description'] = 'Detecta pruebas sin usar y cargos próximos antes de que se cobren.';

$footer['description'] = 'SubFox te ayuda a hacer seguimiento de tus suscripciones y ahorrar dinero.';
$footer['navigation'][0]['title'] = 'Preguntas frecuentes';
$footer['navigation'][1]['title'] = 'Contacto';
$footer['navigation'][2]['title'] = 'Blog';
$footer['navigation'][5]['title'] = 'Reclamar recompensa';
$footer['legal'][0]['title'] = 'Política de privacidad (Android)';
$footer['legal'][1]['title'] = 'Política de privacidad (iOS)';
$footer['legal'][2]['title'] = 'Términos de servicio';
$footer['copyright'] = '© ' . date('Y') . ' SubFox. Todos los derechos reservados.';
$footer['message'] = 'Hecho con ❤️ en Pakistán';

$faqs['title'] = 'Preguntas frecuentes';
$faqs['description'] = 'Encuentra respuestas sobre SubFox y la gestión de suscripciones.';

$featuresScreenshots['title'] = 'Funciones potentes para mejorar tu experiencia';
$featuresIcons['title'] = 'Gestión integral de suscripciones';
$ratings['title'] = 'Lo que dicen nuestros usuarios';
