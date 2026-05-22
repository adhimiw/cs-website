import './Testimonials.css';
import { useCMS } from '../context/CMSContext';

const staticTestimonials = [
  {
    text: "I got the chance to work with Manoj on couple of projects in Singapore, where he played the role of a Functional and QA lead. He did excel in his assigned role at the institution and customers confided in him more than anyone else in the team. But another, more commendable quality is his exceptional Managerial skills - some of the key qualities that I had seen in him was to start with a defined project scope, yet be flexible to incorporate key items provided the timeline is not impacted, impressive quality to go along with the team and ability to get the work done \"on-time & within budget\", something that he most probably would've inherited it from his previous role as an officer in Air Force. End result - happy upper management and delighted customers. He definitely would be an asset to whichever organization, he works with. Wish you all the very best, Manoj !!",
    author: 'Sandeep Mishra',
    image: '/images/testimonial-avatar.png',
  },
  {
    text: "During the five years I have worked with Manoj, seldom did he need much intervention or guidance to be fully successful with his projects. His communication skills, both in terms of the English language and in the art of good project communication, were excellent. His attitude is one of \"can do\" regardless of the challenges faced and this is one of the characteristics that made project teams embrace his leadership. I would be very fortunate to have Manoj on any of my projects again in the future.",
    author: 'Lynn Duffy',
    image: '/images/testimonial-avatar.png',
  },
  {
    text: "Manoj is a great person to work with. We have worked together in multiple projects from my early days in Citagus. Manoj was a great Manager and a great person to work with. His expert functional knowledge in HR was always that extra advantage we had in the projects. He had a laser sharp focus on the deliverables and was always able to maintain the client and the team in high spirits. He is a kind of person who can pull out projects of any nature and come out with flying colors. Looking forward to working with you again Manoj .....",
    author: 'Anoop Joseph',
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
