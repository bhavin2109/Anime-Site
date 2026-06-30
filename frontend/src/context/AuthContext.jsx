import React, { createContext, useState, useEffect, useContext } from 'react';
import axios from 'axios';

const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [admin, setAdmin] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Load persisted auth from localStorage
    const savedUser = localStorage.getItem('anime_user');
    const savedAdmin = localStorage.getItem('anime_admin');

    if (savedUser) {
      setUser(JSON.parse(savedUser));
    }
    if (savedAdmin) {
      setAdmin(JSON.parse(savedAdmin));
    }
    setLoading(false);
  }, []);

  const login = (userData) => {
    setUser(userData);
    localStorage.setItem('anime_user', JSON.stringify(userData));
  };

  const logout = () => {
    setUser(null);
    localStorage.removeItem('anime_user');
  };

  const adminLogin = (adminData) => {
    setAdmin(adminData);
    localStorage.setItem('anime_admin', JSON.stringify(adminData));
  };

  const adminLogout = () => {
    setAdmin(null);
    localStorage.removeItem('anime_admin');
  };

  const refreshUserProfile = async () => {
    if (!user) return;
    try {
      const res = await axios.get(`/api/auth/profile/${user.user_id}`);
      const updatedUser = { ...user, ...res.data };
      setUser(updatedUser);
      localStorage.setItem('anime_user', JSON.stringify(updatedUser));
    } catch (err) {
      console.error('Failed to refresh user profile:', err);
    }
  };

  return (
    <AuthContext.Provider
      value={{
        user,
        admin,
        loading,
        login,
        logout,
        adminLogin,
        adminLogout,
        refreshUserProfile
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};
