import { useState, useEffect, useRef } from 'react';
import { Link } from 'react-router-dom';
import { useCMS, getApiUrl } from '../context/CMSContext';
import './Services.css';

const serviceIcons = {
  'Digital Transformation': (
    <svg viewBox="0 0 64 64" aria-hidden="true">
      <path d="M32 47v7M24 54h16M32 12v7M20 17l5 5M44 17l-5 5" />
      <path d="M23 34a9 9 0 1 1 18 0c0 5-4 7-5 10h-8c-1-3-5-5-5-10Z" />
      <path d="M27 48h10" />
    </svg>
  ),
  'Service Desk & Ticketing': (
    <svg viewBox="0 0 64 64" aria-hidden="true">
      <path d="M18 22h28v18H18z" />
      <path d="M24 22v-6h16v6M24 46h16M32 40v6" />
      <path d="m22 32 6 5 14-11" />
    </svg>
  ),
  'HR Technology': (
    <svg viewBox="0 0 64 64" aria-hidden="true">
      <circle cx="32" cy="22" r="8" />
      <path d="M18 50c2-9 8-14 14-14s12 5 14 14" />
      <path d="M16 34c-5 1-8 5-9 11M48 34c5 1 8 5 9 11" />
    </svg>
  ),
  'Project Management': (
    <svg viewBox="0 0 64 64" aria-hidden="true">
      <path d="M20 44c-8-7-9-19-2-27 7-7 19-8 27-1" />
      <path d="M44 20h11V9" />
      <path d="M44 20c7 8 7 20 0 27-7 7-19 8-27 1" />
      <path d="M20 44H9v11" />
    </svg>
  ),
  'Professional Services': (
    <svg viewBox="0 0 64 64" aria-hidden="true">
      <rect x="12" y="20" width="40" height="28" rx="2" fill="none" stroke="currentColor" strokeWidth="2.5" />
      <path d="M22 20v-6h20v6" fill="none" stroke="currentColor" strokeWidth="2.5" />
      <circle cx="32" cy="34" r="5" fill="none" stroke="currentColor" strokeWidth="2.5" />
      <path d="M42 34h6M16 34h6" fill="none" stroke="currentColor" strokeWidth="2.5" />
    </svg>
  ),
  'Product Partnerships': (
    <svg viewBox="0 0 64 64" aria-hidden="true">
      <circle cx="22" cy="32" r="6" fill="none" stroke="currentColor" strokeWidth="2.5" />
      <circle cx="42" cy="32" r="6" fill="none" stroke="currentColor" strokeWidth="2.5" />
      <path d="M28 32h8" fill="none" stroke="currentColor" strokeWidth="2.5" />
      <path d="M16 38c0 5 4 8 8 8h16c4 0 8-3 8-8" fill="none" stroke="currentColor" strokeWidth="2.5" />
    </svg>
  ),
};

const homeServiceDescriptions = {
  'Digital Transformation': 'Accelerating business outcomes with strategic technology decisions and seamless execution',
  'HR Technology': 'Human-centric design at the core of our design principles delivers exceptional employee experience through integrated systems, workflows and AI enablement.',
  'Service Desk & Ticketing': 'Customer-centricity and AI-powered automation that turns every interaction into an opportunity for meaningful engagement and customer delight.',
  'Project Management': 'Structured agility at our core — excelling across Agile, Waterfall, and Hybrid methodologies, we deliver what matters most.',
  'Professional Services': 'Leveraging deep expertise in strategic account management and disciplined execution to champion customer use cases, we deliver lasting value through seamless implementation and support.',
  'Product Partnerships': 'A well-designed partner ecosystem multiplies reach and creates shared value — we help you build partnership models that deliver real impact across every stakeholder.',
};

