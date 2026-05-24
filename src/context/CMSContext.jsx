import React, { createContext, useContext, useState, useEffect } from 'react';

const CMSContext = createContext(null);

export const getApiUrl = (path) => {
  const isLocal = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
  const origin = isLocal ? 'http://localhost:8000' : '';
  return `${origin}${path}`;
};

export const getBlogImageUrl = (imagePath) => {
  if (!imagePath) return '/images/blog_post_01.png';
  if (imagePath.startsWith('http') || imagePath.startsWith('/images/')) {
    return imagePath;
  }
  if (
    imagePath.startsWith('blogs/') ||
    imagePath.startsWith('testimonials/') ||
    imagePath.startsWith('services/') ||
    imagePath.startsWith('site_contents/')
  ) {
    return getApiUrl('/storage/' + imagePath);
  }
  if (imagePath.includes('/')) {
    return getApiUrl('/storage/' + imagePath);
  }
  return getApiUrl('/storage/blogs/' + imagePath);
};

export const CMSProvider = ({ children }) => {
  const [data, setData] = useState({ contents: {}, settings: {}, services: [], blogs: [], testimonials: [] });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch(getApiUrl('/api/contents'))
      .then((res) => {
        if (!res.ok) throw new Error('Failed to fetch CMS content');
        return res.json();
      })
      .then((resData) => {
        setData({
          contents: resData.contents || {},
          settings: resData.settings || {},
          services: resData.services || [],
          blogs: resData.blogs || [],
          testimonials: resData.testimonials || [],
        });
        setLoading(false);
      })
      .catch((err) => {
        console.warn('CMS load failed, falling back to static content:', err);
        setLoading(false);
      });
  }, []);

  const getCMSContent = (key, defaultValue) => {
    return data.contents[key] !== undefined ? data.contents[key] : defaultValue;
  };

  const getSetting = (key, defaultValue) => {
    // Both prefix settings.key and key formats can be matched
    const fullKey = key.startsWith('settings.') ? key : `settings.${key}`;
    return data.settings[fullKey] !== undefined ? data.settings[fullKey] : defaultValue;
  };

  return (
    <CMSContext.Provider value={{ getCMSContent, getSetting, services: data.services, blogs: data.blogs, testimonials: data.testimonials, loading }}>
      {children}
    </CMSContext.Provider>
  );
};

export const useCMS = () => {
  const context = useContext(CMSContext);
  if (!context) {
    throw new Error('useCMS must be used within a CMSProvider');
  }
  return context;
};
