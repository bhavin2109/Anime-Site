import React, { useState, useEffect } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import axios from 'axios';
import { useAuth } from '../context/AuthContext';

const EditProfile = () => {
  const { user, login } = useAuth();
  const navigate = useNavigate();

  const [username, setUsername] = useState('');
  const [email, setEmail] = useState('');
  const [dateOfBirth, setDateOfBirth] = useState('');
  const [file, setFile] = useState(null);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    if (user) {
      setUsername(user.username || '');
      setEmail(user.email || '');
      // Format date of birth to yyyy-MM-dd if it exists
      if (user.date_of_birth) {
        setDateOfBirth(user.date_of_birth.substring(0, 10));
      }
    }
  }, [user]);

  const handleFileChange = (e) => {
    setFile(e.target.files[0]);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setSuccess('');
    setLoading(true);

    const formData = new FormData();
    formData.append('username', username);
    formData.append('email', email);
    formData.append('date_of_birth', dateOfBirth);
    if (file) {
      formData.append('profile_picture', file);
    }

    try {
      const res = await axios.put(`/api/auth/profile/${user.user_id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });
      setSuccess(res.data.message);
      login(res.data.user);
      setTimeout(() => {
        navigate('/profile');
      }, 1500);
    } catch (err) {
      setError(err.response?.data?.error || 'Failed to update profile.');
    } finally {
      setLoading(false);
    }
  };

  const getProfilePicUrl = () => {
    if (user && user.profile_picture) {
      return `/assets/profile_pics/${user.profile_picture}`;
    }
    const name = user ? user.username : 'User';
    return `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=ccaa00&color=090c11&size=128`;
  };

  if (!user) return null;

  return (
    <div className="pb-16 px-4 md:px-8">
      <div className="max-w-md mx-auto bg-gradient-card border border-gold/15 p-8 rounded-xl shadow-lg flex flex-col items-center gap-6 backdrop-blur-glass mt-12 reveal">
        <h2 className="text-2xl font-bold text-white font-primary border-b border-gold/25 pb-1 w-full text-center">Edit Profile</h2>

        {success && (
          <div className="w-full p-3 rounded bg-green-500/10 border border-green-500/20 text-green-400 text-sm text-center font-medium">
            {success}
          </div>
        )}

        {error && (
          <div className="w-full p-3 rounded bg-red-500/10 border border-red-500/20 text-red-400 text-sm text-center font-medium">
            {error}
          </div>
        )}

        <img 
          src={getProfilePicUrl()} 
          alt="Profile" 
          className="w-28 h-28 rounded-full object-cover border border-gold/30"
        />

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
            type="date"
            value={dateOfBirth}
            onChange={(e) => setDateOfBirth(e.target.value)}
            placeholder="Date of Birth"
            className="w-full bg-[#090c11]/50 border border-gold/15 rounded px-4 py-3 text-white placeholder-gray-light focus:outline-none focus:border-gold transition-all duration-200"
          />
          
          <div className="w-full text-sm text-gray-light flex flex-col gap-1.5">
            <span className="font-semibold text-white">Profile Picture:</span>
            <input
              type="file"
              accept="image/*"
              onChange={handleFileChange}
              className="file:bg-white/10 file:border-none file:text-white file:px-3 file:py-1.5 file:rounded file:cursor-pointer hover:file:bg-white/15 file:mr-3 text-xs w-full"
            />
          </div>

          <div className="flex gap-4 mt-4 w-full">
            <button
              type="submit"
              disabled={loading}
              className="flex-1 bg-gradient-gold text-black font-bold py-2.5 rounded hover:opacity-95 transition-all duration-200 cursor-pointer disabled:opacity-50"
            >
              {loading ? 'Saving...' : 'Save Changes'}
            </button>
            <Link 
              to="/profile" 
              className="flex-1 text-center bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold py-2.5 rounded transition-all duration-200"
            >
              Cancel
            </Link>
          </div>
        </form>
      </div>
    </div>
  );
};

export default EditProfile;
