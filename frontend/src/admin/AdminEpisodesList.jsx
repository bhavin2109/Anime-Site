import React, { useState, useEffect } from 'react';
import { useParams, Link, useLocation } from 'react-router-dom';
import axios from 'axios';

const AdminEpisodesList = () => {
  const { anime_id } = useParams();
  const location = useLocation();
  const queryParams = new URLSearchParams(location.search);
  const animeName = queryParams.get('anime_name') || 'Anime';

  const [episodes, setEpisodes] = useState([]);
  const [loading, setLoading] = useState(true);

  const fetchEpisodes = async () => {
    try {
      const res = await axios.get(`/api/episodes/by-anime/${anime_id}`);
      setEpisodes(res.data);
    } catch (err) {
      console.error('Error fetching anime episodes:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchEpisodes();
  }, [anime_id]);

  const handleDelete = async (id, index) => {
    if (!confirm(`Are you sure you want to delete Episode ${index}?`)) return;
    try {
      await axios.delete(`/api/episodes/${id}`);
      alert('Episode removed successfully!');
      fetchEpisodes();
    } catch (err) {
      console.error('Error deleting episode:', err);
      alert('Error removing episode.');
    }
  };

  if (loading) return <div className="text-gold font-bold text-center py-10">Loading episodes...</div>;

  return (
    <div className="flex flex-col gap-6 w-full">
      <div className="flex justify-between items-center border-b border-white/5 pb-3">
        <div className="flex flex-col gap-1">
          <h2 className="text-2xl font-bold text-white font-primary">Episodes Management</h2>
          <p className="text-xs text-gold/85">Anime: {animeName}</p>
        </div>
        <div className="flex gap-3">
          <Link 
            to={`/admin/episodes/add/${anime_id}?anime_name=${encodeURIComponent(animeName)}`}
            className="bg-gradient-gold text-black text-xs font-bold px-4 py-2.5 rounded hover:opacity-90 transition-all shadow-gold"
          >
            + Add Episode
          </Link>
          <Link 
            to="/admin/anime" 
            className="bg-white/5 hover:bg-white/10 border border-white/10 text-white text-xs font-bold px-4 py-2.5 rounded transition-all"
          >
            Back to Anime
          </Link>
        </div>
      </div>

      <div className="overflow-x-auto bg-gradient-card border border-gold/15 rounded-xl shadow-lg">
        <table className="w-full text-left border-collapse">
          <thead>
            <tr className="border-b border-gold/15 text-gray-light text-xs uppercase tracking-wider bg-white/2">
              <th className="py-3 px-4 text-center">Episode No.</th>
              <th className="py-3 px-4 text-center">Episode ID</th>
              <th className="py-3 px-4">Google Drive File ID</th>
              <th className="py-3 px-4 text-center">Actions</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-white/5 text-[0.9rem]">
            {episodes.map((row, idx) => (
              <tr key={row.episode_id} className="hover:bg-white/2 transition-colors">
                <td className="py-4 px-4 text-center font-bold text-gold font-mono">{idx + 1}</td>
                <td className="py-4 px-4 text-center text-gray-light font-mono">{row.episode_id}</td>
                <td className="py-4 px-4 font-mono text-gray-light max-w-sm truncate">{row.episode_url}</td>
                <td className="py-4 px-4 text-center">
                  <div className="flex justify-center items-center gap-2.5">
                    <Link 
                      to={`/admin/episodes/update/${row.episode_id}?anime_id=${anime_id}&anime_name=${encodeURIComponent(animeName)}`} 
                      className="bg-white/5 hover:bg-gold/10 border border-white/10 hover:border-gold/20 text-white hover:text-gold text-xs font-semibold px-2.5 py-1.5 rounded transition-all"
                    >
                      Update URL
                    </Link>
                    <button 
                      onClick={() => handleDelete(row.episode_id, idx + 1)}
                      className="bg-red-500/10 hover:bg-red-500/25 border border-red-500/30 text-red-400 hover:text-red-300 text-xs font-semibold px-2.5 py-1.5 rounded transition-all cursor-pointer"
                    >
                      Remove
                    </button>
                  </div>
                </td>
              </tr>
            ))}
            {episodes.length === 0 && (
              <tr>
                <td colSpan="4" className="text-center py-8 text-gray-light italic">
                  No episodes found. Click '+ Add Episode' to insert the first episode!
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default AdminEpisodesList;
