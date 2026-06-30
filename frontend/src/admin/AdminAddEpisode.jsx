import React, { useState } from 'react';
import { useNavigate, useParams, Link, useLocation } from 'react-router-dom';
import axios from 'axios';

const AdminAddEpisode = () => {
  const { anime_id } = useParams();
  const navigate = useNavigate();
  const location = useLocation();
  const queryParams = new URLSearchParams(location.search);
  const animeName = queryParams.get('anime_name') || 'Anime';

  const [episodeUrl, setEpisodeUrl] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);

    try {
      await axios.post('/api/episodes', {
        anime_id: parseInt(anime_id),
        episode_url: episodeUrl.trim()
      });
      alert('Episode added successfully!');
      navigate(`/admin/episodes/${anime_id}?anime_name=${encodeURIComponent(animeName)}`);
    } catch (err) {
      setError(err.response?.data?.error || 'Failed to add episode.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="max-w-xl flex flex-col gap-6">
      <div className="border-b border-white/5 pb-3">
        <h2 className="text-2xl font-bold text-white font-primary">Add Episode</h2>
        <p className="text-xs text-gold/85">Anime: {animeName}</p>
      </div>

      {error && (
        <div className="p-3 rounded bg-red-500/10 border border-red-500/20 text-red-400 text-sm font-medium">
          {error}
        </div>
      )}

      <form onSubmit={handleSubmit} className="bg-gradient-card border border-gold/15 p-6 md:p-8 rounded-xl flex flex-col gap-5 shadow-lg">
        <div className="flex flex-col gap-1.5">
          <label className="text-sm font-semibold text-white">Google Drive File ID / Video URL</label>
          <input
            type="text"
            value={episodeUrl}
            onChange={(e) => setEpisodeUrl(e.target.value)}
            required
            placeholder="e.g. 1j6bPQ5nv6kfQ3PZZHifoEkTQ72ZcWsN1"
            className="w-full bg-[#090c11]/50 border border-gold/15 rounded px-4 py-2.5 text-white focus:outline-none focus:border-gold transition-all font-mono text-sm"
          />
          <span className="text-[10px] text-gray-light">
            Enter the 33-character Google Drive File ID representing the episode video preview.
          </span>
        </div>

        <div className="flex gap-4 mt-4">
          <button
            type="submit"
            disabled={loading}
            className="flex-1 bg-gradient-gold text-black font-bold py-2.5 rounded hover:opacity-95 transition-all shadow-gold disabled:opacity-50 cursor-pointer"
          >
            {loading ? 'Adding...' : 'Add Episode'}
          </button>
          <Link 
            to={`/admin/episodes/${anime_id}?anime_name=${encodeURIComponent(animeName)}`} 
            className="flex-1 text-center bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold py-2.5 rounded transition-all"
          >
            Cancel
          </Link>
        </div>
      </form>
    </div>
  );
};

export default AdminAddEpisode;
