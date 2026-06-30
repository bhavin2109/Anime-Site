import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';

const AdminAnimeList = () => {
  const [animeList, setAnimeList] = useState([]);
  const [loading, setLoading] = useState(true);

  const fetchAnime = async () => {
    try {
      const res = await axios.get('/api/anime/all');
      setAnimeList(res.data);
    } catch (err) {
      console.error('Error fetching admin anime catalogue:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchAnime();
  }, []);

  const handleDelete = async (id, name) => {
    if (!confirm(`Are you sure you want to delete "${name}"?`)) return;
    try {
      await axios.delete(`/api/anime/${id}`);
      alert('Anime removed successfully!');
      fetchAnime();
    } catch (err) {
      console.error('Failed to delete anime:', err);
      alert('Error removing anime.');
    }
  };

  if (loading) return <div className="text-gold font-bold text-center py-10">Loading catalogue...</div>;

  return (
    <div className="flex flex-col gap-6 w-full">
      <div className="flex justify-between items-center border-b border-white/5 pb-3">
        <h2 className="text-2xl font-bold text-white font-primary">Anime & Movie List</h2>
        <Link 
          to="/admin/anime/add" 
          className="bg-gradient-gold text-black text-xs font-bold px-4 py-2.5 rounded hover:opacity-90 transition-all shadow-gold"
        >
          + Add Anime
        </Link>
      </div>

      <div className="overflow-x-auto bg-gradient-card border border-gold/15 rounded-xl shadow-lg">
        <table className="w-full text-left border-collapse">
          <thead>
            <tr className="border-b border-gold/15 text-gray-light text-xs uppercase tracking-wider bg-white/2">
              <th className="py-3 px-4 text-center">ID</th>
              <th className="py-3 px-4">Name</th>
              <th className="py-3 px-4 text-center">Type</th>
              <th className="py-3 px-4 text-center">Image</th>
              <th className="py-3 px-4 text-center">Episodes</th>
              <th className="py-3 px-4">Genre</th>
              <th className="py-3 px-4 text-center">Actions</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-white/5 text-[0.9rem]">
            {animeList.map((row) => (
              <tr key={row.anime_id} className="hover:bg-white/2 transition-colors">
                <td className="py-4 px-4 text-center text-gray-light font-mono">{row.anime_id}</td>
                <td className="py-4 px-4 font-semibold text-white max-w-[200px] truncate">{row.anime_name}</td>
                <td className="py-4 px-4 text-center">
                  <span className="bg-[#1a1e24] text-gold/90 text-xs px-2.5 py-1 rounded-full border border-gold/10">
                    {row.anime_type}
                  </span>
                </td>
                <td className="py-4 px-4 text-center">
                  <img 
                    src={`/assets/thumbnails/${row.anime_image}`} 
                    alt="" 
                    className="w-12 h-16 object-cover rounded border border-white/10 mx-auto"
                  />
                </td>
                <td className="py-4 px-4 text-center font-bold">
                  <Link 
                    to={`/admin/episodes/${row.anime_id}?anime_name=${encodeURIComponent(row.anime_name)}`} 
                    className="text-red-400 hover:text-red-300 hover:underline"
                  >
                    {row.episode_count}
                  </Link>
                </td>
                <td className="py-4 px-4 text-gray-light">{row.genre}</td>
                <td className="py-4 px-4 text-center">
                  <div className="flex justify-center items-center gap-2">
                    <Link 
                      to={`/admin/anime/update/${row.anime_id}`} 
                      className="bg-white/5 hover:bg-gold/10 border border-white/10 hover:border-gold/20 text-white hover:text-gold text-xs font-semibold px-2.5 py-1.5 rounded transition-all"
                    >
                      Update
                    </Link>
                    <button 
                      onClick={() => handleDelete(row.anime_id, row.anime_name)}
                      className="bg-red-500/10 hover:bg-red-500/25 border border-red-500/30 text-red-400 hover:text-red-300 text-xs font-semibold px-2.5 py-1.5 rounded transition-all cursor-pointer"
                    >
                      Remove
                    </button>
                    <Link 
                      to={`/admin/episodes/add/${row.anime_id}?anime_name=${encodeURIComponent(row.anime_name)}`} 
                      className="bg-gradient-gold text-black text-xs font-bold px-2.5 py-1.5 rounded hover:opacity-90 transition-all shadow-gold"
                    >
                      + Episode
                    </Link>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default AdminAnimeList;
