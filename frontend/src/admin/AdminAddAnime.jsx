import React, { useState, useEffect } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import axios from 'axios';

const AdminAddAnime = () => {
  const navigate = useNavigate();
  const [animeName, setAnimeName] = useState('');
  const [animeType, setAnimeType] = useState('TV');
  const [genre, setGenre] = useState('');
  const [file, setFile] = useState(null);
  const [genres, setGenres] = useState([]);
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    const fetchGenres = async () => {
      try {
        const res = await axios.get('/api/genres');
        setGenres(res.data);
        if (res.data.length > 0) {
          setGenre(res.data[0].genre_name);
        }
      } catch (err) {
        console.error('Failed to fetch genres:', err);
      }
    };
    fetchGenres();
  }, []);

  const handleFileChange = (e) => {
    setFile(e.target.files[0]);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');

    if (!file) {
      return setError('Please upload an image!');
    }

    setLoading(true);
    const formData = new FormData();
    formData.append('anime_name', animeName);
    formData.append('anime_type', animeType);
    formData.append('genre', genre);
    formData.append('anime_image', file);

    try {
      await axios.post('/api/anime', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
      alert('Anime added successfully!');
      navigate('/admin/anime');
    } catch (err) {
      setError(err.response?.data?.error || 'Failed to add anime.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="max-w-xl flex flex-col gap-6">
      <div className="border-b border-white/5 pb-3">
        <h2 className="text-2xl font-bold text-white font-primary">Add Anime</h2>
      </div>

      {error && (
        <div className="p-3 rounded bg-red-500/10 border border-red-500/20 text-red-400 text-sm font-medium">
          {error}
        </div>
      )}

      <form onSubmit={handleSubmit} className="bg-gradient-card border border-gold/15 p-6 md:p-8 rounded-xl flex flex-col gap-5 shadow-lg">
        <div className="flex flex-col gap-1.5">
          <label className="text-sm font-semibold text-white">Anime Name</label>
          <input
            type="text"
            value={animeName}
            onChange={(e) => setAnimeName(e.target.value)}
            required
            placeholder="e.g. Naruto Shippuden"
            className="w-full bg-[#090c11]/50 border border-gold/15 rounded px-4 py-2.5 text-white focus:outline-none focus:border-gold transition-all"
          />
        </div>

        <div className="flex flex-col gap-1.5">
          <label className="text-sm font-semibold text-white">Anime Type</label>
          <select
            value={animeType}
            onChange={(e) => setAnimeType(e.target.value)}
            className="w-full bg-[#090c11]/80 border border-gold/15 rounded px-4 py-2.5 text-white focus:outline-none focus:border-gold cursor-pointer"
          >
            <option value="TV">TV</option>
            <option value="Movie">Movie</option>
            <option value="ONA">ONA</option>
            <option value="OVA">OVA</option>
            <option value="Special">Special</option>
            <option value="TV Special">TV Special</option>
            <option value="Music">Music</option>
          </select>
        </div>

        <div className="flex flex-col gap-1.5">
          <label className="text-sm font-semibold text-white">Genre</label>
          <select
            value={genre}
            onChange={(e) => setGenre(e.target.value)}
            className="w-full bg-[#090c11]/80 border border-gold/15 rounded px-4 py-2.5 text-white focus:outline-none focus:border-gold cursor-pointer"
          >
            {genres.map((g) => (
              <option key={g.genre_id} value={g.genre_name}>
                {g.genre_name}
              </option>
            ))}
          </select>
        </div>

        <div className="flex flex-col gap-1.5 text-sm text-gray-light">
          <label className="font-semibold text-white mb-1">Anime Thumbnail Image</label>
          <input
            type="file"
            accept="image/*"
            required
            onChange={handleFileChange}
            className="file:bg-white/10 file:border-none file:text-white file:px-3 file:py-2 file:rounded file:cursor-pointer hover:file:bg-white/15 file:mr-3 text-xs w-full"
          />
        </div>

        <div className="flex gap-4 mt-4">
          <button
            type="submit"
            disabled={loading}
            className="flex-1 bg-gradient-gold text-black font-bold py-2.5 rounded hover:opacity-95 transition-all shadow-gold disabled:opacity-50 cursor-pointer"
          >
            {loading ? 'Adding...' : 'Add Anime'}
          </button>
          <Link 
            to="/admin/anime" 
            className="flex-1 text-center bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold py-2.5 rounded transition-all"
          >
            Cancel
          </Link>
        </div>
      </form>
    </div>
  );
};

export default AdminAddAnime;
