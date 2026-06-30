import React from 'react';
import { Outlet, NavLink, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const AdminLayout = () => {
  const { admin, adminLogout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = () => {
    adminLogout();
    navigate('/admin/login');
  };

  if (!admin) return null;

  return (
    <div className="min-h-screen bg-black text-white flex flex-col md:flex-row">
      
      {/* Admin Panel Sidebar */}
      <aside className="w-full md:w-[240px] md:min-w-[240px] bg-gradient-sidebar border-b md:border-b-0 md:border-r border-gold/15 flex flex-col items-center py-6 gap-6">
        <div className="text-center">
          <h1 className="text-lg font-extrabold background-clip-text text-transparent bg-gradient-gold tracking-widest font-primary uppercase">
            Admin Panel
          </h1>
          <p className="text-[10px] text-gray-light mt-1">Logged in as {admin.username}</p>
        </div>

        <nav className="w-[90%] flex flex-row md:flex-col gap-2 overflow-x-auto md:overflow-visible">
          <NavLink 
            to="/admin/dashboard" 
            className={({ isActive }) => `flex-1 md:flex-none text-center md:text-left px-5 py-3 rounded text-sm font-medium transition-all ${
              isActive 
                ? 'bg-gradient-gold text-black shadow-gold font-bold' 
                : 'bg-white/4 border border-transparent text-gray-light hover:bg-gold/10 hover:border-gold/15 hover:text-gold'
            }`}
          >
            Dashboard
          </NavLink>
          <NavLink 
            to="/admin/anime" 
            className={({ isActive }) => `flex-1 md:flex-none text-center md:text-left px-5 py-3 rounded text-sm font-medium transition-all ${
              isActive 
                ? 'bg-gradient-gold text-black shadow-gold font-bold' 
                : 'bg-white/4 border border-transparent text-gray-light hover:bg-gold/10 hover:border-gold/15 hover:text-gold'
            }`}
          >
            Anime
          </NavLink>
          <NavLink 
            to="/admin/users" 
            className={({ isActive }) => `flex-1 md:flex-none text-center md:text-left px-5 py-3 rounded text-sm font-medium transition-all ${
              isActive 
                ? 'bg-gradient-gold text-black shadow-gold font-bold' 
                : 'bg-white/4 border border-transparent text-gray-light hover:bg-gold/10 hover:border-gold/15 hover:text-gold'
            }`}
          >
            Users
          </NavLink>
          <NavLink 
            to="/admin/genres" 
            className={({ isActive }) => `flex-1 md:flex-none text-center md:text-left px-5 py-3 rounded text-sm font-medium transition-all ${
              isActive 
                ? 'bg-gradient-gold text-black shadow-gold font-bold' 
                : 'bg-white/4 border border-transparent text-gray-light hover:bg-gold/10 hover:border-gold/15 hover:text-gold'
            }`}
          >
            Genres
          </NavLink>
          <button 
            onClick={handleLogout}
            className="flex-1 md:flex-none text-center md:text-left px-5 py-3 rounded text-sm font-medium transition-all bg-red-500/10 border border-transparent hover:bg-red-500/20 text-red-400 hover:text-red-300 cursor-pointer"
          >
            Logout
          </button>
        </nav>
      </aside>

      {/* Main Panel Viewport */}
      <main className="flex-1 p-6 md:p-10 max-h-screen overflow-y-auto">
        <Outlet />
      </main>

    </div>
  );
};

export default AdminLayout;
