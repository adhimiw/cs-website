import { useState } from 'react';
import { Link } from 'react-router-dom';
import { getApiUrl } from '../context/CMSContext';
import './ContactPage.css';

export default function ContactPage() {
  const [formData, setFormData] = useState({
    name: '',
    phone: '',
    email: '',
    message: ''
  });

  const [submitted, setSubmitted] = useState(false);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState(null);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSubmitting(true);
    setError(null);
    try {
      const response = await fetch(getApiUrl('/api/contact'), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          name: formData.name,
          email: formData.email,
          phone: formData.phone,
          subject: 'Contact Page Inquiry',
          message: formData.message || 'No message provided.'
        })
      });

      const resData = await response.json();
      if (!response.ok) {
        throw new Error(resData.message || 'Failed to submit contact form.');
      }

      setSubmitted(true);
      setFormData({ name: '', phone: '', email: '', message: '' });
      setTimeout(() => {
        setSubmitted(false);
      }, 5000);
    } catch (err) {
      console.error('Contact submission error:', err);
      setError(err.message || 'An error occurred. Please try again.');
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="contact-page">
      {/* Page Header Banner */}
      <section className="page-header">
        <div className="container">
          <h1 className="page-title">Contact Us</h1>
        </div>
      </section>

      {/* Contact Cards Section */}
      <section className="contact-cards-section">
        <div className="container contact-cards-grid">
          {/* Card 1: Call Us */}
          <div className="contact-card">
            <div className="card-icon-container">
              <svg className="contact-card-svg" viewBox="0 0 300 300" fill="currentColor">
                <g transform="translate(0, 300) scale(1, -1)">
                  <path d="M237.0945070345247 114.1606480289401C230.9527528144431 120.5556704642829 223.544657518056 123.9747913703077 215.6933428449619 123.9747913703077C207.9053452256831 123.9747913703077 200.4339328754807 120.6189875180982 194.038910440138 114.2239650827554L174.0307214345112 94.2790931309439C172.384478035314 95.1655318843578 170.7382346361169 95.9886535839563 169.155308290735 96.8117752835549C166.8758943533851 97.9514822522299 164.7231145236658 99.0278721670895 162.8869199630229 100.1675791357645C144.1450720337016 112.0711852530361 127.1127845573927 127.5838634377784 110.7769846730518 147.6553694972204C102.8623529461425 157.6594640000338 97.5437204256595 166.0806321574654 93.6813801429277 174.6284344225274C98.8733785557802 179.377213458673 103.6854746457411 184.3159436562644 108.3709366280714 189.06472269241C110.1438141348991 190.8376001992377 111.9166916417268 192.6737947598807 113.6895691485545 194.4466722667084C126.9861504497622 207.7432535679161 126.9861504497622 224.9654922056707 113.6895691485545 238.2620735068784L96.4040134569845 255.5476291984484C94.441184788711 257.5104578667219 92.4150390666222 259.5366035888107 90.515527452164 261.5627493108995C86.7165042232475 265.4884066474465 82.7275298328852 269.5406980916241 78.6119213348923 273.3397213205406C72.4701671148107 279.4181584868069 65.1253888722389 282.6473282313859 57.4007083067753 282.6473282313859S42.2046153911094 279.4181584868069 35.872910009582 273.3397213205406C35.8095929557667 273.2764042667253 35.8095929557667 273.2764042667253 35.7462759019514 273.21308721291L14.2184776047581 251.4953377542708C6.1138947164029 243.3907548659156 1.4917497878879 233.5132944707328 0.4786769268435 222.0529077301681C-1.0409323647231 203.5643280161079 4.4043342633905 186.3420893783532 8.5832598151986 175.0716537992344C18.8406225332731 147.4021012819594 34.1633495565696 121.7586944867731 57.0208059838837 94.2790931309439C84.753675554974 61.1642739855553 118.1217629156237 35.0143307598469 156.238629312419 16.589068099602C170.8015516899322 9.6875092337371 190.2398872112214 1.5196092915666 211.9576366698607 0.1266341076306C213.2872947999814 0.0633170538153 214.6802699839175 1e-13 215.946611060223 1e-13C230.5728504915514 1e-13 242.8563589317147 5.2553154666679 252.4805511116364 15.7026293461882C252.5438681654517 15.8292634538187 252.6705022730822 15.892580507634 252.7338193268975 16.0192146152646C256.0263061252918 20.0081890056268 259.8253293542083 23.6172610730975 263.8143037445705 27.4796013558292C266.5369370586274 30.0756005622555 269.3228874264994 32.7982338763123 272.0455207405563 35.6475012979997C278.3139090682684 42.169157840973 281.6063958666627 49.7672042988059 281.6063958666627 57.5552019180847C281.6063958666627 65.4065165911787 278.2505920144532 72.9412459951964 271.8555695791104 79.2729513767239L237.0945070345247 114.1606480289401z" />
                </g>
              </svg>
            </div>
            <div className="card-content">
              <h3>Call Us On</h3>
              <p>
                <a href="tel:+918610486636">+91 861 048 6636</a>
              </p>
            </div>
          </div>

          {/* Card 2: Email Us */}
          <div className="contact-card">
            <div className="card-icon-container">
              <svg className="contact-card-svg" viewBox="0 0 300 300" fill="currentColor">
                <g transform="translate(0, 300) scale(1, -1)">
                  <path d="M243.75 50H56.25A31.286875 31.286875 0 0 0 25 81.25V172.40625A31.1875 31.1875 0 0 0 41.6875 200.0625L135.44375 249.403125A31.245625 31.245625 0 0 0 164.5575 249.403125L258.3075 200.0625A31.18375 31.18375 0 0 0 275 172.40625V81.25A31.286875 31.286875 0 0 0 243.75 50zM150 240.496875A18.7875 18.7875 0 0 1 141.265625 238.3425000000001L47.515625 189.00125A18.724375 18.724375 0 0 1 37.5 172.40625V81.2500000000001A18.770625 18.770625 0 0 1 56.25 62.5000000000001H243.75A18.770625 18.770625 0 0 1 262.5 81.2500000000001V172.4062500000001A18.718125 18.718125 0 0 1 252.478125 189L158.734375 238.3425000000001A18.7875 18.7875 0 0 1 150 240.4968750000001zM150 111.279375A31.209375 31.209375 0 0 0 132.66625 116.546875L52.783125 169.8A6.25 6.25 0 0 0 59.716875 180.2L139.599375 126.946875A18.70375 18.70375 0 0 1 160.400625 126.946875L240.283125 180.2000000000001A6.25 6.25 0 1 0 247.216875 169.8000000000001L167.33375 116.55A31.195625 31.195625 0 0 0 150 111.279375z" />
                </g>
              </svg>
            </div>
            <div className="card-content">
              <h3>Email us</h3>
              <p>
                <a href="mailto:sales@climbsphere.ai">sales@climbsphere.ai</a>
              </p>
            </div>
          </div>

          {/* Card 3: Our Location */}
          <div className="contact-card">
            <div className="card-icon-container">
              <svg className="contact-card-svg" viewBox="0 0 300 300" fill="currentColor">
                <g transform="translate(0, 300) scale(1, -1)">
                  <path d="M239.7215619561757 262.8362555395616C215.7566713997488 286.8017320346329 183.8927419780117 300 150.0008789079666 300S84.24450047754 286.8017320346329 60.2796099211131 262.8362555395616C36.3141334260418 238.8701931058459 23.1158654606747 207.0068496227532 23.1158654606747 173.1155724913526C23.1158654606747 104.5537198314841 87.9429452010649 47.5272412641431 122.7705522862349 16.8908533024479C127.6104054890732 12.6334231121545 131.7899058396599 8.9566581184729 135.1197951558499 5.8464957939372C139.2916783040592 1.9494178699568 144.6465715753351 0.0005859386445 150.0002929693222 0.0005859386445C155.3551862405981 0.0005859386445 160.7089076345852 1.9494178699568 164.8813767214389 5.8464957939372C168.211266037629 8.9572440571174 172.3907663882156 12.6334231121546 177.2306195910539 16.8908533024479C212.058226676224 47.5278272027875 276.8853064166141 104.5537198314841 276.8853064166141 173.1155724913526C276.8853064166141 173.1155724913526 276.8853064166141 173.1155724913526 276.8853064166141 173.1155724913526zM165.6231750452638 30.0850196973041C160.677266947787 25.7344252625493 156.4063601686722 21.9768007359389 152.8848689157596 18.6879271248577C151.2670923185397 17.1779632382094 148.7334936201047 17.177377299565 147.1151310842404 18.6879271248577C143.5942257699722 21.9779726132277 139.322733052213 25.7350112011937 134.3768249547362 30.0856056359485C101.6345735050264 58.8874197019916 40.688165406573 112.5002197269917 40.688165406573 173.1149865527081C40.688165406573 233.3893230260216 89.7247846187199 282.4259422381685 149.9997070306778 282.4259422381685C210.2740435039912 282.4259422381685 259.3106627161381 233.3893230260216 259.3106627161381 173.1149865527081C259.3112486547826 112.5002197269917 198.3654264949736 58.8874197019916 165.6231750452638 30.0850196973041zM150.0008789079666 233.8252613774636C119.1682015003935 233.8252613774636 94.0841681331409 208.7418139488554 94.0841681331409 177.9091365412823S119.1682015003936 121.993011705101 150.0008789079666 121.993011705101S205.9170037441479 147.0764591337092 205.9170037441479 177.9091365412823S180.8335563155397 233.8252613774636 150.0008789079666 233.8252613774636zM150.0008789079666 139.5670694669326C128.8584548016695 139.5670694669326 111.657639956328 156.7678843122741 111.657639956328 177.9097224799267S128.8584548016695 216.2523754929209 150.0008789079666 216.2523754929209S188.3435319209608 199.0515606475794 188.3435319209608 177.9097224799267S171.1433030142637 139.5670694669326 150.0008789079666 139.5670694669326z" />
                </g>
              </svg>
            </div>
            <div className="card-content">
              <h3>Our Location</h3>
              <p>
                <a 
                  href="https://maps.google.com/?q=1E, 1st Floor, Eldorado Building, Nungambakkam, Chennai 600034" 
                  target="_blank" 
                  rel="noopener noreferrer"
                >
                  1E, 1st Floor, Eldorado Building, Nungambakkam, Chennai – 600034
                </a>
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Form & Image Section */}
      <section className="contact-form-section">
        <div className="container grid-two-columns">
          {/* Image Column */}
          <div className="contact-media">
            <img 
              src="/images/contact-suit.jpg" 
              alt="Contact ClimbSphere Team" 
              className="contact-main-image"
            />
          </div>

          {/* Form Column */}
          <div className="contact-form-container">
            <span className="form-subtitle">Send Us A Message</span>
            <h2 className="form-main-title">We would love to hear from you.</h2>
            
            {submitted && (
              <div className="submission-success-alert">
                Thank you! Your message has been sent successfully. We will get back to you shortly.
              </div>
            )}

            {error && (
              <div className="submission-error-alert">
                {error}
              </div>
            )}

            <form className="contact-actual-form" onSubmit={handleSubmit}>
              <div className="form-field-group">
                <label htmlFor="contact-name">Name <span className="required-star">*</span></label>
                <input 
                  type="text" 
                  id="contact-name" 
                  name="name"
                  value={formData.name}
                  onChange={handleChange}
                  placeholder="Your full name"
                  required 
                />
              </div>

              <div className="form-field-group">
                <label htmlFor="contact-phone">Phone <span className="required-star">*</span></label>
                <input 
                  type="tel" 
                  id="contact-phone" 
                  name="phone"
                  value={formData.phone}
                  onChange={handleChange}
                  placeholder="Your contact number"
                  required 
                />
              </div>

              <div className="form-field-group">
                <label htmlFor="contact-email">Email <span className="required-star">*</span></label>
                <input 
                  type="email" 
                  id="contact-email" 
                  name="email"
                  value={formData.email}
                  onChange={handleChange}
                  placeholder="Your email address"
                  required 
                />
              </div>

              <div className="form-field-group">
                <label htmlFor="contact-message">Message</label>
                <textarea 
                  id="contact-message" 
                  name="message"
                  value={formData.message}
                  onChange={handleChange}
                  rows="5" 
                  placeholder="Tell us about your requirements"
                ></textarea>
              </div>

              <button type="submit" className="contact-submit-btn" disabled={submitting}>
                {submitting ? 'Submitting...' : 'Submit'}
              </button>
            </form>
          </div>
        </div>
      </section>

      {/* Map Section */}
      <section className="contact-map-section">
        <div className="map-embed-wrapper">
          <iframe 
            src="https://maps.google.com/maps?q=1E%2C%201st%20Floor%2C%20Eldarado%20building%2C%20Nungambakkam%2C%20Chennai%20600034&t=m&z=14&output=embed&iwloc=near"
            title="ClimbSphere Office Location Map"
            aria-label="1E, 1st Floor, Eldarado building, Nungambakkam, Chennai 600034"
            loading="lazy"
            allowFullScreen
          ></iframe>
        </div>
      </section>
    </div>
  );
}
