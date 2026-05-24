import { useState, useEffect } from 'react';
import { useNavigate, useSearchParams } from 'react-router-dom';
import { getApiUrl } from '../context/CMSContext';
import './BlogEditorPage.css';

// Simple custom renderer to match the BlogReadingPage preview
function renderPreviewMarkdown(content) {
  if (!content) return <p className="preview-empty">Start typing to see live preview...</p>;

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

    if (trimmed.startsWith('### ')) {
      flushList(index);
      renderedElements.push(<h4 key={index} className="blog-body-h4">{trimmed.substring(4)}</h4>);
    } else if (trimmed.startsWith('## ')) {
      flushList(index);
      renderedElements.push(<h3 key={index} className="blog-body-h3">{trimmed.substring(3)}</h3>);
    } else if (trimmed.startsWith('# ')) {
      flushList(index);
      renderedElements.push(<h2 key={index} className="blog-body-h2">{trimmed.substring(2)}</h2>);
    } else if (trimmed.startsWith('- ') || trimmed.startsWith('* ')) {
      currentList.push(trimmed.substring(2));
    } else if (trimmed.length > 0) {
      flushList(index);
      renderedElements.push(<p key={index} className="blog-body-p">{trimmed}</p>);
    } else {
      flushList(index);
    }
  });

  flushList(lines.length);
  return renderedElements;
}

