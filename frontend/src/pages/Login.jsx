import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import axios from 'axios';
import { useAuth } from '../context/AuthContext';

const Login = () => {
  const { login } = useAuth();
  const navigate = useNavigate();
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);

    try {
      const res = await axios.post('/api/auth/login', { username, password });
      login(res.data);
      navigate('/');
    } catch (err) {
      setError(err.response?.data?.error || 'Failed to authenticate. Try again.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center p-4">
      <div className="auth-card max-w-md w-full bg-gradient-card border border-gold/15 px-8 py-10 rounded-lg shadow-lg flex flex-col items-center gap-6 backdrop-blur-glass reveal">
        <img src="/assets/logo.ico" alt="Logo" className="w-14 h-14 object-contain" />
        
        <div className="text-center w-full">
          <h2 className="text-2xl font-bold text-white font-primary">Welcome Back</h2>
          <p className="text-gray-light text-sm mt-1">Sign in to your account to continue</p>
        </div>

        {error && (
          <div className="w-full p-3 rounded bg-red-500/10 border border-red-500/20 text-red-400 text-sm text-center font-medium">
            {error}
          </div>
        )}

        <form onSubmit={handleSubmit} className="w-full flex flex-col gap-4">
          <input
            type="text"
            value={username}
            onChange={(e) => setUsername(e.target.value)}
            placeholder="Username"
            required
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
            {loading ? 'Signing In...' : 'Sign In'}
          </button>
        </form>

        <div className="text-center text-sm text-gray-light">
          <p>
            New here?{' '}
            <Link to="/signup" className="text-gold hover:underline">
              Create an account
            </Link>
          </p>
          <p className="mt-4 text-xs">
            Are you an admin?{' '}
            <Link to="/admin/login" className="text-gold/80 hover:text-gold hover:underline">
              Admin Login
            </Link>
          </p>
        </div>
      </div>
    </div>
  );
};

export default Login;
