import React, { useState, useEffect } from 'react';
import { useNavigate, useParams, Link, useLocation } from 'react-router-dom';
import axios from 'axios';

const AdminUpdateEpisode = () => {
  const { episode_id } = useParams();
  const navigate = useNavigate();
  const location = useLocation();
  const queryParams = new URLSearchParams(location.search);
  const animeId = queryParams.get('anime_id') || '1';
  const animeName = queryParams.get('anime_name') || 'Anime';

  const [episodeUrl, setEpisodeUrl] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    const fetchEpisodeDetails = async () => {
      try {
        const res = await axios.get(`/api/episodes/${episode_id}?anime_id=${animeId}`);
        setEpisodeUrl(res.data.episode_url);
      } catch (err) {
        console.error('Failed to load episode details:', err);
        setError('Failed to load episode details.');
      }
    };
    fetchEpisodeDetails();
  }, [episode_id, animeId]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);

    try {
      await axios.put(`/api/episodes/${episode_id}`, {
        episode_url: episodeUrl.trim()
      });
      alert('Episode updated successfully!');
      navigate(`/admin/episodes/${animeId}?anime_name=${encodeURIComponent(animeName)}`);
    } catch (err) {
      setError(err.response?.data?.error || 'Failed to update episode.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="max-w-xl flex flex-col gap-6">
      <div className="border-b border-white/5 pb-3">
        <h2 className="text-2xl font-bold text-white font-primary">Update Episode URL</h2>
        <p className="text-xs text-gold/85">Anime: {animeName} | Episode ID: {episode_id}</p>
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
            className="w-full bg-[#090c11]/50 border border-gold/15 rounded px-4 py-2.5 text-white focus:outline-none focus:border-gold transition-all font-mono text-sm"
          />
          <span className="text-[10px] text-gray-light">
            Enter the updated 33-character Google Drive File ID.
          </span>
        </div>

        <div className="flex gap-4 mt-4">
          <button
            type="submit"
            disabled={loading}
            className="flex-1 bg-gradient-gold text-black font-bold py-2.5 rounded hover:opacity-95 transition-all shadow-gold disabled:opacity-50 cursor-pointer"
          >
            {loading ? 'Saving...' : 'Save Changes'}
          </button>
          <Link 
            to={`/admin/episodes/${animeId}?anime_name=${encodeURIComponent(animeName)}`} 
            className="flex-1 text-center bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold py-2.5 rounded transition-all"
          >
            Cancel
          </Link>
        </div>
      </form>
    </div>
  );
};

export default AdminUpdateEpisode;
