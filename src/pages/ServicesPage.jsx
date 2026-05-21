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
    description: "Meaningful transformation happens when technology serves business strategy. ClimbSphere's Discover, Design, Deliver, Drive cycle aligns people, processes, and platforms for measurable outcomes, accelerating your evolution with focus and momentum."
  },
  {
    num: '02',
    title: 'HR Technology',
    image: '/images/service-hr.webp',
    tags: ['Talent Selection', 'Analytics', 'Employee Experience'],
    description: "A connected, intelligent HR ecosystem empowers your people teams to attract, retain and grow talent with clarity and confidence. ClimbSphere optimizes talent management, analytics and employee experience through seamless platform selection, implementation and adoption."
  },
  {
    num: '03',
    title: 'Project Management',
    image: '/images/service-dashboard.webp',
    tags: ['Structured Agility', 'Agile & Hybrid', 'Governance'],
    description: "ClimbSphere brings structured agility to every engagement, blending Agile, Waterfall, or Hybrid methodologies with hands-on governance and transparent reporting — keeping your initiatives on track, on budget and aligned to the goals that matter most."
  },
  {
    num: '04',
    title: 'Service Desk & Ticketing',
    image: '/images/service-dashboard.webp',
    tags: ['Ticketing Support', 'Intelligent Automation', 'SLA Governance'],
    description: "A well designed service desk drives productivity, strengthens trust and elevates IT's role as a strategic business partner. ClimbSphere designs and deploys efficient ticketing, self service portals, intelligent automation and SLA-driven governance that turns support operations into a competitive advantage."
  }
];

const partnerServices = [
  {
    num: '01',
    title: 'Professional Services',
    image: '/images/about-expertise.jpeg',
    tags: ['Strategic Accounts', 'Project Execution', 'Portfolio Adoption'],
    description: "Scale your customer wins with end-to-end professional services excellence. ClimbSphere combines strategic key account management, disciplined project execution and seamless implementation to drive adoption, expansion and reference success across your portfolio replacing guesswork with evidence based roadmaps."
  },
  {
    num: '02',
    title: 'Product Partnerships',
    image: '/images/about-saas.jpeg',
    tags: ['Ecosystem Design', 'Partnership Models', 'Shared Value'],
    description: "A well designed partner ecosystem multiplies your reach, strengthens your product and creates shared value across every stakeholder. ClimbSphere helps you design and operationalize partnership models that deliver genuine three way impact for your organization, your partners and the end customer."
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
