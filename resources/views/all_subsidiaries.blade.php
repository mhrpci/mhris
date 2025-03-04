@extends('layouts.submain')

@section('meta')
<meta name="description" content="Discover the diverse portfolio of companies under MHR Property Conglomerates, Inc. From healthcare to energy, explore our subsidiaries across various industries.">
<meta name="keywords" content="MHRPCI, MHR Property Conglomerates, Inc., Cebic Industries, MHRHCI, BGPDI, VHI, MAX, RCG, Luscious Co, MHR Construction">
<meta name="theme-color" content="#6b21a8">
@endsection

@section('title', 'Welcome to MHRPCI | MHRPCI')

<style>
    /* Professional Hero Section */
    .hero {
        position: relative;
        background: linear-gradient(135deg, var(--purple-dark) 0%, var(--purple-primary) 100%);
        color: white;
        padding: clamp(6rem, 15vh, 12rem) 2rem clamp(4rem, 10vh, 8rem);
        text-align: center;
        overflow: hidden;
        margin-bottom: -3rem;
    }

    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect fill="%23ffffff" fill-opacity="0.1" width="100" height="100"/></svg>') repeat;
        opacity: 0.1;
        transform: rotate(45deg);
        animation: backgroundShift 30s linear infinite;
    }

    @keyframes backgroundShift {
        0% { background-position: 0 0; }
        100% { background-position: 100px 100px; }
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: min(1000px, 90%);
        margin: 0 auto;
    }
    .hero h1 {
        font-size: clamp(2rem, 5vw, 4rem);
        font-weight: 800;
        margin-bottom: clamp(1rem, 3vh, 2rem);
        letter-spacing: -0.02em;
        line-height: 1.2;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .hero p {
        font-size: clamp(1rem, 2vw, 1.25rem);
        max-width: min(800px, 95%);
        margin: 0 auto;
        opacity: 0.9;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        line-height: 1.6;
    }

    /* Professional Grid Layout */
    .subsidiaries-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(340px, 100%), 1fr));
        gap: clamp(1.5rem, 3vw, 2.5rem);
        padding: 0 clamp(1rem, 2vw, 2rem);
        max-width: min(1400px, 95%);
        margin: 0 auto;
    }

    .subsidiary-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        display: flex;
        flex-direction: column;
        min-height: clamp(450px, 60vh, 520px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        opacity: 0;
        transform: translateY(20px);
    }

    .subsidiary-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
    }

    .subsidiary-image-container {
        position: relative;
        overflow: hidden;
        height: 240px;
        background: #f8f9fa;
    }

    .subsidiary-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .subsidiary-card:hover .subsidiary-image {
        transform: scale(1.05);
    }

    .subsidiary-content {
        padding: clamp(1.5rem, 3vw, 2.5rem);
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: clamp(1rem, 2vw, 1.5rem);
    }

    .subsidiary-header {
        margin-bottom: 0.5rem;
    }

    .subsidiary-title {
        font-size: clamp(1.25rem, 2.5vw, 1.75rem);
        color: var(--purple-dark);
        margin-bottom: clamp(0.5rem, 1vh, 0.75rem);
        font-weight: 700;
        line-height: 1.3;
    }

    .subsidiary-location {
        color: #666;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .subsidiary-description {
        color: #444;
        line-height: 1.7;
        font-size: clamp(0.95rem, 1.5vw, 1.05rem);
        flex-grow: 1;
    }

    .subsidiary-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
        padding: 1.5rem 0;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        margin: 1rem 0;
    }

    .stat {
        text-align: center;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--purple-primary);
        display: block;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 500;
    }

    .learn-more {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        color: var(--purple-primary);
        text-decoration: none;
        font-weight: 600;
        font-size: 1.1rem;
        padding: 1rem 2rem;
        border: 2px solid var(--purple-primary);
        border-radius: 12px;
        transition: all 0.3s ease;
        width: 100%;
        justify-content: center;
        position: relative;
        overflow: hidden;
        background: white;
    }

    .learn-more:hover {
        background: var(--purple-primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(107, 33, 168, 0.2);
    }

    .learn-more:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(107, 33, 168, 0.3);
    }

    .learn-more svg {
        transition: transform 0.3s ease;
    }

    .learn-more:hover svg {
        transform: translateX(4px);
    }

    /* Enhanced Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .subsidiary-card.visible {
        animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    /* Enhanced Loading State */
    .loading-skeleton {
        position: relative;
        overflow: hidden;
        background: #f0f0f0;
        border-radius: 8px;
    }

    .loading-skeleton::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        transform: translateX(-100%);
        background: linear-gradient(
            90deg,
            rgba(255, 255, 255, 0) 0%,
            rgba(255, 255, 255, 0.2) 20%,
            rgba(255, 255, 255, 0.5) 60%,
            rgba(255, 255, 255, 0) 100%
        );
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        100% {
            transform: translateX(100%);
        }
    }

    /* Enhanced Responsive Design */
    @media (max-width: 1024px) {
        .subsidiaries-grid {
            grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr));
        }
    }

    @media (max-width: 768px) {
        .hero {
            padding: clamp(4rem, 10vh, 6rem) 1rem clamp(3rem, 8vh, 4rem);
        }

        .subsidiary-card {
            min-height: clamp(400px, 50vh, 450px);
        }
    }

    @media (max-width: 480px) {
        .subsidiary-content {
            padding: 1.25rem;
        }
    }

    /* Enhanced Touch Interactions */
    @media (hover: none) {
        .subsidiary-card:active {
            transform: scale(0.98);
        }
    }

    /* Reduced Motion */
    @media (prefers-reduced-motion: reduce) {
        .subsidiary-card,
        .hero::before {
            animation: none;
            transition: none;
        }
    }

    /* Loading Animation */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .loading-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 4px solid var(--purple-primary);
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* No Results State */
    .no-results {
        text-align: center;
        padding: 4rem 2rem;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s ease;
    }

    .no-results.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .no-results-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .no-results-text {
        font-size: 1.5rem;
        color: var(--purple-dark);
        margin-bottom: 0.5rem;
    }

    .no-results-subtext {
        color: #666;
    }
