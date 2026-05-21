import { useEffect } from 'react';
import AOS from 'aos';
import './Stats.css';

const metrics = [
  { value: '500+', label: 'Projects Completed' },
  { value: '50+', label: 'Enterprise Clients' },
  { value: '15+', label: 'Years Experience' },
  { value: '98%', label: 'Client Retention' },
];

export default function Stats() {
  useEffect(() => {
    AOS.refresh();
  }, []);

  return (
    <section className="stats">
      <div className="container">
        <div className="stats-grid">
          {metrics.map((m, i) => (
            <div
              key={m.label}
              className="stat-card"
              data-aos="fade-up"
              data-aos-delay={i * 100}
            >
              <span className="stat-value">{m.value}</span>
              <span className="stat-label">{m.label}</span>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
