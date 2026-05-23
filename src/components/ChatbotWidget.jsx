import React, { useState, useEffect, useRef } from 'react';
import { getApiUrl } from '../context/CMSContext';
import './ChatbotWidget.css';

const DEFAULT_WELCOME = "Hello! Welcome to ClimbSphere. I'm your virtual assistant. How can I help you today? Whether you're looking for web development, custom software, or cloud migrations, I can answer your questions and help you get started!";

export default function ChatbotWidget() {
  const [isOpen, setIsOpen] = useState(false);
  const [messages, setMessages] = useState(() => {
    try {
      const saved = sessionStorage.getItem('climbsphere_chat_messages');
      return saved ? JSON.parse(saved) : [{ role: 'assistant', content: DEFAULT_WELCOME }];
    } catch {
      return [{ role: 'assistant', content: DEFAULT_WELCOME }];
    }
  });
  const [input, setInput] = useState('');
  const [isTyping, setIsTyping] = useState(false);
  const [sessionUuid, setSessionUuid] = useState(() => {
    return sessionStorage.getItem('climbsphere_chat_session_uuid') || null;
  });
  const [hasNewMessage, setHasNewMessage] = useState(false);

  const messagesEndRef = useRef(null);

  useEffect(() => {
    // Scroll to bottom on updates
    if (messagesEndRef.current) {
      messagesEndRef.current.scrollIntoView({ behavior: 'smooth' });
    }
  }, [messages, isTyping]);

  useEffect(() => {
    // Save messages to sessionStorage
    sessionStorage.setItem('climbsphere_chat_messages', JSON.stringify(messages));
  }, [messages]);

  const toggleChat = () => {
    setIsOpen(!isOpen);
    setHasNewMessage(false);
  };

  const handleSend = async (e) => {
    e.preventDefault();
    if (!input.trim() || isTyping) return;

    const userMessage = input.trim();
    const updatedMessages = [...messages, { role: 'user', content: userMessage }];
    setMessages(updatedMessages);
    setInput('');
    setIsTyping(true);

    try {
      const response = await fetch(getApiUrl('/api/chat'), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          message: userMessage,
          session_uuid: sessionUuid,
        }),
      });

      if (!response.ok) {
        throw new Error('Network response was not ok');
      }

      const data = await response.json();
      
      if (data.session_uuid) {
        setSessionUuid(data.session_uuid);
        sessionStorage.setItem('climbsphere_chat_session_uuid', data.session_uuid);
      }

      setMessages((prev) => [
        ...prev,
        { role: 'assistant', content: data.reply || "Sorry, I didn't get that." },
      ]);
      
      if (!isOpen) {
        setHasNewMessage(true);
      }
    } catch (err) {
      console.error('Chat error:', err);
      setMessages((prev) => [
        ...prev,
        {
          role: 'assistant',
          content: "I'm sorry, I'm having trouble connecting to my server right now. Please try again in a moment, or feel free to email our team directly at sales@climbsphere.ai!",
        },
      ]);
    } finally {
      setIsTyping(false);
    }
  };

  return (
    <div className="chatbot-wrapper">
      {/* Floating Action Button */}
      <button
        className={`chatbot-fab ${isOpen ? 'open' : ''}`}
        onClick={toggleChat}
        aria-label="Toggle AI assistant"
      >
        {isOpen ? (
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        ) : (
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
          </svg>
        )}
        {!isOpen && hasNewMessage && <span className="chatbot-pulse" />}
      </button>

      {/* Chat Window */}
      <div className={`chatbot-window ${isOpen ? 'visible' : ''}`}>
        {/* Header */}
        <div className="chatbot-header">
          <div className="chatbot-info">
            <div className="chatbot-avatar">
              <img src="/images/climbsphere-cs-only.png" alt="ClimbSphere CS Logo" />
            </div>
            <div className="chatbot-title">
              <h4>ClimbSphere AI</h4>
              <p>Digital chatbot interface.</p>
            </div>
          </div>
          <button className="chatbot-close" onClick={toggleChat} aria-label="Close chat">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        </div>

        {/* Message Area */}
        <div className="chatbot-messages">
          {messages.map((msg, index) => (
            <div key={index} className={`chatbot-msg ${msg.role}`}>
              {msg.content}
            </div>
          ))}
          {isTyping && (
            <div className="chatbot-typing">
              <span className="typing-dot"></span>
              <span className="typing-dot"></span>
              <span className="typing-dot"></span>
            </div>
          )}
          <div ref={messagesEndRef} />
        </div>

        {/* Input Area */}
        <div className="chatbot-input-wrapper">
          <form onSubmit={handleSend} className="chatbot-input-pill">
            <input
              type="text"
              placeholder="Chat here.."
              value={input}
              onChange={(e) => setInput(e.target.value)}
              disabled={isTyping}
            />

            <button type="submit" className="chatbot-send-btn" disabled={!input.trim() || isTyping}>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                <path d="M5 12h14M12 5l7 7-7 7" />
              </svg>
            </button>
          </form>
        </div>
      </div>
    </div>
  );
}
