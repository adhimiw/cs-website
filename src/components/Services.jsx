import { useState, useEffect, useRef } from 'react';
import { Link } from 'react-router-dom';
import './Services.css';

const services = [
  {
    title: 'Digital Transformation',
    image: '/images/service-digital-transformation.webp',
    icon: (
      <svg viewBox="0 0 64 64" aria-hidden="true">
        <path d="M32 47v7M24 54h16M32 12v7M20 17l5 5M44 17l-5 5" />
        <path d="M23 34a9 9 0 1 1 18 0c0 5-4 7-5 10h-8c-1-3-5-5-5-10Z" />
        <path d="M27 48h10" />
      </svg>
    ),
  },
  {
    title: 'Service Desk & Ticketing',
    image: '/images/service-dashboard.webp',
    icon: (
      <svg viewBox="0 0 64 64" aria-hidden="true">
        <path d="M18 22h28v18H18z" />
        <path d="M24 22v-6h16v6M24 46h16M32 40v6" />
        <path d="m22 32 6 5 14-11" />
      </svg>
    ),
  },
  {
    title: 'HR Technology',
    image: '/images/service-hr.webp',
    icon: (
      <svg viewBox="0 0 64 64" aria-hidden="true">
        <circle cx="32" cy="22" r="8" />
        <path d="M18 50c2-9 8-14 14-14s12 5 14 14" />
        <path d="M16 34c-5 1-8 5-9 11M48 34c5 1 8 5 9 11" />
      </svg>
    ),
  },
  {
    title: 'Project Management',
    image: '/images/service-dashboard.webp',
    icon: (
      <svg viewBox="0 0 64 64" aria-hidden="true">
        <path d="M20 44c-8-7-9-19-2-27 7-7 19-8 27-1" />
        <path d="M44 20h11V9" />
        <path d="M44 20c7 8 7 20 0 27-7 7-19 8-27 1" />
        <path d="M20 44H9v11" />
      </svg>
    ),
  },
  {
    title: 'Professional Services',
    image: '/images/service_professional.png',
    icon: (
      <svg viewBox="0 0 64 64" aria-hidden="true">
        <rect x="12" y="20" width="40" height="28" rx="2" fill="none" stroke="currentColor" strokeWidth="2.5" />
        <path d="M22 20v-6h20v6" fill="none" stroke="currentColor" strokeWidth="2.5" />
        <circle cx="32" cy="34" r="5" fill="none" stroke="currentColor" strokeWidth="2.5" />
        <path d="M42 34h6M16 34h6" fill="none" stroke="currentColor" strokeWidth="2.5" />
      </svg>
    ),
  },
  {
    title: 'Product Partnerships',
    image: '/images/service_partnerships.png',
    icon: (
      <svg viewBox="0 0 64 64" aria-hidden="true">
        <circle cx="22" cy="32" r="6" fill="none" stroke="currentColor" strokeWidth="2.5" />
        <circle cx="42" cy="32" r="6" fill="none" stroke="currentColor" strokeWidth="2.5" />
        <path d="M28 32h8" fill="none" stroke="currentColor" strokeWidth="2.5" />
        <path d="M16 38c0 5 4 8 8 8h16c4 0 8-3 8-8" fill="none" stroke="currentColor" strokeWidth="2.5" />
      </svg>
    ),
  },
];

function ArrowIcon() {
  return (
    <span className="btn-arrow" aria-hidden="true">
      <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor">
        <path d="M8 0 6.59 1.41 12.17 7H0v2h12.17l-5.58 5.59L8 16l8-8-8-8Z" />
      </svg>
    </span>
  );
}

function ChevronIcon() {
  return (
    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
      <path d="M4.5 9l3-3-3-3" />
    </svg>
  );
}

function ChevronLeftIcon() {
  return (
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
      <path d="M15 18l-6-6 6-6" />
    </svg>
  );
}

function ChevronRightIcon() {
  return (
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
      <path d="M9 18l6-6-6-6" />
    </svg>
  );
}

