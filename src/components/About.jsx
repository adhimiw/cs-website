import './About.css';

const aboutPoints = [
  'Trusted partner for business process engineering',
  'Technology advisory that powers and modernizes business systems',
  'Delivery of Technology projects that realize business outcome and adoption',
  'Development of AI native solutions that scales with security and cost efficiency',
];

function CheckArrow() {
  return (
    <span className="about-point-icon" aria-hidden="true">
      <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0Zm4.2 8.5-3.9 3.1-1.1-1.4 1.9-1.5H4V7h5.1L7.2 5.5l1.1-1.4 3.9 3.1.8.7-.8.6Z" />
      </svg>
    </span>
  );
}

export default function About() {
  return (
    <section className="about" id="about">
      <div className="container about-grid">
        <div className="about-media" data-aos="fade-right" data-aos-delay="200">
          <img
            className="about-main-img"
            src="/images/about-leader.jpg"
            alt="ClimbSphere leadership"
          />
          <img
            className="about-secondary-img"
            src="/images/about-workshop.png"
            alt="Business transformation workshop"
          />
        </div>

        <div className="about-content" data-aos="fade-left" data-aos-delay="400">
          <span className="sub-title">About Climbsphere</span>
          <h2 className="section-title">Trusted partners in transformation journey</h2>
          <p className="about-text">
            We are experts in advising, designing, and leading technology transformations that have
            delivered competitive advantage to businesses worldwide. Our deep expertise in processes
            and technology has built our reputation - and the confidence to craft the services that
            empower businesses to scale with clarity and impact.
          </p>

          <ul className="about-list">
            {aboutPoints.map((point) => (
              <li key={point}>
                <CheckArrow />
                <span>{point}</span>
              </li>
            ))}
          </ul>
        </div>
      </div>
    </section>
  );
}
