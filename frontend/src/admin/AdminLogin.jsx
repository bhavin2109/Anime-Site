import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import axios from 'axios';
import { useAuth } from '../context/AuthContext';

const AdminLogin = () => {
  const { adminLogin } = useAuth();
  const navigate = useNavigate();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);

    try {
      const res = await axios.post('/api/auth/admin-login', { email, password });
      adminLogin(res.data);
      navigate('/admin/dashboard');
    } catch (err) {
      setError(err.response?.data?.error || 'Authentication failed.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center p-4 bg-gradient-main">
      <div className="auth-card max-w-md w-full bg-gradient-card border border-gold/15 px-8 py-10 rounded-lg shadow-lg flex flex-col items-center gap-6 backdrop-blur-glass">
        <h2 className="text-2xl font-bold text-white font-primary text-center">Admin Login</h2>
        <p className="text-gray-light text-sm text-center -mt-3">Sign in to manage your anime platform</p>

        {error && (
          <div className="w-full p-3 rounded bg-red-500/10 border border-red-500/20 text-red-400 text-sm text-center font-medium">
            {error}
          </div>
        )}

        <form onSubmit={handleSubmit} className="w-full flex flex-col gap-4">
          <input
            type="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            placeholder="Admin Email"
            required
            autoFocus
            className="w-full bg-[#090c11]/50 border border-gold/15 rounded px-4 py-3 text-white placeholder-gray-light focus:outline-none focus:border-gold transition-all duration-200"
          />
          <input
            type="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            placeholder="Password"
            required
            className="w-full bg-[#090c11]/50 border border-gold/15 rounded px-4 py-3 text-white placeholder-gray-light focus:outline-none focus:border-gold transition-all duration-200"
          />
          <button
            type="submit"
            disabled={loading}
            className="w-full bg-gradient-gold text-black font-bold py-3 rounded hover:opacity-95 hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 shadow-gold hover:shadow-gold-lg cursor-pointer disabled:opacity-50"
          >
            {loading ? 'Logging in...' : 'Login'}
          </button>
        </form>

        <div className="text-center text-sm text-gray-light">
          <Link to="/" className="hover:text-gold hover:underline">
            ← Back to Home
          </Link>
        </div>
      </div>
    </div>
  );
};

export default AdminLogin;
