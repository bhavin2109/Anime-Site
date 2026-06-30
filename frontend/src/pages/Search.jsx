import React, { useState, useEffect } from 'react';
import { useLocation, Link } from 'react-router-dom';
import axios from 'axios';

const Search = () => {
  const location = useLocation();
  const query = new URLSearchParams(location.search).get('query') || '';
  const [results, setResults] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchResults = async () => {
      setLoading(true);
      if (!query.trim()) {
        setResults([]);
        setLoading(false);
        return;
      }

      try {
        const res = await axios.get(`/api/anime/search?query=${encodeURIComponent(query.trim())}`);
        setResults(res.data);
      } catch (err) {
        console.error('Error searching anime:', err);
      } finally {
        setLoading(false);
      }
    };

    fetchResults();
  }, [query]);

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gradient-main text-gold text-lg font-bold">
        Searching...
      </div>
    );
  }

  return (
    <div className="pb-16 px-4 md:px-8">
      <div className="max-w-6xl mx-auto mt-8 reveal">
        <h2 className="section-title text-center text-3xl font-extrabold mb-6 background-clip-text text-transparent bg-gradient-gold uppercase tracking-wider">
          Results for: "{query}"
        </h2>

        {results.length > 0 ? (
          <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 stagger-children">
            {results.map((anime) => (
              <Link 
                key={anime.anime_id}
                to={`/player?anime_id=${anime.anime_id}`} 
                className="group relative rounded-lg overflow-hidden border border-transparent hover:border-gold/20 bg-black/40 hover:shadow-gold transition-all duration-300"
              >
                <div className="w-full aspect-[2/3] md:h-[340px] overflow-hidden">
                  <img 
                    src={`/assets/thumbnails/${anime.anime_image}`} 
                    alt={anime.anime_name} 
                    className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                  />
                </div>
                <div className="p-3 bg-dark/30">
                  <h3 className="text-white font-bold text-sm truncate font-primary">{anime.anime_name}</h3>
                  <div className="text-xs text-gray-light mt-1 flex justify-between">
                    <span>{anime.anime_type}</span>
                    <span>{anime.genre}</span>
                  </div>
                </div>
              </Link>
            ))}
          </div>
        ) : (
          <p className="text-center text-gray-light py-12 text-[1.1rem]">
            No results found for "{query}"
          </p>
        )}
      </div>
    </div>
  );
};

export default Search;
