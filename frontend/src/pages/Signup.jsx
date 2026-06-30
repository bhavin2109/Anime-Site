import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import axios from 'axios';

const Signup = () => {
  const navigate = useNavigate();
  const [username, setUsername] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setSuccess('');

    if (password !== confirmPassword) {
      return setError('Passwords do not match!');
    }

    setLoading(true);

    try {
      await axios.post('/api/auth/signup', {
        username,
        email,
        password,
        confirm_password: confirmPassword
      });
      setSuccess('Registration successful! Redirecting to login...');
      setTimeout(() => {
        navigate('/login');
      }, 1500);
    } catch (err) {
      setError(err.response?.data?.error || 'Registration failed. Try again.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center p-4">
      <div className="auth-card max-w-md w-full bg-gradient-card border border-gold/15 px-8 py-10 rounded-lg shadow-lg flex flex-col items-center gap-6 backdrop-blur-glass reveal">
        <img src="/assets/logo.ico" alt="Logo" className="w-14 h-14 object-contain" />
        
        <div className="text-center w-full">
          <h2 className="text-2xl font-bold text-white font-primary">Create Account</h2>
          <p className="text-gray-light text-sm mt-1">Sign up to get started</p>
        </div>

        {error && (
          <div className="w-full p-3 rounded bg-red-500/10 border border-red-500/20 text-red-400 text-sm text-center font-medium">
            {error}
          </div>
        )}

        {success && (
          <div className="w-full p-3 rounded bg-green-500/10 border border-green-500/20 text-green-400 text-sm text-center font-medium">
            {success}
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
            type="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            placeholder="Email"
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
          <input
            type="password"
            value={confirmPassword}
            onChange={(e) => setConfirmPassword(e.target.value)}
            placeholder="Confirm Password"
            required
            className="w-full bg-[#090c11]/50 border border-gold/15 rounded px-4 py-3 text-white placeholder-gray-light focus:outline-none focus:border-gold transition-all duration-200"
          />
          <button
            type="submit"
            disabled={loading}
            className="w-full bg-gradient-gold text-black font-bold py-3 rounded hover:opacity-95 hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 shadow-gold hover:shadow-gold-lg cursor-pointer disabled:opacity-50"
          >
            {loading ? 'Creating...' : 'Sign Up'}
          </button>
        </form>

        <div className="text-center text-sm text-gray-light">
          <p>
            Already have an account?{' '}
            <Link to="/login" className="text-gold hover:underline">
              Sign In
            </Link>
          </p>
        </div>
      </div>
    </div>
  );
};

export default Signup;
