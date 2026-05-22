import { useState } from 'react';
import { Link } from 'react-router-dom';
import './ServicesPage.css';
import { useCMS } from '../context/CMSContext';

const businessServices = [
  {
    num: '01',
    title: 'Digital Transformation',
    image: '/images/service-digital-transformation.webp',
    tags: ['Discover', 'Design', 'Deliver & Drive'],
    description: "Accelerating business outcomes with strategic technology decisions and seamless execution"
  },
  {
    num: '02',
    title: 'HR Technology',
    image: '/images/service-hr.webp',
    tags: ['Talent Selection', 'Analytics', 'Employee Experience'],
    description: "Human-centric design at the core of our design principles delivers exceptional employee experience through integrated systems, workflows and AI enablement."
  },
  {
    num: '03',
    title: 'Project Management',
    image: '/images/service-dashboard.webp',
    tags: ['Structured Agility', 'Agile & Hybrid', 'Governance'],
    description: "Structured agility at our core — excelling across Agile, Waterfall, and Hybrid methodologies, we deliver what matters most."
  },
  {
    num: '04',
    title: 'Service Desk & Ticketing',
    image: '/images/service-dashboard.webp',
    tags: ['Ticketing Support', 'Intelligent Automation', 'SLA Governance'],
    description: "Customer-centricity and AI-powered automation that turns every interaction into an opportunity for meaningful engagement and customer delight."
  }
];

const partnerServices = [
  {
    num: '01',
    title: 'Professional Services',
    image: '/images/about-expertise.jpeg',
    tags: ['Strategic Accounts', 'Project Execution', 'Portfolio Adoption'],
    description: "Leveraging deep expertise in strategic account management and disciplined execution to champion customer use cases, we deliver lasting value through seamless implementation and support."
  },
  {
    num: '02',
    title: 'Product Partnerships',
    image: '/images/about-saas.jpeg',
    tags: ['Ecosystem Design', 'Partnership Models', 'Shared Value'],
    description: "A well-designed partner ecosystem multiplies reach and creates shared value — we help you build partnership models that deliver real impact across every stakeholder."
  }
];

export default function ServicesPage() {
  const { services } = useCMS();
  const [activeBusiness, setActiveBusiness] = useState(0); // Default to first open
  const [activePartner, setActivePartner] = useState(0); // Default to first open

  const handleToggleBusiness = (index) => {
    setActiveBusiness(activeBusiness === index ? -1 : index);
  };

  const handleTogglePartner = (index) => {
    setActivePartner(activePartner === index ? -1 : index);
  };

  const apiHost = window.location.hostname === 'localhost' ? 'http://localhost:8000' : '';

  const dynamicBusinessServices = services && services.length > 0
    ? services.filter(s => s.type === 'business').map((s, idx) => ({
        num: String(idx + 1).padStart(2, '0'),
        title: s.title,
        image: s.image ? (s.image.startsWith('http') ? s.image : `${apiHost}${s.image}`) : '/images/digital-maturity-assessment.webp',
        tags: s.tags || [],
        description: s.description
      }))
    : businessServices;

  const dynamicPartnerServices = services && services.length > 0
    ? services.filter(s => s.type === 'partner').map((s, idx) => ({
        num: String(idx + 1).padStart(2, '0'),
        title: s.title,
        image: s.image ? (s.image.startsWith('http') ? s.image : `${apiHost}${s.image}`) : '/images/about-expertise.jpeg',
        tags: s.tags || [],
        description: s.description
      }))
    : partnerServices;

  return (
    <div className="services-page">
      {/* Page Header Banner */}
      <section className="page-header">
        <div className="container">
          <h1 className="page-title">Our Services</h1>
        </div>
      </section>

      {/* For Businesses Section */}
      <section className="services-accordion-section section-padding-sm">
        <div className="container">
          <div className="services-section-header">
            <span className="sub-title">What We Offer</span>
            <h2 className="services-group-title">For Businesses</h2>
          </div>

          <div className="accordion-wrapper">
            {dynamicBusinessServices.map((service, index) => {
              const isOpen = activeBusiness === index;
              return (
                <div 
                  key={service.num} 
                  className={`accordion-item ${isOpen ? `active-item color-${(index % 5) + 1}` : ''}`}
                >
                  <div 
                     className="accordion-header" 
                     onClick={() => handleToggleBusiness(index)}
                     role="button"
                     aria-expanded={isOpen}
                  >
                    <span className="item-number">{service.num}</span>
                    <div className="item-main-header">
                      <div className="item-title-row">
                        <h3>{service.title}</h3>
                      </div>
                    </div>
                  </div>

                  <div className={`accordion-body ${isOpen ? 'show-body' : ''}`}>
                    <div className="accordion-body-content">
                      <div className="body-text-col">
                        <p className="service-description">{service.description}</p>
                        <Link to="/contact-us" className="btn-discuss">
                          DISCUSS PROJECT
                        </Link>
                      </div>
                      <div className="body-image-col">
                        <img 
                          src={service.image} 
                          alt={service.title} 
                          className="service-active-image" 
                          onError={(e) => {
                            const currentSrc = e.target.src;
                            if (currentSrc && !currentSrc.includes('/images/')) {
                              const filename = currentSrc.substring(currentSrc.lastIndexOf('/') + 1);
                              if (filename) {
                                e.target.src = `/images/${filename}`;
                                return;
                              }
                            }
                            e.target.onerror = null;
                            e.target.src = 'https://via.placeholder.com/150';
                          }}
                        />
                      </div>
                    </div>
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      </section>

      {/* For Technology Partners Section */}
      <section className="services-accordion-section partner-section-bg section-padding-sm">
        <div className="container">
          <div className="services-section-header">
            <span className="sub-title">Collaborations</span>
            <h2 className="services-group-title">For Technology Partners</h2>
          </div>

          <div className="accordion-wrapper">
            {dynamicPartnerServices.map((service, index) => {
              const isOpen = activePartner === index;
              return (
                <div 
                  key={service.num} 
                  className={`accordion-item ${isOpen ? `active-item color-${(index % 5) + 1}` : ''}`}
                >
                  <div 
                    className="accordion-header" 
                    onClick={() => handleTogglePartner(index)}
                    role="button"
                    aria-expanded={isOpen}
                  >
                    <span className="item-number">{service.num}</span>
                    <div className="item-main-header">
                      <div className="item-title-row">
                        <h3>{service.title}</h3>
                      </div>
                    </div>
                  </div>

                  <div className={`accordion-body ${isOpen ? 'show-body' : ''}`}>
                    <div className="accordion-body-content">
                      <div className="body-text-col">
                        <p className="service-description">{service.description}</p>
                        <Link to="/contact-us" className="btn-discuss">
                          DISCUSS PROJECT
                        </Link>
                      </div>
                      <div className="body-image-col">
                        <img 
                          src={service.image} 
                          alt={service.title} 
                          className="service-active-image" 
                          onError={(e) => {
                            const currentSrc = e.target.src;
                            if (currentSrc && !currentSrc.includes('/images/')) {
                              const filename = currentSrc.substring(currentSrc.lastIndexOf('/') + 1);
                              if (filename) {
                                e.target.src = `/images/${filename}`;
                                return;
                              }
                            }
                            e.target.onerror = null;
                            e.target.src = 'https://via.placeholder.com/150';
                          }}
                        />
                      </div>
                    </div>
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      </section>
    </div>
  );
}
