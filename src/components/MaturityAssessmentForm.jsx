import { useState } from 'react';
import { getApiUrl } from '../context/CMSContext';

export default function MaturityAssessmentForm() {
  const [formData, setFormData] = useState({
    name: '',
    phone: '',
    email: '',
    message: ''
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
          subject: 'Digital Maturity Assessment Form',
          message: formData.message || 'No additional details provided.'
        })
      });

      const resData = await response.json();
      if (!response.ok) {
        throw new Error(resData.message || 'Failed to submit digital maturity assessment form.');
      }

      setSubmitted(true);
      setFormData({ name: '', phone: '', email: '', message: '' });
      setTimeout(() => {
        setSubmitted(false);
      }, 5000);
    } catch (err) {
      console.error('Digital Maturity Assessment form error:', err);
      setError(err.message || 'An error occurred. Please try again.');
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <form className="overview-form" onSubmit={handleSubmit}>
      {submitted && (
        <div className="submission-success-alert" style={{ background: '#d1e7dd', color: '#0f5132', padding: '15px 20px', borderRadius: 'var(--radius-md)', marginBottom: '20px', fontSize: '15px', fontWeight: '600', border: '1px solid #badbcc', width: '100%' }}>
          Thank you! Your request has been sent successfully. We will get back to you shortly.
        </div>
      )}
      {error && (
        <div className="submission-error-alert" style={{ background: '#f8d7da', color: '#842029', padding: '15px 20px', borderRadius: 'var(--radius-md)', marginBottom: '20px', fontSize: '15px', fontWeight: '600', border: '1px solid #f5c2c7', width: '100%' }}>
          {error}
        </div>
      )}
      <div className="overview-form-group">
        <label htmlFor="maturity-name">
          Name <span className="required">*</span>
        </label>
        <input 
          id="maturity-name"
          type="text" 
          name="name" 
          value={formData.name} 
          onChange={handleInputChange} 
          placeholder="e.g. Jane Doe"
          autoComplete="name"
          required 
        />
      </div>
      <div className="overview-form-group">
        <label htmlFor="maturity-phone">
          Phone <span className="required">*</span>
        </label>
        <input 
          id="maturity-phone"
          type="tel" 
          name="phone" 
          value={formData.phone} 
          onChange={handleInputChange} 
          placeholder="e.g. +1 (555) 000-0000"
          autoComplete="tel"
          required 
        />
      </div>
      <div className="overview-form-group">
        <label htmlFor="maturity-email">
          Email <span className="required">*</span>
        </label>
        <input 
          id="maturity-email"
          type="email" 
          name="email" 
          value={formData.email} 
          onChange={handleInputChange} 
          placeholder="e.g. jane@example.com"
          autoComplete="email"
          spellCheck={false}
          required 
        />
      </div>
      <div className="overview-form-group">
        <label htmlFor="maturity-message">Message</label>
        <textarea 
          id="maturity-message"
          name="message" 
          value={formData.message} 
          onChange={handleInputChange} 
          placeholder="e.g. Tell us about your digital goals…"
          rows="3" 
        />
      </div>
      <button type="submit" className="overview-form-submit" disabled={submitting}>
        {submitting ? 'Submitting…' : 'Submit'}
      </button>
    </form>
  );
}
