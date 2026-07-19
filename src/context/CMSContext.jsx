import React, { createContext, useContext, useState, useEffect } from 'react';

const CMSContext = createContext(null);

export const getApiUrl = (path) => {
  const isLocal = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
  const configuredOrigin = import.meta.env.VITE_API_BASE_URL?.replace(/\/$/, '');
  const localOrigin = `${window.location.protocol}//${window.location.hostname}:8000`;
  const origin = configuredOrigin || (isLocal ? localOrigin : '');
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

// Parse inline formatting like **bold**
export function parseInlineFormatting(text) {
  if (typeof text !== 'string') return text;

  const parts = [];
  // Regex for bold: **text**
  const boldRegex = /\*\*([^*]+)\*\*/g;
  let match;
  let lastIndex = 0;

  while ((match = boldRegex.exec(text)) !== null) {
    if (match.index > lastIndex) {
      parts.push(text.substring(lastIndex, match.index));
    }
    parts.push(<strong key={match.index}>{match[1]}</strong>);
    lastIndex = boldRegex.lastIndex;
  }

  if (lastIndex < text.length) {
    parts.push(text.substring(lastIndex));
  }

  return parts.length > 0 ? parts : text;
}

// Simple custom renderer to handle basic Markdown headings, lists, bold text, and paragraphs
export function renderMarkdownContent(content, fallback = null) {
  if (!content) return fallback;

  const lines = content.split('\n');
  let currentList = [];
  const renderedElements = [];

  const flushList = (key) => {
    if (currentList.length > 0) {
      renderedElements.push(
        <ul key={`list-${key}`} className="blog-body-list">
          {currentList.map((li, idx) => <li key={`li-${idx}`}>{li}</li>)}
        </ul>
      );
      currentList = [];
    }
  };

  lines.forEach((line, index) => {
    const trimmed = line.trim();

    // Headers
    if (trimmed.startsWith('### ')) {
      flushList(index);
      renderedElements.push(
        <h4 key={index} className="blog-body-h4">
          {parseInlineFormatting(trimmed.substring(4))}
        </h4>
      );
    } else if (trimmed.startsWith('## ')) {
      flushList(index);
      renderedElements.push(
        <h3 key={index} className="blog-body-h3">
          {parseInlineFormatting(trimmed.substring(3))}
        </h3>
      );
    } else if (trimmed.startsWith('# ')) {
      flushList(index);
      renderedElements.push(
        <h2 key={index} className="blog-body-h2">
          {parseInlineFormatting(trimmed.substring(2))}
        </h2>
      );
    } 
    // Bullet Lists
    else if (trimmed.startsWith('- ') || trimmed.startsWith('* ')) {
      currentList.push(parseInlineFormatting(trimmed.substring(2)));
    } 
    // Ordered Lists
    else if (/^\d+\.\s/.test(trimmed)) {
      currentList.push(parseInlineFormatting(trimmed.replace(/^\d+\.\s/, '')));
    }
    // Paragraphs
    else if (trimmed.length > 0) {
      flushList(index);
      renderedElements.push(
        <p key={index} className="blog-body-p">
          {parseInlineFormatting(trimmed)}
        </p>
      );
    } else {
      flushList(index);
    }
  });

  flushList(lines.length);

  return renderedElements;
}
