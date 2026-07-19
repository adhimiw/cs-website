import { useState, useEffect } from 'react';

export default function ServiceCard({ service, index }) {
  const [imgUrl, setImgUrl] = useState(service.image);

  useEffect(() => {
    if (!service.image) {
      setImgUrl('/images/service-digital-transformation.webp');
      return;
    }

    const img = new Image();
    img.src = service.image;
    img.onerror = () => {
      if (service.image && !service.image.includes('/images/')) {
        const filename = service.image.substring(service.image.lastIndexOf('/') + 1);
        if (filename) {
          setImgUrl(`/images/${filename}`);
          return;
        }
      }
      setImgUrl('/images/service-digital-transformation.webp');
    };
    img.onload = () => {
      setImgUrl(service.image);
    };
  }, [service.image]);

  return (
    <article
      className="service-card"
      style={{ '--service-image': `url(${imgUrl})` }}
      data-aos="fade-up"
      data-aos-delay={100 + index * 100}
    >
      <div className="service-overlay">
        <div className="service-icon">{service.icon}</div>
        <h3>{service.title}</h3>
        <p className="service-card-desc">{service.description}</p>
      </div>
    </article>
  );
}
