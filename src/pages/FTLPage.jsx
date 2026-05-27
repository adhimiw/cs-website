import { Link } from 'react-router-dom';
import SEO from '../components/SEO';
import { useCMS } from '../context/CMSContext';
import './AboutPage.css'; // Reuse container, grid, header, and other styling classes
import './FTLPage.css'; // Add page-specific overrides and stepper layout

export default function FTLPage() {
  const { getCMSContent } = useCMS();

  return (
    <div className="ftl-page">
      <SEO 
        pageKey="ftl" 
        defaultTitle="Fractional Technology Leadership | ClimbSphere"
        defaultDesc="Scaling your business shouldn't mean compromising on strategic expertise. ClimbSphere provides an on-demand leadership duo that bridges the gap between your business objectives and your technology ecosystem."
        defaultKeywords="fractional leadership, fractional CTO, technology consulting, business strategy, operations, ClimbSphere"
      />
      
      {/* Page Header Banner */}
      <section className="page-header ftl-header-banner">
        <div className="container">
          <span className="ftl-badge" data-aos="fade-up">Fractional Service</span>
          <h1 className="page-title" data-aos="fade-up" data-aos-delay="100">
            {getCMSContent('ftl.hero.title', 'Fractional Technology Leadership')}
          </h1>
        </div>
      </section>

      {/* Intro section */}
      <section className="ftl-intro-section section-padding">
        <div className="container grid-two-columns">
          <div className="section-content" data-aos="fade-right">
            <span className="sub-title">ON-DEMAND EXEC DUO</span>
            <h2 className="section-title">Senior Expertise, fraction of the cost</h2>
            <p className="section-lead-text">
              {getCMSContent('ftl.hero.subtitle', `Scaling your business shouldn't mean compromising on strategic expertise. At ClimbSphere, we provide an on-demand leadership duo that bridges the gap between your business objectives and your technology ecosystem. You gain access to two senior minds—a functional expert and a technology leader—thinking and acting as one, all at a fraction of the cost of a full-time executive hire.`)}
            </p>
            <div className="ftl-cta-container">
              <Link to="/contact-us" className="btn-primary">
                Book a Consultation
                <span className="btn-arrow" aria-hidden="true">
                  <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M8 0 6.59 1.41 12.17 7H0v2h12.17l-5.58 5.59L8 16l8-8-8-8Z" />
                  </svg>
                </span>
              </Link>
            </div>
          </div>
          <div className="section-media" data-aos="fade-left" data-aos-delay="200">
            <div className="ftl-image-wrapper">
              <img 
                src="/images/hero-ftl-consultant.webp" 
                alt="Fractional Technology Leadership" 
                className="ftl-image-large"
                width="736"
                height="736"
                loading="lazy"
                onError={(e) => {
                  e.target.src = '/images/hero ftl-consultant.png';
                }}
              />
              <div className="decor-card">
                <h4>2 Senior Minds</h4>
                <p>1 Cohesive Strategy</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* The Convergence section */}
      <section className="ftl-convergence-section section-padding-sm" style={{ backgroundColor: 'var(--section-soft)' }}>
        <div className="container">
          <div className="convergence-card" data-aos="zoom-in">
            <div className="grid-two-columns align-items-center">
              <div className="convergence-graphic">
                <div className="venn-diagram">
                  <div className="circle circle-people"><span>People</span></div>
                  <div className="circle circle-tech"><span>Tech</span></div>
                  <div className="circle circle-process"><span>Process</span></div>
                  <div className="overlap-center">Alignment</div>
                </div>
              </div>
              <div className="convergence-content">
                <span className="sub-title">{getCMSContent('ftl.convergence.title', 'The Convergence of People and Tech')}</span>
                <h3 className="section-title" style={{ fontSize: '28px', marginBottom: '20px' }}>Systems aligned with strategy</h3>
                <p className="convergence-text">
                  {getCMSContent('ftl.convergence.content', `Most fractional providers focus purely on the systems you need. We ask a fundamentally different question: What does your business need to achieve, who are the people executing it, and what technology makes that possible?\n\nWe don't just recommend tools; we align your people & processes with your technology infrastructure. By breaking down the silos, we ensure that every system implementation elevates the value realization and directly drives your overarching growth strategy.`)}
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Seamless Integration & Accountability */}
      <section className="ftl-integration-section section-padding">
        <div className="container">
          <div className="text-center" style={{ marginBottom: '60px' }} data-aos="fade-up">
            <span className="sub-title">EMBEDDED PARTNERSHIP</span>
            <h2 className="section-title">{getCMSContent('ftl.integration.title', 'Seamless Integration, Complete Ownership')}</h2>
            <p style={{ maxWidth: '700px', margin: '0 auto', fontSize: '18px', color: 'var(--text-secondary)' }}>
              {getCMSContent('ftl.integration.content', `You don't need another consultant handing you a slide deck of recommendations. You need an embedded partner.`)}
            </p>
          </div>

          <div className="grid-two-columns">
            <div className="integration-card" data-aos="fade-right">
              <div className="card-icon-header">
                <div className="card-icon-container">
                  <svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H7c0-2.76 2.24-5 5-5s5 2.24 5 5c0 1.04-.42 1.99-1.07 2.75z"/>
                  </svg>
                </div>
                <h3>{getCMSContent('ftl.integration.bullet_1_title', 'Pre-Integrated Alignment')}</h3>
              </div>
              <p>
                {getCMSContent('ftl.integration.bullet_1_desc', 'The knowledge of your business processes is built into our framework from day one, eliminating the coordination overhead that stalls transformation.')}
              </p>
            </div>

            <div className="integration-card" data-aos="fade-left" data-aos-delay="100">
              <div className="card-icon-header">
                <div className="card-icon-container" style={{ backgroundColor: 'var(--accent-soft)', color: 'var(--accent)' }}>
                  <svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                  </svg>
                </div>
                <h3>{getCMSContent('ftl.integration.bullet_2_title', 'True Accountability')}</h3>
              </div>
              <p>
                {getCMSContent('ftl.integration.bullet_2_desc', 'We embed ourselves into your organization. We attend key meetings, steer vendor decisions, coach internal teams, and take full ownership of the implementation outcomes.')}
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Four Steps Stepper section */}
      <section className="ftl-steps-section section-padding-sm" style={{ backgroundColor: 'var(--section-soft)', borderTop: '1px solid var(--border)' }}>
        <div className="container">
          <div className="text-center" style={{ marginBottom: '80px' }} data-aos="fade-up">
            <span className="sub-title">METHODOLOGY</span>
            <h2 className="section-title">{getCMSContent('ftl.steps.title', 'Agile Growth in Four Steps')}</h2>
            <p style={{ maxWidth: '600px', margin: '0 auto', color: 'var(--text-secondary)' }}>
              {getCMSContent('ftl.steps.subtitle', 'We guide organizations through a continuous, sustainable transformation.')}
            </p>
          </div>

          <div className="stepper-horizontal">
            {/* Step 1 */}
            <div className="stepper-step" data-aos="fade-up">
              <div className="step-number-node">1</div>
              <div className="step-connector"></div>
              <div className="step-card">
                <span className="step-tag">Phase 01</span>
                <h3>{getCMSContent('ftl.steps.step_1_title', 'Diagnose')}</h3>
                <p>{getCMSContent('ftl.steps.step_1_desc', 'We conduct a simultaneous audit of your technology posture and human capital, pinpointing exactly where translation gaps are costing you money and efficiency.')}</p>
              </div>
            </div>

            {/* Step 2 */}
            <div className="stepper-step" data-aos="fade-up" data-aos-delay="100">
              <div className="step-number-node">2</div>
              <div className="step-connector"></div>
              <div className="step-card">
                <span className="step-tag">Phase 02</span>
                <h3>{getCMSContent('ftl.steps.step_2_title', 'Map')}</h3>
                <p>{getCMSContent('ftl.steps.step_2_desc', 'We deliver a single, integrated blueprint where every technology decision accounts for its human impact.')}</p>
              </div>
            </div>

            {/* Step 3 */}
            <div className="stepper-step" data-aos="fade-up" data-aos-delay="200">
              <div className="step-number-node">3</div>
              <div className="step-connector"></div>
              <div className="step-card">
                <span className="step-tag">Phase 03</span>
                <h3>{getCMSContent('ftl.steps.step_3_title', 'Climb')}</h3>
                <p>{getCMSContent('ftl.steps.step_3_desc', 'We act as your embedded leadership, driving execution and ensuring no strategic imperative falls through the cracks.')}</p>
              </div>
            </div>

            {/* Step 4 */}
            <div className="stepper-step" data-aos="fade-up" data-aos-delay="300">
              <div className="step-number-node">4</div>
              <div className="step-card">
                <span className="step-tag">Phase 04</span>
                <h3>{getCMSContent('ftl.steps.step_4_title', 'Sustain')}</h3>
                <p>{getCMSContent('ftl.steps.step_4_desc', 'We provide an ongoing, scalable operating rhythm, scaling our support up or down to match your evolving business needs.')}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Closing CTA */}
      <section className="ftl-closing-cta section-padding text-center">
        <div className="container" data-aos="zoom-in">
          <h2 className="section-title">Ready to align your technology with your business trajectory?</h2>
          <p style={{ maxWidth: '600px', margin: '0 auto 30px', fontSize: '18px', color: 'var(--text-secondary)' }}>
            Schedule a briefing with our Directors to understand how our dual fractional executive model can accelerate your momentum.
          </p>
          <Link to="/contact-us" className="btn-primary">
            Schedule a Briefing
            <span className="btn-arrow" aria-hidden="true">
              <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor">
                <path d="M8 0 6.59 1.41 12.17 7H0v2h12.17l-5.58 5.59L8 16l8-8-8-8Z" />
              </svg>
            </span>
          </Link>
        </div>
      </section>
    </div>
  );
}