</style>
@section('content')
<div class="hero" role="banner">
    <div class="hero-content">
        <h1 class="animate-fade-in">Our Subsidiaries</h1>
        <p class="animate-fade-in delay-1">Discover the diverse portfolio of companies that make up MHR Property Conglomerates, Inc., each contributing to our vision of excellence and innovation.</p>
    </div>
</div>

<div class="subsidiaries-grid" role="list">
    <!-- Cebic Industries -->
    <div class="subsidiary-card fade-up" data-industry="healthcare">
        <img src="/vendor/adminlte/dist/img/cebic.png" alt="Cebic Industries" class="subsidiary-image">
        <div class="subsidiary-content">
            <h3 class="subsidiary-title">Cebic Industries OPC</h3>
            <div class="subsidiary-location">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                Cebu, Philippines
            </div>
            <p class="subsidiary-description">Cebic Trading is the original business that laid the foundation for MHRPCI. Initially focused on hospital and medical supplies distribution, it has grown into a key player in the healthcare industry.</p>
            <a href="{{ route('cio') }}" class="learn-more">
                View Website
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </div>

    <!-- MHRHCI -->
    <div class="subsidiary-card fade-up" data-industry="healthcare">
        <img src="/vendor/adminlte/dist/img/mhrhci.png" alt="MHRHCI" class="subsidiary-image">
        <div class="subsidiary-content">
            <h3 class="subsidiary-title">Medical & Hospital Resources Health Care, Inc.</h3>
            <div class="subsidiary-location">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                Cebu, Philippines
            </div>
            <p class="subsidiary-description">MHRHCI specializes in the importation and distribution of medical supplies and equipment, serving healthcare institutions across the Philippines with a commitment to quality and reliability.</p>
            <a href="{{ route('mhrhci') }}" class="learn-more">
                View Website
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </div>

    <!-- BGPDI -->
    <div class="subsidiary-card fade-up" data-industry="energy">
        <img src="/vendor/adminlte/dist/img/bgpdi.png" alt="BGPDI" class="subsidiary-image">
        <div class="subsidiary-content">
            <h3 class="subsidiary-title">Bay Gas Petroleum Distribution Inc.</h3>
            <div class="subsidiary-location">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                Philippines
            </div>
            <p class="subsidiary-description">Founded in 2015, BGPDI started as a small fuel distribution company and has grown into a significant player in the energy sector, providing reliable fuel solutions across the Philippines.</p>

            <a href="{{ route('bgpdi') }}" class="learn-more">
                View Website
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </div>

    <!-- VHI -->
    <div class="subsidiary-card fade-up" data-industry="healthcare">
        <img src="/vendor/adminlte/dist/img/vhi.png" alt="VHI" class="subsidiary-image">
        <div class="subsidiary-content">
            <h3 class="subsidiary-title">Valued Healthcare Innovations</h3>
            <div class="subsidiary-location">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                Philippines
            </div>
            <p class="subsidiary-description">VHI focuses on providing innovative solutions for the healthcare industry, specializing in cutting-edge medical technologies and services that enhance patient care.</p>
            <a href="{{ route('vhi') }}" class="learn-more">
                View Website
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </div>

    <!-- MAX -->
    <div class="subsidiary-card fade-up" data-industry="logistics">
        <img src="/vendor/adminlte/dist/img/max.png" alt="MAX" class="subsidiary-image">
        <div class="subsidiary-content">
            <h3 class="subsidiary-title">Max Hauling and Logistics</h3>
            <div class="subsidiary-location">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                Philippines
            </div>
            <p class="subsidiary-description">MAX was born out of necessity during the pandemic, turning a challenge into an opportunity by providing essential logistics and hauling services across the country.</p>
            <a href="{{ route('max') }}" class="learn-more">
                View Website
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </div>

    <!-- RCG -->
    <div class="subsidiary-card fade-up" data-industry="investment">
        <img src="/vendor/adminlte/dist/img/rcg.png" alt="RCG" class="subsidiary-image">
        <div class="subsidiary-content">
            <h3 class="subsidiary-title">RCG Pharmaceutical</h3>
            <div class="subsidiary-location">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                Philippines
            </div>
            <p class="subsidiary-description">RCG is an investment arm under MHRPCI, responsible for managing the conglomerate's financial assets and pharmaceutical ventures, ensuring sustainable growth and development.</p>
            <a href="{{ route('rcg') }}" class="learn-more">
                View Website
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </div>

    <!-- Luscious Co -->
    <div class="subsidiary-card fade-up" data-industry="hospitality">
        <img src="/vendor/adminlte/dist/img/lus.png" alt="Luscious Co" class="subsidiary-image">
        <div class="subsidiary-content">
            <h3 class="subsidiary-title">Luscious Co.</h3>
            <div class="subsidiary-location">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                Cebu, Philippines
            </div>
            <p class="subsidiary-description">Luscious Co. operates in the food and hospitality sector, offering high-quality dining experiences and catering services with a focus on customer satisfaction and culinary excellence.</p>
            <a href="{{ route('lus') }}" class="learn-more">
                View Website
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </div>

    <!-- MHR Construction -->
    <div class="subsidiary-card fade-up" data-industry="construction">
        <img src="/vendor/adminlte/dist/img/mhrconstruction.jpg" alt="MHR Construction" class="subsidiary-image">
        <div class="subsidiary-content">
            <h3 class="subsidiary-title">MHR Construction</h3>
            <div class="subsidiary-location">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                Philippines
            </div>
            <p class="subsidiary-description">MHR Construction handles various infrastructure projects, including the development of commercial, residential, and industrial properties, with a commitment to quality and innovation.</p>
            <a href="{{ route('mhrcons') }}" class="learn-more">
                View Website
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </div>
</div>

<div class="no-results" style="display: none;" role="alert">
    <div class="no-results-icon">🔍</div>
    <h2 class="no-results-text">No subsidiaries found</h2>
    <p class="no-results-subtext">Try selecting a different industry filter</p>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const subsidiaryCards = document.querySelectorAll('.subsidiary-card');

    // Enhanced intersection observer for animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '50px'
    });

    subsidiaryCards.forEach(card => {
        observer.observe(card);
    });
});
</script>