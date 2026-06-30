import React, { useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const Profile = () => {
  const { user, logout, refreshUserProfile } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {
    refreshUserProfile();
  }, []);

  const handleLogout = () => {
    logout();
    navigate('/login');
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
        <h2 className="text-2xl font-bold text-white font-primary border-b border-gold/25 pb-1 w-full text-center">User Profile</h2>
        
        <img 
          src={getProfilePicUrl()} 
          alt="Profile" 
          className="w-32 h-32 rounded-full object-cover border border-gold/30 shadow-gold"
        />

        <div className="w-full flex flex-col gap-3 text-gray-light text-[0.98rem]">
          <p>
            <strong className="text-white">Username:</strong> {user.username}
          </p>
          <p>
            <strong className="text-white">Email:</strong> {user.email}
          </p>
          <p>
            <strong className="text-white">Date of Birth:</strong> {user.date_of_birth || 'Not Specified'}
          </p>
        </div>

        <div className="w-full flex flex-col gap-3 mt-4">
          <Link 
            to="/edit-profile" 
            className="w-full text-center bg-gradient-gold text-black font-bold py-2.5 rounded hover:opacity-90 transition-all duration-200"
          >
            Edit Profile
          </Link>
          <button 
            onClick={handleLogout}
            className="w-full text-center bg-red-500/10 hover:bg-red-500/25 border border-red-500/30 text-red-400 font-bold py-2.5 rounded transition-all duration-200 cursor-pointer"
          >
            Logout
          </button>
        </div>
      </div>
    </div>
  );
};

export default Profile;
