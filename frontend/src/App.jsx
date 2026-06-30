import React, { useEffect } from 'react';
import { Routes, Route, useLocation, Navigate } from 'react-router-dom';
import { AuthProvider, useAuth } from './context/AuthContext';

// Components
import Header from './components/Header';
import Footer from './components/Footer';

// User Pages
import Home from './pages/Home';
import Explore from './pages/Explore';
import Watchlist from './pages/Watchlist';
import About from './pages/About';
import Contact from './pages/Contact';
import Profile from './pages/Profile';
import EditProfile from './pages/EditProfile';
import Login from './pages/Login';
import Signup from './pages/Signup';
import Player from './pages/Player';
import Search from './pages/Search';

// Admin Pages
import AdminLogin from './admin/AdminLogin';
import AdminLayout from './admin/AdminLayout';
import AdminDashboard from './admin/AdminDashboard';
import AdminAnimeList from './admin/AdminAnimeList';
import AdminAddAnime from './admin/AdminAddAnime';
import AdminUpdateAnime from './admin/AdminUpdateAnime';
import AdminEpisodesList from './admin/AdminEpisodesList';
import AdminAddEpisode from './admin/AdminAddEpisode';
import AdminUpdateEpisode from './admin/AdminUpdateEpisode';
import AdminUsersList from './admin/AdminUsersList';
import AdminGenresList from './admin/AdminGenresList';

// Protected Route helper for regular users
const ProtectedRoute = ({ children }) => {
  const { user, loading } = useAuth();
  if (loading) return <div className="min-h-screen bg-black flex items-center justify-center text-gold">Loading...</div>;
  if (!user) return <Navigate to="/login" replace />;
  return children;
};

// Protected Route helper for admin users
const AdminRoute = ({ children }) => {
  const { admin, loading } = useAuth();
  if (loading) return <div className="min-h-screen bg-black flex items-center justify-center text-gold">Loading...</div>;
  if (!admin) return <Navigate to="/admin/login" replace />;
  return children;
};

function AppContent() {
  const location = useLocation();
  const isAdminPath = location.pathname.startsWith('/admin');

  // Trigger page load and scroll reveal animations on route change
  useEffect(() => {
    window.scrollTo(0, 0);

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('revealed');
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.05,
      rootMargin: '0px 0px -20px 0px'
    });

    const selectors = '.reveal, .reveal-left, .reveal-right, .reveal-scale, .stagger-children, .genre-box, .feature-card, .team-card, .section-title, .movie-item, .anime-item';

    const processElement = (el, index = 0) => {
      const isAutoReveal = el.matches('.genre-box, .feature-card, .team-card, .section-title, .movie-item, .anime-item');
      if (isAutoReveal && !el.classList.contains('reveal') && !el.classList.contains('revealed')) {
        el.classList.add('reveal');
        el.style.transitionDelay = `${Math.min(index * 0.05, 0.4)}s`;
      }
      observer.observe(el);
    };

    // Observe initial elements in DOM
    document.querySelectorAll(selectors).forEach((el, index) => {
      processElement(el, index);
    });

    // Use MutationObserver to observe elements dynamically added later (e.g. after async fetches)
    const mutationObserver = new MutationObserver((mutations) => {
      let index = 0;
      mutations.forEach(mutation => {
        mutation.addedNodes.forEach(node => {
          if (node.nodeType === Node.ELEMENT_NODE) {
            if (node.matches(selectors)) {
              processElement(node, index++);
            }
            node.querySelectorAll(selectors).forEach(child => {
              processElement(child, index++);
            });
          }
        });
      });
    });

    mutationObserver.observe(document.body, {
      childList: true,
      subtree: true
    });

    return () => {
      observer.disconnect();
      mutationObserver.disconnect();
    };
  }, [location.pathname]);

  return (
    <div className="flex flex-col min-h-screen">
      {/* Hide header and footer in admin panel */}
      {!isAdminPath && <Header />}
      
      <main className={`flex-grow ${!isAdminPath ? 'pt-16' : ''}`}>
        <Routes>
          {/* User Routes */}
          <Route path="/" element={<ProtectedRoute><Home /></ProtectedRoute>} />
          <Route path="/explore" element={<ProtectedRoute><Explore /></ProtectedRoute>} />
          <Route path="/watchlist" element={<ProtectedRoute><Watchlist /></ProtectedRoute>} />
          <Route path="/about" element={<ProtectedRoute><About /></ProtectedRoute>} />
          <Route path="/contact" element={<ProtectedRoute><Contact /></ProtectedRoute>} />
          <Route path="/profile" element={<ProtectedRoute><Profile /></ProtectedRoute>} />
          <Route path="/edit-profile" element={<ProtectedRoute><EditProfile /></ProtectedRoute>} />
          <Route path="/player" element={<ProtectedRoute><Player /></ProtectedRoute>} />
          <Route path="/search" element={<ProtectedRoute><Search /></ProtectedRoute>} />
          <Route path="/login" element={<Login />} />
          <Route path="/signup" element={<Signup />} />

          {/* Admin Routes */}
          <Route path="/admin/login" element={<AdminLogin />} />
          <Route path="/admin" element={<AdminRoute><AdminLayout /></AdminRoute>}>
            <Route index element={<Navigate to="/admin/dashboard" replace />} />
            <Route path="dashboard" element={<AdminDashboard />} />
            <Route path="anime" element={<AdminAnimeList />} />
            <Route path="anime/add" element={<AdminAddAnime />} />
            <Route path="anime/update/:anime_id" element={<AdminUpdateAnime />} />
            <Route path="episodes/:anime_id" element={<AdminEpisodesList />} />
            <Route path="episodes/add/:anime_id" element={<AdminAddEpisode />} />
            <Route path="episodes/update/:episode_id" element={<AdminUpdateEpisode />} />
            <Route path="users" element={<AdminUsersList />} />
            <Route path="genres" element={<AdminGenresList />} />
          </Route>

          {/* Fallback */}
          <Route path="*" element={<Navigate to="/" replace />} />
        </Routes>
      </main>

      {!isAdminPath && <Footer />}
    </div>
  );
}

function App() {
  return (
    <AuthProvider>
      <AppContent />
    </AuthProvider>
  );
}

export default App;
