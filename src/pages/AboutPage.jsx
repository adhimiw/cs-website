import { Link } from 'react-router-dom';
import SEO from '../components/SEO';
import './AboutPage.css';
import { useCMS } from '../context/CMSContext';
import { GlobalReachIcon, ProvenExpertiseIcon, SaasOptimizationIcon, ScalableIcon } from '../components/Icons';

export default function AboutPage() {
  const { getCMSContent } = useCMS();

  return (
    <div className="about-page">
      <SEO pageKey="about" />
      {/* Page Header Banner */}
      <section className="page-header">
        <div className="container">
          <h1 className="page-title">About Us</h1>
        </div>
      </section>


      {/* Who We Are Section */}
      <section className="who-we-are-section">
        <div className="container grid-two-columns">
          <div className="section-media">
            <img 
              src="/images/about-saas.jpeg" 
              alt="Who We Are - SaaS" 
              className="about-image-large"
              width="736"
              height="736"
              loading="lazy"
            />
          </div>
          <div className="section-content">
            <span className="section-subtitle">{getCMSContent('about.who_we_are.subtitle', 'Who We Are')}</span>
            <h2 className="section-main-title">{getCMSContent('about.who_we_are.title', 'Driving Transformation at Scale')}</h2>
            <p className="section-lead-text">
              {getCMSContent('about.who_we_are.lead_text', 'Nearly 50 years of global experience delivering large-scale HCM and Service Desk transformation solutions across key regions.')}
            </p>
            
            <div className="about-features-list">
              <div className="about-feature-item">
                <div className="feature-icon-container">
                  <GlobalReachIcon className="about-feature-svg" />
                </div>
                <div className="feature-text">
                  <h3>Global Reach</h3>
                </div>
              </div>

              <div className="about-feature-item">
                <div className="feature-icon-container">
                  <ProvenExpertiseIcon className="about-feature-svg" />
                </div>
                <div className="feature-text">
                  <h3>Proven Expertise</h3>
                </div>
              </div>
            </div>

            <Link to="/our-services" className="btn-primary-custom">
              Our Services
              <span className="btn-arrow" aria-hidden="true">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor">
                  <path d="M8 0 6.59 1.41 12.17 7H0v2h12.17l-5.58 5.59L8 16l8-8-8-8Z" />
                </svg>
              </span>
            </Link>
          </div>
        </div>
      </section>

      {/* Expertise in Scalable SaaS Solution Section */}
      <section className="saas-expertise-section">
        <div className="container grid-two-columns alternate-layout">
          <div className="section-content">
            <span className="section-subtitle">{getCMSContent('about.saas_expertise.subtitle', 'Expertise in Scalable SaaS Solution')}</span>
            <h2 className="section-main-title">{getCMSContent('about.saas_expertise.title', 'Expertise in Scalable SaaS Solution')}</h2>
            <p className="section-lead-text">
              {getCMSContent('about.saas_expertise.lead_text', 'We deliver expert SaaS solutions that help businesses streamline processes, improve workflows, and build scalable, future-ready systems.')}
            </p>

            <div className="about-features-list">
              <div className="about-feature-item">
                <div className="feature-icon-container">
                  <SaasOptimizationIcon className="about-feature-svg" />
                </div>
                <div className="feature-text">
                  <h3>SaaS Expertise</h3>
                </div>
              </div>

              <div className="about-feature-item">
                <div className="feature-icon-container">
                  <ScalableIcon className="about-feature-svg" />
                </div>
                <div className="feature-text">
                  <h3>Scalable & Integrated</h3>
                </div>
              </div>
            </div>

            <Link to="/our-services" className="btn-primary-custom">
              Our Services
              <span className="btn-arrow" aria-hidden="true">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor">
                  <path d="M8 0 6.59 1.41 12.17 7H0v2h12.17l-5.58 5.59L8 16l8-8-8-8Z" />
                </svg>
              </span>
            </Link>
          </div>
          
          <div className="section-media">
            <img 
              src="/images/about-expertise.jpeg" 
              alt="Expertise in SaaS Solution" 
              className="about-image-large"
              width="736"
              height="736"
              loading="lazy"
            />
          </div>
        </div>
      </section>

      {/* Leadership Team Section */}
      <section className="leadership-section">
        <div className="container">
          <div className="leadership-header text-center">
            <h2>{getCMSContent('about.leadership.title', 'Our Leadership Team')}</h2>
            <p className="leadership-subtitle">
              {getCMSContent('about.leadership.subtitle', 'Meet the experienced professionals driving transformation and innovation')}
            </p>
          </div>

          <div className="leadership-grid">
            {/* Manoj Cheruvathoor */}
            <div className="leader-card">
              <div className="leader-image-container">
                <img src="/images/leader-manoj.webp" alt="Manoj Cheruvathoor" className="leader-img" width="300" height="300" loading="lazy" />
              </div>
              <h3>Manoj Cheruvathoor</h3>
              <p className="leader-designation">Consulting Director</p>
              <p className="leader-description">
                Manoj is a seasoned transformation leader with over 20 years of experience helping organizations modernize their HR and core business systems across multiple countries. He combines a background in disciplined, mission-focused execution with hands-on delivery of large, complex programs, giving clients a practical partner who understands both strategy and on-the-ground realities. He is known for building strong teams, simplifying complexity, and putting in place processes and systems that improve efficiency, transparency, and governance.
              </p>
              <div className="leader-socials">
                <a href="mailto:manoj.cheruvathoor@climbsphere.ai" className="social-icon-btn" title="Email Manoj">
                  <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                  </svg>
                </a>
                <a href="https://www.linkedin.com/in/manoj-cheruvathoor" target="_blank" rel="noopener noreferrer" className="social-icon-btn" title="LinkedIn Profile">
                  <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                    <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/>
                  </svg>
                </a>
              </div>
            </div>

            {/* Ranjit Kumar */}
            <div className="leader-card">
              <div className="leader-image-container">
                <img src="/images/leader-ranjit.webp" alt="Ranjit Kumar" className="leader-img" width="287" height="300" loading="lazy" />
              </div>
              <h3>Ranjit Kumar</h3>
              <p className="leader-designation">Consulting Director</p>
              <p className="leader-description">
                Ranjit is a technology and business leader with 17 years of experience guiding companies through HR, finance, and operations transformation. He focuses on making systems work together smoothly, standardizing processes, and using data and automation to create measurable business value. Having worked with fast-growing organizations across several regions, he brings a balanced view of strategy, technology, and execution, helping leaders turn their growth plans into scalable, reliable solutions.
              </p>
              <div className="leader-socials">
                <a href="mailto:ranjit.kumar@climbsphere.ai" className="social-icon-btn" title="Email Ranjit">
                  <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                  </svg>
                </a>
                <a href="https://www.linkedin.com/in/ranjitseeker" target="_blank" rel="noopener noreferrer" className="social-icon-btn" title="LinkedIn Profile">
                  <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                    <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/>
                  </svg>
                </a>
              </div>
            </div>

            {/* Barath Silvester */}
            <div className="leader-card">
              <div className="leader-image-container">
                <img src="/images/leader-barath.webp" alt="Barath Silvester" className="leader-img" width="300" height="300" loading="lazy" />
              </div>
              <h3>Barath Silvester</h3>
              <p className="leader-designation">Managing Partner</p>
              <p className="leader-description">
                Barath is an operations and people leader with over 18 years of experience, driving large teams and complex business functions across BPO, aviation services, and education. He brings strong expertise in large-scale operations, workforce leadership, and stakeholder management, ensuring reliable delivery, cost efficiency, and robust governance. With a focus on process standardization, institutional compliance, and India delivery models, he builds scalable, sustainable operations while investing deeply in training, coaching, and leadership development to grow high-performing teams.
              </p>
              <div className="leader-socials">
                <a href="mailto:barath.silvester@climbsphere.ai" className="social-icon-btn" title="Email Barath">
                  <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                  </svg>
                </a>
                <a href="https://www.linkedin.com/in/barath-silvester-raj-joseph-sengolnathan-a2247668" target="_blank" rel="noopener noreferrer" className="social-icon-btn" title="LinkedIn Profile">
                  <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                    <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/>
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
