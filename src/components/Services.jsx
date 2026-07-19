import { useState, useEffect, useRef } from 'react';
import { Link } from 'react-router-dom';
import { useCMS } from '../context/CMSContext';
import './Services.css';
import ServiceCard from './ServiceCard';
import MaturityAssessmentForm from './MaturityAssessmentForm';
import { ArrowIcon, ChevronIcon, ChevronLeftIcon, ChevronRightIcon } from './Icons';

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

export default function Services() {
  const { services: cmsServices } = useCMS();
  const [isVisible, setIsVisible] = useState(false);
  const [autoplay, setAutoplay] = useState(true);
  const sectionRef = useRef(null);
  const carouselRef = useRef(null);

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

          <MaturityAssessmentForm />
        </div>
      </section>
    </>
  );
}
