import { useState, useEffect, useRef } from 'react';
import { Link } from 'react-router-dom';
import { useCMS } from '../context/CMSContext';
import './Hero.css';

function ArrowIcon() {
  return (
    <span className="btn-arrow" aria-hidden="true">
      <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor">
        <path d="M8 0 6.59 1.41 12.17 7H0v2h12.17l-5.58 5.59L8 16l8-8-8-8Z" />
      </svg>
    </span>
  );
}

export default function Hero() {
  const { getCMSContent } = useCMS();
  const apiHost = window.location.hostname === 'localhost' ? 'http://localhost:8000' : '';
  
  const [currentIndex, setCurrentIndex] = useState(0);
  const autoPlayRef = useRef(null);

  // Setup slide items
  const heroImage1 = getCMSContent('home.hero.image', '/images/hero-consultant.webp');
  const imageUrl1 = heroImage1.startsWith('http') || heroImage1.startsWith('/images')
    ? heroImage1
    : `${apiHost}${heroImage1}`;

  const heroImage2 = getCMSContent('home.hero_slide2.image', '/images/hero-ftl-consultant.webp');
  const imageUrl2 = heroImage2.startsWith('http') || heroImage2.startsWith('/images')
    ? heroImage2
    : `${apiHost}${heroImage2}`;

  const slides = [
    {
      title: getCMSContent('home.hero.title', 'BUSINESS SYSTEM TRANSFORMATION THAT DELIVERS SCALE AND IMPACT'),
      subtitle: getCMSContent('home.hero.subtitle', 'Technology consulting and AI-first thinking that drives digital strategy and transformation roadmaps for businesses, partnering to deliver measurable outcomes across HR tech, service desk, and beyond.'),
      ctaLabel: getCMSContent('home.hero.cta_label', 'Our Services'),
      ctaLink: '/our-services',
      imageUrl: imageUrl1,
      fallbackUrl: '/images/hero-consultant.webp',
      alt: 'ClimbSphere consultant'
    },
    {
      title: getCMSContent('home.hero_slide2.title', 'Fractional technology leadership for businesses that need progress, not just platforms.'),
      subtitle: getCMSContent('home.hero_slide2.subtitle', 'ClimbSphere brings business and technology leadership together to turn strategy into systems, decisions into action, and transformation into measurable momentum.'),
      ctaLabel: getCMSContent('home.hero_slide2.cta_label', 'Explore Fractional Leadership'),
      ctaLink: getCMSContent('home.hero_slide2.cta_link', '/ftl'),
      imageUrl: imageUrl2,
      fallbackUrl: '/images/hero ftl-consultant.png',
      alt: 'Fractional Technology Leadership'
    }
  ];

  const startAutoPlay = () => {
    stopAutoPlay();
    autoPlayRef.current = setInterval(() => {
      setCurrentIndex((prev) => (prev + 1) % slides.length);
    }, 7000);
  };

  const stopAutoPlay = () => {
    if (autoPlayRef.current) {
      clearInterval(autoPlayRef.current);
    }
  };

  const handleManualSelect = (index) => {
    setCurrentIndex(index);
    startAutoPlay(); // restart timer
  };

  useEffect(() => {
    startAutoPlay();
    return () => stopAutoPlay();
  }, []);

  return (
    <section 
      className="hero" 
      id="home"
      onMouseEnter={stopAutoPlay}
      onMouseLeave={startAutoPlay}
    >
      <div className="hero-slider-container">
        {slides.map((slide, index) => (
          <div 
            key={index} 
            className={`hero-slide-item ${currentIndex === index ? 'active' : ''}`}
          >
            <div className="container hero-inner">
              <div className="hero-content">
                <h1 className="hero-title">
                  {slide.title}
                </h1>
                <p className="hero-desc">
                  {slide.subtitle}
                </p>
                <Link to={slide.ctaLink} className="btn-primary hero-cta">
                  {slide.ctaLabel}
                  <ArrowIcon />
                </Link>
              </div>
              <div className="hero-visual">
                <img
                  src={slide.imageUrl}
                  alt={slide.alt}
                  width="1080"
                  height="1250"
                  fetchpriority="high"
                  onError={(e) => {
                    e.target.src = slide.fallbackUrl;
                  }}
                />
              </div>
            </div>
          </div>
        ))}
      </div>

      {/* Navigation Indicators */}
      <div className="hero-slider-controls">
        {slides.map((_, index) => (
          <button
            key={index}
            className={`hero-dot-indicator ${currentIndex === index ? 'active' : ''}`}
            onClick={() => handleManualSelect(index)}
            aria-label={`Go to slide ${index + 1}`}
          />
        ))}
      </div>
    </section>
  );
}
