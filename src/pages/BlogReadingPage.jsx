import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { getApiUrl, getBlogImageUrl } from '../context/CMSContext';
import './BlogReadingPage.css';

function CalendarIcon() {
  return (
    <svg viewBox="0 0 24 24" aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" strokeWidth="2">
      <rect x="3" y="4" width="18" height="16" rx="2" ry="2" />
      <line x1="16" y1="2" x2="16" y2="6" />
      <line x1="8" y1="2" x2="8" y2="6" />
      <line x1="3" y1="10" x2="21" y2="10" />
    </svg>
  );
}

function UserIcon() {
  return (
    <svg viewBox="0 0 24 24" aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" strokeWidth="2">
      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
      <circle cx="12" cy="7" r="4" />
    </svg>
  );
}

function ArrowLeftIcon() {
  return (
    <svg viewBox="0 0 24 24" aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" strokeWidth="2">
      <line x1="19" y1="12" x2="5" y2="12" />
      <polyline points="12 19 5 12 12 5" />
    </svg>
  );
}

// Simple custom renderer to handle basic Markdown headings, lists, bold text, and paragraphs
function renderMarkdownContent(content) {
  if (!content) return null;

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
      // For simplicity, render as standard bullet lists for now
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

// Parse inline formatting like **bold** and [link](url)
function parseInlineFormatting(text) {
  const parts = [];
  let index = 0;

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

export default function BlogReadingPage() {
  const { slug } = useParams();
  const [post, setPost] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [activeFaq, setActiveFaq] = useState(-1);

  // Fallback defaults in case backend is offline
  const fallbackPosts = {
    'workday-ai-disruption': {
      title: "Workday's Last Workday? The Goliath Has Heard This Before",
      content: "Is this the end of traditional HCM Goliaths? A practitioner's take on the A16z thesis, the CHRO's crossroads, and what is next.\n\n### What is the Workday AI Disruption?\nWorkday is facing new competition from AI-first employee workflows. However, Workday's deep enterprise integrations make it a formidable player.\n\n### How does this impact HR teams?\nHR technology is shifting from system-of-record to system-of-intelligence. The future CHRO must adopt tools that integrate workflows and provide direct assistance rather than just data storage.\n\n### What is ClimbSphere's View?\nWe suggest that organizations focus on implementing automation layers on top of their existing Workday core. A well-designed middle layer delivers direct employee value without expensive migrations.",
      image: '/images/blog_post_01.png',
      author: 'Admin',
      published_at: new Date('2026-05-08'),
      seo_meta: {
        seo_title: "Workday AI Disruption: A Practitioner's Perspective",
        seo_description: "Explore the implications of AI on legacy HCM tools like Workday and how HR tech is transitioning from records to intelligence.",
        target_keywords: ['Workday', 'HCM', 'HR Tech', 'AI Disruption', 'CHRO'],
        aeo_summary: "What is the Workday AI Disruption? The Workday AI disruption represents a transition from legacy databases to AI-native workflows that automate employee tasks. While new startups threaten the Core HCM model, enterprise dominance requires implementing overlay systems of intelligence.",
        faqs: [
          {
            question: 'Will AI replace legacy HCM tools like Workday?',
            answer: 'AI is unlikely to completely replace legacy tools immediately due to enterprise contracts and records systems, but it will shift day-to-day employee interaction to overlay intelligence layers.'
          },
          {
            question: 'How can enterprise HR prepare for AI disruption?',
            answer: 'HR teams should focus on implementing integrated workflows and AI middleware that sit on top of legacy cores to provide immediate utility.'
          }
        ]
      }
    },
    'post-demo-paradox-hr-tech': {
      title: 'Why Great Software Often Fails Great People: Lessons From The Post-Demo Paradox',
      content: "It is entirely possible to fall in love with an HCM product demo, only to realize the real-world implementation fails to deliver employee satisfaction.\n\n### What is the Post-Demo Paradox?\nThe post-demo paradox is the gap between expectations set by perfect software demonstrations and the complex reality of custom implementations. Many products look fantastic in mock environments but break down when integrated with real enterprise directory rules and business processes.\n\n### How to Mitigate Implementation Failures?\nTo prevent software implementation failure:\n1. Prioritize design-led discovery before picking the system.\n2. Involve real end-users in testing phases, not just executives.\n3. Focus heavily on clean data migration and adoption planning.",
      image: '/images/blog_post_02.jpg',
      author: 'Admin',
      published_at: new Date('2026-05-08'),
      seo_meta: {
        seo_title: 'The Post-Demo Paradox in HR Tech Implementations',
        seo_description: 'Why perfect software demos lead to failed enterprise implementations, and how design-led discovery resolves this paradox.',
        target_keywords: ['Software Demo', 'HR Tech', 'Software Implementation', 'User Experience'],
        aeo_summary: "What is the Post-Demo Paradox? The Post-Demo Paradox is the failure of software implementations to live up to perfect sales demos. It occurs because real environments contain integration complexities and user adoptability constraints omitted in sales sandbox settings.",
        faqs: [
          {
            question: 'Why do software implementations fail after good demos?',
            answer: 'Implementations fail because demos skip real-world database constraints, custom business logic, and end-user adoption bottlenecks.'
          }
        ]
      }
    }
  };

  useEffect(() => {
    async function getPost() {
      try {
        setLoading(true);
        const res = await fetch(getApiUrl(`/api/blogs/${slug}`));
        if (!res.ok) throw new Error('Blog post not found');
        const data = await res.json();
        setPost(data);
      } catch (err) {
        console.error('Error fetching blog from API, trying fallback:', err);
        if (fallbackPosts[slug]) {
          setPost(fallbackPosts[slug]);
        } else {
          setError('Blog post could not be retrieved.');
        }
      } finally {
        setLoading(false);
      }
    }
    getPost();
  }, [slug]);

  // Handle SEO Meta adjustments & AEO FAQ Schema injection
  useEffect(() => {
    if (!post) return;

    // 1. Dynamic SEO Tags
    const originalTitle = document.title;
    document.title = (post.seo_meta?.seo_title || post.title) + ' | ClimbSphere';

    let metaDesc = document.querySelector('meta[name="description"]');
    const originalDesc = metaDesc ? metaDesc.getAttribute('content') : '';
    if (post.seo_meta?.seo_description) {
      if (!metaDesc) {
        metaDesc = document.createElement('meta');
        metaDesc.setAttribute('name', 'description');
        document.head.appendChild(metaDesc);
      }
      metaDesc.setAttribute('content', post.seo_meta.seo_description);
    }

    // 2. Dynamic JSON-LD AEO Schema Injection
    let scriptTag = document.getElementById('aeo-faq-schema');
    if (post.seo_meta?.faqs && post.seo_meta.faqs.length > 0) {
      const schemaData = {
        '@context': 'https://schema.org',
        '@type': 'FAQPage',
        'mainEntity': post.seo_meta.faqs.map(faq => ({
          '@type': 'Question',
          'name': faq.question,
          'acceptedAnswer': {
            '@type': 'Answer',
            'text': faq.answer
          }
        }))
      };

      if (!scriptTag) {
        scriptTag = document.createElement('script');
        scriptTag.id = 'aeo-faq-schema';
        scriptTag.type = 'application/ld+json';
        document.head.appendChild(scriptTag);
      }
      scriptTag.text = JSON.stringify(schemaData);
    }

    // Cleanup on unmount
    return () => {
      document.title = originalTitle;
      if (metaDesc && originalDesc) {
        metaDesc.setAttribute('content', originalDesc);
      }
      if (scriptTag) {
        scriptTag.remove();
      }
    };
  }, [post]);

  if (loading) {
    return (
      <div className="blog-read-status container">
        <div className="spinner"></div>
        <p>Loading article...</p>
      </div>
    );
  }

  if (error || !post) {
    return (
      <div className="blog-read-status container error-box">
        <h2>Article Not Found</h2>
        <p>{error || 'The blog post you are looking for does not exist.'}</p>
        <Link to="/blog" className="btn-back">
          <ArrowLeftIcon /> Back to Blogs
        </Link>
      </div>
    );
  }

  const publishedDate = post.published_at ? new Date(post.published_at) : null;
  const formattedDate = publishedDate ? publishedDate.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }) : 'May 8, 2026';

  return (
    <article className="blog-read-page">
      <header className="blog-read-header">
        <div className="container">
          <Link to="/blog" className="blog-back-link">
            <ArrowLeftIcon /> Back to Blogs
          </Link>
          
          <h1 className="blog-read-title">{post.title}</h1>
          
          <div className="blog-read-meta">
            <span className="meta-item">
              <UserIcon /> By {post.author || 'Admin'}
            </span>
            <span className="meta-item">
              <CalendarIcon /> {formattedDate}
            </span>
            <span className="meta-tag-badge">Blog</span>
          </div>
        </div>
      </header>

      <div className="blog-read-banner container">
        <img src={getBlogImageUrl(post.image)} alt={post.title} className="blog-read-cover" />
      </div>

      <div className="blog-read-content-container container">
        <div className="blog-read-layout">
          
          {/* Main Article Body */}
          <section className="blog-read-main">
            {/* AEO Featured Snippet Callout (Direct Answer Box) */}
            {post.seo_meta?.aeo_summary && (
              <div className="aeo-answer-callout">
                <div className="callout-header">
                  <span className="callout-badge">AEO Quick Answer</span>
                  <span className="callout-info">Direct facts for voice search and AI</span>
                </div>
                <p className="callout-text">{post.seo_meta.aeo_summary}</p>
              </div>
            )}

            {/* Factual Content Body */}
            <div className="blog-post-body">
              {post.content && /<[a-z/][^>]*>/i.test(post.content) ? (
                <div dangerouslySetInnerHTML={{ __html: post.content }} />
              ) : (
                renderMarkdownContent(post.content)
              )}
            </div>

            {/* Dynamic AEO / GEO FAQ Accordion Section */}
            {post.seo_meta?.faqs && post.seo_meta.faqs.length > 0 && (
              <section className="blog-faq-section">
                <h3 className="faq-heading">Frequently Asked Questions</h3>
                <div className="faq-wrapper">
                  {post.seo_meta.faqs.map((faq, idx) => {
                    const isOpen = activeFaq === idx;
                    return (
                      <div key={idx} className={`faq-card ${isOpen ? 'active' : ''}`}>
                        <button 
                          className="faq-question-btn"
                          onClick={() => setActiveFaq(isOpen ? -1 : idx)}
                          aria-expanded={isOpen}
                        >
                          <span>{faq.question}</span>
                          <span className="faq-chevron"></span>
                        </button>
                        <div className="faq-answer-pane">
                          <p>{faq.answer}</p>
                        </div>
                      </div>
                    );
                  })}
                </div>
              </section>
            )}

            {/* Author details card */}
            <div className="blog-author-card">
              <div className="author-avatar" aria-hidden="true" />
              <div className="author-details">
                <h4>Published by {post.author || 'Admin'}</h4>
                <p>Strategic technology solutions and design leadership advising enterprises on system integrations, cloud infrastructure, and AI automation roadmaps.</p>
              </div>
            </div>
          </section>

          {/* Sidebar / Optimization Summary (GEO and Citations) */}
          <aside className="blog-read-sidebar">
            <div className="sidebar-widget keywords-widget">
              <h4>Topics Covered</h4>
              <div className="keywords-list">
                {post.seo_meta?.target_keywords && post.seo_meta.target_keywords.length > 0 ? (
                  post.seo_meta.target_keywords.map((kw, i) => (
                    <span key={i} className="keyword-tag">{kw}</span>
                  ))
                ) : (
                  <>
                    <span className="keyword-tag">Technology</span>
                    <span className="keyword-tag">HR Systems</span>
                    <span className="keyword-tag">Adoption</span>
                  </>
                )}
              </div>
            </div>

            <div className="sidebar-widget share-widget">
              <h4>Share Article</h4>
              <div className="share-buttons">
                <button 
                  onClick={() => {
                    navigator.clipboard.writeText(window.location.href);
                    alert('Link copied to clipboard!');
                  }}
                  className="share-btn copy-btn"
                >
                  Copy Link
                </button>
                <a 
                  href={`https://twitter.com/intent/tweet?url=${encodeURIComponent(window.location.href)}&text=${encodeURIComponent(post.title)}`}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="share-btn"
                >
                  Share on X
                </a>
              </div>
            </div>
          </aside>

        </div>
      </div>
    </article>
  );
}
