import React, { useState } from 'react';

const Contact = () => {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [subject, setSubject] = useState('');
  const [message, setMessage] = useState('');
  const [success, setSuccess] = useState(false);

  const handleSubmit = (e) => {
    e.preventDefault();
    setSuccess(true);
    // Reset fields
    setName('');
    setEmail('');
    setSubject('');
    setMessage('');
    setTimeout(() => {
      setSuccess(false);
    }, 4000);
  };

  return (
    <div className="pb-16 px-4 md:px-8">
      {/* Contact Hero */}
      <div className="max-w-4xl mx-auto text-center mt-12 mb-10 reveal">
        <h1 className="text-4xl font-extrabold mb-4 background-clip-text text-transparent bg-gradient-gold font-primary uppercase tracking-wider">
          Contact Us
        </h1>
        <p className="text-gray-light text-[1.15rem] leading-relaxed">
          Have a question, suggestion, or just want to say hello? We'd love to hear from you!
        </p>
      </div>

      <div className="max-w-5xl mx-auto">
        <div className="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
          
          {/* Contact Form */}
          <div className="bg-gradient-card border border-gold/15 p-6 md:p-8 rounded-xl shadow-lg backdrop-blur-glass reveal-left">
            <h2 className="text-xl font-bold text-white mb-6 border-l-4 border-gold pl-3 font-primary">Send a Message</h2>
            
            {success && (
              <div className="mb-6 p-4 rounded bg-green-500/10 border border-green-500/20 text-green-400 text-sm font-medium">
                Thank you! Your message has been sent successfully.
              </div>
            )}

            <form onSubmit={handleSubmit} className="flex flex-col gap-4">
              <input
                type="text"
                value={name}
                onChange={(e) => setName(e.target.value)}
                placeholder="Your Name"
                required
                className="w-full bg-[#090c11]/50 border border-gold/15 rounded px-4 py-3 text-white placeholder-gray-light focus:outline-none focus:border-gold transition-all duration-200"
              />
              <input
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder="Your Email"
                required
                className="w-full bg-[#090c11]/50 border border-gold/15 rounded px-4 py-3 text-white placeholder-gray-light focus:outline-none focus:border-gold transition-all duration-200"
              />
              <input
                type="text"
                value={subject}
                onChange={(e) => setSubject(e.target.value)}
                placeholder="Subject"
                required
                className="w-full bg-[#090c11]/50 border border-gold/15 rounded px-4 py-3 text-white placeholder-gray-light focus:outline-none focus:border-gold transition-all duration-200"
              />
              <textarea
                value={message}
                onChange={(e) => setMessage(e.target.value)}
                placeholder="Your Message..."
                required
                rows="5"
                className="w-full bg-[#090c11]/50 border border-gold/15 rounded px-4 py-3 text-white placeholder-gray-light focus:outline-none focus:border-gold transition-all duration-200"
              />
              <button
                type="submit"
                className="w-full bg-gradient-gold text-black font-bold py-3 rounded hover:opacity-95 hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 shadow-gold hover:shadow-gold-lg cursor-pointer"
              >
                Send Message
              </button>
            </form>
          </div>

          {/* Contact Info Card */}
          <div className="bg-white/3 border border-white/5 p-6 md:p-8 rounded-xl shadow-lg flex flex-col gap-6 reveal-right">
            <h2 className="text-xl font-bold text-white border-l-4 border-gold pl-3 font-primary">Get in Touch</h2>
            
            <div className="flex items-start gap-4">
              <span className="text-2xl mt-1">📧</span>
              <div>
                <h3 className="font-bold text-white font-primary">Email</h3>
                <p className="text-gray-light text-sm mt-0.5">support@animestreaming.com</p>
              </div>
            </div>

            <div className="flex items-start gap-4">
              <span className="text-2xl mt-1">📍</span>
              <div>
                <h3 className="font-bold text-white font-primary">Location</h3>
                <p className="text-gray-light text-sm mt-0.5">Mumbai, India</p>
              </div>
            </div>

            <div className="flex items-start gap-4">
              <span className="text-2xl mt-1">⏰</span>
              <div>
                <h3 className="font-bold text-white font-primary">Working Hours</h3>
                <p className="text-gray-light text-sm mt-0.5">Mon - Fri: 9AM - 6PM IST</p>
              </div>
            </div>

            <div className="flex items-start gap-4">
              <span className="text-2xl mt-1">💬</span>
              <div>
                <h3 className="font-bold text-white font-primary">Community</h3>
                <p className="text-gray-light text-sm mt-0.5">Join our Discord for live support and discussions</p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  );
};

export default Contact;
