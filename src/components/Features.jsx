import './Features.css';

const features = [
  {
    title: 'Scaled Processes',
    desc: 'Assess & elevate digital maturity',
    icon: (
      <svg viewBox="0 0 64 64" aria-hidden="true">
        <path d="M14 46h36M18 40l10-10 8 8 12-16" />
        <path d="M19 23h10M19 30h5M13 12h38v40H13z" />
        <circle cx="20" cy="20" r="2" />
        <circle cx="44" cy="20" r="2" />
      </svg>
    ),
  },
  {
    title: 'Technology Advantage',
    desc: 'Make smarter tech choices, implemented right',
    icon: (
      <svg viewBox="0 0 64 64" aria-hidden="true">
        <ellipse cx="25" cy="18" rx="15" ry="7" />
        <path d="M10 18v18c0 4 7 7 15 7 3.5 0 7-.7 9.5-2" />
        <path d="M10 27c0 4 7 7 15 7 3 0 6-.5 8.5-1.6" />
        <circle cx="44" cy="38" r="10" />
        <path d="M44 31v14M37 38h14" />
      </svg>
    ),
  },
  {
    title: 'Transform with AI',
    desc: 'Deploy secure, scalable AI with confidence',
    icon: (
      <svg viewBox="0 0 64 64" aria-hidden="true">
        <path d="m32 8 24 12-24 12L8 20 32 8Z" />
        <path d="m12 30 20 10 20-10M12 40l20 10 20-10" />
      </svg>
    ),
  },
];

export default function Features() {
  return (
    <section className="features-section">
      <div className="container">
        <div className="features-grid">
          {features.map((feature, index) => (
            <article 
              key={feature.title} 
              className="feature-card"
              data-aos="fade-up"
              data-aos-delay={100 + index * 200}
            >
              <div className="feature-icon">{feature.icon}</div>
              <div className="feature-copy">
                <h3>{feature.title}</h3>
                <p>{feature.desc}</p>
              </div>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}
