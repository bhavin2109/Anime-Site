import React from 'react';
import { Link } from 'react-router-dom';

const Footer = () => {
  return (
    <footer className="bg-[#090c11]/85 border-t border-gold/15 backdrop-blur-glass mt-auto py-10 px-5 md:px-[5%]">
      <div className="max-w-7xl mx-auto flex flex-col md:flex-row justify-between gap-8 md:gap-4 text-gray-light text-[0.9rem]">
        <div className="flex-1 md:max-w-sm flex flex-col gap-3">
          <h3 className="text-white text-lg font-bold border-b border-gold/30 pb-1 w-max">About Us</h3>
          <p>Your one-stop destination for streaming and discovering your favorite anime and movies!</p>
          <p>We provide anime fans with a seamless and enjoyable experience.</p>
        </div>
        <div className="flex-1 md:max-w-[200px] flex flex-col gap-2">
          <h3 className="text-white text-lg font-bold border-b border-gold/30 pb-1 w-max mb-1">Quick Links</h3>
          <Link to="/" className="hover:text-gold transition-colors duration-200">Home</Link>
          <Link to="/explore" className="hover:text-gold transition-colors duration-200">Explore Movies</Link>
          <Link to="/about" className="hover:text-gold transition-colors duration-200">About Us</Link>
          <Link to="/contact" className="hover:text-gold transition-colors duration-200">Contact Us</Link>
        </div>
        <div className="flex-1 md:max-w-sm flex flex-col gap-3">
          <h3 className="text-white text-lg font-bold border-b border-gold/30 pb-1 w-max">Connect With Us</h3>
          <p>Built by Group No.2 — bringing the best anime content to fans worldwide.</p>
          <div className="flex items-center gap-3 mt-1">
            <a href="#" className="w-8 h-8 rounded bg-white/4 flex items-center justify-center hover:bg-gold/10 hover:border-gold/30 border border-transparent transition-all duration-200" title="Facebook">
              <img src="/assets/icons/facebook.png" alt="Facebook" className="w-4 h-4 object-contain" />
            </a>
            <a href="#" className="w-8 h-8 rounded bg-white/4 flex items-center justify-center hover:bg-gold/10 hover:border-gold/30 border border-transparent transition-all duration-200" title="Instagram">
              <img src="/assets/icons/instagram.png" alt="Instagram" className="w-4 h-4 object-contain" />
            </a>
            <a href="#" className="w-8 h-8 rounded bg-white/4 flex items-center justify-center hover:bg-gold/10 hover:border-gold/30 border border-transparent transition-all duration-200" title="Twitter">
              <img src="/assets/icons/twitter.png" alt="Twitter" className="w-4 h-4 object-contain" />
            </a>
            <a href="#" className="w-8 h-8 rounded bg-white/4 flex items-center justify-center hover:bg-gold/10 hover:border-gold/30 border border-transparent transition-all duration-200" title="Telegram">
              <img src="/assets/icons/telegram.png" alt="Telegram" className="w-4 h-4 object-contain" />
            </a>
          </div>
        </div>
      </div>
      <div className="max-w-7xl mx-auto border-t border-white/5 mt-8 pt-4 text-center text-xs text-gray-light/60">
        <p>&copy; {new Date().getFullYear()} Anime Streaming Site &mdash; Group No.2</p>
      </div>
    </footer>
  );
};

export default Footer;
