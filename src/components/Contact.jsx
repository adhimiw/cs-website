import './Contact.css';

export default function Contact() {
  return (
    <section className="callback" id="contact">
      <div className="container callback-grid">
        <div className="callback-title-wrap" data-aos="fade-right" data-aos-delay="200">
          <img src="/images/h2_request_shape01.png" alt="" className="callback-shape" />
          <h2 className="callback-title">Request a Call Back</h2>
        </div>
        <form 
          className="callback-form" 
          onSubmit={(event) => event.preventDefault()}
          data-aos="fade-left"
          data-aos-delay="400"
        >
          <input type="text" placeholder="Name *" required />
          <input type="email" placeholder="E-mail *" required />
          <input type="tel" placeholder="Phone *" required />
          <button type="submit">SEND NOW</button>
        </form>
      </div>
    </section>
  );
}

