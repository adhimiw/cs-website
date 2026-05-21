import './Testimonials.css';
import { useCMS } from '../context/CMSContext';

const staticTestimonials = [
  {
    text: 'ClimbSphere offers an excellent user experience with smooth navigation and powerful features for businesses looking to streamline operations efficiently.',
    author: 'Priya Sharma',
    image: '/images/testimonial-avatar.png',
  },
  {
    text: 'Great platform with useful tools for management and collaboration. It has significantly improved our workflow and productivity.',
    author: 'Rahul',
    image: '/images/testimonial-avatar.png',
  },
];

function Stars() {
  return (
    <div className="testimonial-stars" aria-label="5 star rating">
      {Array.from({ length: 5 }).map((_, index) => (
        <span key={index}>&#9733;</span>
      ))}
    </div>
  );
}

export default function Testimonials() {
  const { testimonials } = useCMS();

  const apiHost = window.location.hostname === 'localhost' ? 'http://localhost:8000' : '';

  const dynamicTestimonials = testimonials && testimonials.length > 0
    ? testimonials.map(t => ({
        text: t.text,
        author: t.author,
        image: t.image ? (t.image.startsWith('http') ? t.image : `${apiHost}${t.image}`) : '/images/testimonial-avatar.png',
        designation: t.designation,
        relationship: t.relationship,
      }))
    : staticTestimonials;

  return (
    <section className="testimonials">
      <button className="testimonial-arrow testimonial-arrow-left" aria-label="Previous testimonial">
        <svg viewBox="0 0 20 20" aria-hidden="true">
          <path d="M12.8 4 6.8 10l6 6-1.6 1.6L3.6 10l7.6-7.6L12.8 4Z" />
        </svg>
      </button>

      <div className="container testimonials-grid">
        {dynamicTestimonials.map((testimonial, index) => (
          <article 
            key={testimonial.author} 
            className="testimonial-card"
            data-aos="fade-up"
            data-aos-delay={200 + index * 200}
          >
            <Stars />
            <p>" {testimonial.text} "</p>
            <div className="testimonial-author">
              <img
                src={testimonial.image}
                alt=""
                onError={(e) => {
                  e.target.src = '/images/testimonial-avatar.png';
                }}
              />
              <span />
              <div className="testimonial-author-info">
                <h3>{testimonial.author}</h3>
                {testimonial.designation && (
                  <div className="testimonial-designation">{testimonial.designation}</div>
                )}
                {testimonial.relationship && (
                  <div className="testimonial-relationship">{testimonial.relationship}</div>
                )}
              </div>
            </div>
          </article>
        ))}
      </div>

      <button className="testimonial-arrow testimonial-arrow-right" aria-label="Next testimonial">
        <svg viewBox="0 0 20 20" aria-hidden="true">
          <path d="m7.2 16 6-6-6-6 1.6-1.6 7.6 7.6-7.6 7.6L7.2 16Z" />
        </svg>
      </button>
    </section>
  );
}
