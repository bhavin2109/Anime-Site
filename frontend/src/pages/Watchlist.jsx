import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';
import { useAuth } from '../context/AuthContext';

const Watchlist = () => {
  const { user } = useAuth();
  const [watchlist, setWatchlist] = useState([]);
  const [loading, setLoading] = useState(true);
  const [message, setMessage] = useState('');

  const fetchWatchlist = async () => {
    if (!user?.user_id) return;
    try {
      const res = await axios.get(`/api/watchlist/${user.user_id}`);
      setWatchlist(res.data);
    } catch (err) {
      console.error('Error fetching watchlist:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchWatchlist();
  }, [user]);

  const handleStatusChange = async (animeId, newStatus) => {
    setMessage('');
    try {
      const res = await axios.post('/api/watchlist', {
        user_id: user.user_id,
        anime_id: animeId,
        status: newStatus
      });
      setMessage(res.data.message);
      fetchWatchlist();
    } catch (err) {
      console.error('Error updating status:', err);
    }
  };

  const handleRemove = async (animeId) => {
    setMessage('');
    if (!confirm('Are you sure you want to remove this from your watchlist?')) return;
    try {
      const res = await axios.post('/api/watchlist/remove', {
        user_id: user.user_id,
        anime_id: animeId
      });
      setMessage(res.data.message);
      fetchWatchlist();
    } catch (err) {
      console.error('Error removing anime:', err);
    }
  };

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gradient-main text-gold text-lg font-bold">
        Loading watchlist...
      </div>
    );
  }

  return (
    <div className="pb-16 px-4 md:px-8">
      <div className="max-w-6xl mx-auto bg-gradient-card border border-gold/15 p-6 md:p-8 rounded-xl shadow-lg backdrop-blur-glass mt-8 reveal">
        <h2 className="section-title text-center text-3xl font-extrabold mb-6 background-clip-text text-transparent bg-gradient-gold uppercase tracking-wider">
          My Watchlist
        </h2>

        {message && (
          <div className="mb-6 p-3 rounded bg-gold/10 border border-gold/20 text-gold text-sm text-center font-medium">
            {message}
          </div>
        )}

        {watchlist.length > 0 ? (
          <div className="overflow-x-auto">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="border-b border-gold/15 text-gray-light text-sm uppercase tracking-wider">
                  <th className="pb-4 pt-2 px-4">Image</th>
                  <th className="pb-4 pt-2 px-4">Anime</th>
                  <th className="pb-4 pt-2 px-4">Type</th>
                  <th className="pb-4 pt-2 px-4">Status</th>
                  <th className="pb-4 pt-2 px-4">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-white/5 text-[0.95rem]">
                {watchlist.map((item) => (
                  <tr key={item.anime_id} className="hover:bg-white/2 transition-colors">
                    <td className="py-4 px-4">
                      <img 
                        src={`/assets/thumbnails/${item.anime_image}`} 
                        alt="" 
                        className="w-16 h-22 object-cover rounded border border-white/10"
                      />
                    </td>
                    <td className="py-4 px-4 font-semibold">
                      <Link 
                        to={`/player?anime_id=${item.anime_id}`} 
                        className="text-red-400 hover:text-red-300 transition-colors"
                      >
                        {item.anime_name}
                      </Link>
                    </td>
                    <td className="py-4 px-4 text-gray-light">{item.anime_type}</td>
                    <td className="py-4 px-4 text-gray-light font-medium">{item.status}</td>
                    <td className="py-4 px-4">
                      <div className="flex flex-wrap items-center gap-3">
                        <select 
                          value={item.status}
                          onChange={(e) => handleStatusChange(item.anime_id, e.target.value)}
                          className="bg-[#090c11]/80 border border-gold/15 rounded px-2 py-1.5 text-xs text-white focus:outline-none focus:border-gold cursor-pointer"
                        >
                          <option value="Watching">Watching</option>
                          <option value="Completed">Completed</option>
                          <option value="Plan to Watch">Plan to Watch</option>
                          <option value="Dropped">Dropped</option>
                        </select>
                        <button 
                          onClick={() => handleRemove(item.anime_id)}
                          className="bg-red-500/10 hover:bg-red-500/25 border border-red-500/30 hover:border-red-500/50 text-red-400 hover:text-red-300 text-xs font-bold px-3 py-1.5 rounded transition-all cursor-pointer"
                        >
                          Remove
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        ) : (
          <p className="text-center text-gray-light py-12 text-[1.1rem]">
            Your watchlist is empty. Start exploring anime!
          </p>
        )}
      </div>
    </div>
  );
};

export default Watchlist;