export default function Services() {
  const [isVisible, setIsVisible] = useState(false);
  const [autoplay, setAutoplay] = useState(true);
  const sectionRef = useRef(null);
  const carouselRef = useRef(null);

  useEffect(() => {
    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setIsVisible(true);
        }
      },
      { threshold: 0.15 }
    );
    if (sectionRef.current) {
      observer.observe(sectionRef.current);
    }
    return () => {
      if (sectionRef.current) {
        observer.unobserve(sectionRef.current);
      }
    };
  }, []);

  const scrollLeft = () => {
    if (carouselRef.current) {
      const container = carouselRef.current;
      const cardWidth = container.querySelector('.service-card')?.clientWidth || 320;
      const gap = 30;
      container.scrollBy({ left: -(cardWidth + gap), behavior: 'smooth' });
    }
  };

  const scrollRight = () => {
    if (carouselRef.current) {
      const container = carouselRef.current;
      const cardWidth = container.querySelector('.service-card')?.clientWidth || 320;
      const gap = 30;
      const scrollStep = cardWidth + gap;
      
      // If we are at the end, wrap back to the start
      if (container.scrollLeft + container.clientWidth >= container.scrollWidth - 15) {
        container.scrollTo({ left: 0, behavior: 'smooth' });
      } else {
        container.scrollBy({ left: scrollStep, behavior: 'smooth' });
      }
    }
  };

  useEffect(() => {
    if (!autoplay) return;
    const interval = setInterval(() => {
      scrollRight();
    }, 3500);
    return () => clearInterval(interval);
  }, [autoplay]);

  return (
    <>
      <section className="services" id="services">
        <div className="container">
          <div className="services-heading" data-aos="fade-up" data-aos-delay="200">
            <div className="services-heading-left">
              <span className="sub-title">What We Do</span>
              <h2 className="section-title">
                Supercharge HR & Service Management Systems. Deliver Experiences That Delight.
              </h2>
            </div>
            <div className="services-heading-right">
              <Link to="/our-services" className="btn-primary services-link">
                See All Service
                <ArrowIcon />
              </Link>
              <div className="carousel-controls">
                <button className="carousel-control-btn prev" onClick={scrollLeft} aria-label="Previous service">
                  <ChevronLeftIcon />
                </button>
                <button className="carousel-control-btn next" onClick={scrollRight} aria-label="Next service">
                  <ChevronRightIcon />
                </button>
              </div>
            </div>
          </div>

          <div 
            className="services-carousel-wrapper" 
            ref={carouselRef}
            onMouseEnter={() => setAutoplay(false)}
            onMouseLeave={() => setAutoplay(true)}
            onTouchStart={() => setAutoplay(false)}
            onTouchEnd={() => setAutoplay(true)}
          >
            <div className="services-carousel-track">
              {services.map((service, index) => (
                <article
                  key={service.title}
                  className="service-card"
                  style={{ '--service-image': `url(${service.image})` }}
                  data-aos="fade-up"
                  data-aos-delay={100 + index * 100}
                >
                  <div className="service-overlay">
                    <div className="service-icon">{service.icon}</div>
                    <h3>{service.title}</h3>
                  </div>
                </article>
              ))}
            </div>
          </div>
        </div>
      </section>

      <section 
        className={`overview-section ${isVisible ? 'reveal' : ''}`} 
        ref={sectionRef}
      >
        <div className="container overview-grid">
          <div className="overview-media">
            <img src="/images/IDM-TECHPARK.webp" alt="Woman holding laptop" className="overview-main-img" />
            <img src="/images/overview_img_shape.png" alt="" className="overview-shape-img" />
          </div>
          
          <div className="overview-copy">
            <span className="overview-badge">Where to begin?</span>
            <h2 className="overview-title">Digital Maturity Assessments</h2>
            <p className="overview-text">
              The right digital investment starts with clear assessment & allocation.
              ClimbSphere's structured assessment evaluates your capabilities across six dimensions
              Strategy, Technology, Processes, Customer Experience and Governance replacing
              guesswork with evidence based roadmaps.
            </p>
            <Link to="/blog" className="overview-btn">
              KNOW MORE
              <span className="overview-btn-icon">
                <ChevronIcon />
              </span>
            </Link>
          </div>

          <form className="overview-form" onSubmit={(event) => event.preventDefault()}>
            <div className="overview-form-group">
              <label>
                Name <span className="required">*</span>
              </label>
              <input type="text" required />
            </div>
            <div className="overview-form-group">
              <label>
                Phone <span className="required">*</span>
              </label>
              <input type="tel" required />
            </div>
            <div className="overview-form-group">
              <label>
                Email <span className="required">*</span>
              </label>
              <input type="email" required />
            </div>
            <div className="overview-form-group">
              <label>Message</label>
              <textarea rows="3" />
            </div>
            <button type="submit" className="overview-form-submit">Submit</button>
          </form>
        </div>
      </section>
    </>
  );
}
