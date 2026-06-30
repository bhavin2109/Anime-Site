import React, { useState, useEffect } from 'react';
import axios from 'axios';

const AdminDashboard = () => {
  const [stats, setStats] = useState({ anime: 0, movies: 0, users: 0 });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchStats = async () => {
      try {
        const res = await axios.get('/api/anime/stats/count');
        setStats(res.data);
      } catch (err) {
        console.error('Error fetching admin dashboard statistics:', err);
      } finally {
        setLoading(false);
      }
    };
    fetchStats();
  }, []);

  if (loading) return <div className="text-gold font-bold text-center py-10">Loading statistics...</div>;

  return (
    <div className="flex flex-col gap-6 max-w-4xl">
      <h2 className="text-2xl font-bold text-white font-primary border-b border-white/5 pb-2">
        Dashboard Statistics
      </h2>
      
      <div className="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-4">
        
        <div className="bg-gradient-card border border-gold/15 p-6 rounded-xl shadow-lg flex flex-col gap-2">
          <h3 className="text-gray-light text-sm font-semibold uppercase tracking-wider">Total Anime</h3>
          <p className="text-4xl font-extrabold text-gold font-primary">{stats.anime}</p>
        </div>

        <div className="bg-gradient-card border border-gold/15 p-6 rounded-xl shadow-lg flex flex-col gap-2">
          <h3 className="text-gray-light text-sm font-semibold uppercase tracking-wider">Movies</h3>
          <p className="text-4xl font-extrabold text-gold font-primary">{stats.movies}</p>
        </div>

        <div className="bg-gradient-card border border-gold/15 p-6 rounded-xl shadow-lg flex flex-col gap-2">
          <h3 className="text-gray-light text-sm font-semibold uppercase tracking-wider">Users</h3>
          <p className="text-4xl font-extrabold text-gold font-primary">{stats.users}</p>
        </div>

      </div>
    </div>
  );
};

export default AdminDashboard;