const defaultServices = [
  {
    title: 'Digital Transformation',
    image: '/images/service-digital-transformation.webp',
    description: homeServiceDescriptions['Digital Transformation'],
    icon: serviceIcons['Digital Transformation'],
  },
  {
    title: 'Service Desk & Ticketing',
    image: '/images/service-dashboard.webp',
    description: homeServiceDescriptions['Service Desk & Ticketing'],
    icon: serviceIcons['Service Desk & Ticketing'],
  },
  {
    title: 'HR Technology',
    image: '/images/service-hr.webp',
    description: homeServiceDescriptions['HR Technology'],
    icon: serviceIcons['HR Technology'],
  },
  {
    title: 'Project Management',
    image: '/images/service-dashboard.webp',
    description: homeServiceDescriptions['Project Management'],
    icon: serviceIcons['Project Management'],
  },
  {
    title: 'Professional Services',
    image: '/images/service_professional.png',
    description: homeServiceDescriptions['Professional Services'],
    icon: serviceIcons['Professional Services'],
  },
  {
    title: 'Product Partnerships',
    image: '/images/service_partnerships.png',
    description: homeServiceDescriptions['Product Partnerships'],
    icon: serviceIcons['Product Partnerships'],
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

function ServiceCard({ service, index }) {
  const [imgUrl, setImgUrl] = useState(service.image);

  useEffect(() => {
    if (!service.image) {
      setImgUrl('/images/service-digital-transformation.webp');
      return;
    }

    const img = new Image();
    img.src = service.image;
    img.onerror = () => {
      if (service.image && !service.image.includes('/images/')) {
        const filename = service.image.substring(service.image.lastIndexOf('/') + 1);
        if (filename) {
          setImgUrl(`/images/${filename}`);
          return;
        }
      }
      setImgUrl('/images/service-digital-transformation.webp');
    };
    img.onload = () => {
      setImgUrl(service.image);
    };
  }, [service.image]);

  return (
    <article
      className="service-card"
      style={{ '--service-image': `url(${imgUrl})` }}
      data-aos="fade-up"
      data-aos-delay={100 + index * 100}
    >
      <div className="service-overlay">
        <div className="service-icon">{service.icon}</div>
        <h3>{service.title}</h3>
        <p className="service-card-desc">{service.description}</p>
      </div>
    </article>
  );
}

export default function Services() {
  const { services: cmsServices } = useCMS();
  const [isVisible, setIsVisible] = useState(false);
  const [autoplay, setAutoplay] = useState(true);
  const sectionRef = useRef(null);
  const carouselRef = useRef(null);

  const [formData, setFormData] = useState({
    name: '',
    phone: '',
    email: '',
    message: ''
  });
  const [submitted, setSubmitted] = useState(false);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState(null);

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSubmitting(true);
    setError(null);
    try {
      const response = await fetch(getApiUrl('/api/contact'), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          name: formData.name,
          email: formData.email,
          phone: formData.phone,
          subject: 'Digital Maturity Assessment Form',
          message: formData.message || 'No additional details provided.'
        })
      });

      const resData = await response.json();
      if (!response.ok) {
        throw new Error(resData.message || 'Failed to submit digital maturity assessment form.');
      }

      setSubmitted(true);
      setFormData({ name: '', phone: '', email: '', message: '' });
      setTimeout(() => {
        setSubmitted(false);
      }, 5000);
    } catch (err) {
      console.error('Digital Maturity Assessment form error:', err);
      setError(err.message || 'An error occurred. Please try again.');
    } finally {
      setSubmitting(false);
    }
  };

  const apiHost = window.location.hostname === 'localhost' ? 'http://localhost:8000' : '';

  const displayServices = cmsServices && cmsServices.length > 0
    ? cmsServices.map(s => ({
        title: s.title,
        description: homeServiceDescriptions[s.title] || s.description,
        image: s.image ? (s.image.startsWith('http') || s.image.startsWith('/images') ? s.image : `${apiHost}${s.image}`) : '/images/service-digital-transformation.webp',
        icon: serviceIcons[s.title] || (
          <svg viewBox="0 0 64 64" aria-hidden="true">
            <path d="M32 47v7M24 54h16M32 12v7M20 17l5 5M44 17l-5 5" />
            <path d="M23 34a9 9 0 1 1 18 0c0 5-4 7-5 10h-8c-1-3-5-5-5-10Z" />
            <path d="M27 48h10" />
          </svg>
        )
      }))
    : defaultServices;

  useEffect(() => {
    const currentSection = sectionRef.current;
    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setIsVisible(true);
        }
      },
      { threshold: 0.15 }
    );
    if (currentSection) {
      observer.observe(currentSection);
    }
    return () => {
      if (currentSection) {
        observer.unobserve(currentSection);
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
              {displayServices.map((service, index) => (
                <ServiceCard
                  key={service.title}
                  service={service}
                  index={index}
                />
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
            <img src="/images/IDM-TECHPARK.webp" alt="Woman holding laptop" className="overview-main-img" width="736" height="793" loading="lazy" />
            <img src="/images/overview_img_shape.png" alt="" className="overview-shape-img" width="202" height="185" loading="lazy" />
          </div>
          
          <div className="overview-copy">
            <span className="overview-badge">Where to begin?</span>
            <h2 className="overview-title">Digital Maturity Assessments</h2>
            <p className="overview-text">
              The right digital investment starts with clear assessment & allocation.
              ClimbSphere's structured assessment evaluates your capabilities across five dimensions
              Strategy, Technology, Processes, Customer Experience and Governance replacing
              guesswork with evidence based roadmaps.
            </p>
            <Link to="/our-services" className="overview-btn">
              KNOW MORE
              <span className="overview-btn-icon">
                <ChevronIcon />
              </span>
            </Link>
          </div>

          <form className="overview-form" onSubmit={handleSubmit}>
            {submitted && (
              <div className="submission-success-alert" style={{ background: '#d1e7dd', color: '#0f5132', padding: '15px 20px', borderRadius: 'var(--radius-md)', marginBottom: '20px', fontSize: '15px', fontWeight: '600', border: '1px solid #badbcc', width: '100%' }}>
                Thank you! Your request has been sent successfully. We will get back to you shortly.
              </div>
            )}
            {error && (
              <div className="submission-error-alert" style={{ background: '#f8d7da', color: '#842029', padding: '15px 20px', borderRadius: 'var(--radius-md)', marginBottom: '20px', fontSize: '15px', fontWeight: '600', border: '1px solid #f5c2c7', width: '100%' }}>
                {error}
              </div>
            )}
            <div className="overview-form-group">
              <label htmlFor="maturity-name">
                Name <span className="required">*</span>
              </label>
              <input 
                id="maturity-name"
                type="text" 
                name="name" 
                value={formData.name} 
                onChange={handleInputChange} 
                placeholder="e.g. Jane Doe"
                autoComplete="name"
                required 
              />
            </div>
            <div className="overview-form-group">
              <label htmlFor="maturity-phone">
                Phone <span className="required">*</span>
              </label>
              <input 
                id="maturity-phone"
                type="tel" 
                name="phone" 
                value={formData.phone} 
                onChange={handleInputChange} 
                placeholder="e.g. +1 (555) 000-0000"
                autoComplete="tel"
                required 
              />
            </div>
            <div className="overview-form-group">
              <label htmlFor="maturity-email">
                Email <span className="required">*</span>
              </label>
              <input 
                id="maturity-email"
                type="email" 
                name="email" 
                value={formData.email} 
                onChange={handleInputChange} 
                placeholder="e.g. jane@example.com"
                autoComplete="email"
                spellCheck={false}
                required 
              />
            </div>
            <div className="overview-form-group">
              <label htmlFor="maturity-message">Message</label>
              <textarea 
                id="maturity-message"
                name="message" 
                value={formData.message} 
                onChange={handleInputChange} 
                placeholder="e.g. Tell us about your digital goals…"
                rows="3" 
              />
            </div>
            <button type="submit" className="overview-form-submit" disabled={submitting}>
              {submitting ? 'Submitting…' : 'Submit'}
            </button>
          </form>
        </div>
      </section>
    </>
  );
}
