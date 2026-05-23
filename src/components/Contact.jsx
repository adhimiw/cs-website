import { useState } from 'react';
import { getApiUrl } from '../context/CMSContext';
import './Contact.css';

export default function Contact() {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: ''
  });
  const [submitted, setSubmitted] = useState(false);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState(null);

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
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
          subject: 'Request a Callback Form',
          message: 'Requested a callback via the home page callback form.'
        })
      });

      const resData = await response.json();
      if (!response.ok) {
        throw new Error(resData.message || 'Failed to request callback.');
      }

      setSubmitted(true);
      setFormData({ name: '', email: '', phone: '' });
      setTimeout(() => {
        setSubmitted(false);
      }, 5000);
    } catch (err) {
      console.error('Callback form error:', err);
      setError(err.message || 'An error occurred. Please try again.');
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <section className="callback" id="contact">
      <div className="container callback-grid">
        <div className="callback-title-wrap" data-aos="fade-right" data-aos-delay="200">
          <img src="/images/h2_request_shape01.png" alt="" className="callback-shape" />
          <h2 className="callback-title">Request a Call Back</h2>
        </div>
        <div style={{ display: 'flex', flexDirection: 'column', gap: '15px', width: '100%' }}>
          {submitted && (
            <div style={{ background: '#d1e7dd', color: '#0f5132', padding: '12px 18px', borderRadius: '5px', fontSize: '14px', fontWeight: 'bold', border: '1px solid #badbcc' }}>
              Callback request sent successfully!
            </div>
          )}
          {error && (
            <div style={{ background: '#f8d7da', color: '#842029', padding: '12px 18px', borderRadius: '5px', fontSize: '14px', fontWeight: 'bold', border: '1px solid #f5c2c7' }}>
              {error}
            </div>
          )}
          <form 
            className="callback-form" 
            onSubmit={handleSubmit}
            data-aos="fade-left"
            data-aos-delay="400"
          >
            <input 
              type="text" 
              name="name"
              placeholder="Name (e.g. Jane Doe) *" 
              value={formData.name}
              onChange={handleInputChange}
              autoComplete="name"
              required 
            />
            <input 
              type="email" 
              name="email"
              placeholder="E-mail (e.g. jane@example.com) *" 
              value={formData.email}
              onChange={handleInputChange}
              autoComplete="email"
              spellCheck={false}
              required 
            />
            <input 
              type="tel" 
              name="phone"
              placeholder="Phone (e.g. +1 555 000 0000) *" 
              value={formData.phone}
              onChange={handleInputChange}
              autoComplete="tel"
              required 
            />
            <button type="submit" disabled={submitting}>
              {submitting ? 'SENDING…' : 'SEND NOW'}
            </button>
          </form>
        </div>
      </div>
    </section>
  );
}