export default function BlogEditorPage() {
  const navigate = useNavigate();
  const [searchParams] = useSearchParams();
  const editSlug = searchParams.get('edit');

  // Blog Fields
  const [title, setTitle] = useState('');
  const [content, setContent] = useState('');
  const [image, setImage] = useState('/images/blog_post_01.png');
  const [author, setAuthor] = useState('Admin');
  const [publishedAt, setPublishedAt] = useState(new Date().toISOString().substring(0, 10));

  // SEO/AEO/GEO Metadata
  const [seoTitle, setSeoTitle] = useState('');
  const [seoDescription, setSeoDescription] = useState('');
  const [targetKeywords, setTargetKeywords] = useState('');
  const [aeoSummary, setAeoSummary] = useState('');
  const [faqs, setFaqs] = useState([]);

  // Editor states
  const [loading, setLoading] = useState(false);
  const [optimizing, setOptimizing] = useState(false);
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState(null);
  const [successMsg, setSuccessMsg] = useState(null);
  const [previewTab, setPreviewTab] = useState('edit-split'); // 'edit-split' or 'preview-only'

  // Real-time analysis metrics
  const [metrics, setMetrics] = useState({
    wordCount: 0,
    hasQuestionHeader: false,
    hasFactualBullets: false,
    hasStats: false,
  });

  useEffect(() => {
    if (!editSlug) return;
    async function fetchPost() {
      try {
        setLoading(true);
        const res = await fetch(getApiUrl(`/api/blogs/${editSlug}`));
        if (!res.ok) throw new Error('Failed to load blog post for editing');
        const data = await res.json();
        
        setTitle(data.title);
        setContent(data.content);
        setImage(data.image);
        setAuthor(data.author);
        if (data.published_at) {
          setPublishedAt(new Date(data.published_at).toISOString().substring(0, 10));
        }

        if (data.seo_meta) {
          setSeoTitle(data.seo_meta.seo_title || '');
          setSeoDescription(data.seo_meta.seo_description || '');
          setTargetKeywords(data.seo_meta.target_keywords ? data.seo_meta.target_keywords.join(', ') : '');
          setAeoSummary(data.seo_meta.aeo_summary || '');
          setFaqs(data.seo_meta.faqs || []);
        }
      } catch (err) {
        setError(err.message || 'Error fetching blog details');
      } finally {
        setLoading(false);
      }
    }
    fetchPost();
  }, [editSlug]);

  // Real-time content analyzer hook
  useEffect(() => {
    const words = content.trim() ? content.trim().split(/\s+/).length : 0;
    const hasQuestion = /\#+\s.*\?\s*$/m.test(content) || /\#+\s.*\?$/m.test(content);
    const hasBullets = content.includes('\n- ') || content.includes('\n* ');
    const hasNumbersOrStats = /\d+\%|\d+\s/g.test(content);

    setMetrics({
      wordCount: words,
      hasQuestionHeader: hasQuestion,
      hasFactualBullets: hasBullets,
      hasStats: hasNumbersOrStats,
    });
  }, [content]);

  // Quick insertion helpers for toolbar
  const insertText = (before, after = '') => {
    const textarea = document.getElementById('blog-editor-textarea');
    if (!textarea) return;

    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    const selected = text.substring(start, end);
    const replacement = before + selected + after;

    setContent(text.substring(0, start) + replacement + text.substring(end));
    
    // Reset focus and cursor position
    setTimeout(() => {
      textarea.focus();
      textarea.setSelectionRange(start + before.length, start + before.length + selected.length);
    }, 50);
  };

  const handleAiOptimize = async () => {
    if (!title || !content) {
      setError('Please add a Title and Content before optimizing.');
      return;
    }

    try {
      setOptimizing(true);
      setError(null);
      const res = await fetch(getApiUrl('/api/blogs/optimize'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ title, content })
      });

      if (!res.ok) throw new Error('AI optimization request failed');
      const data = await res.json();

      setSeoTitle(data.seo_title || '');
      setSeoDescription(data.seo_description || '');
      setTargetKeywords(data.target_keywords ? data.target_keywords.join(', ') : '');
      setAeoSummary(data.aeo_summary || '');
      setFaqs(data.faqs || []);

      setSuccessMsg('AI Optimization successful! Metrics have been updated.');
      setTimeout(() => setSuccessMsg(null), 4000);
    } catch (err) {
      setError(err.message || 'AI Optimization failed. Please try again.');
    } finally {
      setOptimizing(false);
    }
  };

  const handleSave = async (e) => {
    e.preventDefault();
    if (!title || !content) {
      setError('Title and Content are required.');
      return;
    }

    try {
      setSaving(true);
      setError(null);

      const parsedKeywords = targetKeywords
        ? targetKeywords.split(',').map(k => k.trim()).filter(k => k.length > 0)
        : [];

      const payload = {
        title,
        content,
        image,
        author,
        published_at: publishedAt,
        seo_meta: {
          seo_title: seoTitle,
          seo_description: seoDescription,
          target_keywords: parsedKeywords,
          aeo_summary: aeoSummary,
          faqs: faqs
        }
      };

      const url = editSlug ? getApiUrl(`/api/blogs/${editSlug}`) : getApiUrl('/api/blogs');
      const method = editSlug ? 'PUT' : 'POST';

      const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });

      if (!res.ok) throw new Error('Failed to save blog post.');

      setSuccessMsg('Blog post saved successfully!');
      setTimeout(() => {
        setSuccessMsg(null);
        navigate('/blog');
      }, 1500);

    } catch (err) {
      setError(err.message || 'Error occurred while saving.');
    } finally {
      setSaving(false);
    }
  };

  if (loading) {
    return (
      <div className="blog-editor-loading">
        <div className="spinner"></div>
        <p>Loading editor...</p>
      </div>
    );
  }

  return (
    <div className="blog-editor-page">
      <header className="editor-top-header">
        <div className="container editor-header-inner">
          <div className="header-left">
            <button onClick={() => navigate('/blog')} className="btn-back-editor">
              ← Exit Editor
            </button>
            <h2>{editSlug ? 'Edit Blog Post' : 'Write New Blog Post'}</h2>
          </div>
          <div className="header-actions">
            <button 
              onClick={handleAiOptimize} 
              disabled={optimizing || saving} 
              className={`btn-editor-action btn-ai ${optimizing ? 'loading' : ''}`}
            >
              {optimizing ? 'Optimizing...' : '⚡ AI Optimize (AEO/GEO)'}
            </button>
            <button 
              onClick={handleSave} 
              disabled={saving || optimizing} 
              className="btn-editor-action btn-save"
            >
              {saving ? 'Saving...' : 'Publish Post'}
            </button>
          </div>
        </div>
      </header>

      <main className="editor-main-container">
        {error && <div className="editor-alert error">{error}</div>}
        {successMsg && <div className="editor-alert success">{successMsg}</div>}

        <div className="editor-meta-inputs container">
          <div className="meta-grid">
            <div className="input-group">
              <label>Title</label>
              <input 
                type="text" 
                value={title} 
                onChange={(e) => setTitle(e.target.value)} 
                placeholder="e.g. Workday's Last Workday?..." 
                required 
              />
            </div>
            <div className="input-group">
              <label>Cover Image URL</label>
              <input 
                type="text" 
                value={image} 
                onChange={(e) => setImage(e.target.value)} 
                placeholder="/images/blog_post_01.png" 
              />
            </div>
            <div className="input-group">
              <label>Author</label>
              <input 
                type="text" 
                value={author} 
                onChange={(e) => setAuthor(e.target.value)} 
                placeholder="Admin" 
              />
            </div>
            <div className="input-group">
              <label>Publish Date</label>
              <input 
                type="date" 
                value={publishedAt} 
                onChange={(e) => setPublishedAt(e.target.value)} 
              />
            </div>
          </div>
        </div>

        <div className="editor-workspace container">
          <div className="workspace-main">
            
            {/* Editor formatting toolbar */}
            <div className="editor-toolbar">
              <button type="button" onClick={() => insertText('## ', '\n')} title="Heading 2">H2</button>
              <button type="button" onClick={() => insertText('### ', '\n')} title="Heading 3">H3</button>
              <button type="button" onClick={() => insertText('**', '**')} title="Bold">B</button>
              <button type="button" onClick={() => insertText('- ', '\n')} title="Bullet List">List</button>
              <button type="button" onClick={() => insertText('> ', '\n')} title="Blockquote">Quote</button>
              <button type="button" onClick={() => insertText('### 99% \n Factual description', '\n')} title="Stat Block">Stat</button>
              <button type="button" onClick={() => insertText('[Link text](', ')')} title="Insert Link">Link</button>
              
              <div className="view-selector">
                <button 
                  onClick={() => setPreviewTab('edit-split')}
                  className={previewTab === 'edit-split' ? 'active' : ''}
                >
                  Split View
                </button>
                <button 
                  onClick={() => setPreviewTab('preview-only')}
                  className={previewTab === 'preview-only' ? 'active' : ''}
                >
                  Preview Only
                </button>
              </div>
            </div>

            {/* Split Writing Area */}
            <div className={`workspace-panes ${previewTab}`}>
              {previewTab !== 'preview-only' && (
                <div className="pane-write">
                  <textarea
                    id="blog-editor-textarea"
                    value={content}
                    onChange={(e) => setContent(e.target.value)}
                    placeholder="Write your article in Markdown..."
                    spellCheck="true"
                  />
                </div>
              )}

              <div className="pane-preview">
                <h2 className="preview-indicator">LIVE PREVIEW</h2>
                <div className="blog-read-main preview-frame">
                  {aeoSummary && (
                    <div className="aeo-answer-callout">
                      <div className="callout-header">
                        <span className="callout-badge">AEO Quick Answer</span>
                      </div>
                      <p className="callout-text">{aeoSummary}</p>
                    </div>
                  )}
                  <div className="blog-post-body">
                    {content && /<[a-z/][^>]*>/i.test(content) ? (
                      <div dangerouslySetInnerHTML={{ __html: content }} />
                    ) : (
                      renderPreviewMarkdown(content)
                    )}
                  </div>
                  {faqs && faqs.length > 0 && (
                    <div className="blog-faq-section">
                      <h3 className="faq-heading">Frequently Asked Questions</h3>
                      <div className="faq-wrapper">
                        {faqs.map((faq, i) => (
                          <div key={i} className="faq-card">
                            <button className="faq-question-btn">
                              <span>{faq.question}</span>
                            </button>
                            <div className="faq-answer-pane" style={{ maxHeight: '100px' }}>
                              <p style={{ padding: '0 24px 20px 24px' }}>{faq.answer}</p>
                            </div>
                          </div>
                        ))}
                      </div>
                    </div>
                  )}
                </div>
              </div>
            </div>

          </div>

          {/* Real-time SEO/AEO/GEO Optimization sidebar */}
          <aside className="editor-sidebar-panel">
            
            {/* Real-time checks */}
            <div className="sidebar-section audit-section">
              <h3>SEO/AEO/GEO Audits</h3>
              <div className="audit-list">
                
                <div className={`audit-item ${metrics.wordCount >= 300 ? 'passed' : 'failed'}`}>
                  <span className="status-icon"></span>
                  <div className="audit-info">
                    <strong>Length Check:</strong>
                    <span>{metrics.wordCount} words (Target: &gt;300 words)</span>
                  </div>
                </div>

                <div className={`audit-item ${metrics.hasQuestionHeader ? 'passed' : 'failed'}`}>
                  <span className="status-icon"></span>
                  <div className="audit-info">
                    <strong>AEO Question Header:</strong>
                    <span>Contains H2/H3 ending with "?"</span>
                  </div>
                </div>

                <div className={`audit-item ${aeoSummary ? 'passed' : 'failed'}`}>
                  <span className="status-icon"></span>
                  <div className="audit-info">
                    <strong>AEO Quick Answer:</strong>
                    <span>Direct answer summary populated</span>
                  </div>
                </div>

                <div className={`audit-item ${metrics.hasFactualBullets ? 'passed' : 'failed'}`}>
                  <span className="status-icon"></span>
                  <div className="audit-info">
                    <strong>GEO Factual Structure:</strong>
                    <span>Contains lists for easy AI synthesis</span>
                  </div>
                </div>

                <div className={`audit-item ${metrics.hasStats ? 'passed' : 'failed'}`}>
                  <span className="status-icon"></span>
                  <div className="audit-info">
                    <strong>GEO Authority stats:</strong>
                    <span>Contains numbers or percentages</span>
                  </div>
                </div>

              </div>
            </div>

            {/* AI Optimization Values */}
            <div className="sidebar-section ai-values-section">
              <h3>AI Optimizer Settings</h3>
              
              <div className="field-group">
                <label>SEO Meta Title</label>
                <input 
                  type="text" 
                  value={seoTitle} 
                  onChange={(e) => setSeoTitle(e.target.value)} 
                  placeholder="Generated title..." 
                />
              </div>

              <div className="field-group">
                <label>SEO Meta Description</label>
                <textarea 
                  value={seoDescription} 
                  onChange={(e) => setSeoDescription(e.target.value)} 
                  placeholder="Generated description..." 
                  rows="3"
                />
              </div>

              <div className="field-group">
                <label>Target Keywords (comma-separated)</label>
                <input 
                  type="text" 
                  value={targetKeywords} 
                  onChange={(e) => setTargetKeywords(e.target.value)} 
                  placeholder="e.g. Workday, HR, AI..." 
                />
              </div>

              <div className="field-group">
                <label>AEO Summary (Direct Answer)</label>
                <textarea 
                  value={aeoSummary} 
                  onChange={(e) => setAeoSummary(e.target.value)} 
                  placeholder="Direct answer summary..." 
                  rows="4"
                />
              </div>

              {faqs && faqs.length > 0 && (
                <div className="faq-review-list">
                  <label>AEO FAQ Schema ({faqs.length})</label>
                  {faqs.map((faq, i) => (
                    <div key={i} className="faq-review-item">
                      <strong>Q: {faq.question}</strong>
                      <p>A: {faq.answer}</p>
                    </div>
                  ))}
                </div>
              )}
              
            </div>

          </aside>
        </div>
      </main>
    </div>
  );
}
