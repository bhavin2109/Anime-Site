import React, { useState, useEffect } from 'react';
import axios from 'axios';

const AdminGenresList = () => {
  const [genres, setGenres] = useState([]);
  const [newGenre, setNewGenre] = useState('');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  const fetchGenres = async () => {
    try {
      const res = await axios.get('/api/genres');
      setGenres(res.data);
    } catch (err) {
      console.error('Error fetching admin genres:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchGenres();
  }, []);

  const handleAddGenre = async (e) => {
    e.preventDefault();
    setError('');

    if (!newGenre.trim()) return;

    try {
      await axios.post('/api/genres', { genre_name: newGenre.trim() });
      setNewGenre('');
      fetchGenres();
    } catch (err) {
      setError(err.response?.data?.error || 'Failed to add genre.');
    }
  };

  const handleDelete = async (id, name) => {
    if (!confirm(`Are you sure you want to delete the genre "${name}"?`)) return;
    try {
      await axios.delete(`/api/genres/${id}`);
      fetchGenres();
    } catch (err) {
      console.error('Failed to delete genre:', err);
      alert('Error removing genre.');
    }
  };

  if (loading) return <div className="text-gold font-bold text-center py-10">Loading genres...</div>;

  return (
    <div className="flex flex-col gap-8 w-full max-w-4xl">
      <div className="border-b border-white/5 pb-3">
        <h2 className="text-2xl font-bold text-white font-primary font-bold">Category & Genre Management</h2>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
        
        {/* Left Form: Add Genre */}
        <div className="bg-gradient-card border border-gold/15 p-6 rounded-xl shadow-lg flex flex-col gap-4">
          <h3 className="text-base font-bold text-white border-l-4 border-gold pl-2 font-primary">Add New Genre</h3>
          
          {error && (
            <div className="p-2.5 rounded bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-medium">
              {error}
            </div>
          )}

          <form onSubmit={handleAddGenre} className="flex flex-col gap-4">
            <input
              type="text"
              value={newGenre}
              onChange={(e) => setNewGenre(e.target.value)}
              placeholder="e.g. Cyberpunk"
              required
              className="w-full bg-[#090c11]/50 border border-gold/15 rounded px-3.5 py-2 text-sm text-white placeholder-gray-light focus:outline-none focus:border-gold transition-all"
            />
            <button
              type="submit"
              className="bg-gradient-gold text-black font-bold py-2 rounded text-sm hover:opacity-95 transition-all shadow-gold cursor-pointer"
            >
              Add Genre
            </button>
          </form>
        </div>

        {/* Right Section: Genres Table */}
        <div className="md:col-span-2 overflow-x-auto bg-gradient-card border border-gold/15 rounded-xl shadow-lg">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="border-b border-gold/15 text-gray-light text-xs uppercase tracking-wider bg-white/2">
                <th className="py-3 px-4 text-center">ID</th>
                <th className="py-3 px-4">Genre Name</th>
                <th className="py-3 px-4 text-center">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-white/5 text-[0.9rem]">
              {genres.map((row) => (
                <tr key={row.genre_id} className="hover:bg-white/2 transition-colors">
                  <td className="py-3 px-4 text-center text-gray-light font-mono">{row.genre_id}</td>
                  <td className="py-3 px-4 font-semibold text-white">{row.genre_name}</td>
                  <td className="py-3 px-4 text-center">
                    <button 
                      onClick={() => handleDelete(row.genre_id, row.genre_name)}
                      className="bg-red-500/10 hover:bg-red-500/25 border border-red-500/30 text-red-400 hover:text-red-300 text-xs font-semibold px-2 py-1 rounded transition-all cursor-pointer"
                    >
                      Delete
                    </button>
                  </td>
                </tr>
              ))}
              {genres.length === 0 && (
                <tr>
                  <td colSpan="3" className="text-center py-6 text-gray-light italic">
                    No genres configured.
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>

      </div>
    </div>
  );
};

export default AdminGenresList;
