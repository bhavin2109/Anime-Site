import React, { useState, useEffect } from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const Header = () => {
  const { user } = useAuth();
  const location = useLocation();
  const navigate = useNavigate();
  const [navOpen, setNavOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');

  // Track scrolled state for styling
  useEffect(() => {
    const handleScroll = () => {
      if (window.scrollY > 50) {
        setScrolled(true);
      } else {
        setScrolled(false);
      }
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  // Close nav menu on route change
  useEffect(() => {
    setNavOpen(false);
  }, [location.pathname]);

  const handleSearch = (e) => {
    if (e) e.preventDefault();
    if (searchQuery.trim()) {
      navigate(`/search?query=${encodeURIComponent(searchQuery.trim())}`);
    } else {
      alert('Please enter a search term.');
    }
  };

  const getProfilePicUrl = () => {
    if (user && user.profile_picture) {
      return `/assets/profile_pics/${user.profile_picture}`;
    }
    const name = user ? user.username : 'User';
    return `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=ccaa00&color=090c11&size=128`;
  };

  const isActive = (paths) => {
    if (Array.isArray(paths)) {
      return paths.some(p => location.pathname === p);
    }
    return location.pathname === paths;
  };

  return (
    <header className={`fixed top-0 left-0 w-full z-[1000] flex justify-between items-center px-6 py-3 transition-all duration-300 ${
      scrolled 
        ? 'bg-[#090c11]/97 py-2 shadow-md border-b border-gold/15' 
        : 'bg-[#090c11]/85 border-b border-gold/15'
    } backdrop-blur-glass`}>
      <div className="logo h-10">
        <Link to="/"><img src="/assets/logo.ico" alt="Logo" className="h-full object-contain" /></Link>
      </div>

      {/* Hamburger Menu Icon */}
      <button 
        className={`hamburger flex flex-col justify-between w-6 h-4 md:hidden focus:outline-none z-[1010] ${navOpen ? 'active' : ''}`}
        onClick={() => setNavOpen(!navOpen)}
        aria-label="Toggle menu"
      >
        <span className={`w-full h-[2px] bg-gray-light transition-all duration-300 origin-left ${navOpen ? 'rotate-45 translate-x-[2px] translate-y-[-1px]' : ''}`}></span>
        <span className={`w-full h-[2px] bg-gray-light transition-all duration-300 ${navOpen ? 'opacity-0 scale-0' : ''}`}></span>
        <span className={`w-full h-[2px] bg-gray-light transition-all duration-300 origin-left ${navOpen ? '-rotate-45 translate-x-[2px] translate-y-[1px]' : ''}`}></span>
      </button>

      {/* Navigation Menu */}
      <nav className={`fixed inset-y-0 right-0 w-[240px] bg-gradient-sidebar flex flex-col items-center pt-24 gap-6 transform transition-transform duration-300 ease-in-out md:relative md:inset-auto md:w-auto md:bg-none md:flex-row md:pt-0 md:translate-x-0 ${
        navOpen ? 'translate-x-0' : 'translate-x-full md:translate-x-0'
      }`}>
        <Link 
          to="/" 
          className={`font-medium text-[0.95rem] py-1 relative after:content-[''] after:absolute after:bottom-[-2px] after:left-0 after:h-[2px] after:bg-gradient-gold after:transition-all after:duration-300 ${
            isActive('/') ? 'text-gold after:w-full' : 'text-gray-light after:w-0 hover:text-white'
          }`}
        >
          Home
        </Link>
        <Link 
          to="/explore" 
          className={`font-medium text-[0.95rem] py-1 relative after:content-[''] after:absolute after:bottom-[-2px] after:left-0 after:h-[2px] after:bg-gradient-gold after:transition-all after:duration-300 ${
            isActive('/explore') ? 'text-gold after:w-full' : 'text-gray-light after:w-0 hover:text-white'
          }`}
        >
          Movies
        </Link>
        <Link 
          to="/watchlist" 
          className={`font-medium text-[0.95rem] py-1 relative after:content-[''] after:absolute after:bottom-[-2px] after:left-0 after:h-[2px] after:bg-gradient-gold after:transition-all after:duration-300 ${
            isActive('/watchlist') ? 'text-gold after:w-full' : 'text-gray-light after:w-0 hover:text-white'
          }`}
        >
          Watchlist
        </Link>
        <Link 
          to="/about" 
          className={`font-medium text-[0.95rem] py-1 relative after:content-[''] after:absolute after:bottom-[-2px] after:left-0 after:h-[2px] after:bg-gradient-gold after:transition-all after:duration-300 ${
            isActive('/about') ? 'text-gold after:w-full' : 'text-gray-light after:w-0 hover:text-white'
          }`}
        >
          About
        </Link>
        <Link 
          to="/contact" 
          className={`font-medium text-[0.95rem] py-1 relative after:content-[''] after:absolute after:bottom-[-2px] after:left-0 after:h-[2px] after:bg-gradient-gold after:transition-all after:duration-300 ${
            isActive('/contact') ? 'text-gold after:w-full' : 'text-gray-light after:w-0 hover:text-white'
          }`}
        >
          Contact
        </Link>
        
        {/* User profile section */}
        {user ? (
          <Link 
            to="/profile" 
            className={`flex items-center gap-2 font-medium text-[0.95rem] py-1 relative after:content-[''] after:absolute after:bottom-[-2px] after:left-0 after:h-[2px] after:bg-gradient-gold after:transition-all after:duration-300 ${
              isActive(['/profile', '/edit-profile']) ? 'text-gold after:w-full' : 'text-gray-light after:w-0 hover:text-white'
            }`}
          >
            <img 
              src={getProfilePicUrl()} 
              alt={user.username} 
              className="w-6 h-6 rounded-full object-cover border border-gold/25"
            />
            {user.username}
          </Link>
        ) : (
          <Link 
            to="/login" 
            className="text-gray-light hover:text-gold text-[0.95rem] font-medium"
          >
            Sign In
          </Link>
        )}
      </nav>

      {/* Search Input Container */}
      <form onSubmit={handleSearch} className="search-container flex items-center bg-white/5 border border-gold/15 rounded-md px-3 py-1.5 gap-2 max-w-[240px] md:max-w-xs transition-transform duration-200 focus-within:scale-[1.02]">
        <input 
          type="search" 
          value={searchQuery}
          onChange={(e) => setSearchQuery(e.target.value)}
          placeholder="Search Anime" 
          className="bg-transparent text-white placeholder-gray-light text-[0.9rem] focus:outline-none w-full border-none p-0"
        />
        <button type="submit" className="focus:outline-none opacity-85 hover:opacity-100 transition-opacity">
          <img src="/assets/icons/search.png" alt="Search" className="w-[18px] h-[18px] object-contain" />
        </button>
      </form>
    </header>
  );
};

export default Header;
