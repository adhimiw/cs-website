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
  const heroImage = getCMSContent('home.hero.image', '/images/hero-consultant.webp');
  const imageUrl = heroImage.startsWith('http') || heroImage.startsWith('/images')
    ? heroImage
    : `${apiHost}${heroImage}`;

  return (
    <section className="hero" id="home">
      <div className="container hero-inner">
        <div className="hero-content">
          <h1 className="hero-title" data-aos="fade-up" data-aos-delay="300">
            {getCMSContent('home.hero.title', 'BUSINESS SYSTEM TRANSFORMATION THAT DELIVERS SCALE AND IMPACT')}
          </h1>
          <p className="hero-desc" data-aos="fade-up" data-aos-delay="500">
            {getCMSContent('home.hero.subtitle', 'Technology consulting and AI-first thinking that drives digital strategy and transformation roadmaps for businesses, partnering to deliver measurable outcomes across HR tech, service desk, and beyond.')}
          </p>
          <Link to="/our-services" className="btn-primary hero-cta" data-aos="fade-up" data-aos-delay="700">
            {getCMSContent('home.hero.cta_label', 'Our Services')}
            <ArrowIcon />
          </Link>
        </div>
        <div className="hero-visual" data-aos="fade-left" data-aos-delay="400">
          <span className="hero-ring" aria-hidden="true" />
          <span className="hero-dots" aria-hidden="true" />
          <img
            src={imageUrl}
            alt="ClimbSphere consultant"
            width="1080"
            height="1250"
            fetchpriority="high"
            onError={(e) => {
              e.target.src = '/images/hero-consultant.webp';
            }}
          />
        </div>
      </div>
    </section>
  );
}
